<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gate Pass System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="header">
    <div><strong>SIMATS Gate Pass System</strong></div>
    <div>
        <?php echo $_SESSION['name']; ?> |
        <a href="logout.php" style="color:white; text-decoration:none;">Logout</a>
    </div>
</div>

<div class="sidebar">
