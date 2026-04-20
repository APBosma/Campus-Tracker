# Campus-Tracker

# Description:
This project aims to create an accessible website for Concord
University students to view how busy the locations on campus are from
anywhere and plan their routine around it.

# Main Features: 
- Live Simulated Occupancy
- Predicted Occupancy
- Updated Operating Hours
- Announcements
- Admin Functionality 

# Getting Started
First: 

 - Install AMPPS
 - Set up MySQL inside AMPPS (Make sure no other instance of MySQL is present on the machine)

Installation: 

- Clone this repository into the www directory in AMPPS
        (/Application/AMPPS/www/Campus_Tracker)

  - Import the given database file into myPHPAdmin within AMPPS: "campus_tracker-2.sql"

# User Guide
Student View:

    Navigate to index.html to access the main dashboard. From there, you can view live and predicted occupancy for each campus location, check operating hours, and read announcements.
  
Admin View:

    Navigate to admin.php and log in with your administrator credentials. The admin panel allows you to:
        Create, edit, and delete announcements
        Update location details and occupancy settings
  


# File Structure: 
```
Campus_Tracker/
├── css/
│   ├── admin.css
│   └── style.css
├── javascript/
│   ├── adminLiveStatus.js
│   ├── chronos.js
│   ├── getAnnouncements.js
│   ├── graph.js
│   ├── liveBusy.js
│   └── script.js
├── microPages/
│   ├── cafMicroPage.html
│   ├── gymMicroPage.html
│   └── subMicroPage.html
├── php/
│   ├── admin_login.php
│   ├── create_announcement.php
│   ├── delete_announcement.php
│   ├── display_announcement.php
│   ├── get_announcements.php
│   ├── get_capacity.php
│   ├── get_data.php
│   ├── get_hourly_data.php
│   ├── get_hours.php
│   ├── get_predicted_data.php
│   ├── location_names.php
│   ├── simulation.php
│   ├── update_announcement.php
│   └── update_location.php
├── pictures/
│   ├── bell_tower.webp
│   ├── caf.webp
│   ├── gym.webp
│   ├── img1.jpg
│   ├── img2.jpg
│   ├── img3.jpg
│   ├── logo.png
│   ├── staff.png
│   └── sub.webp
├── admin.php
├── admins.sql
├── announcement_create.php
├── cafeteria.html
├── edit_announcement.php
├── edit_location.php
├── gym.html
├── index.html
├── README.md
└── subway.html
```     

Tech Stack: 
HTML, CSS, JavaScript, PHP, MySQL, AMPPS



