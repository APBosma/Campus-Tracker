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
        <a href = "edit_announcement.php">
            <button class="menu-btn">Edit Announcement</button>
        </a>
    </aside>

    <!-- RIGHT CONTENT -->
    <section id="admin-content">
        <section class="admin-box">
            <h3>Edit Announcement</h3>
            <!-- Display announcements here -->
        </section>

    </section>
</main>

<footer id="footer">
    <p>
        ConcordRush Admin Panel – Authorized Staff Only
    </p>
</footer>

</html>
