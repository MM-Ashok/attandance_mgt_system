<?php
// Enable all error reporting
error_reporting(E_ALL);

// Display errors on the page (good for development, disable on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set a custom session name for teacher
//session_name('teacher_session');
session_start();

// Redirect to teacher dashboard if already logged in
if (isset($_SESSION['teacher_id']) && $_SESSION['emailAddress']) {
    header("Location: dashboard.php");
    exit();
}

include '../include/db_connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];

    // Basic validation for form inputs
    if (empty($emailAddress) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prepare query to check teacher login
        $query = "SELECT * FROM miraiteachers WHERE emailAddress = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $emailAddress);
        $stmt->execute();
        $result = $stmt->get_result();
        $teacher = $result->fetch_assoc();

        // Verify password
        if ($teacher && password_verify($password, $teacher['password'])) {
            // Set session variables and redirect to teacher dashboard

            $_SESSION['teacher_id'] = $teacher['Id'];
            $_SESSION['firstName'] = $teacher['firstName']; // Store first name
            $_SESSION['lastName'] = $teacher['lastName']; // Store first name
            $_SESSION['emailAddress'] = $teacher['emailAddress'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<?php include '../include/header.php'; ?>
<div class="container mx-auto my-10">
    <h2 class="text-2xl text-center">Teacher Login</h2>
    <form action="login.php" method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow-md mt-6">
        <div class="mb-4">
            <label for="emailAddress" class="block text-gray-700">Email Address</label>
            <input type="email" id="emailAddress" name="emailAddress" required class="w-full p-2 border border-gray-300 rounded">
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