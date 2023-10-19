<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require "connect_db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Check if tournament_id is in POST or GET
if (isset($_POST['tournament_id'])) {
    $_SESSION['tournament_id'] = $_POST['tournament_id'];
} elseif (isset($_GET['tournament_id'])) {
    $_SESSION['tournament_id'] = $_GET['tournament_id'];
} else {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$tournament_id = $_SESSION['tournament_id'];

// Fetch and display matches with team details from the database
$matchesQuery = "SELECT matches.*, teams1.user_id AS user_id_team1, teams2.user_id AS user_id_team2,
                     teams1.club_id AS club_id_team1, teams2.club_id AS club_id_team2
                 FROM matches 
                 JOIN teams AS teams1 ON matches.team1_id = teams1.team_id 
                 JOIN teams AS teams2 ON matches.team2_id = teams2.team_id 
                 WHERE matches.tournament_id = ? 
                 AND (teams1.user_id = ? OR teams2.user_id = ?) and matches.match_status = 0 order by match_id";




$stmt = $conn->prepare($matchesQuery);
$stmt->bind_param("iii", $tournament_id, $user_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tournament Matches</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            padding: 20px;
            text-align: center;
            background: red;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .match-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
        }
        .match-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .match-card p {
            margin: 5px 0;
        }
        .form-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-section input,
        .form-section textarea {
            margin-bottom: 10px;
            padding: 10px;
            width: calc(100% - 20px);
            box-sizing: border-box;
        }
        .form-section label {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .form-section button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-section button:hover {
            background-color: #45a049;
        }
        .result-uploaded {
            color: green;
        }
    
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Tournament Matches</h1>
    </div>

    <div class="container">
        <h2>Matches</h2>

        <?php
        $matchActiveQuery = "SELECT match_active FROM result_from_user WHERE tournament_id = ? and user_id = ?";
        $stmt = $conn->prepare($matchActiveQuery);
        $stmt->bind_param("ii", $tournament_id, $user_id);
        $stmt->execute();
        
        $stmt->bind_result($match_active);
        $stmt->fetch();
        $position = "pes";
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the first match
            
            // Display the current match
            echo '<div class="match-card">';
            echo '<h3>Current Match - Match #' . $position . '</h3>';
            echo '<p>Team 1 ID: ' . $row['team1_id'] . '</p>';
            echo '<p>Team 2 ID: ' . $row['team2_id'] . '</p>';

            if ($match_active === 1) {
                echo '<div class="form-section">';
                echo '<p class="result-uploaded">Result uploaded for this match. Match finished.</p>';
                echo '</div>';
            } else {
                echo '<div class="form-section">';
                echo '<form action="submit_result.php" method="post" enctype="multipart/form-data">';
                echo '<input type="hidden" name="match_id" value="' . $row["match_id"] . '">';
                echo '<input type="file" name="image" accept="image/*" required>';
                echo '<input type="number" name="team1_score" placeholder="Team 1 Score" required>';
                echo '<input type="number" name="team2_score" placeholder="Team 2 Score" required>';
                echo '<label>
                <input type="checkbox" name="match_active" value="1" required>
                Finished
              </label>
              ';
                echo '<textarea name="message" placeholder="Message"></textarea>';
                echo '<button type="submit">Submit Result</button>';
                echo '</form>';
                echo '</div>';
            }

            echo '</div>';

            // Fetch the next match
            $row = $result->fetch_assoc();
            if ($row) {
                echo '<div class="match-card">';
                echo '<h3>Next Match - Match #' . $position . '</h3>';
                echo '<p>Team 1 ID: ' . $row['team1_id'] . '</p>';
                echo '<p>Team 2 ID: ' . $row['team2_id'] . '</p>';
                echo '<div class="form-section">';
                echo '<p>Upload the result for the next match after finishing the current match.</p>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>No more matches for this tournament.</p>';
            }
        } else {
            echo "No matches found for this tournament.";
        }
        ?>
    </div>

    <script>
        // Simulate finishing a match after 5 seconds (replace with your actual logic)
        // setTimeout(function() {
        //     // Redirect or handle switching to the next match
        //     // For now, we'll just reload the page
        //     location.reload();
        // }, 5000);
    </script>
</body>
</html>

