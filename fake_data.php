<?php
$servername = "localhost";
$username = "root";
$password = "mysql"; // Default for AMMPS
$dbname = "campus_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$locations = [
  1 => "cafeteria",
  2 => "fitness_center",
  3 => "subway"
];

// Algorithm: vary traffic by hour and location
$hour = date("G");

function fakeCount($hour, $name) {
  switch ($name) {
    case "cafeteria":
      if ($hour < 9) return rand(0, 10);
      elseif ($hour < 11) return rand(20, 50);
      elseif ($hour < 14) return rand(80, 200);
      elseif ($hour < 17) return rand(20, 50);
      else return rand(0, 15);

    case "fitness_center":
      if ($hour < 6) return rand(0, 5);
      elseif ($hour < 9) return rand(10, 30);
      elseif ($hour < 17) return rand(15, 40);
      else return rand(30, 70);

    case "subway":
      if ($hour < 9) return rand(5, 15);
      elseif ($hour < 11) return rand(20, 40);
      elseif ($hour < 14) return rand(60, 100);
      elseif ($hour < 17) return rand(25, 50);
      else return rand(10, 30);
  }
}

foreach ($locations as $id => $name) {
  $count = fakeCount($hour, $name);
  $sql = "INSERT INTO occupancy (location_id, count) VALUES ($id, $count)";
  $conn->query($sql);
  echo "Inserted $count for $name<br>";
}

$conn->close();
?>
