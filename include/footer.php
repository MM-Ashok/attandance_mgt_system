<!-- Footer -->
<footer class="bg-gray-800 text-white p-4 mt-4 text-center">
    <p>&copy; 2024 Student Attendance Management System</p>
</footer>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#classId').select2({
        placeholder: "Select Classes"
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#studentsTable, #teacherTable, #classesTable').DataTable({
            "paging": true,        // Enable pagination
            "lengthChange": true,  // Allow the user to change the number of rows per page
            "searching": true,     // Enable search functionality
            "ordering": true,      // Enable sorting
            "info": true,          // Show table info
            "autoWidth": false     // Disable automatic column width calculation
        });
    });

</script>
</body>
</html>
