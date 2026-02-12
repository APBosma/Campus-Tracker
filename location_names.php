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

//sql script to retrieve the data from the database 
$sql = "
    SELECT name
    FROM locations;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $location);
$stmt->execute();
$capacity = $stmt->get_result();

$row = $capacity->fetch_assoc();

echo json_encode($row);