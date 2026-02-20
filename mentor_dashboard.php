<?php
session_start();
include "config.php";

if ($_SESSION['role'] != "mentor") {
    header("Location: login.php");
    exit();
}

echo "<h2>Mentor Dashboard</h2>";

$sql = "SELECT * FROM gate_pass_requests ORDER BY id DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    echo "<hr>";
    echo "<p><strong>Student ID:</strong> " . $row['student_id'] . "</p>";
    echo "<p><strong>Leaving:</strong> " . $row['leave_datetime'] . "</p>";
    echo "<p><strong>Expected Arrival:</strong> " . $row['expected_return_datetime'] . "</p>";
    echo "<p><strong>Reason:</strong> " . $row['reason'] . "</p>";

    echo "<p><strong>Mentor Status:</strong> " . $row['mentor_status'] . "</p>";
    echo "<p><strong>Warden Status:</strong> " . $row['warden_status'] . "</p>";

    if ($row['mentor_status'] == "Pending") {
        echo "<form method='POST' action='mentor_action.php'>";
        echo "<input type='hidden' name='id' value='".$row['id']."'>";
        echo "<button name='action' value='Approved'>Approve</button>";
        echo "<button name='action' value='Rejected'>Reject</button>";
        echo "</form>";
    }
}
?>

<br>
<a href="logout.php">Logout</a>
