<!-- 
Wasn't for sure how to do a numerical amount for max_capacity so I looked up "W3 php forms int amount" on google.
I first checked some websites but didn't see what I was looking for so I used the google AI result. The Google AI
showed this line "<input type="number" id="amount" name="amount" min="0" required>" which pretty much showed me 
what I was looking for as an example. 

Needed to format the name of the location for the user so I looked up "how to make php capitalize first letter and make underscores spaces"
and the Google AI showed me code that had these lines:
"
// 1. Replace underscores with spaces
$string_with_spaces = str_replace('_', ' ', $string_with_underscores);
    
// 2. Capitalize the first letter of each word
$formatted_string = ucwords($string_with_spaces);
"
which were basically about what I was looking for. I then used this for the PHP to fix the location name.

Okay so maybe I will finally admit that this file was initially too long (1300 lines). I wasn't sure how to efficiently get the hours 
for each location inserted without making like 6 calls to the database for everything so I ended up ask ChatGPT. The real main reason
this was shortened was because I was unable to paste the entire code in it lol. I only have it monday as an example of what we were doing.
This is like a whole adventure for a source citation isn't it. Hey Professor Bowe! I used this prompt:
    "I need to create a PHP call to get me what I need for the locations hours. My table I have in PHPMyAdmin has 
    location_id, day, open_time1, close_time1, open_time2, and close_time2. Everything is a string except location_id 
    which is an int. I need to each time thing individually to set the value in my HTML"
Then I later added more such as if the location had NULL for the time, to put NA and splitting the time up to hour, minutes,
and am/pm.
-->
<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "mysql", "campus_tracker");
if ($conn->connect_error) {
    die("Database connection failed.");
}

// Get location_id (from GET)
$location_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$location = NULL;

