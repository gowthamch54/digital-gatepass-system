<?php
session_start();
include "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "warden") {
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];
$action = $_POST['action'];

if ($action == "Approved") {

    // Generate unique QR text
    $qr_code = "GATEPASS_" . $id . "_" . time();

    $sql = "UPDATE gate_pass_requests 
            SET warden_status='Approved',
                qr_code='$qr_code'
            WHERE id='$id'";

} else {

    $sql = "UPDATE gate_pass_requests 
            SET warden_status='Rejected'
            WHERE id='$id'";
}

$conn->query($sql);

header("Location: warden_dashboard.php");
exit();
?>
