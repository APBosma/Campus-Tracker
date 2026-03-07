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
    header("Location: announcements.php");
    exit();
}

// Get form data
$id = $_POST['announcement_id'];
$message = trim($_POST['message']);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Validate dates
if ($end_date <= $start_date) {
    $_SESSION["flash"] = [
        "text" => "End date must be after the start date.",
        "type" => "error"
    ];
    header("Location: ../edit_announcement.php?id=" . $id);
    exit();
}

// Update announcement
$stmt = $conn->prepare("
    UPDATE announcements 
    SET message = ?, start_date = ?, end_date = ?
    WHERE announcement_id = ?
");

$stmt->bind_param("sssi", $message, $start_date, $end_date, $id);

if ($stmt->execute()) {
    $_SESSION["flash"] = [
        "text" => "Announcement updated successfully!",
        "type" => "success"
    ];
} else {
    $_SESSION["flash"] = [
        "text" => "Failed to update announcement.",
        "type" => "error"
    ];
}

$stmt->close();
$conn->close();

// Redirect back
header("Location: ../edit_announcement.php");
exit();
?>