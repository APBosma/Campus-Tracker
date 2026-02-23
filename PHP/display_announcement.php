<?php
$conn = new mysqli("localhost", "username", "password", "database");

$result = $conn->query("
    SELECT announcement_id, message, start_date, end_date
    FROM announcements
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

        <div class="dates">
            <?php echo $row['start_date']; ?> → <?php echo $row['end_date']; ?>
        </div>

    </a>

<?php endwhile; ?>

</div>