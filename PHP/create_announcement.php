<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//connect to database
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "campus_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => $conn->connect_error]);
    exit;
}

$res = $conn->prepare("
    INSERT INTO announcements
    (location_id, message, start_date, end_date) 
    VALUES (?, ?, ?, ?);
");
$res->bind_param("ssss", $location, $message, $start_date, $end_date);

$locations = [
    "Cafeteria" => 1,
    "North Tower Gym" => 2,
    "Subway" => 3
];
$currentTimestamp = time();

$location = $locations[$_POST['location']];
$message = $_POST['message'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
echo($location);
echo($message);
echo($start_date);
echo($end_date);

if ($start_date < $currentTimestamp) {
    echo("Error: Start date is in the past");
}
if ($end_date < $start_date) {
    echo("Error: End date is before start date");
}

if (!($res->execute())) {
    echo "Error: " . $stmt->error;
}

$res->close();
$conn->close();
?>
