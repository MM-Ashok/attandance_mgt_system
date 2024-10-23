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

// Fetch the student ID from the URL
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Fetch student details
    $studentQuery = "SELECT * FROM miraistudent WHERE id = ?";
    $studentStmt = $conn->prepare($studentQuery);
    $studentStmt->bind_param("i", $studentId);
    $studentStmt->execute();
    $studentResult = $studentStmt->get_result();

    // Check if the student exists
    if ($studentResult->num_rows == 0) {
        header("Location: add_student.php"); // Redirect if student not found
        exit();
    }

    $student = $studentResult->fetch_assoc();
    $studentStmt->close(); // Close the statement after use
} else {
    header("Location: add_student.php"); // Redirect if no ID provided
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $otherName = $_POST['otherName'];
    $emailAddress = $_POST['emailAddress'];
    $admissionNumber = $_POST['admissionNumber'];
    $classId = $_POST['classId'];

    // Update student details
    $updateQuery = "UPDATE miraistudent SET firstName = ?, lastName = ?, otherName = ?, emailAddress = ?, admissionNumber = ?, class_id = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssiii", $firstName, $lastName, $otherName, $emailAddress, $admissionNumber, $classId, $studentId);

    if ($updateStmt->execute()) {
        $success = "Student updated successfully!";
    } else {
        $error = "Error updating student: " . $updateStmt->error;
    }
    $updateStmt->close(); // Close the update statement after execution
}

// Fetch all classes for the dropdown
$classQuery = "SELECT * FROM miraiclass";
$classResult = $conn->query($classQuery);
?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">Edit Student</h2>
        <form action="edit_student.php?id=<?php echo $studentId; ?>" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="firstName" class="block text-gray-700">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo $student['firstName']; ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="lastName" class="block text-gray-700">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo $student['lastName']; ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="otherName" class="block text-gray-700">Other Name</label>
                    <input type="text" id="otherName" name="otherName" value="<?php echo $student['otherName']; ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="emailAddress" class="block text-gray-700">Email Address</label>
                    <input type="email" id="emailAddress" name="emailAddress" value="<?php echo $student['emailAddress']; ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="admissionNumber" class="block text-gray-700">Admission Number</label>
                    <input type="text" id="admissionNumber" name="admissionNumber" value="<?php echo $student['admissionNumber']; ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="classId" class="block text-gray-700">Select Class</label>
                    <select id="classId" name="classId" required class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Select a Class</option>
                        <?php while ($class = $classResult->fetch_assoc()): ?>
                            <option value="<?php echo $class['ID']; ?>" <?php echo ($class['ID'] == $student['class_id']) ? 'selected' : ''; ?>><?php echo $class['className']; ?></option>
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
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Student</button>
            </div>
        </form>
    </div>
</div>

<?php include '../include/footer.php'; ?>
