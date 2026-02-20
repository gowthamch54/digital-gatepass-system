<?php
include "config.php";

$qrText = $_POST['qr_text'];

$result = $conn->query("SELECT * FROM gate_pass_requests WHERE qr_code='$qrText'");

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    if (is_null($row['out_scan_time'])) {

        $conn->query("UPDATE gate_pass_requests 
                      SET out_scan_time=NOW() 
                      WHERE id=".$row['id']);

        echo "OUT Time Recorded";

    } elseif (is_null($row['in_scan_time'])) {

        $conn->query("UPDATE gate_pass_requests 
                      SET in_scan_time=NOW(), status='Completed'
                      WHERE id=".$row['id']);

        echo "IN Time Recorded";

    } else {
        echo "Gate Pass Already Used";
    }

} else {
    echo "Invalid QR Code";
}
?>
