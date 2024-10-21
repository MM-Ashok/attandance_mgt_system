<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                    <img src="/student-attendance-system/admin/assets/img/profile_male.png" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                    <span>Admin</span>
                </div>

                <!-- Dropdown menu -->
                <div id="dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg hidden">
                    <a href="/student-attendance-system/admin/change_profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        Change Profile
                    </a>
                    <a href="/student-attendance-system/admin/logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>
