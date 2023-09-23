<?php
session_start();
require "connect_db.php";

$query_result = "SELECT * FROM result";
$result_result = $conn->query($query_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/home.css">
  <!-- <link rel="stylesheet" href="styles/general.css"> -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
     <title>Football Score Table</title>
     <style>
           body {
               font-family: Arial, sans-serif;
               margin: 20px;
          }

          h2 {
               text-align: center;
               margin-bottom: 20px;
          }

          .table-club {
               width: 100%;
               border-collapse: collapse;
               margin-top: 20px;
          }

          .table-club th, td {
               border: 1px solid black;
               padding: 10px;
               text-align: center;
          }

          .table-club  th {
               background-color: #f2f2f2;
          }

          .table-club  tr {
               background-color: rgba(255, 255, 255, 0.5);
          }
          /* .table-club  tr:nth-child(even) {
               background-color: #fff;
          } */

          .table-club .club-dp {
               display: flex;
               align-items: center;
          }

          .team-logo-table {
               height: 50px;
               width: 50px;
               margin-right: 10px;
          }
          h3{
               font-size:15px;
               font-weight: 600;
          }

     </style>
</head>
<body>
<header>
    <a href="home.php"><img class="nav-img" src="/images/home.png"></a>
    <a href="main.php"><img class="nav-img" src="/images/contact.png"></a>
    <a href="#card2"><img class="nav-img" src="/images/matches.png"></a>
    <a id="settingsBtn"><img class="nav-img" src="/images/settings.png"></a>



  </header>
     <section>
          <h2>eFootball Score Table</h2>
          <table class="table-club">
               <tr>
                    <th>Club</th>
                    <th>Matches Played</th>
                    <th>Wins</th>
                    <th>Draws</th>
                    <th>Losses</th>
                    <th>Score</th>
                    <th>Points</th>
               </tr>

               <?php
               while ($row = $result_result->fetch_assoc()) {
                    $club = $row['club'];
                    $matchesPlayed = $row['matches_played'];
                    $wins = $row['wins'];
                    $draws = $row['draws'];
                    $losses = $row['losses'];
                    $score = $row['score'];
                    $points = $row['points'];

                    // Set image and clubName based on the club ID
                    if ($club == "1") {
                         $image = 'images/psg.webp';
                         $clubName = "P-S-G";
                       } else if ($club == "2") {
                         $image = 'images/Fcb.webp';
                         $clubName = "FC barcelona";
                       } else if ($club == "3") {
                         $image = 'images/real_madrid.webp';
                         $clubName = "Real madrid";
                       } else if ($club == "4") {
                         $image = 'images/Manchester-United-Logo.webp';
                         $clubName = "Manchester United";
                       } else if ($club == "5") {
                         $image = 'images/m-city.webp';
                         $clubName = "Manchester City<";
                       } else if ($club == "6") {
                         $image = 'images/benfica.webp';
                         $clubName = "Benfica";
                       } else if ($club == "7") {
                         $image = 'images/napoli.webp';
                         $clubName = "Napoli";
                       } else if ($club == "8") {
                         $image = 'images/ac-milan-logo.webp';
                         $clubName = "AC milan";
                       } else if ($club == "9") {
                         $image = 'images/arsenal-logo-0.webp';
                         $clubName = "Arsenal";
                       } else if ($club == "10") {
                         $image = 'images/Chelsea-Logo.webp';
                         $clubName = "Chelsea";
                       } else if ($club == "11") {
                         $image = 'images/new-castle.webp';
                         $clubName = "New Castle";
                       } else if ($club == "12") {
                         $image = 'images/bayern.webp';
                         $clubName = "Bayern";
                       } else if ($club == "13") {
                         $image = 'images/logo-de-juventus.webp';
                         $clubName = "Juventus";
                       } else if ($club == "14") {
                         $image = 'images/new-castle.webp';
                         $clubName = "Inter milan";
                       } else if ($club == "15") {
                         $image = 'images/inter-logo.webp';
                         $clubName = "Inter milan";
                       } else if ($club == "16") {
                         $image = 'images/liverpool-fc-logo.webp';
                         $clubName = "Liverpool";
                       } else {
                         $image_url = "";
                         $clubName = "Team name";
                       }
                    // ... Add similar conditions for other clubs

                    echo "<tr>";
                    echo "<td><div class='club-dp'><img class='team-logo-table' src='$image' alt='club logo'><h3>$clubName</h3></div></td>";
                    echo "<td>$matchesPlayed</td>";
                    echo "<td>$wins</td>";
                    echo "<td>$draws</td>";
                    echo "<td>$losses</td>";
                    echo "<td>$score</td>";
                    echo "<td>$points</td>";
                    echo "</tr>";
               }
               ?>
          </table>
     </section>
</body>
</html>
