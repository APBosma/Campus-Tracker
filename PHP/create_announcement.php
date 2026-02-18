<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "campus_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION["flash"] = [
    "text" => "Database connection failed.",
    "type" => "error"];
    header("Location: ../announcement_create.php");
    exit();
}

// Define location IDs
$locations = [
    "Cafeteria" => 1,
    "North Tower Gym" => 2,
    "Subway" => 3
];

// Get form data
$locationName = $_POST['location'] ?? null;
$message = $_POST['message'] ?? "";
$start_date = trim($_POST['start_date'] ?? "");
$end_date = trim($_POST['end_date'] ?? "");

// Validate location
if (!$locationName || !isset($locations[$locationName])) {
    $_SESSION["flash"] = [
    "text" => "Invalid location selected.",
    "type" => "error"];
    header("Location: ../announcement_create.php");
    exit();
}

// Subtract one day from today for comparison
$today_minus_one = date('Y-m-d', strtotime('-1 day'));

// Validate start date (today is allowed)
if ($start_date < $today_minus_one) {
    $_SESSION["flash"] = [
    "text" => "Start date cannot be in the past.",
    "type" => "error"];
    header("Location: ../announcement_create.php");
    exit();
}

// Validate end date
if ($end_date < $start_date) {
    $_SESSION["flash"] = [
    "text" => "End date must be after start date.",
    "type" => "error"];
    header("Location: ../announcement_create.php");
    exit();
}

// Insert announcement into database
$location = $locations[$locationName];

$stmt = $conn->prepare("
    INSERT INTO announcements
    (location_id, message, start_date, end_date)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $location, $message, $start_date, $end_date);

if ($stmt->execute()) {
    $_SESSION["flash"] = [
    "text" => "Announcement created successfully!",
    "type" => "success"];
    header("Location: ../announcement_create.php");
} else {
    $_SESSION["flash"] = [
    "text" => "Error saving announcement.",
    "type" => "error"];
    header("Location: ../announcement_create.php");
}

exit();
