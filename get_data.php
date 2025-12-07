<?php

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
    SELECT count
    FROM population
    JOIN locations l ON l.location_id = population.location_id
    WHERE l.name = ?
    ORDER BY population.entry_id DESC
    LIMIT 30
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $location);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);