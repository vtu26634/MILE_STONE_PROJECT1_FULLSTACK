<?php
include "config.php";
session_start();

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            // ✅ STORE ALL REQUIRED SESSION VALUES
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['name'] = $row['name'];   // 🔥 THIS FIXES YOUR ERROR
            $_SESSION['is_admin'] = isset($row['is_admin']) ? $row['is_admin'] : 0;

            if($_SESSION['is_admin'] == 1){
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();

        } else {
            $error = "Wrong Password!";
        }

    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login - BookYourShow</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(-45deg, #ff512f, #dd2476, #1fa2ff, #12d8fa);
    background-size: 400% 400%;
    animation: gradientMove 10s ease infinite;
}

@keyframes gradientMove {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

.card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(15px);
    padding: 40px;
    width: 350px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    color: white;
}

.card h2 {
    text-align: center;
    margin-bottom: 25px;
}

input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 14px;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 8px;
    border: none;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    background: linear-gradient(45deg, #00f260, #0575e6);
    color: white;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
    opacity: 0.9;
}

.error {
    background: rgba(255, 0, 0, 0.2);
    padding: 8px;
    border-radius: 6px;
    text-align: center;
    margin-bottom: 10px;
}

.link {
    text-align: center;
    margin-top: 15px;
}

.link a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

.link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="card">
<h2>Welcome Back 👋</h2>

<?php if($error != "") echo "<div class='error'>$error</div>"; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Login</button>
</form>

<div class="link">
    Don’t have account? <a href="register.php">Register</a>
</div>

</div>

</body>
</html>