<?php
// https://www.youtube.com/watch?v=LiomRvK7AM8
$host = "localhost";
$user = "root";
$password = "";
$database = "users_db";

$conn = new mysqli(
    $host, 
    $user, 
    $password, 
    $database
); 

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

?>