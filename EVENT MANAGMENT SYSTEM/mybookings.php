<?php
include "config.php";
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT events.title, events.event_date, events.price
        FROM bookings
        JOIN events ON bookings.event_id = events.id
        WHERE bookings.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f5f7fb;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background: #4a6cf7;
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin-left: 15px;
        font-weight: 500;
    }

    .container {
        padding: 30px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin: 15px auto;
        max-width: 500px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .event-title {
        font-size: 20px;
        font-weight: 600;
        color: #4a6cf7;
    }

    .details {
        margin-top: 10px;
        color: #555;
    }

    .price {
        margin-top: 10px;
        font-weight: bold;
        color: #27ae60;
        font-size: 18px;
    }

    .no-data {
        text-align: center;
        color: gray;
        margin-top: 40px;
    }
</style>

</head>
<body>

<div class="navbar">
    <div><strong>EventZone</strong></div>
    <div>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>My Bookings</h2>

    <?php
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<div class='card'>";
            echo "<div class='event-title'>".$row['title']."</div>";
            echo "<div class='details'>📅 Date: ".$row['event_date']."</div>";
            echo "<div class='price'>₹".$row['price']."</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-data'>No bookings found 😕</div>";
    }
    ?>
</div>

</body>
</html>