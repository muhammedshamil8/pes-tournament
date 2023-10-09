<?php
// manage_fixtures.php
// ALTER TABLE matches MODIFY COLUMN match_id BIGINT UNSIGNED NOT NULL;
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "connect_db.php";

if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: admin.php");
    exit();
}

$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;

if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}

function displayErrorMessage($message) {
    echo "<div class='error-message'>$message</div>";
}

function generateFixture($num_teams) {
    $tournament_id = isset($_GET['tournament_id']) ? intval($_GET['tournament_id']) : null;

    // Ensure the number of teams is even for round-robin scheduling
    if ($num_teams % 2 != 0) {
        $num_teams++;
    }

    if ($tournament_id === null) {
        echo "Invalid tournament ID.";
        exit();
    }

    $matches = array();
    $teams_query = "SELECT team_id FROM teams WHERE tournament_id = $tournament_id";
    $teams_result = executeQuery($teams_query);

    // Fetch team IDs from the database
    $team_ids = array();
    while ($row = $teams_result->fetch_assoc()) {
        $team_ids[] = $row['team_id'];
    }

    $total_rounds = $num_teams - 1;
    $matches_per_round = $num_teams / 2;

    for ($round = 1; $round <= $total_rounds; $round) {
        // Home matches
        echo "<tr><td colspan='5' class='center'>Home Matches</td></tr>";
    for ($round = 1; $round <= $total_rounds; $round++) {
        // Home matches
        echo "<tr><td colspan='5'>Round " . $round . " (Home Matches)</td></tr>";

        // Display home matches for this round
        displayMatches($round, $matches_per_round, $num_teams, $team_ids, true);
    }
    }
    for ($round = 1; $round <= $total_rounds; $round) {
        // Home matches
        echo "<tr><td colspan='5' class='center'>Away Matches</td></tr>";
    for ($round = 1; $round <= $total_rounds; $round++) {
        // Away matches
        echo "<tr><td colspan='5'>Round " . $round . " (Away Matches)</td></tr>";

        // Display away matches for this round
        displayMatches($round, $matches_per_round, $num_teams, $team_ids, false);
    }
    }
    return $matches;
}
 function generateUniqueMatchId($tournament_id) {
        // Generate a unique match_id using timestamp and a random number
        $timestamp = time();
        $random_number = mt_rand(1000, 9999);
        $unique_match_id = $timestamp . $random_number . $tournament_id;
    
        return $unique_match_id;
    }
function displayMatches($round, $matches_per_round, $num_teams, $team_ids, $isHomeMatch) {
    $tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;
    // $baseMatchId = ($round - 1) * $matches_per_round * 2;
   
    
    // Rest of your code...
    
    for ($match = 0; $match < $matches_per_round; $match++) {
        for ($match = 0; $match < $matches_per_round; $match++) {
            // Generate a unique match_id using the provided function
            $match_id = generateUniqueMatchId($tournament_id);
        
            $team1_index = ($round + $match) % ($num_teams - 1);
            $team2_index = ($round + $num_teams - $match - 1) % ($num_teams - 1);
        
            $team1 = $team_ids[$team1_index];
            $team2 = $team_ids[$team2_index];
        
            // Avoid a team playing against itself and ensure valid team IDs
            if ($team1 !== null && $team2 !== null && $team1 != $team2) {
                $day_offset = ($round - 1) * $matches_per_round;
                $match_date = date('Y-m-d', strtotime("+$day_offset days"));
        
                $insert_query = "INSERT INTO matches (tournament_id, match_id, team1_id, team2_id, match_date) 
                                VALUES ($tournament_id, $match_id, $team1, $team2, '$match_date')
                                ON DUPLICATE KEY UPDATE match_date='$match_date'";
        
                executeQuery($insert_query);

                $query2 = "SELECT 
            lt.user_id, 
            lt.tournament_id, 
            lt.matches, 
            lt.win, 
            lt.draw, 
            lt.loss, 
            lt.score, 
            lt.points, 
            lt.club_id, 
            m.match_id,
            m.tournament_id,
            m.team1_id,
            m.team2_id,
            m.match_date,
            m.team1_result,
            m.team2_result,
            m.match_status,
            t.team_id,
            t.club_id
          FROM 
            league_table lt
          INNER JOIN 
            matches m ON lt.tournament_id = ? AND m.match_id = ?
          INNER JOIN
            teams t ON (lt.user_id = t.user_id AND m.team1_id = t.team_id) OR (lt.user_id = t.user_id AND m.team2_id = t.team_id)";
// $match_id = 3;
global $conn;

// $match_id = isset($_GET['match_id']);
// Prepare and bind the statement
$stmt = $conn->prepare($query2);
$stmt->bind_param("ii", $tournament_id, $match_id);

// Set the values and execute
$stmt->execute();

// Fetch the results
$result = $stmt->get_result();

// Close the statement
$stmt->close();

$result->data_seek(0); // Reset result pointer to fetch data again

if ($result) {
    if ($result->num_rows == 0) {
        echo '<tr><td colspan="7">No match details available for this tournament.</td></tr>';
    } else {
        $seenMatchIds = array();  // Keep track of seen match IDs to avoid duplicates
        while ($row = $result->fetch_assoc()) {
            $matchId = $row['match_id'];

            // Check if we have already seen this match ID
            if (in_array($matchId, $seenMatchIds)) {
                // Skip this row if the match ID has been seen before
                continue;
            }

            $seenMatchIds[] = $matchId;// Reset result pointer to fetch data again
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
                 }else if($m_status === 0){
                    $match_status = "played";
                 }else{
                    $match_status = "Error";
                 };
               
                 echo "
                         <tr>
                         <td>{$row['match_id']}</td>
                         <td>club id:$m_team1_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team1' alt='club logo'>
                         <p>$clubName_team1</p></div></td>
                         <td>club id:$m_team2_club_id<br> <div class='match-team--intable'><img class='match-team-image-intable' src='$image_team2' alt='club logo'>
                         <p>$clubName_team2</p></div></td>
                         

                         
                        

               
                   
                        <td class='center'>{$match_date}</td> <!-- Corrected the display of match date -->
                        <td class='center'>
                            <a href='manage_match_result.php?match_id=$match_id&tournament_id=$tournament_id'>Manage Result</a>
                        </td>
                    </tr>";
            }
        }
    }
}
}
        }
    }        
}

