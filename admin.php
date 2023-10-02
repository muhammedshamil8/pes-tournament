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
          header("Location: tournament.php"); // Redirect to the admin dashboard
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
     <style>
          body {
               font-family: Arial, sans-serif;
               background-color: #f9f9f9;
               margin: 0;
               padding: 0;
               display: flex;
               justify-content: center;
               align-items: center;
               height: 100vh;
          }

          .card {
               width: 350px;
               padding: 20px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
               border-radius: 10px;
               background-color: white;
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
</body>

</html>