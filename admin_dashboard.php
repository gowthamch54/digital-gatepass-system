<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit();
}

echo "<h2>Admin Dashboard</h2>";

$result = $conn->query("SELECT * FROM gate_pass_requests ORDER BY id DESC");

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<hr>";
        echo "<p><strong>Student ID:</strong> ".$row['student_id']."</p>";
        echo "<p><strong>Leaving:</strong> ".$row['leave_datetime']."</p>";
        echo "<p><strong>Expected Arrival:</strong> ".$row['expected_return_datetime']."</p>";
        echo "<p><strong>Mentor Status:</strong> ".$row['mentor_status']."</p>";
        echo "<p><strong>Warden Status:</strong> ".$row['warden_status']."</p>";
        echo "<p><strong>Out Time:</strong> ".$row['out_scan_time']."</p>";
        echo "<p><strong>In Time:</strong> ".$row['in_scan_time']."</p>";
        echo "<p><strong>Final Status:</strong> ".$row['status']."</p>";
    }

} else {
    echo "No records found.";
}

echo "<br><br><a href='logout.php'>Logout</a>";
?>
