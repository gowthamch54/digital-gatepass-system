<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();

        if ($row['password'] == $password) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'student') {
                header("Location: student_dashboard.php");
            } elseif ($row['role'] == 'mentor') {
                header("Location: mentor_dashboard.php");
            } elseif ($row['role'] == 'warden') {
                header("Location: warden_dashboard.php");
            } elseif ($row['role'] == 'security') {
                header("Location: security_dashboard.php");
            } elseif ($row['role'] == "admin") {
                header("Location: admin_dashboard.php");
            }
            exit();

        } else {
            $message = "Invalid Email or Password!";
        }

    } else {
        $message = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIMATS Gate Pass System</title>
    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('assets/images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
        }

        .login-box {
            width: 350px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            text-align: center;
            color: #fff;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #00b894;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #019875;
        }

        .error {
            color: #ff7675;
            margin-bottom: 10px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 14px;
        }

    </style>
</head>
<body>

<div class="overlay">
    <div class="login-box">
        <h2>SIMATS Engineering</h2>
        <h3>Gate Pass Login</h3>

        <?php if($message != "") { ?>
            <div class="error"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
        </form>
    </div>
</div>

<div class="footer">
    © 2026 SIMATS Gate Pass System | Privacy Policy
</div>

</body>
</html>
