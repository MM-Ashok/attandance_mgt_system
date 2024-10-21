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

// Fetch teacher details based on ID
if (isset($_GET['id'])) {
    $teacherId = $_GET['id'];

    // Prepare and execute the query to get teacher details
    $query = "SELECT * FROM miraiteachers WHERE Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacherId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if teacher exists
    if ($result->num_rows == 0) {
        die("Teacher not found.");
    }

    $teacher = $result->fetch_assoc();
}

// Handle form submission for updating teacher details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];

    // Prepare and bind for updating the teacher details
    $updateQuery = "UPDATE miraiteachers SET firstName = ?, lastName = ?, emailAddress = ?, phoneNo = ?, classId = ? WHERE Id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssi", $firstName, $lastName, $emailAddress, $phoneNo, $classId, $teacherId);

    if ($updateStmt->execute()) {
        $success = "Teacher details updated successfully!";
        header("Location: add_teacher.php"); // Redirect back to the teachers list
        exit();
    } else {
        $error = "Error updating teacher: " . $updateStmt->error;
    }
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
        <h2 class="text-2xl text-center">Edit Teacher</h2>
        <form action="edit_teacher.php?id=<?php echo $teacherId; ?>" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="firstName" class="block text-gray-700">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($teacher['firstName']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="lastName" class="block text-gray-700">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($teacher['lastName']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="emailAddress" class="block text-gray-700">Email Address</label>
                    <input type="email" id="emailAddress" name="emailAddress" value="<?php echo htmlspecialchars($teacher['emailAddress']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="phoneNo" class="block text-gray-700">Phone Number</label>
                    <input type="text" id="phoneNo" name="phoneNo" value="<?php echo htmlspecialchars($teacher['phoneNo']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="classId" class="block text-gray-700">Select Class</label>
                    <select id="classId" name="classId" required class="w-full p-2 border border-gray-300 rounded">
                        <option value="">Select a Class</option>
                        <?php while ($class = $classResult->fetch_assoc()): ?>
                            <option value="<?php echo $class['ID']; ?>" <?php echo ($class['ID'] == $teacher['classId']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class['className']); ?>
                            </option>
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
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Teacher</button>
            </div>
        </form>
    </div>
</div>
<?php include '../include/footer.php'; ?>
