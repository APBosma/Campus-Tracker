<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

$conn = new mysqli("localhost", "root", "mysql", "campus_tracker");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$now = new DateTime("now");
$nowStr = $now->format("Y-m-d H:i:s");
$day = (int)$now->format("w"); // 0=Sun...6=Sat

$locations = [
  1 => ["name" => "cafeteria",       "capacity" => 250, "stay" => [20, 60]],
  2 => ["name" => "north_tower_gym", "capacity" => 75,  "stay" => [30, 90]],
  3 => ["name" => "subway",          "capacity" => 30,  "stay" => [5,  20]],
];

// paste your $arrivalRates array exactly as-is here
$arrivalRates = [ /* ... */ ];

function clamp($v, $min, $max) { return max($min, min($max, $v)); }

function currentSlotKey(DateTime $dt, bool $leadingZero = false): string {
  $h = (int)$dt->format("G");
  $m = (int)$dt->format("i");
  $m = intdiv($m, 15) * 15;
  $hh = $leadingZero ? str_pad((string)$h, 2, "0", STR_PAD_LEFT) : (string)$h;
  $mm = str_pad((string)$m, 2, "0", STR_PAD_LEFT);
  return $hh . ":" . $mm;
}

function getArrivalRangeForNow(array $arrivalRates, string $locName, int $day, DateTime $now): array {
  if (!isset($arrivalRates[$locName][$day])) return [0, 0];

  $k1 = currentSlotKey($now, false); // "7:15"
  if (isset($arrivalRates[$locName][$day][$k1])) return $arrivalRates[$locName][$day][$k1];

  $k2 = currentSlotKey($now, true);  // "07:15"
  if (isset($arrivalRates[$locName][$day][$k2])) return $arrivalRates[$locName][$day][$k2];

  return [0, 0];
}

function randBetween($min, $max) {
  if ($max < $min) return $min;
  return random_int((int)$min, (int)$max);
}

// 1) People leave
$stmt = $conn->prepare("DELETE FROM visits WHERE leave_at <= ?");
$stmt->bind_param("s", $nowStr);
$stmt->execute();
$stmt->close();

// 2) For each location: add arrivals (as visits), then snapshot into population
foreach ($locations as $locId => $loc) {
  $locName = $loc["name"];
  $capacity = (int)$loc["capacity"];
  [$stayMin, $stayMax] = $loc["stay"];

  // current active count
  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM visits WHERE location_id = ?");
  $stmt->bind_param("i", $locId);
  $stmt->execute();
  $current = (int)$stmt->get_result()->fetch_assoc()["c"];
  $stmt->close();

  // arrivals for this time slot
  [$aMin, $aMax] = getArrivalRangeForNow($arrivalRates, $locName, $day, $now);
  $arrivals = randBetween($aMin, $aMax);

  // capacity enforcement
  $free = $capacity - $current;
  $arrivals = clamp($arrivals, 0, $free);

  if ($arrivals > 0) {
    $ins = $conn->prepare("INSERT INTO visits (location_id, arrived_at, leave_at) VALUES (?, ?, ?)");

    for ($i = 0; $i < $arrivals; $i++) {
      $stayMinutes = randBetween($stayMin, $stayMax);
      $leave = clone $now;
      $leave->modify("+{$stayMinutes} minutes");
      $leaveStr = $leave->format("Y-m-d H:i:s");

      $ins->bind_param("iss", $locId, $nowStr, $leaveStr);
      $ins->execute();
    }
    $ins->close();
  }

  // recompute count after arrivals
  $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM visits WHERE location_id = ?");
  $stmt->bind_param("i", $locId);
  $stmt->execute();
  $newCount = (int)$stmt->get_result()->fetch_assoc()["c"];
  $stmt->close();

  // 3) snapshot into YOUR population table (time-series live feed)
  $snap = $conn->prepare("INSERT INTO population (location_id, count, timestamp) VALUES (?, ?, ?)");
  $snap->bind_param("iis", $locId, $newCount, $nowStr);
  $snap->execute();
  $snap->close();
}

$conn->close();

echo "OK $nowStr";