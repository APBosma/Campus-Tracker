<?php
// Didn't know how to use multiple variables for the SQL and stuff so I looked up "PHP SQL 
// statements with multiple variables with $_GET" on Google and the Google AI showed me how. It showed me that the bind_param thing
// is in charge of that thing. P cool stuff.

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
$day = $_GET["day"] ?? null;

$locations = [
    "cafeteria" => 1,
    "north_tower_gym" => 2,
    "subway" => 3
];

if (!$location) {
    echo json_encode(["error" => "missing location"]);
    exit;
}
if (!$day) {
    echo json_encode(["error" => "missing day"]);
    exit;
}

//sql script to retrieve the data from the database 
$sql = "
    SELECT open_time1, close_time1, open_time2, close_time2
    FROM hours
    JOIN locations l ON l.location_id = hours.location_id
    WHERE l.name = ? AND hours.day = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $location, $day);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Removing null values for a cleaner result in the javacsript
$row = array_filter($row, function ($value) {
    return $value !== null;
});

echo json_encode($row);