<?php
session_start();
// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<?php include '../include/header.php'; ?>
<div class="flex">
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content -->
    <div class="flex-1 p-6 bg-gray-100">
        <h1 class="text-2xl font-semibold text-gray-800">Admin Dashboard</h1>
        <div class="mt-4">
            <p>Welcome to the Admin Dashboard.</p>
        </div>
    </div>
</div>
<?php include '../include/footer.php'; ?>
