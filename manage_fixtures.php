<?php
// manage_fixtures.php

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

// Check if a valid tournament ID is provided
if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}

// Function to generate fixture based on the formula
function generateFixture($num_teams) {
    $matches = array();

    if ($num_teams % 2 != 0) {
        // If odd number of teams, add a "bye" (team vs no one) for each round
        $num_teams++;
    }

    $total_rounds = $num_teams - 1;
    $matches_per_round = $num_teams / 2;

    for ($round = 1; $round <= $total_rounds; $round++) {
        for ($match = 1; $match <= $matches_per_round; $match++) {
            $team1 = ($round + $match - 1) % $total_rounds + 1;
            $team2 = ($round + $total_rounds - $match) % $total_rounds + 1;

            // Avoid the "bye" match
            if ($team1 != $num_teams && $team2 != $num_teams) {
                $matches[] = array('team1' => $team1, 'team2' => $team2);
            }
        }
    }

    return $matches;
}

// Calculate the total number of matches based on the number of teams and format (single or double round-robin)
$num_teams_query = "SELECT COUNT(DISTINCT team_id) AS num_teams FROM teams WHERE tournament_id = $tournament_id";
$num_teams_result = $conn->query($num_teams_query);

if (!$num_teams_result) {
    echo "Error fetching number of teams: " . $conn->error;
    exit();
}

$row = $num_teams_result->fetch_assoc();
$num_teams = $row['num_teams'];

if ($num_teams < 2) {
    echo "Insufficient teams to generate fixtures.";
    exit();
}

// Determine the number of matches based on the format (single or double round-robin)
$is_double_round_robin = true; // Set this based on your tournament format
$total_matches = ($is_double_round_robin) ? $num_teams * ($num_teams - 1) : $num_teams * ($num_teams - 1) / 2;

// Generate fixtures
$fixtures = generateFixture($num_teams);

// TODO: Store fixtures in the database (matches table) and display them

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fixtures</title>
    <link rel="stylesheet" href="styles/admin-general.css">
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
    </style>
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
            <table>
                <thead>
                    <tr>
                        <th class="center">Match ID</th>
                        <th>Team 1</th>
                        <th>Team 2</th>
                        <th class="center">Match Date</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display fixtures and options
                    foreach ($fixtures as $index => $match) {
                        $match_id = $index + 1; // Match ID starts from 1
                        $team1_id = $match['team1'];
                        $team2_id = $match['team2'];
                        $match_date = date('Y-m-d', strtotime("+$index days")); // Simple date increment for demonstration

                        // TODO: Store this fixture in the database (matches table)

                        echo "<tr>
                                <td class='center'>{$match_id}</td>
                                <td>{$team1_id}</td>
                                <td>{$team2_id}</td>
                                <td class='center'>{$match_date}</td>
                                <td class='center'>
                                    <a href='edit_fixture.php?match_id={$match_id}'>Edit</a>
                                    <a href='delete_fixture.php?match_id={$match_id}'>Delete</a>
                                    <a href='manage_result.php?match_id={$match_id}'>Manage Result</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
