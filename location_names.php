<?php
// I am not sure I will ever be emotionally prepared to discuss what occured with this file.
// I started by copying what we had from get_hours and went form there, however I was unsure of how to display it. Since
// PHP is how it is I went to Professor Chat and had to work with him for about an hour. The following occured in the 
// conversation:
// 1. I realized I misnamed my file in the HTML file
// 2. I learned you need to name the HTML file as .php so the PHP works
// 3. I learned how to do some funky formatting stuff with PHP

$conn = new mysqli("localhost", "root", "mysql", "campus_tracker");
if ($conn->connect_error) {
    echo "<option>Connection failed</option>";
    exit;
}

$res = $conn->query("SELECT name FROM locations");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $name = str_replace('_', ' ', $row['name']); // Turns underscores into spaces
        $name = ucwords(strtolower(trim($name))); // Capitalizes first letter of words
        echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>";
    }
} else {
    echo "<option>No locations found</option>";
}

$conn->close();
?>
