<?php
include "config.php";
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>BookYourShow - Events</title>
<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

/* NAVBAR */
.navbar {
    background: rgba(0,0,0,0.3);
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar h2 {
    margin: 0;
}

.navbar a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
    font-weight: bold;
}

.navbar a:hover {
    text-decoration: underline;
}

/* CONTAINER */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    padding: 40px;
}

/* CARD DESIGN */
.card {
    background: white;
    color: black;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-10px);
}

.card h3 {
    margin-top: 0;
    color: #764ba2;
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
}

.book-btn {
    background: linear-gradient(45deg, #ff512f, #dd2476);
    color: white;
}

.book-btn:hover {
    opacity: 0.9;
}

.sold-btn {
    background: gray;
    color: white;
    cursor: not-allowed;
}

.section-title {
    text-align: center;
    margin-top: 30px;
    font-size: 28px;
}

</style>
</head>
<body>

<div class="navbar">
    <h2>🎟 BookYourShow</h2>
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> |
        <a href="mybookings.php">My Bookings</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<h2 class="section-title">Available Events</h2>

<div class="container">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="card">
        <h3><?php echo $row['title']; ?></h3>
        <p><strong>Date:</strong> <?php echo $row['event_date']; ?></p>
        <p><strong>Available Seats:</strong> <?php echo $row['available_seats']; ?></p>
        <p><strong>Price:</strong> ₹<?php echo $row['price']; ?></p>

        <?php if($row['available_seats'] > 0) { ?>
            <a href="book.php?id=<?php echo $row['id']; ?>">
                <button class="book-btn">Book Now</button>
            </a>
        <?php } else { ?>
            <button class="sold-btn" disabled>Sold Out</button>
        <?php } ?>
    </div>
<?php } ?>
</div>

</body>
</html>