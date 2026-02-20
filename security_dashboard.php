<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "security") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Security Dashboard</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js"></script>
</head>
<body>

<h2>Security Dashboard</h2>

<h3>Live QR Scan</h3>

<div id="reader" style="width:300px;"></div>
<br>
<button onclick="startCamera()">Start Camera</button>
<p id="scanStatus"></p>

<hr>

<h3>Manual QR Entry (If Camera Fails)</h3>
<form method="POST">
    Enter QR Code:
    <input type="text" name="manual_qr" required>
    <button type="submit" name="manual_scan">Scan</button>
</form>

<hr>

<?php

// Manual Scan Processing
if (isset($_POST['manual_scan'])) {

    $qrText = $_POST['manual_qr'];

    $result = $conn->query("SELECT * FROM gate_pass_requests WHERE qr_code='$qrText'");

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if (is_null($row['out_scan_time'])) {

            $conn->query("UPDATE gate_pass_requests 
                          SET out_scan_time=NOW() 
                          WHERE id=".$row['id']);

            echo "<p>OUT Time Recorded</p>";

        } elseif (is_null($row['in_scan_time'])) {

            $conn->query("UPDATE gate_pass_requests 
                          SET in_scan_time=NOW(), status='Completed'
                          WHERE id=".$row['id']);

            echo "<p>IN Time Recorded</p>";

        } else {
            echo "<p>Gate Pass Already Used</p>";
        }

    } else {
        echo "<p>Invalid QR Code</p>";
    }
}
?>

<hr>

<h3>All Gate Pass Records</h3>

<?php
$result = $conn->query("SELECT * FROM gate_pass_requests ORDER BY id DESC");

while ($row = $result->fetch_assoc()) {

    echo "<hr>";
    echo "<p><strong>Student ID:</strong> ".$row['student_id']."</p>";
    echo "<p><strong>Leaving:</strong> ".$row['leave_datetime']."</p>";
    echo "<p><strong>Expected Arrival:</strong> ".$row['expected_return_datetime']."</p>";
    echo "<p><strong>Out Time:</strong> ".$row['out_scan_time']."</p>";
    echo "<p><strong>In Time:</strong> ".$row['in_scan_time']."</p>";
    echo "<p><strong>Status:</strong> ".$row['status']."</p>";
}
?>

<br>
<a href="logout.php">Logout</a>

<script>

function startCamera() {

    const status = document.getElementById("scanStatus");
    status.innerHTML = "Initializing camera...";

    const html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(devices => {

        if (devices.length > 0) {

            let cameraId = devices[0].id;

            html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: 250
                },
                function(decodedText) {

                    status.innerHTML = "QR Detected";

                    fetch("scan_process.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "qr_text=" + encodeURIComponent(decodedText)
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        html5QrCode.stop();
                        status.innerHTML = "Scan Complete";
                    });

                },
                function(errorMessage) {
                    // Ignore scan errors silently
                }
            );

        } else {
            status.innerHTML = "No camera found.";
        }

    }).catch(err => {
        status.innerHTML = "Camera permission denied or not supported.";
    });
}

</script>

</body>
</html>
