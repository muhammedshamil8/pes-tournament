<!-- admin_login.php -->
<?php
session_start();
require "connect_db.php"; // Connect to your database
$username = '';
$password = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_POST['username'];
     $password = $_POST['password'];
     // $hashed_password = password_hash($password, PASSWORD_DEFAULT);


     // Query to check if the provided credentials match an admin
     $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
     $result = $conn->query($query);

     if ($result->num_rows == 1) {
          // Admin is authenticated
          $_SESSION['admin_username'] = $username;
          header("Location: admin_home.php"); // Redirect to the admin dashboard
     } else {
          $error_msg = "Invalid details. Please try again.";
     }
}
?>
<!DOCTYPE html>
<html>

<head>
     <title>Admin Login</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/general.css">


     <style>
         body {
   font-family: Arial, sans-serif;
   background-image: url('/images/pes.jpg');
   background-repeat: no-repeat;
   background-size: cover; 
   /* background-position: center center;  */
   font-family: 'Roboto Condensed', sans-serif;
   min-height: 100vh; 
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
margin: 0;
}

          .card {
               width: 350px;
               padding: 30px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
               border-radius: 10px;
               backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
               background-color: rgba(0, 0, 0, 0.1);
               text-align: center;
          }

          .card h2 {
               margin-bottom: 20px;
          }

          .card input {
               width: 100%;
               padding: 10px;
               margin-bottom: 10px;
               border-radius: 5px;
               border: 1px solid #ccc;
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
     </style>
</head>

<body>
     <div class="card">
          <h2>Admin Login</h2>
          <form action="" method="POST">
               <input type="text" id="username" name="username" placeholder="Username" required><br>
               <input type="password" id="password" name="password" placeholder="Password" required><br>
               <input type="submit" value="Submit">
               <?php echo $error_msg ?>
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

</body>

</html>