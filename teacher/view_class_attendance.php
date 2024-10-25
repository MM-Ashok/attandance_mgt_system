<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../include/db_connection.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../login.php");
    exit();
}

$teacherId = $_SESSION['teacher_id']; // Teacher's ID

// Fetch the classes assigned to the teacher
$classQuery = "SELECT mc.ID, mc.className
               FROM miraiclass mc
               JOIN miraiteacherclasses mtc ON mc.ID = mtc.class_id
               WHERE mtc.teacher_id = ?";
$classStmt = $conn->prepare($classQuery);
$classStmt->bind_param("i", $teacherId);
$classStmt->execute();
$classResult = $classStmt->get_result();

// Handle form submission for filtering
$selectedClassId = null;
$selectedDate = null;
$attendanceRecords = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedClassId = $_POST['classId'];
    $selectedDate = $_POST['attendance_date'];

    // Fetch attendance records based on selected class and date
    $attendanceQuery = "SELECT ms.firstName, ms.lastName, ms.admissionNumber, mta.status
                        FROM miraitakeattendance mta
                        JOIN miraistudent ms ON mta.student_id = ms.id
                        WHERE mta.class_id = ? AND mta.attendance_date = ?";
    $attendanceStmt = $conn->prepare($attendanceQuery);
    $attendanceStmt->bind_param("is", $selectedClassId, $selectedDate);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();

    while ($row = $attendanceResult->fetch_assoc()) {
        $attendanceRecords[] = $row;
    }
}
?>

<?php include '../include/header.php'; ?>
<div class="flex">
    <?php include '../include/sidebar.php'; ?>
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">View Attendance</h2>

        <form action="view_class_attendance.php" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
            <div>
                <label for="classId" class="block text-gray-700">Select Class</label>
                <select id="classId" name="classId" required class="w-full p-2 border border-gray-300 rounded">
                    <option value="">Select a Class</option>
                    <?php while ($class = $classResult->fetch_assoc()): ?>
                        <option value="<?php echo $class['ID']; ?>"
                            <?php echo ($selectedClassId == $class['ID']) ? 'selected' : ''; ?>>
                            <?php echo $class['className']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mt-4">
                <label for="attendance_date" class="block text-gray-700">Select Date</label>
                <input type="date" id="attendance_date" name="attendance_date" required class="w-full p-2 border border-gray-300 rounded"
                       value="<?php echo isset($selectedDate) ? $selectedDate : ''; ?>">
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
            </div>
        </form>

        <!-- Display the attendance records if available -->
        <?php if (!empty($attendanceRecords)): ?>
            <div class="mt-6">
                <h3 class="text-xl font-bold">Attendance Records</h3>
                <table id="viewAttendance" class="min-w-full mt-4 bg-white shadow-md rounded">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Student Name</th>
                            <th class="px-4 py-2 text-left">Admission Number</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceRecords as $record): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $record['firstName'] . ' ' . $record['lastName']; ?></td>
                                <td class="border px-4 py-2"><?php echo $record['admissionNumber']; ?></td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded <?php echo ($record['status'] == 'Present') ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                                        <?php echo $record['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="text-red-500 mt-4">No attendance records found for the selected class and date.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../include/footer.php'; ?>
