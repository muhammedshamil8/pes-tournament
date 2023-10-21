<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "./connect_db.php"; // Connect to your database

if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: ./admin.php");
    exit();
}

$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;

if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}

function getTeamsForTournament($tournament_id, $conn) {
    $query = "SELECT team_id FROM teams WHERE tournament_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $team_ids = [];
    while ($row = $result->fetch_assoc()) {
        $team_ids[] = $row['team_id'];
    }

    return $team_ids;
}

function calculateTotalMatches($num_teams) {
    return ($num_teams * ($num_teams - 1)) ;
}

function generateAndInsertFixtures($tournament_id, $conn) {
    $team_ids = getTeamsForTournament($tournament_id, $conn);
    $num_teams = count($team_ids);
    $num_rounds = $num_teams % 2 === 0 ? ($num_teams - 1) : $num_teams;
    $matches_per_round = $num_teams % 2 === 0 ? $num_teams / 2 : ($num_teams - 1) / 2;
    $total_matches = calculateTotalMatches($num_teams );
    $match_date = date("Y-m-d");
    $fixtures = [];

    for ($phase = 1; $phase <= 2; $phase++) {
        $phase_name = $phase === 1 ? "Home matches" : "Away matches";
        $fixtures["Phase $phase - $phase_name"] = [];

        for ($round = 1; $round <= $num_rounds; $round++) {
            $matches = [];

            for ($i = 0; $i < $matches_per_round; $i++) {
                $team1_id = $team_ids[($round + $i) % $num_teams];
                $team2_id = $team_ids[($round + $num_teams - $i - 1) % $num_teams];

                if ($phase === 2) {
                    [$team1_id, $team2_id] = [$team2_id, $team1_id];
                }

                $matches[] = "Game " . ($i + 1) . ": Team $team1_id v Team $team2_id";
             // Insert the match into the database
             $query_insert_match = "INSERT INTO matches (tournament_id, team1_id, team2_id, match_date, match_status) 
             VALUES (?, ?, ?, ?, 0)";
$stmt_insert_match = $conn->prepare($query_insert_match);
$stmt_insert_match->bind_param("iiss", $tournament_id, $team1_id, $team2_id, $match_date);

if (!$stmt_insert_match->execute()) {
echo "Error inserting match: " . $stmt_insert_match->error;
return null;  // Return null to indicate an error
}
}

// Increment the match date for the next round
$match_date = date('Y-m-d', strtotime($match_date . ' + 1 day'));



            $fixtures["Phase $phase - $phase_name"]["Round $round"] = $matches;
        }
    }

    return [$fixtures, $total_matches];
}
$query_tournament_name = "SELECT tournament_name FROM tournament WHERE tournament_id = ?";
    $stmt_tournament_name = $conn->prepare($query_tournament_name);
    $stmt_tournament_name->bind_param("i", $tournament_id);
    $stmt_tournament_name->execute();
    $stmt_tournament_name->bind_result($tournament_name);
    $stmt_tournament_name->fetch();
    $stmt_tournament_name->close();

// $query_match_id = "SELECT match_id FROM matches WHERE tournament_id = ?";
//     $stmt_tournament_name = $conn->prepare($query_tournament_name);
//     $stmt_tournament_name->bind_param("i", $tournament_id);
//     $stmt_tournament_name->execute();
//     $stmt_tournament_name->bind_result($match_id);
//     $stmt_tournament_name->fetch();
//     $stmt_tournament_name->close();
function deleteMatchesForTournament($tournament_id, $conn,$tournament_name) {
    // Fetch the tournament name
    

    $query = "DELETE FROM matches WHERE tournament_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tournament_id);

    if ($stmt->execute()) {
        echo "Matches for tournament '$tournament_name'fixture have been successfully updated.";
    } else {
        echo "Error deleting matches: " . $conn->error;
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions button {
            padding: 10px 20px;
            font-size: 18px;
            margin: 0 10px;
        }

        .fixtures {
            margin-left: 20px;
        }

        .fixtures h2 {
            margin-top: 20px;
        }

        .fixtures h3 {
            margin-top: 10px;
        }

        .fixtures ul {
            list-style-type: none;
            padding: 0;
        }

        .fixtures ul li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Main Page</h1>

    <!-- Buttons to trigger actions -->
    <div class="actions">
        <form action="manage_the_fixtures.php" method="post" style="display: inline;">
            <input type="hidden" name="action" value="generate">
            <button type="submit" name="perform_action" value="true">Generate Fixtures</button>
        </form>

        <form action="manage_the_fixtures.php" method="post" style="display: inline;">
            <input type="hidden" name="action" value="update">
            <button type="submit" name="perform_action" value="true">Update Fixtures</button>
        </form>
    </div>

    <div class="fixtures">
        <!-- Display generated fixtures here -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $action = isset($_POST['action']) ? $_POST['action'] : null;
     $perform_action = isset($_POST['perform_action']) ? $_POST['perform_action'] : false;

     if ($perform_action && ($action === 'generate' || $action === 'update')) {
         deleteMatchesForTournament($tournament_id, $conn,$tournament_name);

         list($fixtures, $total_matches) = generateAndInsertFixtures($tournament_id, $conn);
         $team_ids = getTeamsForTournament($tournament_id, $conn);
         echo "<h2>Teams for Tournament</h2>";
         echo "<ul>";
         foreach ($team_ids as $team_id) {
             echo "<li>Team $team_id</li>";
         }
         echo "</ul>";
         // Display match information
         echo "<h2>Generated Fixtures:</h2>";
         foreach ($fixtures as $phase => $rounds) {
             echo "<h3>$phase</h3>";
             echo "<ul>";
             foreach ($rounds as $round => $matches) {
                 echo "<li>$round:";
                 echo "<ul>";
                 foreach ($matches as $match) {
                     echo "<li>$match </li>";
                 }
                 echo "</ul></li>";
             }
             echo "</ul>";
         }

         echo "Total Matches: $total_matches";
     }
 }
        //  &nbsp;   <a href='manage_match_result.php?tournament_id=$tournament_id&match_id=$match_id'></a>
       
       
        ?>
    </div>
</body>

</html>
