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

    // Delete from miraiteacherclasses first
    $deleteClasses = "DELETE FROM miraiclass WHERE ID = ?";
    $deleteClassesStmt = $conn->prepare($deleteClasses);
    $deleteClassesStmt->bind_param("i", $deleteId);
    $deleteClassesStmt->execute();

    header("Location: add_class.php"); // Redirect back to the same page after deletion
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST['className'];

    // Check if class already exists
    $checkQuery = "SELECT * FROM miraiclass WHERE className = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $className);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $error = "Class with this name already exists.";
    } else {
        // Prepare and bind for insertion
        $query = "INSERT INTO miraiclass (className) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $className);

        if ($stmt->execute()) {
            $success = "Class added successfully!";
        } else {
            $error = "Error adding class: " . $stmt->error;
        }
    }
}

// Fetch all classes
$query = "SELECT * FROM miraiclass";
$result = $conn->query($query);
?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
    <h2 class="text-2xl text-center">Add Class</h2>
    <form action="add_class.php" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
        <div class="mb-4">
            <label for="className" class="block text-gray-700">Class Name</label>
            <input type="text" id="className" name="className" required class="w-full p-2 border border-gray-300 rounded">
        </div>
        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Class</button>
        </div>
    </form>

    <h2 class="text-2xl text-center mt-10">All Classes</h2>
    <div class="overflow-x-auto mt-4">
        <table id="classesTable" class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Class Name</th>
                    <th class="py-2 px-4 border">Created At</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($class = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4 border"><?php echo $class['ID']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $class['className']; ?></td>
                        <td class="py-2 px-4 border"><?php echo $class['created_at']; ?></td>
                        <td class="py-2 px-4 border flex space-x-2">
                            <a href="edit_class.php?id=<?php echo $class['ID']; ?>" class="text-blue-500 hover:underline">
                                <i class="fas fa-edit"></i> Edit
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
