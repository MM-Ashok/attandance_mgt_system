<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    // If already logged in, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

include '../include/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to check admin login
    $query = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login details";
    }
}
?>

<?php include '../include/header.php'; ?>
<div class="container mx-auto my-10">
    <h2 class="text-2xl text-center">Admin Login</h2>
    <form action="login.php" method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow-md mt-6">
        <div class="mb-4">
            <label for="username" class="block text-gray-700">Username</label>
            <input type="text" id="username" name="username" required class="w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded">
        </div>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
        </div>
    </form>
</div>
<?php include '../include/footer.php'; ?>