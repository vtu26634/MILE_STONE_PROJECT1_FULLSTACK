<?php
include "config.php";
session_start();

// 🔒 Allow only admin
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1){
    header("Location: login.php");
    exit();
}

$message = "";

// ➕ Add Event
if(isset($_POST['add_event'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $date = $_POST['event_date'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];

    $sql = "INSERT INTO events (title, event_date, available_seats, price) 
            VALUES ('$title', '$date', '$seats', '$price')";

    if(mysqli_query($conn, $sql)){
        $message = "Event Added Successfully!";
    } else {
        $message = "Error Adding Event!";
    }
}

// ❌ Delete Event
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM events WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// Fetch events
$result = mysqli_query($conn, "SELECT * FROM events");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
}

.navbar {
    background: rgba(0,0,0,0.3);
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
}

.navbar a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.container {
    padding: 40px;
}

.form-box {
    background: white;
    color: black;
    padding: 25px;
    border-radius: 15px;
    width: 400px;
    margin-bottom: 40px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.add-btn {
    background: linear-gradient(45deg, #00c6ff, #0072ff);
    color: white;
    width: 100%;
}

.add-btn:hover {
    opacity: 0.9;
}

table {
    width: 100%;
    background: white;
    color: black;
    border-radius: 10px;
    overflow: hidden;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: #2a5298;
    color: white;
}

tr:nth-child(even){
    background: #f2f2f2;
}

.delete-btn {
    background: red;
    color: white;
    padding: 6px 10px;
}
.message {
    color: yellow;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="navbar">
    <h2>⚙ Admin Panel</h2>
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> |
        <a href="index.php">User View</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<div class="form-box">
    <h3>Add New Event</h3>
    <form method="POST">
        <input type="text" name="title" placeholder="Event Title" required>
        <input type="date" name="event_date" required>
        <input type="number" name="seats" placeholder="Available Seats" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button type="submit" name="add_event" class="add-btn">Add Event</button>
    </form>
</div>

<h3>All Events</h3>

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Date</th>
    <th>Seats</th>
    <th>Price</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['event_date']; ?></td>
    <td><?php echo $row['available_seats']; ?></td>
    <td>₹<?php echo $row['price']; ?></td>
    <td>
        <a href="admin.php?delete=<?php echo $row['id']; ?>">
            <button class="delete-btn">Delete</button>
        </a>
    </td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>