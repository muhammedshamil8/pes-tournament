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
    $image_path = $_POST["image"];
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

    if ($stmt->execute()) {
        header("Location: user_fixture.php?tournament_id=$tournament_id"); // Redirect to display next match
        exit();
    } else {
        echo "Error submitting result: " . $conn->error;
    }
}
?>

<!-- 
#	Name	Type	Collation	Attributes	Null	Default	Comments	Extra	Action
	1	id Primary	int			No	None		AUTO_INCREMENT	Change Change	Drop Drop	
	2	tournament_id Index	int			No	None			Change Change	Drop Drop	
	3	user_id Index	int			No	None			Change Change	Drop Drop	
	4	match_id Index	int			No	None			Change Change	Drop Drop	
	5	result_image	varchar(255)	utf8mb4_0900_ai_ci		Yes	NULL			Change Change	Drop Drop	
	6	team1_score	int			Yes	NULL			Change Change	Drop Drop	
	7	team2_score	int			Yes	NULL			Change Change	Drop Drop	
	8	message	varchar(50)	utf8mb4_0900_ai_ci		Yes	NULL			Change Change	Drop Drop	
	9	match_active	int			No	0			Change Change	Drop Drop	
 -->
