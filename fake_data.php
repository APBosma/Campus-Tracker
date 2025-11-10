<?php
$servername = "localhost";
$username = "root";
$password = "1163"; // Default for AMMPS
$dbname = "campus_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$locations = [
  1 => "cafeteria",
  2 => "fitness_center",
  3 => "subway"
];

// Algorithm: vary traffic by hour and location
$hour = date("G"); //represents the current hour 0-23
$day = date("w"); //represents the current day Sunday=0 Monday=1 etc. 


function fakeCount($hour,$day, $name) {
  switch ($name) {
    //dining hall 
    case "cafeteria":
        //Monday-Friday
      if ($day >= 1 && $day <= 5) {
        //main breakfast
        if ($hour >= 7 && $hour < 9) return rand(60, 80);
        //continental breakfast       
        elseif ($hour >= 9 && $hour < 10) return rand(20, 30); 
        //peak lunch hours   
        elseif ($hour >= 11 && $hour < 13) return rand(100, 180); 
        //the lite lunch 
        elseif ($hour >= 13 && $hour < 15) return rand(30, 70);  
        //dinner 
        elseif ($hour >= 16 && $hour < 19) return rand(80, 160);  
        //catches all closed hours
        else return 0; 

        //Saturday and Sunday
      } elsif($day == 6 || $day == 0){
        //brunch
        if ($hour >= 10 && $hour < 13) return rand(60, 90);  
        //dinner on weekend   
        elseif ($hour >= 16 && $hour < 18) {                      
          return ($day == 6) ? rand(50, 100) : rand(60, 80);
        } else return 0;
      }
      return 0;
      
      //fitness center 
    case "fitness_center":
        //Sundays
      if ($day == 0) { 
        //hours 4-10
        if ($hour >= 16 && $hour < 22) return rand(20, 50);
        //Monday-Thursdays
      } elseif ($day >= 1 && $day <= 4) { 
        //8am-10pm
        if ($hour >= 8 && $hour < 10) return rand(15, 35);
        elseif ($hour >= 10 && $hour < 17) return rand(10, 25);
        elseif ($hour >= 17 && $hour < 22) return rand(30, 70);
        //Fridays
      } elseif ($day == 5) { 
        //hours 8am-4pm
        if ($hour >= 8 && $hour < 10) return rand(20, 40);
        elseif ($hour >= 10 && $hour < 16) return rand(25, 50);
        //Saturdays
      } elseif ($day == 6) { 
        //2pm-8pm
        if ($hour >= 14 && $hour < 20) return rand(15, 35);
      }
      return 0;

    case "subway":
      if ($day >= 1 && $day <= 5) { // Mon-Fri 7:30am - 10:30pm
        if ($hour >= 7 && $hour < 9) return rand(10, 30);     // Morning
        elseif ($hour >= 11 && $hour < 14) return rand(40, 90); // Lunch
        elseif ($hour >= 17 && $hour < 20) return rand(50, 100); // Dinner rush
        elseif ($hour < 23) return rand(10, 40); // Late evening
      } elseif ($day == 6) { // Sat 5pm - 10pm
        if ($hour >= 17 && $hour < 22) return rand(20, 60);
      } elseif ($day == 0) { // Sun 5pm - 10:30pm
        if ($hour >= 17 && $hour < 22) return rand(25, 70);
      }
      return 0;
  }
}

foreach ($locations as $id => $name) {
  $count = fakeCount($hour, $name);
  $sql = "INSERT INTO occupancy (location_id, count) VALUES ($id, $count)";
  $conn->query($sql);
  echo "Inserted $count for $name<br>";
}

$conn->close();
?>
