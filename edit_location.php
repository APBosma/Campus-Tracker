<!-- 
Wasn't for sure how to do a numerical amount for max_capacity so I looked up "W3 php forms int amount" on google.
I first checked some websites but didn't see what I was looking for so I used the google AI result. The Google AI
showed this line "<input type="number" id="amount" name="amount" min="0" required>" which pretty much showed me 
what I was looking for as an example. 
-->
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

                // Check if location was clicked
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                if ($id) {

                    // Get location info
                    // Please leave it vertical because it is more readable for me
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
                        WHERE location_id = ?
                    ");

                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $location = $result->fetch_assoc();
                }
                ?>

                <?php if ($id && $location): ?>

                <!-- EDIT FORM -->
                <form action="php/update_location.php" method="POST">

                    <input type="hidden" name="location_id" value="<?php echo $location['location_id']; ?>">
                    <div> Location: <?php echo $location['name']; ?></div><br>
                    
                    <!-- 
                        Put time stuff here plz!
                        You will need to pull from the hours table. Lmk if you need help with the SQL
                    -->
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
                    <a class="location-card"
                    href="edit_location.php?id=<?php echo $row['location_id']; ?>">

                        <div class="location_name">
                            <?php echo $row['name']; ?>
                        </div>
                        <div>
                            <strong>Max Capacity:</strong> <?php echo $row['max_capacity']; ?>
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
