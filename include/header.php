<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started already
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include Select2 JS -->
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
            <?php if (isset($_SESSION['user_id']) || isset($_SESSION['teacher_id'])): // Check if user is logged in ?>
                <div class="relative">
                    <div class="flex items-center cursor-pointer" onclick="toggleDropdown()">
                        <!-- Profile picture -->
                        <img src="/student-attendance-system/assets/img/profile_male.png" alt="Profile" class="w-8 h-8 rounded-full mr-2">

                        <!-- Display user name -->
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                            <span>Admin</span>
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
            <?php endif; ?>
        </div>
    </header>
</body>
</html>
