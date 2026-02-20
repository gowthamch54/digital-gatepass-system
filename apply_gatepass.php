<?php
session_start();
include "config.php";

if ($_SESSION['role'] != "student") {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $leave = $_POST['leave_datetime'];
    $return = $_POST['expected_return_datetime'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO gate_pass_requests 
            (student_id, leave_datetime, expected_return_datetime, reason, mentor_status, warden_status)
            VALUES 
            ('$student_id', '$leave', '$return', '$reason', 'Pending', 'Pending')";

    $conn->query($sql);

    echo "Request Submitted Successfully<br><br>";
}
?>

<h2>Apply Gate Pass</h2>

<form method="POST">
Leaving:
<input type="datetime-local" name="leave_datetime" required><br><br>

Expected Arrival:
<input type="datetime-local" name="expected_return_datetime" required><br><br>

Reason:
<textarea name="reason" required></textarea><br><br>

<button type="submit">Apply</button>
</form>

<br>
<a href="student_dashboard.php">Back</a>
