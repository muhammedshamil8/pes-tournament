<?php
// var_dump($stored_password); 
// var_dump($password);        
// var_dump($_POST); 
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// session_start();

// if ($_SERVER["REQUEST_METHOD"] == "POST"){
//   $username = $_POST["username"];
//   $password = $_POST["password"];

//   require 'connect_db.php';
//   $sql = "SELECT id, username, password FROM `login` WHERE username = '$username'";
//    $result = $conn->query($sql);

//    if ($result->num_rows == 1){
//     $row = $result->fetch_assoc();
//     $stored_password = trim($row["password"]);

//     if ($password == $stored_password){

//     //   $_SESSION["user_id"] = $row["id"];
//       // $_SESSION["username"] = $row["username"];
//       header("Location: register.php");
//       exit();
//     }else {
//       $error_msg = "Incorrect username or password.";
//     }
//    }else {
//     $error_msg = "Incorrect username or password.";
//    }

//    $conn->close();
// }


// if (isset($_SESSION['user_id'])) {
//     // User is logged in, redirect to home page
//     header("Location: home.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eFootball</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-sun-fill" viewBox="0 0 16 16">
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
                <td class="close-btn">
                    <button id="closeSettingsBtn">-X-</button>
                </td>
            </tr>
        </table>

        <main>

            <div class="container1">
                <div class="card1">
                    <h1>WeLcOmE tO eFoOtBaLl</h1>
                    <div class="image">
                        <img class="logo" src="/images/logo.webp" alt="Logo">
                    </div>
                    <div class="button-contanier-head">
                        <button disabled>Let's Get in</button></a>
                    </div>
                    <div class="button-contanier">
                        <a href="register.php"><button>Player</button></a>

                        <a href="admin.php"><button>Organizer</button></a>
                    </div>
                    <!-- <div class="btn-container"> -->

                    <!-- <div class="login-container">
    <h2>Login to tournament</h2>
    <form action="" method="post">
      <input type="text" placeholder="Username" name="username" class="input" required>
      <input type="password" placeholder="Password" name="password" class="input" required>
      <button type="submit" class="btn-login">Login</button>

    </form>
     <?php
     //  echo '<p class="error_message">' .$error_msg;'</p>'
     ?> 
                         <a href="signup.php"><button class="btn btn-primary">Create Account</button></a> 
                         <a href="login.php"><button class="btn btn-primary">Login Account</button></a> 

                    </div> -->
                </div>

            </div>
        </main>

        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>
        <script src="/script/general.js"></script>

</body>

</html>