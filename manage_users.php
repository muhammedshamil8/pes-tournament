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

</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Manage User Profiles</h1>
        <a href="logout.php">Log out</a>
     </div>
      
    </header>

    <main>
        <!-- TODO: Display user profiles and provide options for management -->
    </main>
</body>
</html>
