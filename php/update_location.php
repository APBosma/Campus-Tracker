<?php
session_start();

// Connect to database
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "campus_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $_SESSION["flash"] = [
        "text" => "Database connection failed.",
        "type" => "error"
    ];
    header("Location: ../edit_location.php");
    exit();
}

// Get form data
$id = $_POST['location_id'];
$message = trim($_POST['message']);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];


// Update announcement
// ADD HOURS SOON
$stmt = $conn->prepare("
    UPDATE locations
    SET name = ?, max_capacity = ?
    WHERE announcement_id = ?
");

$stmt->bind_param("sii", $name, $max_capacity, $id);

if ($stmt->execute()) {
    $_SESSION["flash"] = [
        "text" => "Location updated successfully!",
        "type" => "success"
    ];
} else {
    $_SESSION["flash"] = [
        "text" => "Failed to update location.",
        "type" => "error"
    ];
}

$stmt->close();
$conn->close();

// Redirect back
header("Location: ../edit_location.php");
exit();
?>