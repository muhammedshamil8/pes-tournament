<?php
// admin_tournament.php

// Include necessary files and initialize the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "./connect_db.php"; // Connect to your database

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: ./admin.php");
    exit();
}

// Get the tournament ID from the URL
$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;

// Check if a valid tournament ID is provided
if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Admin Dashboard</title>
  <link rel="stylesheet" href="./styles/general.css">
  <link rel="stylesheet" href="./styles/admin-general.css">


</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Welcome, <?php echo $_SESSION['admin_username']; ?></h1>
        <a href="./logout.php">Log out</a>
     </div>
        <?php
echo "Welcome to Tournament Admin Page for Tournament ID: $tournament_id";
?>
    </header>

    <main>
        <section>
            <h2>Manage Tournament</h2>
            <a href="manage_tournaments.php?tournament_id=<?php echo $tournament_id; ?>">Manage Tournaments</a>
           
        </section>

        <section>
            <h2>Match Fixtures</h2>
            <a href="manage_fixtures.php?tournament_id=<?php echo $tournament_id; ?>">Manage Match Fixtures</a>
        </section>
        <section>
            <h2>Manage Match Results</h2>
            <a href="manage_result.php?tournament_id=<?php echo $tournament_id; ?>">Update Match Results</a>
        </section>

        <section>
            <h2>User Profiles</h2>
            <a href="manage_users.php?tournament_id=<?php echo $tournament_id; ?>">Manage User Profiles</a>
        </section>
    </main>
    <a href="admin_home.php">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
    <!-- <div id="loading-overlay">
  <div class="spinner"></div>
</div> -->
<script src="/script/general.js"></script>
</body>
</html>
