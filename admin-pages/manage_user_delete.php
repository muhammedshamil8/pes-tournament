<?php
// delete_user.php

// Include necessary files and initialize the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "../connect_db.php"; // Connect to your database

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: ../admin.php");
    exit();
}

// Get the user ID from the URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;

// Check if a valid user ID is provided
if ($user_id === null) {
    echo "Invalid user ID.";
    exit();
}
if ($tournament_id === null) {
     echo "Invalid tournament ID.";
     exit();
 }

// Delete related entries in league_table
$delete_related_sql = "DELETE FROM league_table WHERE user_id = $user_id";
if ($conn->query($delete_related_sql) === FALSE) {
    echo "Error deleting related entries: " . $conn->error;
    exit();
}

// Now, delete the user
$delete_user_sql = "DELETE FROM registration WHERE user_id = $user_id";
if ($conn->query($delete_user_sql) === TRUE) {
    header("Location: manage_users.php?tournament_id=$tournament_id");
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();
?>
