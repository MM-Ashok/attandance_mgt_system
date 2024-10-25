<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['emailAddress'])) {
    header("Location: login.php");
    exit();
}

include '../include/db_connection.php';

$teacher_id = $_SESSION['teacher_id'];

// Fetch the total number of students assigned to this teacher
$studentCountQuery = "
    SELECT COUNT(DISTINCT s.id) AS total_students
    FROM miraistudent s
    INNER JOIN miraiclass c ON s.class_id = c.ID
    INNER JOIN miraiteacherclasses tc ON tc.class_id = c.ID
    WHERE tc.teacher_id = ?
";
$studentCountStmt = $conn->prepare($studentCountQuery);
$studentCountStmt->bind_param("i", $teacher_id);
$studentCountStmt->execute();
$studentCountResult = $studentCountStmt->get_result();
$studentCount = $studentCountResult->fetch_assoc()['total_students'];

// Fetch the total number of classes assigned to this teacher
$classCountQuery = "
    SELECT COUNT(DISTINCT c.ID) AS total_classes
    FROM miraiclass c
    INNER JOIN miraiteacherclasses tc ON tc.class_id = c.ID
    WHERE tc.teacher_id = ?
";
$classCountStmt = $conn->prepare($classCountQuery);
$classCountStmt->bind_param("i", $teacher_id);
$classCountStmt->execute();
$classCountResult = $classCountStmt->get_result();
$classCount = $classCountResult->fetch_assoc()['total_classes'];

// Close the database connection
$studentCountStmt->close();
$classCountStmt->close();
$conn->close();
?>

<?php include '../include/header.php'; ?>
<div class="flex">
    <?php include '../include/sidebar.php'; ?>
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center mb-6">Teacher Dashboard</h2>
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo $studentCount; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Classes</h3>
                <p class="text-3xl font-bold text-green-600 mt-2"><?php echo $classCount; ?></p>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
