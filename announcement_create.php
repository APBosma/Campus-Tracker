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

        <a href = "admin.html">
            <button class="menu-btn">Home</button>
        </a>
        <button class="menu-btn">Edit Location</button>
        <button class="menu-btn">Remove Location</button>
        <a href = "announcement_create.php">
            <button class="menu-btn">Create Announcement</button>
        </a>
        <button class="menu-btn">Edit Announcement</button>
    </aside>

    <!-- RIGHT CONTENT -->
    <section id="admin-content">

        <!-- ANNOUNCEMENT FORM -->
        <section class="admin-box">
            <h3>Create Announcement</h3>
            <?php
            session_start();
            if (isset($_SESSION["flash"])) {
                $flash = $_SESSION["flash"];
                if (is_array($flash)) {
                    $text = $flash['text'] ?? '';
                    $type = $flash['type'] ?? 'success';
                } else {
                    $text = $flash;
                    $type = 'error';
                }

                if ($type === 'success') {
                    $style = "background:#e6ffed; border:1px solid #b7ebc6; color:#1f7a3e; padding:12px; border-radius:6px; margin-bottom:20px;";
                } else { 
                    $style = "background:#ffe6e6; border:1px solid #ffb3b3; color:#a10000; padding:12px; border-radius:6px; margin-bottom:20px;";
                }
                echo "<div style='$style'>$text</div>";
                unset($_SESSION["flash"]); }?>
                
            <form action = "PHP/create_announcement.php" method = "post">
                Location: <br>
                <select name="location" id="location" class="form_item">
                    <?php include __DIR__ . '/PHP/location_names.php'; ?>
                    <option value = "All">All</option>
                </select><br>

                Start Date:<br>
                <input type="date" id="start_date" name="start_date" class="form_item" required><br>

                End Date:<br>
                <input type="date" id="end_date" name="end_date" class="form_item" required><br>

                Message:<br>
                <textarea id="message" name="message" rows="5" cols="33" maxlength="767" class="form_item" required></textarea><br>
                <input id="submit_button" type="submit" name="submit" value="Submit">
            </form>
        </section>

    </section>
</main>

<footer id="footer">
    <p>
        ConcordRush Admin Panel – Authorized Staff Only
    </p>
</footer>

</html>