function refreshFixture($tournament_id) {
    $delete_query = "DELETE FROM matches WHERE tournament_id = $tournament_id";
    executeQuery($delete_query);
}
function executeQuery($query) {
    global $conn;
    $result = $conn->query($query);

    if (!$result) {
        displayErrorMessage("Error executing query: " . $conn->error);
        exit();
    } else {
        // echo "Query executed successfully.";
    }

    return $result;
}

$num_teams_query = "SELECT COUNT(DISTINCT team_id) AS num_teams FROM teams WHERE tournament_id = $tournament_id";
$num_teams_result = executeQuery($num_teams_query);
$row = $num_teams_result->fetch_assoc();
$num_teams = $row['num_teams'];
// echo $num_teams;
if ($num_teams < 2) {
    echo "Insufficient teams to generate fixtures.";
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fixtures</title>
    <link rel="stylesheet" href="styles/admin-general.css">
    <link rel="stylesheet" href="styles/general.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        th:first-child, td:first-child {
            width: 10%;
        }

        th:nth-child(2), td:nth-child(2) {
            width: 25%;
        }

        th:nth-child(3), td:nth-child(3) {
            width: 25%;
        }

        th:nth-child(4), td:nth-child(4) {
            width: 20%;
        }

        th:nth-child(5), td:nth-child(5) {
            width: 20%;
        }

        .center {
            text-align: center;
        }

        .match-team--intable {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .match-team-image-intable {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
    </style>
  <script>

function refreshFixtures(tournament_id) {
    if (confirm('Are you sure you want to delete all fixtures?')) {
       <?php
            $delete_query = "DELETE FROM matches WHERE tournament_id = $tournament_id";
            executeQuery($delete_query);

        ?>
    }
}

    // function refreshFixture(num_teams, tournament_id) {
    //     // Simulate generating fixtures
    //     alert('Fixtures generated!');
    //     generateFixtures(num_teams, tournament_id);
    // }
        function generateFixtures() {
            // Simulate generating fixtures
            alert('Fixtures generated!');
            showFixturesTable();
        }

        function updateFixtures(tournament_id) {
    if (confirm('Are you sure you want to update fixtures? This will delete all existing data for this tournament.')) {
        refreshFixtures(tournament_id);
        $fixtures = generateFixture($num_teams);
        // const Thetable = document.querySelector('.table_data');

        // Thetable.style.display = 'none';
    }
}


        // function stopProcess() {
        //     // Simulate stopping the process
        //     alert('finalized fixture you cant change .');
        //     const generateButton = document.getElementById('generateButton');
        //     generateButton.style.display = 'none';

        // }

        function showFixturesTable() {
            const table = document.getElementById('fixturesTable');
            const generateButton = document.getElementById('generateButton');
            // const updateButton = document.getElementById('updateButton');
            const stopButton = document.getElementById('stopButton');

            table.style.display = 'block';
            generateButton.style.display = 'none';
            updateButton.style.display = 'block';
            stopButton.style.display = 'block';
        }

        // function hideFixturesTable() {
        //     const table = document.getElementById('fixturesTable');
        //     const generateButton = document.getElementById('generateButton');
        //     const updateButton = document.getElementById('updateButton');
        //     const stopButton = document.getElementById('stopButton');

        //     table.style.display = 'none';
        //     generateButton.style.display = 'block';
        //     updateButton.style.display = 'none';
        //     stopButton.style.display = 'none';
        // }
    </script>
</head>
<body>
<header>
    <div class="header-card1">
        <h1>Manage Fixtures</h1>
        <a href="logout.php">Log out</a>
    </div>
</header>
<main>
    <section>
        <h2>Fixtures for Tournament ID: <?php echo $tournament_id; ?></h2>
        <button id="generateButton" onclick="generateFixtures()">Upload Fixtures</button>
        <a href="manage_fixtures.php?tournament_id=<?php echo $tournament_id; ?>"><button onclick="alert('if registeration closed for tournament then not need to refresh other wise refresh becuase new player will register')">Refresh the page</button></a>
        <button id="updateButton"  onclick="updateFixtures(<?php echo $tournament_id; ?>)">Update Fixtures</button>
        <!-- <button id="stopButton" style="display: none;" onclick="stopProcess()">Stop</button> -->
        <table id="fixturesTable" >
            <thead>
                <tr>
                    <th class="center">Match ID</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th class="center">Match Date</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody class="table_data">
                <?php
                    // Display fixtures and options
                    $fixtures = generateFixture($num_teams);
                ?>
            </tbody>
        </table>
    </section>
</main>
<a href="tournament_home.php?tournament_id=<?php echo $tournament_id; ?>">
    <button class="return">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
        </svg>
    </button>
</a>
</body>
</html>