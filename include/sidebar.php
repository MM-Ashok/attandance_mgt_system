<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<aside id="sidebar" class="w-64 bg-gray-800 text-white hidden md:block">

            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                <div class="p-6">
                    <h2 class="text-lg font-bold">Admin Menu</h2>
                    <ul class="mt-6">
                        <li class="mb-4">
                            <a href="/student-attendance-system/admin/dashboard.php" class="text-gray-300 hover:text-white">Dashboard</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/admin/add_class.php" class="text-gray-300 hover:text-white">Create Class</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/admin/add_teacher.php" class="text-gray-300 hover:text-white">Manage Teachers</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/admin/add_student.php" class="text-gray-300 hover:text-white">Manage Students</a>
                        </li>
                    </ul>
                </div>
            <?php elseif (isset($_SESSION['teacher_id'])): ?>
                <div class="p-6">
                    <h2 class="text-lg font-bold">Teacher Menu</h2>
                    <ul class="mt-6">
                       <li class="mb-4">
                            <a href="/student-attendance-system/teacher/dashboard.php" class="text-gray-300 hover:text-white">Dashboard</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/teacher/take_attendance.php" class="text-gray-300 hover:text-white">Take Attendance</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/teacher/view_class_attendance.php" class="text-gray-300 hover:text-white">View Class Attendance</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/teacher/viewStudentAttendance.php" class="text-gray-300 hover:text-white">View Student Attendance</a>
                        </li>
                        <li class="mb-4">
                            <a href="/student-attendance-system/teacher/downloadRecord.php" class="text-gray-300 hover:text-white">Today's Report (xls)</a>
                        </li>
                    </ul>
                </div>
            <?php elseif (isset($_SESSION['student_id'])): ?>
                <div class="p-6">
                    <h2 class="text-lg font-bold">Student Menu</h2>
                    <ul class="mt-6">
                       <li class="mb-4">
                            <a href="/student-attendance-system/student/dashboard.php" class="text-gray-300 hover:text-white">Dashboard</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>