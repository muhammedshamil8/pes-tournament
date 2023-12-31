<?php
// manage_tournaments.php

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

// Fetch tournaments from the database
$tournament_query = "SELECT * FROM tournament";
$tournament_result = $conn->query($tournament_query);

if (!$tournament_result) {
    echo "Error fetching tournaments: " . $conn->error;
    exit();
}

$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'edit') {
        // Handle tournament edit
        $tournament_id = isset($_POST['tournament_id']) ? $_POST['tournament_id'] : null;
        $edited_tournament_name = $_POST['tournament_name'];
        $edited_start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $edited_tournament_type = isset($_POST['tournament_type']) ? $_POST['tournament_type'] : null;

        // Check if a valid tournament ID is provided
        if ($tournament_id === null) {
            echo "Invalid tournament ID.";
            exit();
        }

        // Build the query based on provided data
        $query_tournament = "UPDATE tournament SET ";
        $updates = array();

        if (!empty($edited_tournament_name)) {
            $updates[] = "tournament_name = '$edited_tournament_name'";
        }

        if (!is_null($edited_start_date) && $edited_start_date !== '') {
            $updates[] = "start_date = '$edited_start_date'";
        }

        if (!is_null($edited_tournament_type)) {
            $updates[] = "model = '$edited_tournament_type'";
        }

        if (!empty($updates)) {
            $query_tournament .= implode(', ', $updates);
            $query_tournament .= " WHERE tournament_id = $tournament_id";

            // Update the tournament
            if ($conn->query($query_tournament) === TRUE) {
                $error_msg = "Tournament updated successfully: '$edited_tournament_name'";
                header("Location: ./tournament_manage.php");
                 exit();
            } else {
                $error_msg = "Error updating the tournament: " . $conn->error;
            }
        } else {
            $error_msg = "No updates provided.";
        }
    } elseif ($action === 'delete') {
        // Handle tournament deletion
        $tournament_id = isset($_POST['tournament_id']) ? $_POST['tournament_id'] : null;

        // Check if a valid tournament ID is provided
        if ($tournament_id === null) {
            echo "Invalid tournament ID.";
            exit();
        }

        // Delete the tournament
        $query_delete_tournament = "DELETE FROM tournament WHERE tournament_id = $tournament_id";

        if ($conn->query($query_delete_tournament) === TRUE) {
            $error_msg = "Tournament deleted successfully.";
            header("Location: ./tournament_manage.php");
                 exit();
        } else {
            $error_msg = "Error deleting the tournament: " . $conn->error;
        }
    } elseif ($action === 'status') {
        // Handle tournament status change (stop/start)
        $tournament_id = isset($_POST['tournament_id']) ? $_POST['tournament_id'] : null;
        $registration_open = isset($_POST['stop']) ? 0 : 1;
    
        // Check if a valid tournament ID is provided
        if ($tournament_id === null) {
            echo "Invalid tournament ID.";
            exit();
        }
    
        $query_tournament = "UPDATE tournament SET registration_open = $registration_open WHERE tournament_id = $tournament_id";
    
        if ($conn->query($query_tournament) === TRUE) {
            $error_msg = "Tournament registration status updated successfully.";
            header("Location: ./tournament_manage.php");
                 exit();
        } else {
            $error_msg = "Error updating tournament registration status: " . $conn->error;
        }
    
}
}
$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Manage Tournament</title>
     <link rel="stylesheet" href="./styles/admin-general.css">
     <link rel="stylesheet" href="./styles/general.css">
     <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            z-index:2;
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
        .card {
    border: 1px solid #ccc;
    padding: 20px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display:flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
}

.card h3 {
    margin-top: 0;
}

.card p {
    margin-bottom: 0;
}



