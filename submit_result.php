<?php
session_start();
require "connect_db.php"; // Assuming this file contains the database connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_POST["match_id"];
    $your_score = $_POST["your_score"];
    $opponent_score = $_POST["opponent_score"];
    $message = $_POST["message"];

    // Add code to handle image upload if needed
    // Save image to appropriate location and get the image path

    // Update the result_from_user table
    $insertQuery = "INSERT INTO result_from_user (tournament_id, user_id, match_id, result_image, your_score, opponent_score, message) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiisiss", $tournament_id, $user_id, $match_id, $image_path, $your_score, $opponent_score, $message);

    // Assuming $tournament_id and $user_id are the actual tournament and user IDs
    
    $user_id = $_SESSION['user_id'];
    $tournament_id  = $_SESSION['tournament_id'];
    $image_path = "path/to/your/image.jpg"; // Replace with the actual image path

    if ($stmt->execute()) {
        header("Location: display_next_match.php"); // Redirect to display next match
        exit();
    } else {
        echo "Error submitting result: " . $conn->error;
    }
}
?>
