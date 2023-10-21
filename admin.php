<?php
session_start();
require "connect_db.php"; // Connect to your database
$username = '';
$password = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
/* in sql INSERT INTO admins (username, password) VALUES ('username', SHA2('password', 256));
*/
    // Hash the entered password using SHA-256
    $hashed_input_password = hash('sha256', $password);

    // Query to check if the provided username exists
    $query = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['password'];

        // Compare the hashed input password with the hashed password from the database
        if ($hashed_input_password === $hashed_password_from_db) {
            // Passwords match, proceed with login
            $_SESSION['admin_username'] = $username;
            header("Location: ./admin_home.php"); // Redirect to the admin dashboard
            exit();
        } else {
            $error_msg = "Invalid password. Please try again.";
        }
    } else {
        $error_msg = "Invalid username. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html >

<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Admin Login</title>
     <link rel="stylesheet" href="styles/admin-login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preload" href="./images/player-image.svg" as="image">


</head>
<!--  onclick="loadDoc('index.php')" -->
<!--  onclick="window.location='index.php';" -->
<body id="content">
<header>
  <div class="header">
  <!-- <div class="seeus"> -->
  <h1 class="title" onclick="window.location='index.php';">seeee<span>.</span>us</h1>
  <!-- </div> -->
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
          <h1>Admin Login</h1>
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
      </div>


      <div class="login-image">
      <button onclick="window.location='index.php';">
      <img src="./images/Vecto-rclose.svg" />
      </button>
        <img src="./images/player-image.svg" alt="player-image" class="player-image">
      </div>
  </div>
  </div>
  <div id="loading-page">
      <div class="spinner"></div>
  </div>
<script src="./script/header-opening.js"></script>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

togglePassword.addEventListener('click', () => {
  const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordField.setAttribute('type', type);
  togglePassword.src = type === 'password' ?  './images/Vector-password.svg' : './images/eye.svg' ;

  // togglePassword.classList.toggle('fa-eye-slash');
});
  </script>

</body>

</html>