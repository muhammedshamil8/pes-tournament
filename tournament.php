<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require_once "connect_db.php"; // Connect to your database

$error_msg ='';
// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as admin.";
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action === 'register') {
        // Handle registration
        $admin_username = $_POST['username'];
        $tournament_name = $_POST['tournament_name'];
        $start_date = $_POST['start_date'];

        // Check if the tournament name is unique
        $check_query = "SELECT * FROM tournament WHERE tournament_name='$tournament_name'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
          $error_msg = "Tournament with this name already exists.";
        } else {
            // Insert tournament
            $query_tournament = "INSERT INTO tournament (tournament_name, start_date) VALUES ('$tournament_name', '$start_date')";
            if ($conn->query($query_tournament) === TRUE) {
                // Retrieve the tournament_id for the newly inserted tournament
                $tournament_id = $conn->insert_id;
                $error_msg = "Tournament registered successfully with ID: $tournament_id";
            } else {
               $error_msg = "Error registering tournament: " . $conn->error;
            }
        }
    } elseif ($action === 'login') {
        // Handle login
        $admin_username = $_POST['login_username'];
        $password = $_POST['login_password'];
        $tournament_name = isset($_POST['tournament_name']) ? $_POST['tournament_name'] : '';

        $query = "SELECT * FROM admins WHERE username='$admin_username' AND password='$password'";
        $result = $conn->query($query);
        
        if ($result->num_rows == 1) {
            $admin_row = $result->fetch_assoc();
          header("Location: admin_home.php"); 
            
            // Check if 'tournament_id' is defined in $admin_row
          //   if (isset($admin_row['tournament_id'])) {
          //       $admin_tournament_id = $admin_row['tournament_id'];
          //       // ... rest of your code ...
          //   } else {
          //       echo "Admin's tournament ID is not defined. Please contact support.";
          //   }
        } else {
          $error_msg = "Invalid credentials. Please try again.";
        }
    } else {
     $error_msg = "Invalid action.";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Tournament Registration and Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register {
            width: 350px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
            text-align: center;
            display: none;

        }

        .login {
            width: 350px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
            text-align: center;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .card input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
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

        .card p {
            color: #555;
            font-size: 14px;
        }

        .card a {
            color: #007BFF;
            text-decoration: none;
            cursor: pointer;
        }

        .card a:hover {
            text-decoration: underline;
        }
        .error-msg {
    background-color: #f8f9fa; /* Light gray background */
    color: green; /* Green text */
    font-weight: bold;
}

    </style>
</head>
<body>
    <div class="card">
    <div class="register">

        <h2>Tournament Registration</h2>
        <!-- Your registration form here -->
        <form action="" method="POST">
    <input type="text" id="username" name="username" placeholder="admin Username" required><br>
    <input type="text" id="tournament_name" name="tournament_name" placeholder="Tournament Name" required><br>
    <input type="date" id="start_date" name="start_date" placeholder="Start Date" required><br>
    <input type="hidden" name="action" value="register">  <!-- Hidden field to indicate registration -->
    <input type="submit" value="Register">
</form>
        <p>Already have an account? <a onclick="loginPage();">Login here</a>.</p>
        <?php echo "<p class='error-msg' style='color:red;'>$error_msg </P>"?>


    </div>
    </div>

    <div class="card">
    <div class="login">

        <h2>Tournament Login</h2>
        <!-- Your login form here -->
        <form action="" method="POST">
    <input type="text" id="login_username" name="login_username" placeholder="admin Username" required><br>
    <input type="password" id="login_password" name="login_password" placeholder="admin Password" required><br>
    <input type="text" id="login_tournament_name" name="tournament_name" placeholder="Tournament Name" required><br>  <!-- Modified to include tournament_name -->
    <input type="hidden" name="action" value="login">  <!-- Hidden field to indicate login -->
    <input type="submit" value="Login">

</form>

        <p>Don't have an account? <a onclick="RegisterPage();">Register here</a>.</p>
        <?php echo "<p class='error-msg' style='color:red;'>$error_msg </P>"?>

    </div>
    
    </div>

    <script>
        const register = document.querySelector('.register');
        const login = document.querySelector('.login');

        function loginPage() {
          register.style.display = 'none';
          login.style.display = 'block';
        }

        function RegisterPage() {
          login.style.display = 'none';
            register.style.display = 'block';
        }
     </script>
</body>
</html>
