<!-- HTML for the home admin page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script type="module" src="javascript/adminLiveStatus.js"></script>
    <title>ConcordRush Admin</title>

    <!-- Base styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Admin-only styles -->
    <link rel="stylesheet" href="css/admin.css">
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

        <a href = "admin.php">
            <button class="menu-btn">Home</button>
        </a>
        <a href = "edit_location.php">
            <button class="menu-btn">Edit Location</button>
        </a>
        <a href = "announcement_create.php">
            <button class="menu-btn">Create Announcement</button>
        </a>
        <a href = "edit_announcement.php">
            <button class="menu-btn">Edit Announcement</button>
        </a>
    </aside>

    <!-- RIGHT CONTENT -->
    <section id="admin-content">

        <!-- CURRENT ANNOUNCEMENTS -->
        <section class="admin-box">
            <h3>Current Announcements</h3>

            <?php
                $conn = new mysqli("localhost", "root", "mysql", "campus_tracker");

                if ($conn->connect_error) {
                    echo "<p>Database connection failed.</p>";
                } else {

                    $result = $conn->query("
                        SELECT announcement_id, message, start_date, end_date, name
                        FROM announcements a
                        JOIN locations l ON l.location_id = a.location_id
                        WHERE end_date >= CURDATE()
                        ORDER BY start_date DESC
                    ");

                    if ($result->num_rows == 0) {
                        echo "<div class='empty-box'><p>No active announcements</p></div>";
                    } else {
                        echo "<div class='announcements-container'>";

                        while ($row = $result->fetch_assoc()) {
                            $row['name'] = str_replace('_', ' ', $row['name']);
                            $row['name'] = ucfirst($row['name']);
                            echo "
                            <div class='announcement-card'>
                                <div class='message'>
                                    " . htmlspecialchars($row['message']) . "
                                </div>

                                <div class='dates'>
                                    <strong>Location:</strong> {$row['name']} <br>
                                    <strong>Start:</strong> {$row['start_date']} <br>
                                    <strong>End:</strong> {$row['end_date']}
                                </div>
                            </div>
                            ";
                        }

                        echo "</div>";
                    }

                    $conn->close();
                }
            ?>
        </section>

        <!-- LOCATION SUMMARIES -->
        <section class="admin-box">
            <h3>Location Summaries</h3>

            <!-- NORTH TOWER GYM -->
            <div class="location-card" data-location="North Tower Gym">
                <div class="status-dot" id="north_tower_gym-circle"></div>
                <p>NT Gym - <span id="north_tower_gym-level">Loading...</span></p>
            </div>

            <!-- CAFETERIA -->
            <div class="location-card" data-location="Cafeteria">
                <div class="status-dot" id="cafeteria-circle"></div>
                <p>Cafeteria - <span id="cafeteria-level">Loading...</span></p>
            </div>

            <!-- SUBWAY -->
            <div class="location-card" data-location="Subway">
                <div class="status-dot" id="subway-circle"></div>
                <p>Subway - <span id="subway-level">Loading...</span></p>
            </div>
        </section>

    </section>
</main>

<footer id="footer">
    <p>
        ConcordRush Admin Panel – Authorized Staff Only
    </p>
</footer>

</html>