// Fetch hours
$stmt = $conn->prepare("
    SELECT day, open_time1, close_time1, open_time2, close_time2
    FROM hours
    WHERE location_id = ?
");
$stmt->bind_param("i", $location_id);
$stmt->execute();
$result = $stmt->get_result();

// Store by day
$hours = [];
while ($row = $result->fetch_assoc()) {
    $hours[$row['day']] = $row;
}

// Helper function
function splitTime($time)
{
    if (!$time) {
        return ["NA", "NA", "NA"];
    }

    return [
        date("g", strtotime($time)),  // hour (1-12)
        date("i", strtotime($time)),  // minute (00-59)
        date("A", strtotime($time))   // AM/PM
    ];
}

// Days of week
$days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
$mins = ["00", "05", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ConcordRush Admin</title>

    <!-- Base styles -->
    <link rel="stylesheet" href="style.css">
    <!-- Admin-only styles -->
    <link rel="stylesheet" href="admin.css">
</head>

<header>
    <div id="title-stuff">
        <div class="picture">
            <img src="pictures/logo.png" alt="Concord Rush Logo" id="logo">
        </div>
        <div class="title-text">
            <p id="main-title">ConcordRush - Admin</p>
        </div>
    </div>
</header>

<hr>

<main id="admin-container">

    <!-- LEFT MENU -->
    <aside id="admin-menu">
        <h3>Menu</h3>

        <a href="admin.php">
            <button class="menu-btn">Home</button>
        </a>
        <a href="edit_location.php">
            <button class="menu-btn">Edit Location</button>
        </a>
        <a href="announcement_create.php">
            <button class="menu-btn">Create Announcement</button>
        </a>
        <a href="edit_announcement.php">
            <button class="menu-btn">Edit Announcement</button>
        </a>
    </aside>

    <!-- RIGHT CONTENT -->
    <section id="admin-content">
        <section class="admin-box">
            <h3>Edit Location</h3>
            <?php if (isset($_SESSION["flash"])): ?>
                <div class="flash <?php echo $_SESSION["flash"]["type"]; ?>">
                    <?php echo $_SESSION["flash"]["text"]; ?>
                </div>
                <?php unset($_SESSION["flash"]); ?>
            <?php endif; ?>
            <?php
            // Check if location was clicked
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
            if ($id) {

                // Get location info
                $stmt = $conn->prepare("
                        SELECT  l.location_id, 
                                l.name, 
                                l.max_capacity, 
                                h.open_time1, 
                                h.open_time2, 
                                h.close_time1, 
                                h.close_time2
                        FROM locations l
                        JOIN hours h on l.location_id = h.location_id
                        WHERE l.location_id = ?
                    ");

                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $location = $result->fetch_assoc();

                // Format location name for user display
                $location['name'] = str_replace('_', ' ', $location['name']);
                $location['name'] = ucwords($location['name']);
            }
            ?>

            <!-- EDIT FORM -->
            <?php if ($location): ?>
                <form action="php/update_location.php" method="POST">
                    <input type="hidden" name="location_id" value="<?= $location['location_id'] ?>">
                    Location:<br>
                    <input type="text" name="name" value="<?= $location['name'] ?>" class="form_item" required><br>
                    Max Capacity:<br>
                    <input type="number" name="capacity" min="0" value="<?= $location['max_capacity'] ?>" class="form_item"
                        required><br><br>

                    <?php foreach ($days as $day):
                        list($o1h, $o1m, $o1a) = splitTime($hours[$day]['open_time1'] ?? null);
                        list($c1h, $c1m, $c1a) = splitTime($hours[$day]['close_time1'] ?? null);
                        list($o2h, $o2m, $o2a) = splitTime($hours[$day]['open_time2'] ?? null);
                        list($c2h, $c2m, $c2a) = splitTime($hours[$day]['close_time2'] ?? null);

                        $o1a = strtolower($o1a);
                        $c1a = strtolower($c1a);
                        $o2a = strtolower($o2a);
                        $c2a = strtolower($c2a);
                        $dayLabel = ucfirst($day);
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="font-size: 1.1rem; margin-bottom: 8px;"><strong><?= $dayLabel ?></strong></div>

                            <!-- OPEN 1 -->
                            <div style="margin-bottom: 8px;">
                                <select name="<?= $day ?>HourOpen1">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($o1h == $i ? "selected" : "") ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select> :
                                <select name="<?= $day ?>MinuteOpen1">
                                    <?php foreach ($mins as $m): ?>
                                        <option value="<?= $m ?>" <?= ($o1m == $m ? "selected" : "") ?>><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="<?= $day ?>OpenTime1">
                                    <option value="am" <?= ($o1a == "am" ? "selected" : "") ?>>a.m.</option>
                                    <option value="pm" <?= ($o1a == "pm" ? "selected" : "") ?>>p.m.</option>
                                </select> -
                                <!-- CLOSE 1 -->
                                <select name="<?= $day ?>HourClose1">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($c1h == $i ? "selected" : "") ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select> :
                                <select name="<?= $day ?>MinuteClose1">
                                    <?php foreach ($mins as $m): ?>
                                        <option value="<?= $m ?>" <?= ($c1m == $m ? "selected" : "") ?>><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="<?= $day ?>CloseTime1">
                                    <option value="am" <?= ($c1a == "am" ? "selected" : "") ?>>a.m.</option>
                                    <option value="pm" <?= ($c1a == "pm" ? "selected" : "") ?>>p.m.</option>
                                </select>
                            </div>

                            <!-- OPTIONAL SECOND HOURS (same pattern) -->
                            <div style="margin-bottom: 8px; color: #333;">Optional Second Hours</div>
                            <div style="margin-bottom: 8px;">
                                <select name="<?= $day ?>HourOpen2">
                                    <option value="NA" <?= ($o2h == "NA" ? "selected" : "") ?>>NA</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($o2h == $i ? "selected" : "") ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select> :
                                <select name="<?= $day ?>MinuteOpen2">
                                    <option value="NA" <?= ($o2m == "NA" ? "selected" : "") ?>>NA</option>
                                    <?php foreach ($mins as $m): ?>
                                        <option value="<?= $m ?>" <?= ($o2m == $m ? "selected" : "") ?>><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="<?= $day ?>OpenTime2">
                                    <option value="NA" <?= ($o2a == "NA" ? "selected" : "") ?>>NA</option>
                                    <option value="am" <?= ($o2a == "am" ? "selected" : "") ?>>a.m.</option>
                                    <option value="pm" <?= ($o2a == "pm" ? "selected" : "") ?>>p.m.</option>
                                </select> -
                                <select name="<?= $day ?>HourClose2">
                                    <option value="NA" <?= ($c2h == "NA" ? "selected" : "") ?>>NA</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($c2h == $i ? "selected" : "") ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select> :
                                <select name="<?= $day ?>MinuteClose2">
                                    <option value="NA" <?= ($c2m == "NA" ? "selected" : "") ?>>NA</option>
                                    <?php foreach ($mins as $m): ?>
                                        <option value="<?= $m ?>" <?= ($c2m == $m ? "selected" : "") ?>><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="<?= $day ?>CloseTime2">
                                    <option value="NA" <?= ($c2a == "NA" ? "selected" : "") ?>>NA</option>
                                    <option value="am" <?= ($c2a == "am" ? "selected" : "") ?>>a.m.</option>
                                    <option value="pm" <?= ($c2a == "pm" ? "selected" : "") ?>>p.m.</option>
                                </select>
                            </div>
                            <hr style="border: none; border-bottom: 1px solid #a3a3a3; margin: 15px 0;">
                        </div>
                    <?php endforeach; ?>

                    <input id="submit_button" type="submit" name="submit" value="Save Location">
                </form>

            <?php else: ?>

                <?php
                // Display locations
                $result = $conn->query("
                    SELECT location_id, name, max_capacity
                    FROM locations
                    ORDER BY name DESC
                ");
                ?>

                <div class="location-container">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <a class="location-card" href="edit_location.php?id=<?php echo $row['location_id']; ?>">
                            <?php
                            // Format location name for user display
                            $row['name'] = str_replace('_', ' ', $row['name']);
                            $row['name'] = ucwords($row['name']);
                            ?>

                            <div class="location_name">
                                <span style="text-decoration: underline;"><?php echo $row['name']; ?></span>
                                <br>
                                <strong style="font-size: medium;">Max Capacity:<?php echo $row['max_capacity']; ?></strong>
                            </div>

                        </a>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </section>

    </section>
</main>

<footer id="footer">
    <p>
        ConcordRush Admin Panel – Authorized Staff Only
    </p>
</footer>

</html>