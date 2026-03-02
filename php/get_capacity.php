<?php
// Normally I do not use AI, but working on this PHP file and getting this to work with the javascript file was life changingly
// difficult. I showed chatGPT what I had of this file (Most of it was already done using the get_data file) and it showed me that
// I had misspelled capacity in one spot towards the bottom and showed me how to use this in the javascript file. Once I got this
// working I used a recommendation from the AI to clean up the bottom return so I only returned one item rather than an array.

// This gets the max capacity value from the database for the location

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
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

$location = $_GET["location"] ?? null;

$locations = [
    "cafeteria" => 1,
    "north_tower_gym" => 2,
    "subway" => 3
];

if (!$location) {
    echo json_encode(["error" => "missing location"]);
    exit;
}

//sql script to retrieve the data from the database 
$sql = "
    SELECT max_capacity
    FROM locations
    WHERE name = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $location);
$stmt->execute();
$capacity = $stmt->get_result();

$row = $capacity->fetch_assoc();

echo json_encode($row);