<?php
include "config.php";

$id = $_POST['id'];
$action = $_POST['action'];

$sql = "UPDATE gate_pass_requests 
        SET mentor_status='$action' 
        WHERE id='$id'";

$conn->query($sql);

header("Location: mentor_dashboard.php");
?>
