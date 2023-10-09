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
     <link rel="stylesheet" href="styles/general.css">
     <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        th:nth-child(1), th:nth-child(5),
        td:nth-child(1), td:nth-child(5) {
            width: 10%;
        }

        td a {
            margin-right: 10px;
        }
    </style>
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
                                    <a onclick='alert('are you sure you want to delete table')'>Delete</a>
                                    <a href='end_tournament.php?tournament_id={$row['tournament_id']}'>End</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <a href="admin_home.php">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
</body>
</html>
