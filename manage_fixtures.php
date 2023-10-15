<?php
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

function generateFixture($num_teams, $tournament_id) {
  if ($num_teams < 2) {
      echo "Insufficient teams to generate fixtures.";
      exit();
  }

  // Ensure the number of teams is even for round-robin scheduling
  if ($num_teams % 2 != 0) {
      $num_teams++;
  }

  // Retrieve the team IDs for the tournament
  $teams_query = "SELECT team_id FROM teams WHERE tournament_id = $tournament_id";
  $teams_result = executeQuery($teams_query);

  $team_ids = array();
  while ($row = $teams_result->fetch_assoc()) {
      $team_ids[] = $row['team_id'];
  }

  echo "<table border='1'>";
  echo "<tr><th colspan='5'>Fixtures for Tournament ID: $tournament_id</th></tr>";

  // Generate matches for phase 1
  echo "<tr><td colspan='5'><strong>Phase 1 - Initial Matches</strong></td></tr>";
  generateRoundRobinMatches($team_ids, $tournament_id);

  // Generate matches for phase 2 (return matches)
  // echo "<tr><td colspan='5'><strong>Phase 2 - Return Matches</strong></td></tr>";
  // generateRoundRobinMatches($team_ids, $tournament_id);

  echo "</table>";
}
function generateRoundRobinMatches($team_ids, $tournament_id) {
  $num_teams = count($team_ids);

  // Get the current date in the "Y-m-d" format
  $currentDate = date('Y-m-d');

  for ($i = 0; $i < $num_teams - 1; $i++) {
      for ($j = $i + 1; $j < $num_teams; $j++) {
          $team1 = $team_ids[$i];
          $team2 = $team_ids[$j];
          $match_id = generateUniqueMatchId($tournament_id);

          // Insert the match into the database
          $insert_query = "INSERT INTO matches (tournament_id, match_id, team1_id, team2_id, match_date) 
                           VALUES ($tournament_id, $match_id, $team1, $team2, '$currentDate')";
          executeQuery($insert_query);

          // Display the match
          echo "<tr>";
          echo "<td>Match ID: $match_id</td>";
          echo "<td>Team $team1</td>";
          echo "<td>Team $team2</td>";
          echo "<td>Match Date: $currentDate</td>";
          echo "  <td class='center'>
                  <a href='manage_match_result.php?match_id=$match_id&tournament_id=$tournament_id'>Manage Result</a>
                </td>";
          echo "</tr>";

          // Increment the date for the next match
          $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
      }
  }
}

function generateUniqueMatchId($tournament_id) {
    // Generate a unique match_id using timestamp and a random number
    $timestamp = time();
    $random_number = mt_rand(1000, 9999);
    $unique_match_id = $timestamp . $random_number . $tournament_id;

    return $unique_match_id;
}

function displayMatches($round, $matches_per_round, $num_teams, $team_ids, $isHomeMatch, $tournament_id) {
   
  $day = ceil($round / 2);

  // Get the current date in the "y-m-d" format
  $matchDate = date('Y-m-d', strtotime("+$round day"));

  for ($match = 0; $match < $matches_per_round; $match++) {
    $team1_index = ($round + $match) % $num_teams;
    $team2_index = ($round + $num_teams - $match - 1) % $num_teams;

    // Check if the team indices are valid
    if ($team1_index >= 0 && $team1_index < count($team_ids) &&
        $team2_index >= 0 && $team2_index < count($team_ids)) {
        if ($isHomeMatch) {
            $team1 = $team_ids[$team1_index];
            $team2 = $team_ids[$team2_index];
        } else {
            $team1 = $team_ids[$team2_index];
            $team2 = $team_ids[$team1_index];
        }
        // Avoid a team playing against itself and ensure valid team IDs
        if ($team1 !== null && $team2 !== null && $team1 != $team2) {
            // Generate a unique match_id using the provided function
            $match_id = generateUniqueMatchId($tournament_id);
            // Modify the following part based on your match insertion logic
            // Insert the match into the database
            $insert_query = "INSERT INTO matches (tournament_id, match_id, team1_id, team2_id,match_date) 
                             VALUES ($tournament_id, $match_id, $team1, $team2)";
            executeQuery($insert_query);


            
            // Display the match
            echo "<tr>";
            echo "<td>Match ID: $match_id</td>";
            echo "<td>Team $team1</td>";
            echo "<td>Team $team2</td>";
            echo "<td>Match Date:$day</td>";
            echo "<td>Actions: $day</td>";
            echo "</tr>";
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

if ($num_teams < 2) {
    echo "Insufficient teams to generate fixtures.";
    exit();
}

// generateFixture($num_teams);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fixturess</title>
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
   const  tournament_id = <?php echo $tournament_id ?>;
document.querySelector('.updateButton')
.addEventListener('click',()=>{
  refreshFixtures(tournament_id)
})
function refreshFixtures(tournament_id) {
    if (confirm('Are you sure you want to delete all fixtures?')) {
      alert('if registeration closed for tournament then not need to refresh other wise refresh becuase new player will register');
       <?php
            $delete_query = "DELETE  FROM matches WHERE tournament_id = $tournament_id";
            executeQuery($delete_query);
           
        ?>
         window.location.href = 'manage_fixtures.php?tournament_id=<?php echo $tournament_id; ?>';
    }
}

    // function refreshFixture(num_teams, tournament_id) {
    //     // Simulate generating fixtures
    //     alert('Fixtures generated!');
    //     generateFixtures(num_teams, tournament_id);
    // }
        // function generateFixtures() {
        //     // Simulate generating fixtures
        //     alert('Fixtures generated!');
        //     showFixturesTable();
        // }

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

        // function showFixturesTable() {
        //     const table = document.getElementById('fixturesTable');
        //     const generateButton = document.getElementById('generateButton');
        //     // const updateButton = document.getElementById('updateButton');
        //     const stopButton = document.getElementById('stopButton');

        //     table.style.display = 'block';
        //     generateButton.style.display = 'none';
        //     updateButton.style.display = 'block';
        //     stopButton.style.display = 'block';
        // }

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
        
       
        <button id="updateButton"   onclick="updateFixtures(<?php echo $tournament_id; ?>)">Update Fixtures</button>
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
                   generateFixture($num_teams, $tournament_id);
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