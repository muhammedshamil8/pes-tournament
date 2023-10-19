<?php
// manage_users.php

// Include necessary files and initialize the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "connect_db.php"; // Connect to your database

if (!isset($_SESSION['user_id'])) {
     header("Location: home.php");
     exit();
 }
 
 if (isset($_POST['tournament_id'])) {
     $_SESSION['tournament_id'] = $_POST['tournament_id'];
 } else {
     header("Location: home.php");
     exit();
 }
 
 $user_id = $_SESSION['user_id'];
 $tournament_id = $_SESSION['tournament_id'];

// TODO: Fetch and display user profiles for the given tournament ID
// You'll need to fetch and display the user profiles associated with this tournament ID.

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Manage User Profiles</title>
     <link rel="stylesheet" href="styles/admin-general.css">
     <link rel="stylesheet" href="styles/general.css">
<style>
    /* Add your specific styles for the Manage User Profiles page */

/* Example styles for the header */
.header-card1 {
    background-color: #3498db;
    color: white;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-card1 a {
    color: white;
    text-decoration: none;
}

.header-card1 a:hover {
    text-decoration: underline;
}

/* Example styles for the card container */
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    margin-top: 20px;
    display: none;
}

/* Example styles for the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f0f0f0;
    font-weight: bold;
}
.match-team-image-intable{
width:30px;
height:30px;
}
.match-team--intable{
    display:flex;
    align-items: center;
    flex-wrap: wrap;
}
/* Example styles for the return button */
/* .return {
    display: block;
    text-align: center;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    text-decoration: none;
}

.return svg {
    margin-right: 10px;
} */
/* Add these styles to your CSS file */

/* Example styles for the search container and input */
.search-container {
    display: flex;
    margin-left: 20px;
}

.search-container input {
    padding: 5px;
    width: 200px;
}

.search-container button {
    padding: 5px 10px;
    background-color: #3498db;
    color: white;
    border: none;
    cursor: pointer;
}

.search-container button:hover {
    background-color: #2980b9;
}
.hide{
  display:none;
}
</style>

