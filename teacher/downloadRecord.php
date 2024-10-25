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

// Get today's date
$today = date('Y-m-d');

// Fetch attendance records for today
// Fetch attendance records for today
$attendanceQuery = "SELECT
                        ta.teacher_id,
                        c.className,
                        s.firstName,
                        s.lastName,
                        s.admissionNumber,
                        ta.attendance_date,
                        ta.status
                    FROM
                        miraitakeattendance ta
                    JOIN
                        miraistudent s ON ta.student_id = s.id
                    JOIN
                        miraiclass c ON ta.class_id = c.ID
                    WHERE
                        ta.attendance_date = ?";
$attendanceStmt = $conn->prepare($attendanceQuery);
$attendanceStmt->bind_param("s", $today);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->get_result();

// Set header for XLS download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="attendance_report_' . $today . '.xls"');

// Output the table headers
echo "Teacher ID\tClass Name\tFirst Name\tLast Name\tAdmission Number\tAttendance Date\tStatus\n";

// Output the attendance records
while ($row = $attendanceResult->fetch_assoc()) {
    echo $row['teacher_id'] . "\t" .
         $row['className'] . "\t" .
         $row['firstName'] . "\t" .
         $row['lastName'] . "\t" .
         $row['admissionNumber'] . "\t" .
         $row['attendance_date'] . "\t" .
         $row['status'] . "\n";
}

// Close the statement and connection
$attendanceStmt->close();
$conn->close();
exit();
?>
