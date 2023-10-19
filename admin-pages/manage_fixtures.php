<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "../connect_db.php"; // Connect to your database

if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: ../admin.php");
    exit();
}

$tournament_id = isset($_GET['tournament_id']) ? $_GET['tournament_id'] : null;

if ($tournament_id === null) {
    echo "Invalid tournament ID.";
    exit();
}

function getTeamsForTournament($tournament_id, $conn) {
    $query = "SELECT team_id FROM teams WHERE tournament_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $team_ids = [];
    while ($row = $result->fetch_assoc()) {
        $team_ids[] = $row['team_id'];
    }

    return $team_ids;
}
$query = "SELECT team_id, club_id FROM teams WHERE tournament_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$result = $stmt->get_result();

$clubs = [
    "1" => ["image" => 'images/psg.webp', "clubName" => "P-S-G"],
    "2" => ["image" => 'images/Fcb.webp', "clubName" => "FC Barcelona"],
    "3" => ["image" => 'images/real_madrid.webp', "clubName" => "Real Madrid"],
    "4" => ["image" => 'images/Manchester-United-Logo.webp', "clubName" => "Manchester United"],
    "5" => ["image" => 'images/m-city.webp', "clubName" => "Manchester City"],
    "6" => ["image" => 'images/benfica.webp', "clubName" => "Benfica"],
    "7" => ["image" => 'images/napoli.webp', "clubName" => "Napoli"],
    "8" => ["image" => 'images/ac-milan-logo.webp', "clubName" => "AC Milan"],
    "9" => ["image" => 'images/arsenal-logo-0.webp', "clubName" => "Arsenal"],
    "10" => ["image" => 'images/Chelsea-Logo.webp', "clubName" => "Chelsea"],
    "11" => ["image" => 'images/new-castle.webp', "clubName" => "New Castle"],
    "12" => ["image" => 'images/bayern.webp', "clubName" => "Bayern"],
    "13" => ["image" => 'images/logo-de-juventus.webp', "clubName" => "Juventus"],
    "14" => ["image" => 'images/new-castle.webp', "clubName" => "New Castle"],
    "15" => ["image" => 'images/inter-logo.webp', "clubName" => "Inter Milan"],
    "16" => ["image" => 'images/liverpool-fc-logo.webp', "clubName" => "Liverpool"],
];


$position = 0;






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions button {
            padding: 10px 20px;
            font-size: 18px;
            margin: 0 10px;
        }

        .fixtures {
            margin-left: 20px;
        }

        .fixtures h2 {
            margin-top: 20px;
        }

        .fixtures h3 {
            margin-top: 10px;
        }

        .fixtures ul {
            list-style-type: none;
            padding: 0;
        }

        .fixtures ul li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Main Page</h1>

    <!-- Buttons to trigger actions -->
    <div class="actions">
    <form action="manage_the_fixtures.php?tournament_id=<?php echo $tournament_id; ?>" method="post" style="display: inline;">
    <input type="hidden" name="action" value="generate">
    <button type="submit" name="perform_action" value="true">Generate Fixtures</button>
</form>


        <form action="manage_the_fixtures.php?tournament_id=<?php echo $tournament_id; ?>" method="post" style="display: inline;">
            <input type="hidden" name="action" value="update">
            <button type="submit" name="perform_action" value="true">Update Fixtures</button>
        </form>
    </div>

    <div class="fixtures">
    <table>
                <thead>
                <tr>
            <th>Position</th>
            <th>Team ID</th>
                 <th>Club ID</th>
                 <th>Club Name</th>
                 <th>Logo</th>
                </tr>
                 <thead>
                 <tbody>"
        <!-- Display generated fixtures here -->
        <?php
        $team_ids = getTeamsForTournament($tournament_id, $conn);
        echo "<h2>Teams for Tournament</h2>";
        echo "<ul>";
        foreach ($team_ids as $team_id) {
            echo "<li>Team $team_id  </li>";
        }
            while ($row = $result->fetch_assoc()) {
                $team_id = $row['team_id'];
                $club_id = $row['club_id'];
            
                // Check if the club ID exists in the $clubs array
                if (array_key_exists($club_id, $clubs)) {
                    $clubInfo = $clubs[$club_id];
                    $image = $clubInfo["image"];
                    $clubName = $clubInfo["clubName"];
                } else {
                    // Use default values if the club ID is not recognized
                    $image = 'images/logo.webp';
                    $clubName = "Unknown Club";
                }
            
                $position++;
              

                echo "<tr>";
                // Output the team information
                echo "<td>$position</td>";
                echo "<td>Team $team_id</td>";
                echo "<td>$clubName</td>";

                echo "<td>$club_id</td>";
                echo "<td><img src='$image' alt='$clubName' width='50' height='50'></td>";
                echo "</tr>";
                
            
            }
       
        
        ?>
        </tbody>
       </table>
    </div>
</body>

</html>
