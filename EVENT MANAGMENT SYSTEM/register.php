<?php
include 'config.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check);

    if($result->num_rows > 0){
        $message = "Email already registered!";
    } else {

        $sql = "INSERT INTO users (name, email, password) 
                VALUES ('$name', '$email', '$password')";

        if($conn->query($sql)){
            $message = "Registration Successful! You can login now.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(120deg, #36b9cc, #858796);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    background: white;
    padding: 40px;
    width: 350px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.card h2 {
    text-align: center;
    margin-bottom: 25px;
}

input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 12px;
    background: #36b9cc;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #2c9faf;
}

.link {
    text-align: center;
    margin-top: 15px;
}

.link a {
    color: #36b9cc;
    text-decoration: none;
}

.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="card">
<h2>Create Account</h2>

<?php if($message != "") echo "<p class='message'>$message</p>"; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<div class="link">
    Already have account? <a href="login.php">Login</a>
</div>

</div>
</body>
</html>