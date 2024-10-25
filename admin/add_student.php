<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../include/db_connection.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Delete from miraistudent
    $deleteStudentQuery = "DELETE FROM miraistudent WHERE id = ?";
    $deleteStudentStmt = $conn->prepare($deleteStudentQuery);
    $deleteStudentStmt->bind_param("i", $deleteId);
    $deleteStudentStmt->execute();

    header("Location: add_student.php"); // Redirect back to the same page after deletion
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new student logic
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $otherName = $_POST['otherName'];
    $admissionNumber = $_POST['admissionNumber'];
    $phoneNo = $_POST['phoneNo']; // Get phone number
    $passWord = password_hash($_POST['passWord'], PASSWORD_DEFAULT); // Hash the password before saving
    $classId = isset($_POST['classId']) ? $_POST['classId'] : null;

    // Check if email or phone number already exists
    //$checkQuery = "SELECT * FROM miraistudent WHERE emailAddress = ? OR phoneNo = ?";
    $checkQuery = "SELECT * FROM miraistudent WHERE emailAddress = ? OR phoneNo = ? OR (admissionNumber = ? AND class_id = ?)";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("sssi", $emailAddress, $phoneNo, $admissionNumber, $classId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $existingData = $checkResult->fetch_assoc();
        if ($existingData['emailAddress'] == $emailAddress) {
            $error = "Email address already exists. Please use a different email.";
        }
        elseif ($existingData['admissionNumber'] == $admissionNumber && $existingData['class_id'] == $classId) {
            $error = "The admission number already exists for the selected class. Please use a different admission number.";
        }
        //elseif ($existingData['phoneNo'] == $phoneNo) {
        //     $error = "Phone number already exists. Please use a different phone number.";
        // }
    } else {
        // Insert into miraistudent with class ID
        $insertStudentQuery = "INSERT INTO miraistudent (firstName, lastName, emailAddress, otherName, admissionNumber, passWord, phoneNo, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStudentStmt = $conn->prepare($insertStudentQuery);
        $insertStudentStmt->bind_param("sssssssi", $firstName, $lastName, $emailAddress, $otherName, $admissionNumber, $passWord, $phoneNo, $classId);

        if ($insertStudentStmt->execute()) {
            $success = "Student added successfully!";
        } else {
            $error = "Error adding student: " . $insertStudentStmt->error;
        }
    }
}


// Fetch all classes for the dropdown
$classQuery = "SELECT * FROM miraiclass";
$classResult = $conn->query($classQuery);

// Fetch all students with associated class names
$query = "
    SELECT s.*, c.className
    FROM miraistudent s
    LEFT JOIN miraiclass c ON s.class_id = c.ID
";
$result = $conn->query($query);
?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">Add Student</h2>
        <form action="add_student.php" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="firstName" class="block text-gray-700">First Name</label>
                    <input type="text" id="firstName" name="firstName" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="lastName" class="block text-gray-700">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="otherName" class="block text-gray-700">Other Name</label>
                    <input type="text" id="otherName" name="otherName" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="emailAddress" class="block text-gray-700">Email Address</label>
                    <input type="email" id="emailAddress" name="emailAddress" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="admissionNumber" class="block text-gray-700">Admission Number</label>
                    <input type="text" id="admissionNumber" name="admissionNumber" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="phoneNo" class="block text-gray-700">Phone Number</label>
                    <input type="text" id="phoneNo" name="phoneNo" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="passWord" class="block text-gray-700">Password</label>
                    <input type="password" id="passWord" name="passWord" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="classId" class="block text-gray-700">Select Class</label>
                    <select id="classId" name="classId" required class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Select a Class</option>
                        <?php while ($class = $classResult->fetch_assoc()): ?>
                            <option value="<?php echo $class['ID']; ?>"><?php echo $class['className']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <?php if (isset($success)): ?>
                <p class="text-green-500 text-center mt-4"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="text-red-500 text-center mt-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Student</button>
            </div>
        </form>

        <div class="overflow-x-auto mt-4">
            <table id="studentsTable" class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">First Name</th>
                        <th class="py-2 px-4 border">Last Name</th>
                        <th class="py-2 px-4 border">Other Name</th>
                        <th class="py-2 px-4 border">Email Address</th>
                        <th class="py-2 px-4 border">Admission Number</th>
                        <th class="py-2 px-4 border">Class Name</th>
                        <th class="py-2 px-4 border">Created At</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4 border"><?php echo $student['id']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['firstName']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['lastName']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['otherName']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['emailAddress']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['admissionNumber']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['className']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $student['created_at']; ?></td>
                            <td class="py-2 px-4 border flex space-x-2">
                                <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="text-blue-500 hover:underline">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="add_student.php?delete_id=<?php echo $student['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this student?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
