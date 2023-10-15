<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require "connect_db.php"; // Assuming this file contains the database connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_POST["match_id"];
    $your_score = $_POST["team1_score"];
    $opponent_score = $_POST["team2_score"];
    $message = $_POST["message"];
  $match_active = $_POST["match_active"];
    // Add code to handle image upload if needed
    // Save image to appropriate location and get the image path

    // Update the result_from_user table
    $insertQuery = "INSERT INTO result_from_user (tournament_id, user_id, match_id, result_image ,team1_score ,team2_score,message,match_active
    ) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiisissi", $tournament_id, $user_id, $match_id, $image_path, $your_score, $opponent_score, $message, $match_active);

    // Assuming $tournament_id and $user_id are the actual tournament and user IDs
    
    $user_id = $_SESSION['user_id'];
    $tournament_id  = $_SESSION['tournament_id'];
    $image_path = "path/to/your/image.jpg"; // Replace with the actual image path

    if ($stmt->execute()) {
        header("Location: user_fixture.php?tournament_id=$tournament_id"); // Redirect to display next match
        exit();
    } else {
        echo "Error submitting result: " . $conn->error;
    }
}
?>
