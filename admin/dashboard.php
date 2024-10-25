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

// Fetch total number of teachers
$totalTeachersQuery = "SELECT COUNT(*) as total FROM miraiteachers";
$totalTeachersResult = $conn->query($totalTeachersQuery);
$totalTeachers = $totalTeachersResult->fetch_assoc()['total'];

// Fetch total number of classes
$totalClassesQuery = "SELECT COUNT(*) as total FROM miraiclass";
$totalClassesResult = $conn->query($totalClassesQuery);
$totalClasses = $totalClassesResult->fetch_assoc()['total'];

// Fetch total number of Student
$totalStudentsQuery = "SELECT COUNT(*) as total FROM miraistudent";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total'];
?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center mb-6">Dashboard</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Total Teachers Box -->
            <div class="bg-white p-6 rounded shadow-md flex items-center">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 14v1m8.121-10.121l-.707.707m-1.415-1.415l-.707.707M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold">Total Teachers</h3>
                    <p class="text-3xl font-bold"><?php echo $totalTeachers; ?></p>
                </div>
            </div>

            <!-- Total Teachers Box -->
            <div class="bg-white p-6 rounded shadow-md flex items-center">
                <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4a4 4 0 00-4 4v2H4v6h2a8 8 0 0016 0h2v-6h-6V8a4 4 0 00-4-4zM8 8a4 4 0 118 0" />
                </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold">Total Students</h3>
                    <p class="text-3xl font-bold"><?php echo $totalStudents; ?></p>
                </div>
            </div>

            <!-- Total Classes Box -->
            <div class="bg-white p-6 rounded shadow-md flex items-center">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M3 9h18M3 15h18M3 21h18" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold">Total Classes</h3>
                    <p class="text-3xl font-bold"><?php echo $totalClasses; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../include/footer.php'; ?>
