<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if user is NOT logged in and is a teacher
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['emailAddress'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../include/db_connection.php';

?>

<?php include '../include/header.php'; ?>
<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center mb-6">Teacher Dashboard</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Content goes here -->
        </div>
    </div>
</div>
<?php include '../include/footer.php'; ?>
