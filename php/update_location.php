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
    header("Location: ../edit_location.php");
    exit();
}

// Puts together the times so it is ready for the database 
// Ex. 1:05 PM -> 13:05
function buildTime($hour, $minute, $ampm) {
    if ($hour === "NA" || $minute === "NA" || $ampm === "NA") {
        return null;
    }

    $hour = intval($hour);

    // Convert to 24-hour time
    if ($ampm === "pm" && $hour != 12) {
        $hour += 12;
    }
    if ($ampm === "am" && $hour == 12) {
        $hour = 0;
    }

    return sprintf("%02d:%02d", $hour, $minute);
}

// Get form data
$days = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
$id = $_POST['location_id'];
$max_capacity = $_POST['max_capacity'];

foreach ($days as $day) {

    $o1h = $_POST[$day . 'HourOpen1'] ?? null;
    $o1m = $_POST[$day . 'MinuteOpen1'] ?? null;
    $o1a = $_POST[$day . 'OpenTime1'] ?? null;

    $c1h = $_POST[$day . 'HourClose1'] ?? null;
    $c1m = $_POST[$day . 'MinuteClose1'] ?? null;
    $c1a = $_POST[$day . 'CloseTime1'] ?? null;

    $o2h = $_POST[$day . 'HourOpen2'] ?? null;
    $o2m = $_POST[$day . 'MinuteOpen2'] ?? null;
    $o2a = $_POST[$day . 'OpenTime2'] ?? null;

    $c2h = $_POST[$day . 'HourClose2'] ?? null;
    $c2m = $_POST[$day . 'MinuteClose2'] ?? null;
    $c2a = $_POST[$day . 'CloseTime2'] ?? null;

    // Convert to SQL time
    $open1  = buildTime($o1h, $o1m, $o1a);
    $close1 = buildTime($c1h, $c1m, $c1a);
    $open2  = buildTime($o2h, $o2m, $o2a);
    $close2 = buildTime($c2h, $c2m, $c2a);

    $stmt = $conn->prepare("
        UPDATE hours
        SET open_time1 = ?, close_time1 = ?, open_time2 = ?, close_time2 = ?
        WHERE location_id = ? AND day = ?
    ");

    $stmt->bind_param("ssssis", $open1, $close1, $open2, $close2, $location_id, $day);
    $stmt->execute();
}

// Redirect back
header("Location: ../edit_location.php");
exit();
?>