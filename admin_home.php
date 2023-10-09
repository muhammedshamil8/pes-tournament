<?php
// admin_tournament.php

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




?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles/general.css">

  <link rel="stylesheet" href="styles/admin-general.css">

</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Welcome to  Admin Page</h1>
        <a href="logout.php">Log out</a>
     </div>
        
    </header>

    <main>
    <section>
            <h2>Manage Tournaments</h2>
            <a href="tournament_manage.php">Manage Tournaments</a>
           
        </section>
        <section>
            <h2>Create New Tournament</h2>
            <a href="tournament-register.php">Create New Tournament</a>
           
        </section>

        <section>
            <h2>Login Existing Tournament</h2>
            <a href="tournament-login.php">Login Existing Tournament</a>
        </section>

        <section>
            <h2>Edit Admin Profiles</h2>
            <a href="admin_edit.php">Manage Admin Profiles</a>
        </section>
    </main>

    <!-- <a href="admin.php">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a> -->
    <div id="loading-overlay">
  <div class="spinner"></div>
</div>
<script src="/script/general.js"></script>
</body>
</html>