/*  */
/* body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #fffafa, #848482);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } */
       
        .register {
            max-width: 350px;
            padding: 20px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
            border-radius: 10px;
            background-color: white;
            text-align: center;


        }

        

        .card h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .card input,
        .card select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* .card input:focus,
    .card select:focus {
        outline: blue;
    } */

        .card select {
            background-color: white;
        }


        .card input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        .card input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .card p {
            color: #555;
            font-size: 14px;
        }

        .card a {
            color: #007BFF;
            text-decoration: none;
            cursor: pointer;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background-color: #f8f9fa;
            /* Light gray background */
            color: green;
            /* Green text */
            font-weight: bold;
            display: none;
        }
        .edit-card,
        .delete-card,
        .stop-card{
            display:none;
        }


        .slide-in {
            animation: slide-in 0.3s forwards;
        }

        /* Define a class for slide-out effect */
        .slide-out {
            animation: slide-out 0.3s forwards;
        }

        @keyframes slide-in {
            from {
                transform: translateX(-50%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes slide-out {
            from {
                transform: translate(0);
            }
            to {
                transform: translateX(100%);
            }
        }
    </style>
</head>
<body>
    <header>
     <div class="header-card1">
        <h1>Manage Tournament</h1>
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
                    <a onclick='showEditCard({$row['tournament_id']});'>Edit</a><br>
                    <a onclick='showDeleteCard({$row['tournament_id']});'>Delete</a><br>
                    <a onclick='showStopCard({$row['tournament_id']});'>Stop</a>
                </td>
            </tr>";
        }
    if ($tournament_result->num_rows == 0) {
        echo "<tr><td colspan='5'>No data available</td></tr>";
    }
    ?>
</tbody>
            </table>
           
            
           
       
            <div class="card edit-card" id="editCard">
        <button onclick="hideEditCard();" class="card-close-btn" id=""><img src="./images/x-lg.svg" class="close-img"></button>
        <div class="card">
            <div class="register">
                <h2>Tournament Edit</h2>
                <form action="" method="POST">
                    <input type="text" id="tournament_name" name="tournament_name" placeholder="Tournament Name"><br>
                    <input type="date" id="start_date" name="start_date" placeholder="Edit Start Date"><br>
                    <select class="select" name="tournament_type">
                        <option value="" disabled selected>Select Your Tournament Type</option>
                        <option value="league">league</option>
                        <option value="knockout">knockout</option>
                    </select>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="tournament_id" id="editTournamentId">
                    <input type="submit" value="Edit">
                </form>
                <?php echo "<p class='error-msg' style='color:red;'>$error_msg </P>" ?>
            </div>
        </div>
    </div>

    <div class="card delete-card" id="deleteCard">
        <button onclick="hideDeleteCard();" class="card-close-btn" id=""><img src="./images/x-lg.svg" class="close-img"></button>
        <div class="card">
            <p>Do you really want to delete the tournament?</p>
            <form action="" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="tournament_id" id="deleteTournamentId">
                <button type="submit">Yes</button>
                <button type="button" onclick="hideDeleteCard()">No</button>
            </form>
        </div>
    </div>

    <div class="card stop-card" id="stopCard">
    <button onclick="hideStopCard();" class="card-close-btn" id=""><img src="../images/x-lg.svg" class="close-img"></button>
    <div class="card">
        <p>Stop registration for this tournament</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="status">
            <input type="hidden" name="tournament_id" id="stopTournamentId">
            <button type="submit" name="stop" value="1">Stop</button>
            <button type="submit" name="stop" value="0">Start</button>
        </form>
    </div>
</div>
<a href="admin_home.php">
        <button class="return" style="background-color:lightblue"><img src="../images/x-lg.svg" class="close-img" > </button>
    </a>


    <script>
    function showEditCard(tournamentId) {
        const editCard = document.getElementById('editCard');
        const editTournamentIdInput = document.getElementById('editTournamentId');
        editTournamentIdInput.value = tournamentId;
        editCard.classList.add('slide-in');
        editCard.style.display = 'block';
    }

    function showDeleteCard(tournamentId) {
        const deleteCard = document.getElementById('deleteCard');
        const deleteTournamentIdInput = document.getElementById('deleteTournamentId');
        deleteTournamentIdInput.value = tournamentId;
        deleteCard.classList.add('slide-in');
        deleteCard.style.display = 'block';
    }

    function showStopCard(tournamentId) {
    const stopCard = document.getElementById('stopCard');
    const stopTournamentIdInput = document.getElementById('stopTournamentId');
    stopTournamentIdInput.value = tournamentId;
    stopCard.classList.add('slide-in');
    stopCard.style.display = 'block';
}


    function hideEditCard() {
        const editCard = document.getElementById('editCard');
        editCard.classList.add('slide-out');
        setTimeout(() => {
            editCard.style.display = 'none';
            editCard.classList.remove('slide-out');
        }, 300);
    }

    function hideDeleteCard() {
        const deleteCard = document.getElementById('deleteCard');
        deleteCard.classList.add('slide-out');
        setTimeout(() => {
            deleteCard.style.display = 'none';
            deleteCard.classList.remove('slide-out');
        }, 300);
    }

    function hideStopCard() {
        const stopCard = document.getElementById('stopCard');
        stopCard.classList.add('slide-out');
        setTimeout(() => {
            stopCard.style.display = 'none';
            stopCard.classList.remove('slide-out');
        }, 300);
    }
</script>
</body>
</html>