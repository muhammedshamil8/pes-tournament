<?php
// manage_users.php

// Include necessary files and initialize the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "connect_db.php"; // Connect to your database

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: admin.php");
    exit();
}

// Get the tournament ID from the URL
$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;
// $match_id = isset($_GET['match_id']) ? $_GET['match_id'] : null;

// Check if a valid tournament ID is provided
if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}

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

.card-container2 {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .card {
            border: 1px solid #000;
            padding: 20px;
            text-align: center;
        }

        .card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
</style>
<script>
        function generateTable() {
            // Simulate generating fixtures
            alert('Table generated!');
            showTable();
        }

        

        function hidetheTable() {
            // Simulate stopping the process
            alert('Table hide.');
            hideTable();
        }

        function showTable() {
            const table = document.querySelector('.card-container');
            const generateButton = document.querySelector('.show');
            const stopButton = document.querySelector('.hide');

            table.style.display = 'flex';
            generateButton.style.display = 'none';
            stopButton.style.display = 'block';
        }

        function hideTable() {
            const table = document.querySelector('.league_table');
            const generateButton = document.querySelector('.show');
            const stopButton = document.querySelector('.hide');

            table.style.display = 'none';
            generateButton.style.display = 'block';
            stopButton.style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Manage match Result</h1>
        <a href="logout.php">Log out</a>
     </div>
      
    </header>

    <main>
   
        
        <button class="show" onclick="generateTable();">show the league table</button>
<button class="hide" onclick="hidetheTable();">hide the league table</button>
    <div class="card-container">

    <table class="league_table">
        <thead>
            <tr>
                <th>Position</th>
                <th>Club</th>
                <th>Matches</th>
                <th>Wins</th>
                <th>Draws</th>
                <th>Losses</th>
                <th>Score</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch tournament names and related information
            $query = "SELECT user_id, tournament_id, matches, win, draw, loss, score, points, club_id FROM league_table WHERE tournament_id = $tournament_id ORDER BY points DESC";
            $position = 1;
// echo $match_id;
            $result = $conn->query($query);

            if ($result) {
                if ($result->num_rows == 0) {
                    echo '<tr><td colspan="8">No users available for this tournament.</td></tr>';
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $club2 = $row['club_id'];
    
                // Additional improvement: Use a switch case to determine club information
                switch ($club2) {
                  case "1":
                    $image = 'images/psg.webp';
                    $clubName = "P-S-G";
                    break;
                  case "2":
                    $image = 'images/Fcb.webp';
                    $clubName = "FC Barcelona";
                    break;
                  case "3":
                    $image = 'images/real_madrid.webp';
                    $clubName = "Real madrid";
                    break;
                  case "4":
                    $image = 'images/Manchester-United-Logo.webp';
                    $clubName = "Manchester United";
                    break;
                  case "5":
                    $image = 'images/m-city.webp';
                    $clubName = "Manchester City ";
                    break;
                  case "6":
                    $image = 'images/benfica.webp';
                    $clubName = "Benfica";
                    break;
                  case "7":
                    $image = 'images/napoli.webp';
                    $clubName = "Napoli";
                    break;
                  case "8":
                    $image = 'images/ac-milan-logo.webp';
                    $clubName = "AC milan";
                    break;
                  case "9":
                    $image = 'images/arsenal-logo-0.webp';
                    $clubName = "Arsenal";
                    break;
                  case "10":
                    $image = 'images/Chelsea-Logo.webp';
                    $clubName = "Chelsea";
                    break;
                  case "11":
                    $image = 'images/new-castle.webp';
                    $clubName = "New Castle";
                    break;
                  case "12":
                    $image = 'images/bayern.webp';
                    $clubName = "Bayern";
                    break;
                  case "13":
                    $image = 'images/logo-de-juventus.webp';
                    $clubName = "Juventus";
                    break;
                  case "14":
                    $image = 'images/new-castle.webp';
                    $clubName = "New castle";
                    break;
                  case "15":
                    $image = 'images/inter-logo.webp';
                    $clubName = "Inter milan";
                    break;
                  case "16":
                    $image = 'images/liverpool-fc-logo.webp';
                    $clubName = "Liverpool";
                    break;
                  // Add cases for other clubs
                  default:
                    $image = 'images/logo.webp'; // Provide a default image if club is not recognized
                    $clubName = "Unknown Club";
                    break;
                }
                        echo "<tr>
                                <td>$position</td>
                                <td>
                                <div class='match-team--intable'><img class='match-team-image-intable' src='$image' alt='club logo'>
                                <p>$clubName</p></div>
                                </td>
                                <td>{$row['matches']}</td>
                                <td>{$row['win']}</td>
                                <td>{$row['draw']}</td>
                                <td>{$row['loss']}</td>
                                <td>{$row['score']}</td>
                                <td>{$row['points']}</td>
                            </tr>";

                        $position++;
                    }
                }
            } else {
                $error_msg = "Error fetching tournament names and related information: " . $conn->error;
                echo '<tr><td colspan="8">Error fetching data: ' . $error_msg . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
   
        </div>


        <div class="container">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Match number">
        <button onclick="search()">Search</button>
    </div>
    <div class="card-container">
        <button onclick="showAllCards()">Show All Cards</button>
        <div id="cardsContainer">
            <!-- Cards will be dynamically added here -->
        </div>
    </div>
</div>

    <div class="search-container">
            <input type="text" id="searchInput" placeholder="Match number">
            <button onclick="search2()">Search</button>

        </div>
        <table class="match_table">
              <?php
              $query = "SELECT * FROM matches WHERE tournament_id = ? order by match_status asc";
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
            <th>Team 1 Result</th>
            <th>Team 2 Result</th>
            <th>Match Status</th>
        </tr>
    </thead>
    <tbody id="matchTableBody">
        <?php
        $position = 0;
       while ($row = $result->fetch_assoc()) {
        $m_team1_id = $row['team1_id'];
        $m_team2_id = $row['team2_id'];
    
        // Query to fetch club_id for team1_id
        $query_team1 = "SELECT club_id FROM teams WHERE team_id = $m_team1_id";
        $result_team1 = mysqli_query($conn, $query_team1);
        $row_team1 = mysqli_fetch_assoc($result_team1);
        $m_team1_club_id = $row_team1['club_id'];
    
        // Query to fetch club_id for team2_id
        $query_team2 = "SELECT club_id FROM teams WHERE team_id = $m_team2_id";
        $result_team2 = mysqli_query($conn, $query_team2);
        $row_team2 = mysqli_fetch_assoc($result_team2);
        $m_team2_club_id = $row_team2['club_id'];

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
        echo "<tr>
        <tr><td colspan='8'>Match # {$position}</td></tr>
            <td>{$row['match_id']}</td>
            <td>{$row['tournament_id']}</td>
                                        <td>club id:$m_team1_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team1' alt='club logo'>
                                        <p>$clubName_team1</p></div></td>
                                        <td>club id:$m_team2_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team2' alt='club logo'>
                                        <p>$clubName_team2</p></div></td>
            <td>{$row['match_date']}</td>
            <td>{$row['team1_result']}<br>
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
            </td>
            <td>$match_status<br>
                <select>
                    <option disabled selected>Update</option>
                    <option value='1'>Played</option>
                    <option value='0'>not played</option>
                </select><br>
                <button>Save</button>
            </td>
        </tr>";
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

    <a href="tournament_home.php?tournament_id=<?php echo $tournament_id; ?>">
        <button class="return">
            <!-- Your return button content here -->
        </button>
    </a>
</div>
<div>
Matches
</div>
    </main>
    <a href="tournament_home.php?tournament_id=<?php echo $tournament_id; ?>">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
    <script>
      function search2() {
    const matchId = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('#matchTableBody tr');

    tableRows.forEach(row => {
        const matchIdCell = row.querySelector('td:first-child');  // Updated selector to target the first cell
        if (matchIdCell) {
            const matchIdText = matchIdCell.textContent || matchIdCell.innerText;

            if (matchIdText.toLowerCase().includes(matchId)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}


      function search() {
    // Fetch match_id from the input field
    const matchId = document.getElementById('searchInput').value;

    // Use matchId to fetch card details from the result_from_user table
    // Make an API call or use AJAX to fetch data from your server
    // Replace the following code with the actual fetching logic
    const cardDetails = fetchCardDetailsFromDatabase(matchId);

    // Display the fetched card details
    displayCardDetails(cardDetails);
}

function fetchCardDetailsFromDatabase(matchId) {
    // Simulated card details
    return [
        {
            id: 1,
            tournament_id: 123,
            user_id: 456,
            match_id: 1,
            result_image: 'path_to_image1.jpg',
            team1_score: 2,
            team2_score: 1,
            message: 'Great match!',
            match_active: 1
        },
        {
            id: 2,
            tournament_id: 123,
            user_id: 789,
            match_id: 2,
            result_image: 'path_to_image2.jpg',
            team1_score: 3,
            team2_score: 0,
            message: 'Awesome victory!',
            match_active: 1
        }
    ];
}

function displayCardDetails(cardDetails) {
    const cardsContainer = document.getElementById('cardsContainer');
    cardsContainer.innerHTML = '';

    cardDetails.forEach(card => {
        const cardDiv = document.createElement('div');
        cardDiv.classList.add('card');

        const cardContent = `
            <p>Name: ${card.user_id}</p>
            <img src="${card.result_image}" alt="Image Alt Text">
            <p>Score: ${card.team1_score} - ${card.team2_score}</p>
            <p>Message: ${card.message}</p>
            <p>Match Active: ${card.match_active === 1 ? 'Yes' : 'No'}</p>
        `;

        cardDiv.innerHTML = cardContent;
        cardsContainer.appendChild(cardDiv);
    });
}

function showAllCards() {
    const cardsContainer = document.getElementById('cardsContainer');
    cardsContainer.innerHTML = '';  // Clear the container to show all cards

    // Fetch all card details (you can modify this based on your actual data retrieval)
    const allCardDetails = [
        {
            id: 1,
            tournament_id: 123,
            user_id: 456,
            match_id: 1,
            result_image: 'path_to_image1.jpg',
            team1_score: 2,
            team2_score: 1,
            message: 'Great match!',
            match_active: 1
        },
        {
            id: 2,
            tournament_id: 123,
            user_id: 789,
            match_id: 2,
            result_image: 'path_to_image2.jpg',
            team1_score: 3,
            team2_score: 0,
            message: 'Awesome victory!',
            match_active: 1
        }
        // Add more card details as needed
    ];

    // Display all card details
    displayCardDetails(allCardDetails);
}

    </script>
</body>
</html>
