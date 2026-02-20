<?php
include "config.php";

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Email from DB: " . $row['email'] . "<br>";
    }
} else {
    echo "No users found!";
}
?>
