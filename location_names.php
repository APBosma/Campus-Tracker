<?php
$conn = new mysqli("localhost", "root", "mysql", "campus_tracker");
if ($conn->connect_error) {
    echo "<option>Connection failed</option>";
    exit;
}

$res = $conn->query("SELECT name FROM locations");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        // Replace underscores with spaces
        $name = str_replace('_', ' ', $row['name']);
        // Capitalize the first letter of each word
        $name = ucwords(strtolower(trim($name)));
        echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>";
    }
} else {
    echo "<option>No locations found</option>";
}

$conn->close();
?>
