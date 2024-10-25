<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../include/db_connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch students based on teacher's assigned classes
$studentsQuery = "
    SELECT s.id, s.firstName, s.lastName
    FROM miraistudent s
    INNER JOIN miraiclass c ON s.class_id = c.ID
    INNER JOIN miraiteacherclasses tc ON tc.class_id = c.ID
    WHERE tc.teacher_id = ?
";
$studentsStmt = $conn->prepare($studentsQuery);
$studentsStmt->bind_param("i", $teacher_id);
$studentsStmt->execute();
$studentsResult = $studentsStmt->get_result();

$attendanceRecords = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $attendanceType = $_POST['attendanceType'];
    $date = $_POST['attendance_date'] ?? '';

    if ($attendanceType === 'All') {
        $attendanceQuery = "SELECT ta.attendance_date, ta.status FROM miraitakeattendance ta WHERE ta.student_id = ?";
        $attendanceStmt = $conn->prepare($attendanceQuery);
        $attendanceStmt->bind_param("i", $studentId);
    } else {
        $attendanceQuery = "SELECT ta.attendance_date, ta.status FROM miraitakeattendance ta WHERE ta.student_id = ? AND ta.attendance_date = ?";
        $attendanceStmt = $conn->prepare($attendanceQuery);
        $attendanceStmt->bind_param("is", $studentId, $date);
    }

    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();

    while ($row = $attendanceResult->fetch_assoc()) {
        $attendanceRecords[] = $row;
    }

    $attendanceStmt->close();
}

// Close the database connection
$conn->close();
?>

<?php include '../include/header.php'; ?>

<div class="flex">
    <?php include '../include/sidebar.php'; ?>
    <div class="flex-1 p-6 bg-gray-100">
        <h1 class="text-2xl font-bold mb-6">View Student Attendance</h1>

        <form action="viewStudentAttendance.php" method="POST" class="space-y-4">
            <div>
                <label for="studentId" class="block text-sm font-medium text-gray-700">Select Student</label>
                <select id="studentId" name="studentId" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select a Student</option>
                    <?php while ($student = $studentsResult->fetch_assoc()): ?>
                        <option value="<?php echo $student['id']; ?>">
                            <?php echo $student['firstName'] . ' ' . $student['lastName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="attendanceType" class="block text-sm font-medium text-gray-700">Attendance Filter</label>
                <select name="attendanceType" id="attendanceType" onchange="toggleDateField()" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select</option>
                    <option value="All">All</option>
                    <option value="By Single Date">By Single Date</option>
                </select>
            </div>

            <div id="dateField" class="hidden">
                <label for="attendance_date" class="block text-sm font-medium text-gray-700">Select Date</label>
                <input type="date" id="attendance_date" name="attendance_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">View Attendance</button>
            </div>
        </form>

        <?php if (!empty($attendanceRecords)): ?>
            <h2 class="text-xl font-semibold mt-8">Attendance Records</h2>
            <table id="vewStuAttendance" class="min-w-full mt-4 bg-white shadow-md rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($attendanceRecords as $record): ?>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap"><?php echo $record['attendance_date']; ?></td>
                            <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded <?php echo ($record['status'] == 'Present') ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                                        <?php echo $record['status']; ?>
                                    </span>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="mt-4 text-red-500">No attendance records found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../include/footer.php'; ?>