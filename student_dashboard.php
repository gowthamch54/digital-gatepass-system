<?php
session_start();
include "config.php";
require_once "phpqrcode/qrlib.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "student") {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

echo "<h2>Student Dashboard</h2>";
echo "<p>Welcome: " . $_SESSION['name'] . "</p>";
echo "<a href='apply_gatepass.php'>Apply Gate Pass</a><br><br>";

$sql = "SELECT * FROM gate_pass_requests 
        WHERE student_id='$student_id'
        ORDER BY id DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    if (!file_exists("qrcodes")) {
        mkdir("qrcodes");
    }

    while ($row = $result->fetch_assoc()) {

        echo "<hr>";
        echo "<p><strong>Leaving:</strong> " . $row['leave_datetime'] . "</p>";
        echo "<p><strong>Expected Arrival:</strong> " . $row['expected_return_datetime'] . "</p>";
        echo "<p><strong>Reason:</strong> " . $row['reason'] . "</p>";
        echo "<p><strong>Mentor Status:</strong> " . $row['mentor_status'] . "</p>";
        echo "<p><strong>Warden Status:</strong> " . $row['warden_status'] . "</p>";

        if ($row['warden_status'] == "Approved" && !empty($row['qr_code'])) {

            $qrText = $row['qr_code'];
            $fileName = "qrcodes/qr_" . $row['id'] . ".png";

            QRcode::png($qrText, $fileName, QR_ECLEVEL_L, 8);

            echo "<p><strong>Scan this QR at Security:</strong></p>";
            echo "<img src='$fileName'><br>";
            echo "<p><strong>QR Code Value (Manual Entry):</strong> $qrText</p><br>";
        }
    }

} else {
    echo "No Gate Pass Requests Found.";
}

echo "<br><a href='logout.php'>Logout</a>";
?>
