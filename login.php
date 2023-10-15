<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Include the database connection
require 'connect_db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["username"];
    $password = $_POST["password"];
    $tournament_id = isset($_POST['tournament_name2']) ? (int)$_POST['tournament_name2'] : 0;

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, name, password FROM registration WHERE name = ? AND tournament_id = ?");
    $stmt->bind_param("si", $name, $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stored_password = trim($row["password"]);
            if (password_verify($password, $stored_password)) {
              $_SESSION["user_id"] = $row["user_id"];
              header("Location: home.php");
              exit();
            } else {
                $error_msg = "Incorrect username or password.";
            }
        } else {
            $error_msg = "Incorrect username or password or dont in this tournamnet.";
        }
    } else {
        $error_msg = "Error in SQL query: " . $conn->error;
    }

    // $stmt->close();
    // $conn->close();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seee us -login</title>
  <link rel="stylesheet" href="styles/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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


    <div class="login-container">
     
      <div class="login-details">
<div class="card-flex-intro">
            <h1>Player Login</h1>
              <div class="underline"></div>
            
</div>
          

        <form action="" method="post">
          <div class="input-container">
            <input type="text" id="username" name="username" required>
            <label for="username">Username</label>
            <span class="icon">
              <img src="./images/icon-username.svg" />
            </span>
          </div>
          <div class="input-container">
            <input type="password" id="password" name="password" required>
            <label for="password">Password</label>
            <span class="icon">
              <img src="./images/Vector-password.svg" id="togglePassword" />
              <!-- <i class="far fa-eye" ></i> -->
            </span>
          </div>

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
          <div id="custom-alert" class="custom-alert">
  <div class="card">
    <span class="close" onclick="closeCustomAlert()">&times;</span>
    <div class="message" id="custom-alert-message">
    </div>
  </div>
</div>

<p class="forget-pass" onclick="showForgetPassMessage()">Forget password?</p>

          <button type="submit" class="btn-login">Login<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
          </svg>
</button>
        </form>
        <?php
        echo '<p class="error_message">' . $error_msg . '</p>';
        ?>
        <p class="change">Don't have an account ?<a href="register.php">Sign up</a></p>
      </div>
    <div class="login-image">
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
const passwordField = document.getElementById('password');

togglePassword.addEventListener('click', () => {
  const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordField.setAttribute('type', type);
  // togglePassword.classList.toggle('fa-eye-slash');
});

  </script>
</body>

</html>
