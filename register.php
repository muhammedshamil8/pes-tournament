<?php
// var_dump($stored_password);
// var_dump($password);
// var_dump($_POST);
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

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
    $_SESSION["tournament_id"] = $row["tournament_id"];
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
      $_SESSION['registered'] = true;
      $success_msg = "Registration successful! Redirecting in 5 seconds...";
      
      header("Refresh: 5; URL=home.php");
  
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
  <title>See us -Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/register.css">
</head>

<body>

<header>
  <div class="header">
  <h1 class="title" onclick="window.location = 'index.php';">seeee<span>.</span>us</h1>
        <input type="checkbox" id="nav-toggle" class="input-nav">
        <div class="nav-icon">
        <label for="nav-toggle" >
            <div class="line"></div>
            <div class="line middle-line"></div>
            <div class="line"></div>
        </label>
        </div>
  </div>
   
       
</header>
<nav class="nav-content">
    <a href="#" active>Home</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
</nav>

<div class="body">

  <div class="registration-container">

    <?php
    if (isset($_GET['error'])) {
      $error_msg = $_GET['error'];
      // echo '<p class="error_message">' . $error_msg . '</p>';
    }
    ?>
     <div class="registration-details">
<div class="card-flex-intro">
            <h1>Player registration</h1>
              <div class="underline"></div>
            
</div>
    <form id="registration-form" action="" method="post">
    <div class="input-container">
      <input type="text"  name="name" id="username" required>
      <label for="username">Username</label>
            <span class="icon">
              <img src="./images/icon-username.svg" />
            </span>
          </div>
          <div class="input-container">
      <input type="text"  name="full_name" id="full_name" required>
      <label for="full_name">Full name</label>
            <span class="icon">
              <img src="./images/person-lock.svg" />
            </span>
          </div>
          <div class="input-container-special">
      <input type="number" name="age" id="age" min="1" required>
      <label for="age">Age</label>
            <!-- <span class="icon age">
              <img src="./images/calendar2-x.svg" />
            </span> -->
          
      <input type="tel"  name="phone_number" id="number" pattern="\+?[0-9]{10}" required>
      <label for="number" class="wp">Whatsapp number</label>
            <span class="icon">
              <img src="./images/whatsapp.svg" />
            </span>
          </div>

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
        <option value="" disabled selected>Select Your Tournament</option>
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

      <div class="input-container">
            <input type="password" id="password" name="password" required>
            <label for="password">Password</label>
            <span class="icon">
              <img src="./images/eye-slash.svg" id="togglePassword" />
              <!-- <i class="far fa-eye" ></i> -->
            </span>
          </div>
          <div class="input-container">
            <input type="password" id="confirm_password" name="confirm_password" required>
            <label for="password">confirm password</label>
            <span class="icon">
              <img src="./images/Vector-password.svg" id="togglePassword_confirm" />
               <!-- <i class="far fa-eye" ></i> -->
            </span>
          </div>

     

      <button type="submit" class="btn-registration">register<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
          </svg>
</button>
     
    </form>
    <?php
      echo '<p class="error_message">' . $error_msg . '</p>';
      ?>
    <p class="change">Already registered?<a href="login.php">sign in</a></p>
    <?php
      echo '<p class="success_message">' . $success_msg . '</p>';
      ?>
    <!-- </div> -->
    </div>
    <div class="registration-image">
      <button onclick="
      window.location = 'intro-page.php';
      ">
      <img src="./images/Vecto-rclose.svg" />
      </button>
        <img src="./images/player-image.svg" alt="player-image" class="player-image">
      </div>
  </div>
  </div>
<script src="./script/header-opening.js"></script>
  <script>
 const togglePassword = document.getElementById('togglePassword');
const togglePasswordConfirm = document.getElementById('togglePassword_confirm');
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('confirm_password');

togglePassword.addEventListener('click', () => {
  const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordField.setAttribute('type', type);
  togglePassword.src = type === 'password' ?  './images/eye-slash.svg' :'./images/eye.svg' ;
});

togglePasswordConfirm.addEventListener('click', () => {
  const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
  confirmPasswordField.setAttribute('type', type);
  togglePasswordConfirm.src = type === 'password' ?  './images/Vector-password.svg' : './images/eye.svg' ;
});


  </script>
</body>

</html>