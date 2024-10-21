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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM miraiteachers WHERE emailAddress = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $emailAddress);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        $error = "Email address already exists. Please use a different email.";
    } else {
        // Prepare and bind for insertion
        $query = "INSERT INTO miraiteachers (firstName, lastName, emailAddress, password, phoneNo, classId) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $firstName, $lastName, $emailAddress, $password, $phoneNo, $classId);

        if ($stmt->execute()) {
            $success = "Teacher added successfully!";
        } else {
            $error = "Error adding teacher: " . $stmt->error;
        }
    }
}

// Fetch all classes for the dropdown
$classQuery = "SELECT * FROM miraiclass";
$classResult = $conn->query($classQuery);

// Fetch all teachers
$query = "SELECT * FROM miraiteachers";
$result = $conn->query($query);
?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">Add Teacher</h2>
        <form action="add_teacher.php" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
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
                    <label for="emailAddress" class="block text-gray-700">Email Address</label>
                    <input type="email" id="emailAddress" name="emailAddress" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="phoneNo" class="block text-gray-700">Phone Number</label>
                    <input type="text" id="phoneNo" name="phoneNo" required class="w-full p-2 border border-gray-300 rounded">
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
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Teacher</button>
            </div>
        </form>

        <h2 class="text-2xl text-center mt-10">All Teachers</h2>
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">First Name</th>
                        <th class="py-2 px-4 border">Last Name</th>
                        <th class="py-2 px-4 border">Email Address</th>
                        <th class="py-2 px-4 border">Phone Number</th>
                        <th class="py-2 px-4 border">Class ID</th>
                        <th class="py-2 px-4 border">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($teacher = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4 border"><?php echo $teacher['Id']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['firstName']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['lastName']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['emailAddress']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['phoneNo']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['classId']; ?></td>
                            <td class="py-2 px-4 border"><?php echo $teacher['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php include '../include/footer.php'; ?>
