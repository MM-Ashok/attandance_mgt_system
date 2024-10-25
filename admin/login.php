<?php
// Enable all error reporting
error_reporting(E_ALL);

// Display errors on the page (good for development, disable on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set a custom session name for admin
//session_name('admin_session');
session_start();
// Debugging output to see current session variables


if (isset($_SESSION['user_id']) && $_SESSION['username'] == 'admin') {
    echo "Already logged in as admin. Redirecting to dashboard...";

    // If already logged in, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

include '../include/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to check admin login
    $query = "SELECT * FROM miraiadmin WHERE username = 'admin'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Clear any existing session variables before starting a new session

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = 'admin';
        $_SESSION['profile_image'] = $user['profile_image'] ?? '/path/to/default/image.jpg'; // add default if null
        $_SESSION['email'] = $user['email'] ?? ''; // make sure email is not empty
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