</head>
<body>
    <header>
        <h1> match Result</h1>
      
    </header>

    <main>
   

        
    
        <table class="match_table">
              <?php
              $query = "SELECT * FROM matches WHERE tournament_id = ? and match_status = 1";
              $stmt = $conn->prepare($query);
              $stmt->bind_param("i", $tournament_id);
              $stmt->execute();
              
              $result = $stmt->get_result();
              ?>
       
    <thead>
        <tr>
            <th>Match ID</th>
            <th>Tournament ID</th>
            <th>Team 1 ID</th>
            <th>Team 2 ID</th>
            <th>Match Date</th>
            
            <th>Match Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $position = 0;
        if ($result->num_rows == 0) {
          echo '<tr><td colspan="7">No match finished or result not uploaded  for this tournament.</td></tr>';
      } else {
       while ($row = $result->fetch_assoc()) {
        $m_team1_id = $row['team1_id'];
        $m_team2_id = $row['team2_id'];
    
        // Query to fetch club_id for team1_id
        $query_team1 = "SELECT club_id,user_id FROM teams WHERE team_id = $m_team1_id";
        $result_team1 = mysqli_query($conn, $query_team1);
        $row_team1 = mysqli_fetch_assoc($result_team1);
        $m_team1_club_id = $row_team1['club_id'];
        $m_team1_user_id = $row_team1['user_id'];
    
        // Query to fetch club_id for team2_id
        $query_team2 = "SELECT club_id,user_id FROM teams WHERE team_id = $m_team2_id";
        $result_team2 = mysqli_query($conn, $query_team2);
        $row_team2 = mysqli_fetch_assoc($result_team2);
        $m_team2_club_id = $row_team2['club_id'];
        $m_team2_user_id = $row_team2['user_id'];

     $m_status = $row['match_status'];

     switch ($m_team1_club_id) {
        case "1":
          $image_team1 = 'images/psg.webp';
          $clubName_team1 = "P-S-G";
          break;
        case "2":
          $image_team1 = 'images/Fcb.webp';
          $clubName_team1 = "FC Barcelona";
          break;
        case "3":
          $image_team1 = 'images/real_madrid.webp';
          $clubName_team1 = "Real madrid";
          break;
        case "4":
          $image_team1 = 'images/Manchester-United-Logo.webp';
          $clubName_team1 = "Manchester United";
          break;
        case "5":
          $image_team1 = 'images/m-city.webp';
          $clubName_team1 = "Manchester City ";
          break;
        case "6":
          $image_team1 = 'images/benfica.webp';
          $clubName_team1 = "Benfica";
          break;
        case "7":
          $image_team1 = 'images/napoli.webp';
          $clubName_team1 = "Napoli";
          break;
        case "8":
          $image_team1 = 'images/ac-milan-logo.webp';
          $clubName_team1 = "AC milan";
          break;
        case "9":
          $image_team1 = 'images/arsenal-logo-0.webp';
          $clubName_team1 = "Arsenal";
          break;
        case "10":
          $image_team1 = 'images/Chelsea-Logo.webp';
          $clubName_team1= "Chelsea";
          break;
        case "11":
          $image_team1 = 'images/new-castle.webp';
          $clubName_team1 = "New Castle";
          break;
        case "12":
          $image_team1 = 'images/bayern.webp';
          $clubName_team1 = "Bayern";
          break;
        case "13":
          $image_team1 = 'images/logo-de-juventus.webp';
          $clubName_team1 = "Juventus";
          break;
        case "14":
          $image_team1 = 'images/new-castle.webp';
          $clubName_team1 = "New castle";
          break;
        case "15":
          $image_team1 = 'images/inter-logo.webp';
          $clubName_team1 = "Inter milan";
          break;
        case "16":
          $image_team1 = 'images/liverpool-fc-logo.webp';
          $clubName_team1 = "Liverpool";
          break;
        // Add cases for other clubs
        default:
          $image_team1 = 'images/logo.webp'; // Provide a default image if club is not recognized
          $clubName_team1 = "Unknown Club";
          break;
      }
     switch ($m_team2_club_id) {
        case "1":
          $image_team2 = 'images/psg.webp';
          $clubName_team2 = "P-S-G";
          break;
        case "2":
          $image_team2 = 'images/Fcb.webp';
          $clubName_team2 = "FC Barcelona";
          break;
        case "3":
          $image_team2 = 'images/real_madrid.webp';
          $clubName_team2= "Real madrid";
          break;
        case "4":
          $image_team2 = 'images/Manchester-United-Logo.webp';
          $clubName_team2 = "Manchester United";
          break;
        case "5":
          $image_team2 = 'images/m-city.webp';
          $clubName_team2 = "Manchester City ";
          break;
        case "6":
          $image_team2 = 'images/benfica.webp';
          $clubName_team2 = "Benfica";
          break;
        case "7":
          $image_team2 = 'images/napoli.webp';
          $clubName_team2 = "Napoli";
          break;
        case "8":
          $image_team2 = 'images/ac-milan-logo.webp';
          $clubName_team2 = "AC milan";
          break;
        case "9":
          $image_team2 = 'images/arsenal-logo-0.webp';
          $clubName_team2 = "Arsenal";
          break;
        case "10":
          $image_team2 = 'images/Chelsea-Logo.webp';
          $clubName_team2 = "Chelsea";
          break;
        case "11":
          $image_team2 = 'images/new-castle.webp';
          $clubName_team2 = "New Castle";
          break;
        case "12":
          $image_team2 = 'images/bayern.webp';
          $clubName_team2 = "Bayern";
          break;
        case "13":
          $image_team2 = 'images/logo-de-juventus.webp';
          $clubName_team2 = "Juventus";
          break;
        case "14":
          $image_team2 = 'images/new-castle.webp';
          $clubName_team2 = "New castle";
          break;
        case "15":
          $image_team2 = 'images/inter-logo.webp';
          $clubName_team2 = "Inter milan";
          break;
        case "16":
          $image_team2 = 'images/liverpool-fc-logo.webp';
          $clubName_team2 = "Liverpool";
          break;
        // Add cases for other clubs
        default:
          $image_team2 = 'images/logo.webp'; // Provide a default image if club is not recognized
          $clubName_team2 = "Unknown Club";
          break;
      }
     if ($m_status === 0 ){
        $match_status = "not played";
     }else if($m_status === 1){
        $match_status = "played";
     }else{
        $match_status = "Error";
     };
     $position ++;
     if ($m_team1_user_id == $user_id || $m_team2_user_id == $user_id) {
          // Add a class to the row for highlighting
          echo "
          <tr><td colspan='5'>Match # {$position}</td></tr>
          <tr class='highlighted-row'>
  
              <td>{$row['match_id']}</td>
              <td>{$row['tournament_id']}</td>
                                          <td>club id:$m_team1_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team1' alt='club logo'>
                                          <p>$clubName_team1</p></div></td>
                                          <td>club id:$m_team2_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team2' alt='club logo'>
                                          <p>$clubName_team2</p></div></td>
              <td>{$row['match_date']}</td>
             
              <td>$match_status
                  
              </td>
          </tr>";
      } else {
          echo "
        <tr><td colspan='5'>Match # {$position}</td></tr>
        <tr>

            <td>{$row['match_id']}</td>
            <td>{$row['tournament_id']}</td>
                                        <td>club id:$m_team1_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team1' alt='club logo'>
                                        <p>$clubName_team1</p></div></td>
                                        <td>club id:$m_team2_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team2' alt='club logo'>
                                        <p>$clubName_team2</p></div></td>
            <td>{$row['match_date']}</td>
           
            <td>$match_status
                
            </td>
        </tr>";
     /*     <td>{$row['team1_result']}<br>
     <select>
     <option disabled selected>Result</option>
     <option value='1'>Won</option>
     <option value='-1'>Loss</option>
     <option value='0'>Draw</option>
 </select><br>
 <button>Upload</button>
</td>
<td>{$row['team2_result']}<br>
 <select>
     <option disabled selected>Result</option>
     <option value='1'>Won</option>
     <option value='-1'>Loss</option>
     <option value='0'>Draw</option>
 </select><br>
 <button>Upload</button>
</td> */
    }
}
      }
        ?>
    </tbody>
</table>

<?php
$stmt->close();
$conn->close();
?>


            </table>
    </main>

   
</div>

    </main>
    <a href="home.php">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
</body>
</html>
