<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started already
}

// Include database connection
include '../include/db_connection.php';

// Default profile picture for teacher
$profilePicture = "/student-attendance-system/uploads/dummyImage.webp"; // Fallback image

// Fetch teacher's profile image if logged in as a teacher
if (isset($_SESSION['teacher_id'])) {
    $teacher_id = $_SESSION['teacher_id'];
    $query = "SELECT profile_image FROM miraiteachers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['profile_image'])) {
            $profilePicture = $row['profile_image'];
        }
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <?php if (isset($_SESSION['teacher_id'])): ?>
       <link href="/student-attendance-system/assets/teacher/css/style.css" rel="stylesheet">
    <?php endif; ?>
    <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
        <link href="/student-attendance-system/assets/admin/css/style.css" rel="stylesheet">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Toggle dropdown menu visibility
        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Student Attendance Management System</h1>

            <!-- Profile Section -->
                <div class="relative">
                    <div class="flex items-center cursor-pointer" onclick="toggleDropdown()">
                        <!-- Profile picture -->
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                           <img src="http://localhost/student-attendance-system/uploads/<?php echo $_SESSION['profile_image'];?>" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                        <?php endif; ?>
                        <?php if (isset($_SESSION['teacher_id'])): ?>
                           <img src="http://localhost/student-attendance-system/uploads/<?php echo $profilePicture; ?>" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                        <?php endif; ?>
                        <!-- Display user name -->
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                            <span><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Email not available'; ?></span>
                        <?php elseif (isset($_SESSION['firstName']) && isset($_SESSION['lastName'])): ?>
                            <span><?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Dropdown menu -->
                    <div id="dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg hidden">
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                            <a href="/student-attendance-system/admin/change_profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Change Profile
                            </a>
                            <a href="/student-attendance-system/admin/logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Logout
                            </a>
                        <?php elseif (isset($_SESSION['teacher_id'])): ?>
                            <a href="/student-attendance-system/teacher/change_profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Change Profile
                            </a>
                            <a href="/student-attendance-system/teacher/logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                Logout
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
    </header>
</body>
</html>
