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

$res = $conn->query("
    SELECT message
    FROM announcements
    WHERE start_date <= CURRENT_DATE()
    AND end_date > CURRENT_DATE()
    AND location_id = ?
");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        echo "<div class=\"announcementMsg\">" . htmlspecialchars($name) . "</div>";
    }
} 
$conn->close();
?>
