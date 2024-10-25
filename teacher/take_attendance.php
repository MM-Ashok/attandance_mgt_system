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

// Handle attendance form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classId = $_POST['classId'];
    $attendanceDate = $_POST['attendance_date'];

    // Check if attendance has already been recorded for the selected class today
    $checkQuery = "SELECT * FROM miraitakeattendance
                   WHERE class_id = ? AND attendance_date = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("is", $classId, $attendanceDate);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Attendance has already been taken for today
        $error = "Attendance has been taken for today!";
    } else {
        // Attendance hasn't been taken yet, proceed with recording attendance
        $attendanceStatus = isset($_POST['attendance']) ? $_POST['attendance'] : [];

        // Fetch all students in the selected class to handle both present and absent
        $studentQuery = "SELECT id FROM miraistudent WHERE class_id = ?";
        $studentStmt = $conn->prepare($studentQuery);
        $studentStmt->bind_param("i", $classId);
        $studentStmt->execute();
        $studentsResult = $studentStmt->get_result();

        // Loop through all students and record attendance
        while ($student = $studentsResult->fetch_assoc()) {
            $studentId = $student['id'];

            // Check if the student is marked as "Present"
            $status = isset($attendanceStatus[$studentId]) ? "Present" : "Absent";

            // Insert or update the attendance record for each student
            $attendanceQuery = "INSERT INTO miraitakeattendance (teacher_id, class_id, student_id, attendance_date, status)
                                VALUES (?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE status = ?";
            $attendanceStmt = $conn->prepare($attendanceQuery);
            $attendanceStmt->bind_param("iiisss", $teacherId, $classId, $studentId, $attendanceDate, $status, $status);
            $attendanceStmt->execute();
        }

        $attendanceStmt->close();
        $success = "Attendance has been recorded successfully!";
    }
}

// Fetch students based on the selected class
$students = [];
if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];

    $studentQuery = "SELECT * FROM miraistudent WHERE class_id = ?";
    $studentStmt = $conn->prepare($studentQuery);
    $studentStmt->bind_param("i", $classId);
    $studentStmt->execute();
    $studentsResult = $studentStmt->get_result();

    while ($row = $studentsResult->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<?php include '../include/header.php'; ?>
<div class="flex">
    <?php include '../include/sidebar.php'; ?>
    <div class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl text-center">Take Attendance</h2>

        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="take_attendance.php" method="POST" class="mx-auto bg-white p-6 rounded shadow-md mt-6">
    <div>
        <label for="classId" class="block text-gray-700">Select Class</label>
        <select id="classIdDropdown" name="classIdDropdown" required class="w-full p-2 border border-gray-300 rounded" onchange="location = this.value;">
            <option value="">Select a Class</option>
            <?php while ($class = $classResult->fetch_assoc()): ?>
                <option value="take_attendance.php?classId=<?php echo $class['ID']; ?>"
                    <?php echo (isset($_GET['classId']) && $_GET['classId'] == $class['ID']) ? 'selected' : ''; ?>>
                    <?php echo $class['className']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Hidden field to store selected classId -->
    <?php if (isset($_GET['classId'])): ?>
        <input type="hidden" name="classId" value="<?php echo $_GET['classId']; ?>">
    <?php endif; ?>

    <!-- Attendance and other form fields here -->
    <?php if (!empty($students)): ?>
        <div class="mt-4">
            <label for="attendance_date" class="block text-gray-700">Date</label>
            <input type="date" id="attendance_date" name="attendance_date" required class="w-full p-2 border border-gray-300 rounded" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-bold">Students</h3>
            <table id="takeAttendance" class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 p-2 text-left">Student Name</th>
                        <th class="border border-gray-300 p-2 text-left">Admission Number</th>
                        <th class="border border-gray-300 p-2 text-left">Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>

                            <td class="border border-gray-300 p-2">
                                <?php echo $student['firstName'] . ' ' . $student['lastName']; ?>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <?php echo $student['admissionNumber']; ?>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="checkbox" name="attendance[<?php echo $student['id']; ?>]" value="Present">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Take Attendance</button>
        </div>
    <?php elseif (isset($_GET['classId'])): ?>
        <p class="text-red-500 mt-4">No students found for this class.</p>
    <?php endif; ?>
</form>

    </div>
</div>

<?php include '../include/footer.php'; ?>
