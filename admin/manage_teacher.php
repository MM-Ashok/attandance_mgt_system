<?php
session_start();
include '../include/db_connection.php';

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page if not a teacher
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current teacher profile data
$query = "SELECT * FROM teachers WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $profile_pic = $_FILES['profile_pic'];

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($phone_number)) {
        $error = "All fields are required.";
    } else {
        // Handle file upload for profile picture
        if ($profile_pic['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($profile_pic["name"]);
            move_uploaded_file($profile_pic["tmp_name"], $target_file);
        } else {
            $target_file = $teacher['profile_pic']; // Keep the old profile picture
        }

        // Update teacher profile
        $query = "UPDATE teachers SET first_name = ?, last_name = ?, phone_number = ?, profile_pic = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $first_name, $last_name, $phone_number, $target_file, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
        } else {
            $error = "Error updating profile. Please try again.";
        }
        $stmt->close();
    }
}
?>

<?php include '../include/header.php'; ?>

<!-- Page layout with sidebar -->
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl font-bold text-center mb-6">Manage Teacher Profile</h2>

        <!-- Display success or error message -->
        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Manage Profile Form -->
        <form action="manage_teacher.php" method="POST" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($teacher['first_name']); ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($teacher['last_name']); ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($teacher['phone_number']); ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="profile_pic" class="block text-gray-700">Profile Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php include '../include/footer.php'; ?>
