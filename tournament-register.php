<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "connect_db.php"; // Connect to your database

$error_msg = '';

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action === 'register') {
        // Handle registration
        $admin_username = $_SESSION['admin_username'];
        $tournament_name = $_POST['tournament_name'];
        $tournament_type = isset($_POST['tournament_type']) ? $_POST['tournament_type'] : '';
        $start_date = $_POST['start_date'];

        // Check if the tournament name is empty
        if (empty($tournament_name)) {
            $error_msg = "Please provide a tournament name.";
        } else {
            // Check if the tournament name is unique
            $check_query = "SELECT * FROM tournament WHERE tournament_name='$tournament_name'";
            $check_result = $conn->query($check_query);

            if ($check_result->num_rows > 0) {
                $error_msg = "Tournament with this name already exists.";
            } else {
                // Insert tournament
                $query_tournament = "INSERT INTO tournament (tournament_name, start_date, admins_id, model) 
                VALUES ('$tournament_name', '$start_date', 
                        (SELECT admins_id FROM admins WHERE username='$admin_username'),
                        '$tournament_type')";
                if ($conn->query($query_tournament) === TRUE) {
                    // Retrieve the tournament_id for the newly inserted tournament
                    $tournament_id = $conn->insert_id;
                    $error_msg = "Tournament registered successfully : $tournament_name";
                } else {
                    $error_msg = "Error registering the tournament: " . $conn->error;
                }
            }
        }
        // login actions
    } elseif ($action === 'login') {
        $tournament_name2 = isset($_POST['tournament_name2']) ? $_POST['tournament_name2'] : '';

        // Check if a valid tournament name is selected
        if (empty($tournament_name2)) {
            $error_msg = "Please select a valid tournament.";
        } else {
            // Check if the selected tournament exists
            $check_query = "SELECT * FROM tournament WHERE tournament_name='$tournament_name2'";
            $check_result = $conn->query($check_query);

            if ($check_result->num_rows > 0) {
                $row = $check_result->fetch_assoc();
                $tournament_id = $row['tournament_id'];
                // Redirect to the tournament admin page with the specific tournament_id
                header("Location: tournament_home.php?tournament_id=$tournament_id");
                exit();
            } else {
                $error_msg = "Invalid tournament selected.";
            }
        }
    }
    // $conn->close();  // Close the database connection
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/general.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #fffafa, #848482);
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
            /* display: none; */

        }

        .login {
            width: 350px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
            text-align: center;
            display: none;

        }

        .card h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .card input,
        .card select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* .card input:focus,
    .card select:focus {
        outline: blue;
    } */

        .card select {
            background-color: white;
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
            background-color: #f8f9fa;
            /* Light gray background */
            color: green;
            /* Green text */
            font-weight: bold;
            display: none;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="register">

            <h2>Tournament Creation</h2>
            <!-- Your registration form here -->
            <form action="" method="POST">

                <input type="text" id="tournament_name" name="tournament_name" placeholder="Tournament Name"
                    required><br>
                <input type="date" id="start_date" name="start_date" placeholder="Start Date" required><br>
                <select class="select" name="tournament_type" required>
                    <option value="" disabled selected>Select Your Tournament Type</option>
                    <option value="league" >league</option>
                    <option value="knockout" >knockout</option>
                   
                </select>
                <input type="hidden" name="action" value="register"> <!-- Hidden field to indicate registration -->
                <input type="submit" value="Register">
            </form>
            <p>Already have an tournament? <a onclick="loginPage();">Login here</a>.</p>
            <?php echo "<p class='error-msg' style='color:red;'>$error_msg </P>" ?>


        </div>
    </div>

    <div class="card">
        <div class="login">

            <h2>Tournament Login</h2>
            <!-- Your login form here -->
            <form action="" method="POST">

                <br>
                <select class="select" name="tournament_name2">
                    <option value="" disabled selected>Select Your Tournament Name</option>
                    <?php
                    // Fetch tournament names
                    $tournament_names = [];
                    $tournament_query = "SELECT tournament_name FROM tournament";
                    $tournament_result = $conn->query($tournament_query);

                    if ($tournament_result) {
                        while ($row = $tournament_result->fetch_assoc()) {
                            $tournament_names[] = $row['tournament_name'];
                        }
                    } else {
                        $error_msg = "Error fetching tournament names: " . $conn->error;
                    }

                    foreach ($tournament_names as $name) {
                        echo '<option value="' . $name . '">' . $name . '</option>';
                    }
                    ?>
                </select>


                <br>
                <br>
                <input type="hidden" name="action" value="login">
                <input type="submit" value="Login">
            </form>


            <p>Don't have an tournament? <a onclick="RegisterPage();">create here</a>.</p>
            <p class='error-msg' style='color:red;'>
                <?php
                echo $error_msg;
                ?>
            </P>

        </div>

    </div>
    <a href="admin_home.php">
        <button class="return"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg></button>
    </a>
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>
    <!-- <script src="/script/general.js"></script> -->
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
        function errorMsg() {
            // Initially hide the error message
            const errorMsgElement = document.querySelector('.error-msg');
            errorMsgElement.style.display = 'none';

            // Check if there's an error message to display
            const errorMessage = '<?php echo $error_msg; ?>';
            if (errorMessage.trim() !== '') {
                // Show the error message
                errorMsgElement.style.display = 'block';
                errorMsgElement.innerHTML = errorMessage;

                // Hide the error message after 5 seconds (adjust as needed)
                setTimeout(function () {
                    errorMsgElement.style.display = 'none';
                }, 5000); // 5 seconds delay
            }
        }

        // Trigger the loading animation when loading content
        errorMsg();

    </script>
</body>

</html>