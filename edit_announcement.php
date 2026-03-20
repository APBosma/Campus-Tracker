<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ConcordRush Admin</title>

    <!-- Base styles -->
    <link rel="stylesheet" href="style.css">
    <!-- Admin-only styles -->
    <link rel="stylesheet" href="admin.css">
    <?php session_start();?>  <!-- For flash msg -->
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
        <button class="menu-btn">Create Location</button>
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
        <section class="admin-box">
            <h3>Edit Announcement</h3>
                <?php if (isset($_SESSION["flash"])): ?>
                <div class="flash <?php echo $_SESSION["flash"]["type"]; ?>">
                    <?php echo $_SESSION["flash"]["text"]; ?>
                </div>
                <?php unset($_SESSION["flash"]); ?>
            <?php endif; ?>
                <?php
                // Connect to database
                $servername = "localhost";
                $username = "root";
                $password = "mysql";
                $dbname = "campus_tracker";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Database connection failed.");
                }

                // Check if an announcement was clicked
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;

                if ($id) {

                    // Get the selected announcement
                    $stmt = $conn->prepare("
                        SELECT announcement_id, message, start_date, end_date, l.location_id, name
                        FROM announcements a
                        JOIN locations l ON l.location_id = a.location_id
                        WHERE announcement_id = ?
                    ");

                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $announcement = $result->fetch_assoc();
                }
                ?>

                <?php if ($id && $announcement): ?>

                <!-- EDIT FORM -->
                <form action="php/update_announcement.php" method="POST">

                    <input type="hidden" name="announcement_id"
                        value="<?php echo $announcement['announcement_id']; ?>">
                    <div> Location: <?php echo $announcement['name']; ?></div><br>
                    <label>Message</label><br>
                    <textarea class="form_item" name="message" rows="4" cols="50"><?php echo htmlspecialchars($announcement['message']); ?></textarea>
                    <br>
                    <br>
                    <label>Start Date</label><br>
                    <input class="form_item" type="date" name="start_date"
                        value="<?php echo $announcement['start_date']; ?>"><br>

                    <label>End Date</label><br>
                    <input class="form_item" type="date" name="end_date" value="<?php echo $announcement['end_date']; ?>">
                    <br><br>

                    <button id="submit_button" type="submit">Submit</button>
                    <br>
                </form>

                <form action="php/delete_announcement.php" method="POST">
                    <input type="hidden" name="announcement_id"
                        value="<?php echo $announcement['announcement_id']; ?>">
                    <button id="submit_button" type="submit"
                        onclick="return confirm('Are you sure you want to delete this announcement?');">
                        Delete
                    </button>
                </form>
                
                <?php else: ?>

                <?php
                // Load announcements list
                $result = $conn->query("
                    SELECT announcement_id, message, start_date, end_date, name
                    FROM announcements a
                    JOIN locations l ON l.location_id = a.location_id
                    WHERE end_date >= CURDATE()
                    ORDER BY start_date DESC
                ");
                ?>

                <div class="announcements-container">
                <?php while ($row = $result->fetch_assoc()): ?>

                    <a class="announcement-card"
                    href="edit_announcement.php?id=<?php echo $row['announcement_id']; ?>">

                        <div class="message">
                            <?php echo htmlspecialchars($row['message']); ?>
                        </div>

                        <div>
                            <strong>Location:</strong> <?php echo $row['name']; ?>
                        </div>

                        <div class="dates">
                            <strong>Start:</strong> <?php echo $row['start_date']; ?><br>
                            <strong>End:</strong> <?php echo $row['end_date']; ?><br>
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
