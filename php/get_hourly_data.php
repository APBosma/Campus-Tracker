<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
date_default_timezone_set('America/New_York');

$conn = new mysqli("localhost", "root", "mysql", "campus_tracker");
if ($conn->connect_error) {
  echo json_encode(["error" => $conn->connect_error]);
  exit;
}

$location = $_GET["location"] ?? null;
$hoursParam = $_GET["hours"] ?? null;

if (!$location || !$hoursParam) {
  echo json_encode(["error" => "missing location or hours"]);
  exit;
}

// Map to location_id (no need to join locations table)
$locMap = [
  "cafeteria" => 1,
  "north_tower_gym" => 2,
  "subway" => 3
];

if (!isset($locMap[$location])) {
  echo json_encode(["error" => "invalid location"]);
  exit;
}

$location_id = $locMap[$location];

// Parse labels passed from JS
$labels = explode("|", $hoursParam);

// Convert "7 am" / "12 pm" / "10 pm" -> 24-hour integer
function labelToHour24($label) {
  $label = trim($label);
  if ($label === "") return null;

  $parts = explode(" ", $label); // ["7","am"]
  if (count($parts) < 2) return null;

  $h = (int)$parts[0];
  $ampm = strtolower(trim($parts[1]));

  if ($ampm === "pm" && $h !== 12) $h += 12;
  if ($ampm === "am" && $h === 12) $h = 0;

  return $h;
}

$today = date("Y-m-d");

// Prepared statement: latest row in [start, end)
$stmt = $conn->prepare("
  SELECT count
  FROM population
  WHERE location_id = ?
    AND time_stamp >= ?
    AND time_stamp < ?
  ORDER BY time_stamp DESC
  LIMIT 1
");

$out = [];
$carry = 0;

foreach ($labels as $lab) {
  $h = labelToHour24($lab);

  if ($h === null) {
    $out[] = ["count" => $carry];
    continue;
  }

  $start = $today . " " . str_pad((string)$h, 2, "0", STR_PAD_LEFT) . ":00:00";
  $endHour = ($h + 1) % 24;
  $end = $today . " " . str_pad((string)$endHour, 2, "0", STR_PAD_LEFT) . ":00:00";

  $stmt->bind_param("iss", $location_id, $start, $end);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();

  if ($res) {
    $carry = (int)$res["count"];
  }
  $out[] = ["count" => $carry];
}

$stmt->close();
$conn->close();

echo json_encode($out);