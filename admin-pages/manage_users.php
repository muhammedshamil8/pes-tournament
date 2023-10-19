<?php
// manage_users.php

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
    <link rel="stylesheet" href="../styles/general.css"> <!-- Your custom CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #3498db;
    color: white;
    padding: 12px 20px;
}

.logout-button {
    color: white;
    text-decoration: none;
}

.logout-button:hover {
    text-decoration: underline;
}

.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
}

.card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    padding-top: 0px;
    width: 300px;
    margin: auto;
}

.card-content {
    text-align: left;
    padding-top: 0px;

}

.card-content h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    text-align:center;
    color:green;
}

.card-content p {
    margin: 5px 0;
}

/* .return-button {
    display: block;
    text-align: center;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    text-decoration: none;
}

.return-button i {
    margin-right: 10px;
} */

    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Manage User Profiles</h1>
            <a href="logout.php" class="logout-button"><i class="fas fa-sign-out-alt"></i> Log out</a>
        </div>
    </header>

    <main>
        <div class="card-container">
            <?php
            // Fetch tournament names and related information
            $query = "SELECT t.tournament_id, t.tournament_name, r.name, r.club, r.user_id, r.phone_number
                      FROM tournament t
                      INNER JOIN registration r ON t.tournament_id = r.tournament_id
                      WHERE t.tournament_id = $tournament_id";
                      $position = 1;

            $result = $conn->query($query);

            if ($result) {
                if ($result->num_rows == 0) {
                    echo '<div class="card">
                            <div class="card-content">
                                <p>No users available for this tournament.</p>
                            </div>
                          </div>';
                } else {
                while ($row = $result->fetch_assoc()) {
                    
                    $club =$row['club'];
                    switch ($club) {
                        case "1":
                          $image = 'images/psg.webp';
                          $clubName = "P-S-G";
                          break;
                        case "2":
                          $image = 'images/Fcb.webp';
                          $clubName = "FC Barcelona";
                          break;
                        case "3":
                          $image = 'images/real_madrid.webp';
                          $clubName = "Real madrid";
                          break;
                        case "4":
                          $image = 'images/Manchester-United-Logo.webp';
                          $clubName = "Manchester United";
                          break;
                        case "5":
                          $image = 'images/m-city.webp';
                          $clubName = "Manchester City ";
                          break;
                        case "6":
                          $image = 'images/benfica.webp';
                          $clubName = "Benfica";
                          break;
                        case "7":
                          $image = 'images/napoli.webp';
                          $clubName = "Napoli";
                          break;
                        case "8":
                          $image = 'images/ac-milan-logo.webp';
                          $clubName = "AC milan";
                          break;
                        case "9":
                          $image = 'images/arsenal-logo-0.webp';
                          $clubName = "Arsenal";
                          break;
                        case "10":
                          $image = 'images/Chelsea-Logo.webp';
                          $clubName = "Chelsea";
                          break;
                        case "11":
                          $image = 'images/new-castle.webp';
                          $clubName = "New Castle";
                          break;
                        case "12":
                          $image = 'images/bayern.webp';
                          $clubName = "Bayern";
                          break;
                        case "13":
                          $image = 'images/logo-de-juventus.webp';
                          $clubName = "Juventus";
                          break;
                        case "14":
                          $image = 'images/new-castle.webp';
                          $clubName = "New castle";
                          break;
                        case "15":
                          $image = 'images/inter-logo.webp';
                          $clubName = "Inter milan";
                          break;
                        case "16":
                          $image = 'images/liverpool-fc-logo.webp';
                          $clubName = "Liverpool";
                          break;
                        // Add cases for other clubs
                        default:
                          $image = 'images/logo.webp'; // Provide a default image if club is not recognized
                          $clubName = "Unknown Club";
                          break;
                      }
                      echo '<div class="card">
                              <div class="card-content">
                                  <h5 class="position">' . $position . '</h5>
                                  <h2>' . $row['tournament_name'] . '</h2>
                                  <p><strong>Name:</strong> ' . $row['name'] . '</p>
                                  <p><strong>Club:</strong> ' . $clubName . '</p>
                                  <p><strong>User ID:</strong> ' . $row['user_id'] . '</p>
                                  <p><strong>Phone Number:</strong> ' . $row['phone_number'] . '</p>
                                  <button onclick="editUser(' . $row["user_id"] . ');  ">Edit</button>
                                  <button onclick="deleteUser(' . $row["user_id"] . ',' . $row["tournament_id"] . ');">Delete</button>

                              </div>
                            </div>';
                      $position += 1;
            }

                    }
            } else {
                $error_msg = "Error fetching tournament names and related information: " . $conn->error;
            }
            ?>
        </div>
    </main>

    <a href="tournament_home.php?tournament_id=<?php echo $tournament_id; ?>">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
    <!-- Inside the <script> section -->
    <script>
    function editUser(userId) {
        // Redirect to the edit user page with the user ID
        window.location.href = `manage_user_edit.php?user_id=${userId}`;
    }

    function deleteUser(userId, tournament_id) {
        // Prompt for confirmation
        if (confirm("Are you sure you want to delete the user from this tournament?")) {
            // Redirect to the delete user page
            window.location.href = `manage_user_delete.php?user_id=${userId}&tournament_id=${tournament_id}`;
        }
    }
</script>


</body>
</html>

