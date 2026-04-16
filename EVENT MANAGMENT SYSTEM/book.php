<?php
include "config.php";
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = intval($_GET['id']); // safer

// Get event details
$check = mysqli_query($conn, "SELECT * FROM events WHERE id='$event_id'");
$event = mysqli_fetch_assoc($check);

$status = "";
$seat_number = "";

if($event && $event['available_seats'] > 0){

    // 🎲 Generate Random Seat Number (Example: A12, B7, C25)
    $row_letter = chr(rand(65, 70)); // A to F
    $seat_digit = rand(1, 50);
    $seat_number = $row_letter . $seat_digit;

    // Insert booking
    mysqli_query($conn, 
        "INSERT INTO bookings (user_id, event_id, seat_number) 
         VALUES ('$user_id', '$event_id', '$seat_number')"
    );

    // Reduce seat count
    mysqli_query($conn, 
        "UPDATE events 
         SET available_seats = available_seats - 1 
         WHERE id='$event_id'"
    );

    $status = "success";

} else {
    $status = "failed";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Status</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #ff512f, #dd2476, #1fa2ff);
    color: white;
}

.card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    width: 400px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
}

h2 {
    margin-bottom: 20px;
}

.success {
    color: #00ffae;
    font-size: 20px;
    font-weight: bold;
}

.failed {
    color: #ff4b5c;
    font-size: 20px;
    font-weight: bold;
}

.seat {
    margin-top: 15px;
    font-size: 22px;
    font-weight: bold;
    background: rgba(0,0,0,0.3);
    padding: 10px;
    border-radius: 10px;
}

button {
    margin-top: 25px;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    background: linear-gradient(45deg, #00f260, #0575e6);
    color: white;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.05);
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="card">

<?php if($status == "success") { ?>

    <h2>🎉 Booking Confirmed!</h2>
    <div class="success">Ticket Booked Successfully!</div>
    <p><strong>Event:</strong> <?php echo $event['title']; ?></p>
    <div class="seat">🎟 Seat Number: <?php echo $seat_number; ?></div>

<?php } else { ?>

    <h2>⚠ Booking Failed</h2>
    <div class="failed">No Seats Available!</div>

<?php } ?>

<a href="index.php">
    <button>Back to Events</button>
</a>

</div>

</body>
</html>