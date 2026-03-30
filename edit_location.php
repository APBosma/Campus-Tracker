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
            <h3>Edit Location</h3>
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

                <?php if ($id && $location): ?>

                <!-- EDIT FORM -->
                <form action="php/update_location.php" method="POST">

                    <input type="hidden" name="location_id" value="<?php echo $location['location_id'];?>">

                    <div> Location:</div>
                    <input type="text" id = "name" name="name" maxlength="50" value="<?php echo $location['name'];?>" required><br>

                    <div>Max Capacity:</div>
                    <input type="number" id="capacity" name="capacity" min="0" value="<?php echo $location['max_capacity'];?>"required>

                    <div>Hours:</div><br>
                    <div><strong>Monday</strong></div>
                        <select name="mondayHourOpen1" id="mondayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="mondayMinuteOpen1" id="mondayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="mondayOpenTime1" id="mondayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="mondayHourClose1" id="mondayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="mondayMinuteClose1" id="mondayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="mondayCloseTime1" id="mondayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                    
                        <div>Optional Second Hours</div>
                        <select name="mondayHourOpen2" id="mondayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="mondayMinuteOpen2" id="mondayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="mondayOpenTime2" id="mondayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="mondayHourClose2" id="mondayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="mondayMinuteClose2" id="mondayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="mondayCloseTime2" id="mondayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Tuesday</strong></div>
                        <select name="tuesdayHourOpen1" id="tuesdayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="tuesdayMinuteOpen1" id="tuesdayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="tuesdayOpenTime1" id="tuesdayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="tuesdayHourClose1" id="tuesdayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="tuesdayMinuteClose1" id="tuesdayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="tuesdayCloseTime1" id="tuesdayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="tuesdayHourOpen2" id="tuesdayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="tuesdayMinuteOpen2" id="tuesdayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="tuesdayOpenTime2" id="tuesdayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="tuesdayHourClose2" id="tuesdayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="tuesdayMinuteClose2" id="tuesdayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="tuesdayCloseTime2" id="tuesdayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Wednesday</strong></div>
                        <select name="wednesdayHourOpen1" id="wednesdayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="wednesdayMinuteOpen1" id="wednesdayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="wednesdayOpenTime1" id="wednesdayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="wednesdayHourClose1" id="wednesdayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="wednesdayMinuteClose1" id="wednesdayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="wednesdayCloseTime1" id="wednesdayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="wednesdayHourOpen2" id="wednesdayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="wednesdayMinuteOpen2" id="wednesdayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="wednesdayOpenTime2" id="wednesdayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="wednesdayHourClose2" id="wednesdayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="wednesdayMinuteClose2" id="wednesdayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="wednesdayCloseTime2" id="wednesdayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Thursday</strong></div>
                        <select name="thursdayHourOpen1" id="thursdayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="thursdayMinuteOpen1" id="thursdayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="thursdayOpenTime1" id="thursdayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="thursdayHourClose1" id="thursdayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="thursdayMinuteClose1" id="thursdayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="thursdayCloseTime1" id="thursdayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="thursdayHourOpen2" id="thursdayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="thursdayMinuteOpen2" id="thursdayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="thursdayOpenTime2" id="thursdayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="thursdayHourClose2" id="thursdayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="thursdayMinuteClose2" id="thursdayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="thursdayCloseTime2" id="thursdayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Friday</strong></div>
                        <select name="fridayHourOpen1" id="fridayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="fridayMinuteOpen1" id="fridayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="fridayOpenTime1" id="fridayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="fridayHourClose1" id="fridayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="fridayMinuteClose1" id="fridayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="fridayCloseTime1" id="fridayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="fridayHourOpen2" id="fridayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="fridayMinuteOpen2" id="fridayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="fridayOpenTime2" id="fridayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="fridayHourClose2" id="fridayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="fridayMinuteClose2" id="fridayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="fridayCloseTime2" id="fridayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Saturday</strong></div>
                        <select name="saturdayHourOpen1" id="saturdayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="saturdayMinuteOpen1" id="saturdayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="saturdayOpenTime1" id="saturdayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="saturdayHourClose1" id="saturdayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="saturdayMinuteClose1" id="saturdayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="saturdayCloseTime1" id="saturdayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="saturdayHourOpen2" id="saturdayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="saturdayMinuteOpen2" id="saturdayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="saturdayOpenTime2" id="saturdayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="saturdayHourClose2" id="saturdayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="saturdayMinuteClose2" id="saturdayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="saturdayCloseTime2" id="saturdayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>---
                    <div><strong>Sunday</strong></div>
                        <select name="sundayHourOpen1" id="sundayHourOpen1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="sundayMinuteOpen1" id="sundayMinuteOpen1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="sundayOpenTime1" id="sundayOpenTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="sundayHourClose1" id="sundayHourClose1" class="form_item">
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="sundayMinuteClose1" id="sundayMinuteClose1" class="form_item">
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="sundayCloseTime1" id="sundayCloseTime1" class="form_item">
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>

                        <div>Optional Second Hours</div>
                        <select name="sundayHourOpen2" id="sundayHourOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="sundayMinuteOpen2" id="sundayMinuteOpen2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select> 
                        <select name="sundayOpenTime2" id="sundayOpenTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select> -
                        <select name="sundayHourClose2" id="sundayHourClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "1">1</option>
                            <option value = "2">2</option>
                            <option value = "3">3</option>
                            <option value = "4">4</option>
                            <option value = "5">5</option>
                            <option value = "6">6</option>
                            <option value = "7">7</option>
                            <option value = "8">8</option>
                            <option value = "9">9</option>
                            <option value = "10">10</option>
                            <option value = "11">11</option>
                            <option value = "12">12</option>
                        </select> :
                        <select name="sundayMinuteClose2" id="sundayMinuteClose2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "00">00</option>
                            <option value = "05">05</option>
                            <option value = "10">10</option>
                            <option value = "15">15</option>
                            <option value = "20">20</option>
                            <option value = "25">25</option>
                            <option value = "30">30</option>
                            <option value = "35">35</option>
                            <option value = "40">40</option>
                            <option value = "45">45</option>
                            <option value = "50">50</option>
                            <option value = "55">55</option>
                        </select>
                        <select name="sundayCloseTime2" id="sundayCloseTime2" class="form_item">
                            <option value = "NA">NA</option>
                            <option value = "am">a.m.</option>
                            <option value = "pm">p.m.</option>
                        </select>
                        <br>
                    
                    <input id="submit_button" type="submit" name="submit" value="Submit">
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
                        <?php
                        // Format location name for user display
                        $row['name'] = str_replace('_', ' ', $row['name']);
                        $row['name'] = ucwords($row['name']);
                        ?>

                        <div class="location_name">
                            <?php echo $row['name']; ?>
                            <br>
                            <strong style="font-size: medium">Max Capacity:<?php echo $row['max_capacity']; ?></strong>
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
