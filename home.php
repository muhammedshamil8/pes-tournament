<?php
var_dump($stored_password);
var_dump($password);
var_dump($_POST);
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require "connect_db.php";

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $image_result = $_POST["image_result"];
  $suggestions = $_POST["suggestions"];

  // Prepare SQL statement to insert data into the 'upload' table
  $stmt = $conn->prepare("INSERT INTO upload (image_result, suggestions) VALUES (?, ?)");
  $stmt->bind_param("ss", $image_result, $suggestions);
  $stmt->execute();
  $stmt->close();
}

$user_id = $_SESSION['user_id'];
$query_users = "SELECT name FROM registration WHERE user_id = ?";
$query_registration = "SELECT * FROM registration WHERE user_id = ?";
$query_result = "SELECT * FROM league_table";

// Prepare and execute queries
$stmt_users = $conn->prepare($query_users);
$stmt_users->bind_param("i", $user_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();

$stmt_registration = $conn->prepare($query_registration);
$stmt_registration->bind_param("i", $user_id);
$stmt_registration->execute();
$result_registration = $stmt_registration->get_result();

// $stmt_result = $conn->prepare($query_result);
// $stmt_result->execute();
// $result_result = $stmt_result->get_result();

if ($result_users->num_rows == 1 && $result_registration->num_rows > 0) {
  $user_data = $result_users->fetch_assoc();
  $username = $user_data['name'];
  $welcome = "<h1>Welcome, $username!</h1>";

  while ($row = $result_registration->fetch_assoc()) {
    $full_name = $row['full_name'];
    $age = $row['age'];
    $phone_number = $row['phone_number'];
    $club = $row['club'];
    $tournament_id = $row['tournament_id'];

      // Get the tournament name using the tournament_id
        $query_tournament_name = "SELECT tournament_name FROM tournament WHERE tournament_id = $tournament_id";
        $result_tournament_name = $conn->query($query_tournament_name);

        // league_table
        $query_table = "SELECT lt.*, r.tournament_id, r.club FROM league_table lt JOIN registration r ON lt.user_id = r.user_id WHERE lt.tournament_id = $tournament_id ORDER BY lt.points DESC";

        $result_table = $conn->query($query_table);
        
                


        if ($result_tournament_name->num_rows > 0) {
            $row_tournament = $result_tournament_name->fetch_assoc();
            $tournament_name = $row_tournament['tournament_name'];

            // Display the tournament name
           
        } else {
            echo "Tournament not found.";
        }
      
    // Additional improvement: Use a switch case to determine club information
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
  }
} else {
  echo "User not found or no registration data available.";
}

// $conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Home</title>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/home.css">
  <link rel="stylesheet" href="styles/general.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="./script/general.js"></script>
  <script src="./script/home.js"></script>
</head>

<body>
  <header>
    <button onclick="headerbtn1();"><img class="nav-img" src="/images/home.png"></button>

    <button onclick="headerbtn3();"><img class="nav-img" src="/images/contact.png"></button>

    <button onclick="headerbtn2();">
      <img class="nav-img" src="/images/matches.png">
    </button>


          <button id="settingsBtn"><img class="nav-img" src="/images/settings.png"></button>

          <form action="user_fixture.php" method="post">
        <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
        <button type="submit">
          <img class="nav-img" src="/images/matches.png">
        </button>
      </form>
          <form action="previous_result.php" method="post">
        <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
        <button type="submit">
          <img class="nav-img" src="/images/matches.png">
        </button>
      </form>
      </form>
          <form action="all_fixture.php" method="post">
        <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
        <button type="submit">
          <img class="nav-img" src="/images/matches.png">
        </button>
      </form>

  </header>

 

  <div id="loading-overlay2">
  <div class="spinner"></div>
</div>

    <?php
 
  echo "  <div class='welcome-p'>$welcome </div>";
    ?>
  
  <div class="container1">



    <section class="card1">

      <div class="card1-1">
        <?php
        echo "<div class='team-logo'><img  class='team-logo-img' src='$image' alt='club logo'></div>";

        echo "<p> $clubName </p>";
//         echo $user_id;
// echo $tournament_id;
        ?>
        <br>

        <div class="user-registration-table">
          <table class="table-registration">
            <thead>

            </thead>
            <tbody>
            <tr>
                <td class="head-td">Tournament Name</td>
                <td class="child-td">
                  <?php echo $tournament_name; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Full Name</td>
                <td class="child-td">
                  <?php echo $full_name; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Age</td>
                <td class="child-td">
                  <?php echo $age; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Phone Number</td>
                <td class="child-td">
                  <?php echo $phone_number; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Club</td>
                <td class="child-td">
                  <?php echo $clubName; ?>
                </td>
              </tr>
             
            </tbody>
          </table>
        </div>

       
      </div>

    </section>

    <section class="card2">

      <h2>Upcoming Matches</h2>
      <hr>
      <div class="card2-matches-details">
        <p>Following match:
          <?php
          echo "<div class='match-info'>";
          echo "<div class='match-team-card'><img class='match-team-image' src='$image' alt='club logo'>";
          echo "<p>$clubName</p></div>";
          echo "<h4>vs</h4>";
          echo "<div class='match-team-card'><img class='match-team-image' src='/images/ac-milan-logo.webp'></div>";
          echo "</div>";
          ?><br> on YYYY-MM-DD
        </p>

        <br>

        <p>Next match:
          <?php
          echo "<div class='match-info'>";
          echo "<div class='match-team-card'><img class='match-team-image' src='$image' alt='club logo'></div>";
          // echo "<h3>$clubName</h3>";
          echo "<h4>vs</h4>";
          echo "<div class='match-team-card'><img class='match-team-image' src='/images/ac-milan-logo.webp'></div>";
          echo "</div>";
          ?><br> on YYYY-MM-DD
        </p>
      </div>

      <h2>Tournament Rules</h2>
      <hr>

      <div class="cd3">
        <h2>Tournament Format</h2>
        <p>Our football tournament follows a round-robin format with knockout stages.</p>

        <h2>General Rules</h2>
        <ul>
          <li>All matches will be played on a standard-sized football pitch.</li>
          <li>Each team consists of 11 players, including a goalkeeper.</li>
          <li>Matches will have two halves of 45 minutes each, with a 15-minute halftime break.</li>
          <li>Teams must arrive at the field 30 minutes before their scheduled match time.</li>
        </ul>

        <h2>Match Rules</h2>
        <ul>
          <li>Matches will be officiated by certified referees.</li>
          <li>A goal is scored when the entire ball crosses the goal line between the goalposts and under the
            crossbar.</li>
          <li>Offside rule will be enforced.</li>
          <li>Fouls and misconduct will be penalized with free-kicks, yellow cards, and red cards.</li>
          <li>Substitutions are allowed during specific stoppages in play.</li>
        </ul>

        <h2>Tournament Scoring</h2>
        <p>Points will be awarded as follows:</p>
        <ul>
          <li>Win: 3 points</li>
          <li>Draw: 1 point</li>
          <li>Loss: 0 points</li>
        </ul>

        <h2>Tiebreakers</h2>
        <p>If two or more teams have the same number of points, the following tiebreakers will be used:</p>
        <ol>
          <li>Goal difference</li>
          <li>Total goals scored</li>
          <li>Head-to-head result</li>
        </ol>

        <h2>Code of Conduct</h2>
        <p>All participants and spectators are expected to adhere to a code of conduct promoting fair play and
          sportsmanship.</p>

        <h2>Dispute Resolution</h2>
        <p>Any disputes or conflicts will be resolved by the tournament organizers, and their decisions are final.</p>

        <h2>Contact Information</h2>
        <p>If you have any questions or concerns, please contact the tournament organizers at <a
            href="mailto:contact@CodingStrikerz.com">contact@CodingStrikerz.com</a>
          <br>whatsapp me
          <a href="https://wa.me/123456789"> +123456789</a>
        </p>

        <p class="col">
        <h2><label>Any Suggestions</label></h2>
        <form>

          <input type="text" class="form-control" placeholder="Suggestions" aria-label="First name"
            name="suggestions"><br>
          <input type="submit" class="btn btn-primary" value="Submit" />
        </form>
        </p>

      </div>

    </section>

    <section class="card3">
      <h2>League Table</h2>
      <table class="table-league">
        <tr>
            <th>Position</th>
            <th>Club</th>
            <th>Matches Played</th>
            <th>Wins</th>
            <th>Draws</th>
            <th>Losses</th>
            <th>Score</th>
            <th>Points</th>
        </tr>

        <?php

        if ($result_table->num_rows > 0) {
            $position = 1;

            while ($row = $result_table->fetch_assoc()) {
                // Display table rows for each club
                $userClubId = $row['club_id'];
                $club2 = $row['club_id'];
                $clubUser = $row['user_id'];
    
                // Additional improvement: Use a switch case to determine club information
                switch ($club2) {
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

                echo "<tr" . ($club == $userClubId ? " class='user-club-row'" : "") . ">
                <td>$position </td>
                <td><a href='profile-page.php?uz=$clubUser'><div class='match-team--intable'><img class='match-team-image-intable'  src='$image' alt='club logo'>
                 <p>$clubName</p></div></a></td>
                <td>{$row['matches']}</td>
                <td>{$row['win']}</td>
                <td>{$row['draw']}</td>
                <td>{$row['loss']}</td>
                <td>{$row['score']}</td>
                <td>{$row['points']}</td>
              </tr>";

                $position++;
            }
        } else {
            echo "<tr><td colspan='8'>No data available</td></tr>";
        }
        ?>
    </table>
</section>







  </div>


<?php
$conn->close();

?>
</body>

</html>