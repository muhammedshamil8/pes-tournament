<?php
// manage_tournaments.php

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

// Fetch tournaments from the database
$tournament_query = "SELECT * FROM tournament";
$tournament_result = $conn->query($tournament_query);

if (!$tournament_result) {
    echo "Error fetching tournaments: " . $conn->error;
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Manage Tournaments</title>
     <link rel="stylesheet" href="styles/admin-general.css">

</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Manage Tournaments</h1>
        <a href="logout.php">Log out</a>
     </div>
    </header>

    <main>
        <section>
            <h2>Tournaments List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tourney ID</th>
                        <th>Tourney Name</th>
                        <th>Tourney Type</th>
                        <th>Start Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display tournament details and options
                    while ($row = $tournament_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['tournament_id']}</td>
                                <td>{$row['tournament_name']}</td>
                                <td>{$row['model']}</td>
                                <td>{$row['start_date']}</td>
                                <td>
                                    <a href='edit_tournament.php?tournament_id={$row['tournament_id']}'>Edit</a>
                                    <a href='delete_tournament.php?tournament_id={$row['tournament_id']}'>Delete</a>
                                    <a href='end_tournament.php?tournament_id={$row['tournament_id']}'>End</a>
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
