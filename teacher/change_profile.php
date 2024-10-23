<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['teacher_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include '../include/db_connection.php';

// Fetch the teacher's current profile details
$teacher_id = $_SESSION['teacher_id'];
$query = "SELECT * FROM miraiteachers WHERE Id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $profile_image = $teacher['profile_image']; // Keep the existing image if no new image is uploaded

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../uploads/";
        $profile_image = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $profile_image;

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $error = "Sorry, there was an error uploading your profile image.";
        }
    }

    // Check if password is being updated
    $password = $teacher['password']; // Keep the existing password if not updated
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the new password
    }

    // Update the teacher's profile in the database
    $update_query = "UPDATE miraiteachers SET firstName = ?, lastName = ?, emailAddress = ?, password = ?, phoneNo = ?, profile_image = ? WHERE Id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssssssi', $firstName, $lastName, $emailAddress, $password, $phoneNo, $profile_image, $teacher_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
?>

<?php include '../include/header.php'; ?>
<div class="flex">
    <!-- Include sidebar -->
    <?php include '../include/sidebar.php'; ?>

    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">Change Profile</h2>
        <form action="change_profile.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md mt-6">
                <div class="teacher_Pic">
                    <?php if (!empty($teacher['profile_image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($teacher['profile_image']); ?>" alt="Profile Image" class="mt-2 h-20 w-20 rounded">
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <!-- First Name -->
                    <div class="mb-4">
                        <label for="firstName" class="block text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($teacher['firstName']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label for="lastName" class="block text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($teacher['lastName']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="emailAddress" class="block text-gray-700">Email Address</label>
                        <input type="email" id="emailAddress" name="emailAddress" value="<?php echo htmlspecialchars($teacher['emailAddress']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Password (optional) -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700">Password (Leave blank to keep current password)</label>
                        <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label for="phoneNo" class="block text-gray-700">Phone Number</label>
                        <input type="text" id="phoneNo" name="phoneNo" value="<?php echo htmlspecialchars($teacher['phoneNo']); ?>" required class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    <!-- Profile Image -->
                    <div class="mb-4">
                        <label for="profile_image" class="block text-gray-700">Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" class="w-full p-2 border border-gray-300 rounded">

                    </div>

                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Profile</button>
                    </div>
            </div>
              <!-- Display success or error messages -->
              <?php if (isset($success)): ?>
                        <p class="text-green-500 text-center"><?php echo $success; ?></p>
                    <?php elseif (isset($error)): ?>
                        <p class="text-red-500 text-center"><?php echo $error; ?></p>
                    <?php endif; ?>
        </form>
    </div>
</div>
<?php include '../include/footer.php'; ?>
