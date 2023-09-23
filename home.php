<?php
// var_dump($stored_password); 
// var_dump($password);        
// var_dump($_POST); 
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

session_start();

require "connect_db.php";

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $image_result = $_POST["image_result"];
  $suggestions = $_POST["suggestions"];
  $sql = "INSERT INTO upload (image_result,suggestions) VALUES ('$image_result','$suggestions')";
}

$user_id = $_SESSION['user_id'];

// Query the database to fetch the username from the users table
$query_users = "SELECT username FROM users WHERE id = $user_id";
$result_users = $conn->query($query_users);

// Query the database to fetch the registration details from the registration table
$query_registration = "SELECT * FROM registeration WHERE user_id = $user_id";
$result_registration = $conn->query($query_registration);

if ($result_users->num_rows == 1 && $result_registration->num_rows > 0) {
  //&& $result_upload->num_rows > 0
  // If there is a user with the specified ID in the users table
  // and there are registration records for this user in the registration table

  // Fetch the username from the users table
  $user_data = $result_users->fetch_assoc();
  $username = $user_data['username'];

  // Display the username
  $welcome = "<h1>Welcome, $username!</h1>";

  // Iterate through registration records and display the data
  while ($row = $result_registration->fetch_assoc()) {
    $full_name = $row['full_name'];
    $age = $row['age'];
    $phone_number = $row['phone_number'];
    $club = $row['club'];

    // $image_result = $row['image_result'];


    // Display the retrieved data
    // echo "<div class='registration-card'>";
    // echo "</div>";
  }
  if ($club == "1") {
    $image = 'images/psg.webp';
    $clubName = "P-S-G";
  } else if ($club == "2") {
    $image = 'images/Fcb.webp';
    $clubName = "FC barcelona";
  } else if ($club == "3") {
    $image = 'images/real_madrid.webp';
    $clubName = "Real madrid";
  } else if ($club == "4") {
    $image = 'images/Manchester-United-Logo.webp';
    $clubName = "Manchester United";
  } else if ($club == "5") {
    $image = 'images/m-city.webp';
    $clubName = "Manchester City<";
  } else if ($club == "6") {
    $image = 'images/benfica.webp';
    $clubName = "Benfica";
  } else if ($club == "7") {
    $image = 'images/napoli.webp';
    $clubName = "Napoli";
  } else if ($club == "8") {
    $image = 'images/ac-milan-logo.webp';
    $clubName = "AC milan";
  } else if ($club == "9") {
    $image = 'images/arsenal-logo-0.webp';
    $clubName = "Arsenal";
  } else if ($club == "10") {
    $image = 'images/Chelsea-Logo.webp';
    $clubName = "Chelsea";
  } else if ($club == "11") {
    $image = 'images/new-castle.webp';
    $clubName = "New Castle";
  } else if ($club == "12") {
    $image = 'images/bayern.webp';
    $clubName = "Bayern";
  } else if ($club == "13") {
    $image = 'images/logo-de-juventus.webp';
    $clubName = "Juventus";
  } else if ($club == "14") {
    $image = 'images/new-castle.webp';
    $clubName = "Inter milan";
  } else if ($club == "15") {
    $image = 'images/inter-logo.webp';
    $clubName = "Inter milan";
  } else if ($club == "16") {
    $image = 'images/liverpool-fc-logo.webp';
    $clubName = "Liverpool";
  } else {
    $image_url = "";
    $clubName = "Team name";
  }

} else {
  // Handle the case where no user or no registration data is found.
  echo "User not found or no registration data available.";
}

// Close the database connection
$conn->close();
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
</head>

<body>
  <header>
    <a href="#"><img class="nav-img" src="/images/home.png"></a>
    <a href="main.php"><img class="nav-img" src="/images/contact.png"></a>
    <a href="#card2"><img class="nav-img" src="/images/matches.png"></a>
    <a id="settingsBtn"><img class="nav-img" src="/images/settings.png"></a>



  </header>
  <!-- <button id="settingsBtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
  <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
