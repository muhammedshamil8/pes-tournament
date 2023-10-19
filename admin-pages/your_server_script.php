<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Data from the client-side
$matchId = $_POST["match_id"];
$tournamentId = $_POST["tournament_id"];
$team1Result = $_POST["team1_result"];
$team2Result = $_POST["team2_result"];
$team1Id = $_POST["team_id1"];
$team2Id = $_POST["team_id2"];
$updateStatus = $_POST["update_status"];
$team1Score = $_POST["team1_score"];
$team2Score = $_POST["team2_score"];

try {
    // Create a MySQLi connection
    $conn = new mysqli("mysql_db", "root", "root", "pes");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Start a transaction
    $conn->begin_transaction();

    // Retrieve user_id for team1 from the 'teams' table
    $sqlGetUser1Id = "SELECT user_id FROM teams WHERE team_id = ?";
    $stmtGetUser1Id = $conn->prepare($sqlGetUser1Id);
    $stmtGetUser1Id->bind_param("i", $team1Id);
    $stmtGetUser1Id->execute();
    $stmtGetUser1Id->bind_result($user1Id);
    $stmtGetUser1Id->fetch();
    $stmtGetUser1Id->close();

    // Retrieve user_id for team2 from the 'teams' table
    $sqlGetUser2Id = "SELECT user_id FROM teams WHERE team_id = ?";
    $stmtGetUser2Id = $conn->prepare($sqlGetUser2Id);
    $stmtGetUser2Id->bind_param("i", $team2Id);
    $stmtGetUser2Id->execute();
    $stmtGetUser2Id->bind_result($user2Id);
    $stmtGetUser2Id->fetch();
    $stmtGetUser2Id->close();

    if ($user1Id && $user2Id) {
        // Insert data into the 'matches' table
        $sqlMatches = "UPDATE matches 
        SET 
            match_date = NOW(), 
            team1_result = ?, 
            team2_result = ?, 
            match_status = ? 
        WHERE 
            match_id = ? AND 
            tournament_id = ? AND
            team1_id = ? AND 
            team2_id = ?";

$stmtMatches = $conn->prepare($sqlMatches);
$stmtMatches->bind_param("iiiiiii", $team1Result, $team2Result, $updateStatus, $matchId, $tournamentId, $team1Id, $team2Id);
$stmtMatches->execute();


        // Update data in the 'user_statistics' table for team1
        $sqlUserStatsTeam1 = "UPDATE league_table
                            SET 
                            matches = matches + 1,

                                win = win + CASE WHEN ? = 1 THEN 1 ELSE 0 END,
                                loss = loss + CASE WHEN ? = -1 THEN 1 ELSE 0 END,
                                draw = draw + CASE WHEN ? = 0 THEN 1 ELSE 0 END,
                                score = score + ?,
                                points = points + CASE 
                                    WHEN ? = 1 THEN 3
                                    WHEN ? = 0 THEN 1
                                    ELSE 0
                                END
                            WHERE user_id = ? AND tournament_id = ?";
        $stmtUserStatsTeam1 = $conn->prepare($sqlUserStatsTeam1);
        $stmtUserStatsTeam1->bind_param("iiiiiiii", $team1Result, $team1Result, $team1Result, $team1Score, $team1Result, $team1Result, $user1Id, $tournamentId);

        $stmtUserStatsTeam1->execute();

        // Update data in the 'user_statistics' table for team2
        $sqlUserStatsTeam2 = "UPDATE league_table
                            SET 
                            matches = matches + 1,
                                win = win + CASE WHEN ? = 1 THEN 1 ELSE 0 END,
                                loss = loss + CASE WHEN ? = -1 THEN 1 ELSE 0 END,
                                draw = draw + CASE WHEN ? = 0 THEN 1 ELSE 0 END,
                                score = score + ?,
                                points = points + CASE 
                                    WHEN ? = 1 THEN 3
                                    WHEN ? = 0 THEN 1
                                    ELSE 0
                                END
                            WHERE user_id = ? AND tournament_id = ?";
        $stmtUserStatsTeam2 = $conn->prepare($sqlUserStatsTeam2);
        $stmtUserStatsTeam2->bind_param("iiiiiiii", $team2Result, $team2Result, $team2Result, $team2Score, $team2Result, $team2Result, $user2Id, $tournamentId);

        $stmtUserStatsTeam2->execute();

        // Commit the transaction
        $conn->commit();

        // Send a success response back to the client
        $response = array("message" => "Data successfully updated in the database.");
        echo json_encode($response);
    } else {
        // Handle the case where the user_id for either team was not found
        $errorResponse = array("message" => "User not found for one or both teams.");
        echo json_encode($errorResponse);
    }

    $conn->close();
} catch (Exception $e) {
    // If there's an error, roll back the transaction
    $conn->rollback();

    // Handle the error and send an appropriate response
    $errorResponse = array("message" => "An error occurred: " . $e->getMessage());
    echo json_encode($errorResponse);
}
?>
