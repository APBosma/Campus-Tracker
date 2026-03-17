<?php
session_start();

// Database connection
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
    header("Location: ../edit_announcement.php");
    exit();
}

// Get announcement ID
if (!isset($_POST["announcement_id"])) {
    $_SESSION["flash"] = [
        "text" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../edit_announcement.php");
    exit();
}

$id = intval($_POST["announcement_id"]);

// Delete query
$stmt = $conn->prepare("
    DELETE 
    FROM announcements 
    WHERE announcement_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION["flash"] = [
        "text" => "Announcement deleted successfully.",
        "type" => "success"
    ];
} else {
    $_SESSION["flash"] = [
        "text" => "Failed to delete announcement.",
        "type" => "error"
    ];
}

$stmt->close();
$conn->close();

header("Location: ../edit_announcement.php");
exit();
?>