</svg></button> -->
  <div>

    <table id="settingsCard" class="card-settings hidden">
      <tr>
        <th>
          <h3 style="color:black;">Settings</h3>
          <hr>
        </th>
      </tr>
      <tr>
        <td>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun-fill"
            viewBox="0 0 16 16">
            <path
              d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
          </svg> &nbsp;
          <a onclick="alert('coming soon wait keroo')" class="logout-a">Dark mode</a>
          <label class="switch">
            <input type="checkbox" id="darkModeToggle">
            <span class="slider"></span>
          </label>
        </td>
      </tr>
      <tr>
        <td>

          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-volume-up-fill" viewBox="0 0 16 16">
            <path
              d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z" />
            <path
              d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z" />
            <path
              d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z" />
          </svg>
          <!-- <input type="range" max="10" min="0" style=""/> -->
          <audio autoplay loop controls class="aduio-btn">
            <source src="/home/ajad/work/pes/images/pes.mp3" type="audio/mpeg">
            <source src="images/pes2.mp3" type="audio/ogg">
          </audio>
        </td>
      </tr>
      <tr>
        <td>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-telephone-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
              d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
          </svg>&nbsp;
          <a onclick="alert('coming soon wait keroo')" class="logout-a">Contact</a>

        </td>
      </tr>
      <tr>
        <td>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-x-fill"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
              d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0zM6.854 8.146a.5.5 0 1 0-.708.708L7.293 10l-1.147 1.146a.5.5 0 0 0 .708.708L8 10.707l1.146 1.147a.5.5 0 0 0 .708-.708L8.707 10l1.147-1.146a.5.5 0 0 0-.708-.708L8 9.293 6.854 8.146z" />
          </svg>&nbsp;
          <a href="logout.php" class="logout-a">Log out</a>

        </td>
      </tr><br>
      <tr>
        <td class="close-btn">
          <button id="closeSettingsBtn">-X-</button>
        </td>
      </tr>
    </table>

    <div class="container1">

      <div class="card1">
        <?php
        echo $welcome;
        ?><br>
        <div class="card1-1">
          <!-- <img class="team-logo" src="/images/ac-milan-logo.webp" alt="team logo"> -->
          <?php
          echo "<img  class='team-logo' src='$image' alt='club logo'>";

          echo "<h3> $clubName </h3>";
          ?>
          <hr>
        </div>
        <div class="info">
          <?php
          echo "<h3>Full Name: $full_name</h3>";

          echo "<p>Age: $age</p>";

          echo "<p>Phone Number: $phone_number</p>";
          ?>
        </div>

      </div>

      <div id="card2">
        <div class="cd1">
          <h3>
            Match
          </h3>
          <hr>
        </div>

        <!-- <h1>Welcome to Page B</h1> -->


        <div class="cd2">

          <h2>First match</h2>
          <p>
            <?php
            echo "<div class='match-info'>";
            echo "<img class='match-team-image' src='$image' alt='club logo'>";
            // echo "<h3>$clubName</h3>";
            echo "<h4>vs</h4>";
            echo "<img class='match-team-image' src='/images/ac-milan-logo.webp'>";
            echo "</div>";
            ?>


            <br><br>
          <form action="" method="post" enctype="multipart/form-data" class="row mb-3">

            <h2><B>Result</B></h2>
            <p>

              <input type="file" class="form-control" name="image_result"><br>
              <input type="submit" class="btn btn-primary" value="Upload" />
              <?php
              // echo "$image_result";
              ?>
            </p><br><br><br><br>
            <hr class="line-hr"><br>



        </div>



        <div class="cd3">
          <h3>e-Football Tournament Rules</h3>
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
              href="mailto:contact@CodingStrikerz.com">contact@CodingStrikerz.com</a>.</p>

          <p class="col">
            <label>Any Suggestions</label>
            <input type="text" class="form-control" placeholder="Suggestions" aria-label="First name"
              name="suggestions"><br>
            <input type="submit" class="btn btn-primary" value="Submit" />

            </form>
          </p>
        </div>
      </div>
    </div>
    <!-- <div>
        <a href="index.php" style="color: #fff;top: 0; margin-right: 0;">Log out</a>

    </div> -->
    <script src="/script/general.js"></script>

</body>

</html>