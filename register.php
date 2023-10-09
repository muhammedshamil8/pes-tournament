<?php
var_dump($stored_password);
var_dump($password);
var_dump($_POST);
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require "connect_db.php";

$error_msg = "";
$success_msg = "";
$chosen_clubs = array();
$chosen_clubs_sql = "SELECT club FROM registration";
$result = $conn->query($chosen_clubs_sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $chosen_clubs[] = $row['club'];
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $name = $_POST["name"];
  $full_name = $_POST["full_name"];
  $age = isset($_POST['age']) ? $_POST['age'] : null;
  $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
  $tournament_name2 = isset($_POST['tournament_name2']) ? $_POST['tournament_name2'] : '';

  // Check if 'club' is set and not an empty string
  if (isset($_POST['club']) && $_POST['club'] !== '') {
    $club = ($_POST['club']);
  } else {
    // Handle the case when 'club' is not selected
    $error_msg = "Please select a club.";
    // You might want to redirect or display an error message in this case
    // For now, we'll stop processing further
    exit();
  }
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  if ($password !== $confirm_password) {
    $error_msg = "Password and Confirm Password do not match.";

  } else {
    /* else {*/
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    


    $check_duplicate_sql = "SELECT * FROM registration WHERE club = '$club' AND tournament_id = '$tournament_name2'";
    $result_duplicate = $conn->query($check_duplicate_sql);
    $check_username_sql = "SELECT * FROM registration WHERE name = '$name'AND tournament_id = '$tournament_name2'";
    $result = $conn->query($check_username_sql);
    $_SESSION["user_id"] = $row["user_id"];
    if ($result->num_rows > 0) {
      $error_msg = "Username is already taken. Please choose a different username.";
    } else {

      if ($result_duplicate->num_rows > 0) {
        $error_msg = "This club has already been chosen for the selected tournament. Please choose a different club.";
      } else {
        if (!is_numeric($age) || $age <= 0) {
          $error_msg = "Age must be a positive number.";
        } elseif (!preg_match("/^[0-9+]+$/", $phone_number)) {
          $error_msg = "Phone number must contain only numbers.";
        } else {
          $tournament_id = $tournament_name2;

          // Check if registration is open for the specified tournament
          $registration_check_query = "SELECT registration_open FROM tournament WHERE tournament_id = $tournament_id";
          $registration_check_result = $conn->query($registration_check_query);
          
          if ($registration_check_result) {
              $registration_status = $registration_check_result->fetch_assoc()['registration_open'];
          
              if ($registration_status == 0) {
                  // Registration is closed for this tournament
                  $error_msg = "Registration for this tournament is closed.";
                  // header("Location: register.php?$error_msg");
                  header("Location: register.php?error=Registration for this tournament is closed");

                  exit();
              } else {
                  // Proceed with registration
                  // ...
           
          
          $sql = "INSERT INTO registration (name, full_name, age, phone_number, club, password, tournament_id) 
        VALUES ('$name', '$full_name', '$age', '$phone_number', '$club', '$hashed_password', '$tournament_name2')";

          //'$hashed_password'
          if ($conn->query($sql) === TRUE) {
            // Insert into league_table
$user_id = $conn->insert_id;
$tournament_id = $_POST['tournament_name2'];

// SQL query to insert into league_table
$league_table_sql = "INSERT INTO league_table (user_id, tournament_id, matches, win, draw, loss, score, points, club_id) 
                     VALUES ('$user_id', '$tournament_id', 0, 0, 0, 0, 0, 0, '$club')";

// Execute the league_table SQL query
if ($conn->query($league_table_sql) === TRUE) {
    // Insert into teams table
    $teams_sql = "INSERT INTO teams (tournament_id, user_id, club_id) 
                  VALUES ('$tournament_id', '$user_id', '$club')";

    // Execute the teams SQL query
    if ($conn->query($teams_sql) === TRUE) {
        // Set the 'registered' session variable to true upon successful registration
        $_SESSION['registered'] = true;
        $success_msg = "Registration successful!";
        header("Location: home.php");
        exit();
    } else {
        $error_msg = "Error inserting into teams table: " . $conn->error;
    }
} else {
    $error_msg = "Error inserting into league_table: " . $conn->error;
}

        } else {
            $error_msg = "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    } else {
        echo "Error checking registration status: " . $conn->error;
    }
          $conn->close();
        }
      }
    }
  }
}


// Check if the user is not logged in
// if (!isset($_SESSION['user_id'])) {
//   // Redirect to the login page if not logged in
//   header("Location: login.php");
//   exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Football Registration</title>
  <link rel="stylesheet" href="styles/general.css">
  <link rel="stylesheet" href="styles/register.css">
</head>

<body>
  <!-- <div id="settingsCard2" class="card2 hidden">
heloo
</div> -->
  <button id="settingsBtn" class="settingsBtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
      fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
      <path
        d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
    </svg></button>
  <div>

    <table id="settingsCard" class="card-settings hidden table-stn">
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
            <source src="/home/ajad/work/pes/images/pes2.mp3" type="audio/mpeg">
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

  </div>
  <div class="registration-container">
    <?php
    if (isset($_GET['error'])) {
      $error_msg = $_GET['error'];
      // echo '<p class="error_message">' . $error_msg . '</p>';
    }
    ?>
    <h2>Football Registration</h2>
    <form id="registration-form" action="" method="post">
      <input type="text" placeholder="User Name" name="name" class="input" required>
      <input type="text" placeholder="Full Name" name="full_name" class="input" required>
      <input type="number" placeholder="Age" name="age" class="input" min="1" required>
      <input type="tel" placeholder="Whatsapp Number" name="phone_number" class="input" pattern="\+?[0-9]{10}" required>

      <select class="select" name="club" required>
        <option value="" disabled <?php if (!isset($_POST['club']))
          echo 'selected'; ?>>Select Your club</option>
        <?php
        $club_names = array(
          "Paris Saint-Germain",
          "FC Barcelona",
          "Real Madrid",
          "Manchester United",
          "Manchester City",
          "Benfica",
          "Napoli",
          "AC milan",
          "Arsenal",
          "Chelsea",
          "New Castle",
          "Bayern",
          "Juventus",
          "Inter milan",
          "Athletico madrid",
          "Liverpool"
        );

        // Fetch the clubs that have already been chosen
        

        // Display options with appropriate status
        for ($i = 1; $i <= count($club_names); $i++) {
          $club_name = $club_names[$i - 1];
          $is_disabled = in_array($i, $chosen_clubs) ? ' class="taken" ' : '';
          $selected = isset($_POST['club']) && $_POST['club'] == $i ? 'selected' : '';
          echo '<option value="' . $i . '" ' . $is_disabled . ' ' . $selected . '>' . $club_name . '</option>';
        }
        ?>
      </select>
      <select class="select" name="tournament_name2" required>
        <option value="" disabled selected>Select Your Tournament Name</option>
        <?php
        // Fetch tournament names
        $tournament_names = [];
        $tournament_query = "SELECT tournament_id, tournament_name FROM tournament";
        $tournament_result = $conn->query($tournament_query);

        
        if ($tournament_result) {
          while ($row = $tournament_result->fetch_assoc()) {
            echo '<option value="' . $row['tournament_id'] . '">' . $row['tournament_name'] . '</option>';
          }
        } else {
          $error_msg = "Error fetching tournament names: " . $conn->error;
        }
        ?>
      </select>




      <!-- 
       <select class="select" name="club" required>
    <option value="" disabled selected>Select Your club</option>
        <option value="1">Paris Saint-Germain</option>
        <option value="2">FC barcelona</option>
        <option value="3">Real madrid</option>
        <option value="4">Manchester United</option>
        <option value="5">Manchester City</option>
        <option value="6">Benfica</option>
        <option value="7">Napoli</option>
        <option value="8">AC milan</option>
        <option value="9">Arsenal</option>
        <option value="10">Chelsea</option>
        <option value="11">New Castle</option>
        <option value="12">Bayern</option>
        <option value="13">Juventus</option>
        <option value="14">Inter milan</option>
        <option value="15">Athletico madrid</option>
        <option value="16">Liverpool</option>

      </select> -->

      <!-- <select class="select" name="tournament" >
    <option value="1" selected>E-football</option>
      </select> -->
      <input type="password" placeholder="Password" name="password" class="input" required>
      <input type="password" placeholder="confirm password" name="confirm_password" class="input" required>

      <button type="submit">Submit</button>
      <P>Already registered ? <a href="login.php">sign in</a></p>
      <?php
      echo '<p class="success_message">' . $success_msg . '</p>';
      echo '<p class="error_message">' . $error_msg . '</p>';
      ?>
    </form>
  </div>

  <a href="index.php"><button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
        fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
          d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
      </svg></button></a>
  <div id="loading-overlay">
    <div class="spinner"></div>
  </div>
  <script src="/script/general.js"></script>
  <script>


  </script>
</body>

</html>