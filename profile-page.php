<?php
var_dump($stored_password);
var_dump($password);
var_dump($_POST);
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require "connect_db.php";

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}


if (isset($_GET['uz'])) {
     $user_id = $_GET['uz'];
$query_registration = "SELECT * FROM registration WHERE user_id = ?";



$stmt_registration = $conn->prepare($query_registration);
$stmt_registration->bind_param("i", $user_id);
$stmt_registration->execute();
$result_registration = $stmt_registration->get_result();



if ($result_registration->num_rows > 0) {
  

  while ($row = $result_registration->fetch_assoc()) {
    $full_name = $row['full_name'];
    $age = $row['age'];
    $phone_number = $row['phone_number'];
    $club = $row['club'];
    $tournament_id = $row['tournament_id'];

      // Get the tournament name using the tournament_id
        $query_tournament_name = "SELECT tournament_name FROM tournament WHERE tournament_id = $tournament_id";
        $result_tournament_name = $conn->query($query_tournament_name);

        // league_table
        $query_table = "SELECT lt.*, r.tournament_id, r.club FROM league_table lt JOIN registration r ON lt.user_id = r.user_id WHERE lt.tournament_id = $tournament_id ORDER BY lt.points DESC";

        $result_table = $conn->query($query_table);
        
                


        if ($result_tournament_name->num_rows > 0) {
            $row_tournament = $result_tournament_name->fetch_assoc();
            $tournament_name = $row_tournament['tournament_name'];

            // Display the tournament name
           
        } else {
            echo "Tournament not found.";
        }
      
    // Additional improvement: Use a switch case to determine club information
    switch ($club) {
      case "1":
        $image = 'images/psg.webp';
        $clubName = "P-S-G";
        break;
      case "2":
        $image = 'images/Fcb.webp';
        $clubName = "FC Barcelona";
        break;
      case "3":
        $image = 'images/real_madrid.webp';
        $clubName = "Real madrid";
        break;
      case "4":
        $image = 'images/Manchester-United-Logo.webp';
        $clubName = "Manchester United";
        break;
      case "5":
        $image = 'images/m-city.webp';
        $clubName = "Manchester City ";
        break;
      case "6":
        $image = 'images/benfica.webp';
        $clubName = "Benfica";
        break;
      case "7":
        $image = 'images/napoli.webp';
        $clubName = "Napoli";
        break;
      case "8":
        $image = 'images/ac-milan-logo.webp';
        $clubName = "AC milan";
        break;
      case "9":
        $image = 'images/arsenal-logo-0.webp';
        $clubName = "Arsenal";
        break;
      case "10":
        $image = 'images/Chelsea-Logo.webp';
        $clubName = "Chelsea";
        break;
      case "11":
        $image = 'images/new-castle.webp';
        $clubName = "New Castle";
        break;
      case "12":
        $image = 'images/bayern.webp';
        $clubName = "Bayern";
        break;
      case "13":
        $image = 'images/logo-de-juventus.webp';
        $clubName = "Juventus";
        break;
      case "14":
        $image = 'images/new-castle.webp';
        $clubName = "New castle";
        break;
      case "15":
        $image = 'images/inter-logo.webp';
        $clubName = "Inter milan";
        break;
      case "16":
        $image = 'images/liverpool-fc-logo.webp';
        $clubName = "Liverpool";
        break;
      // Add cases for other clubs
      default:
        $image = 'images/logo.webp'; // Provide a default image if club is not recognized
        $clubName = "Unknown Club";
        break;
    }
  }
} else {
  echo "User not found or no registration data available.";
}
} else {
     echo "User ID not provided.";
   }
// $conn->close();
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
  <script src="./script/general.js"></script>
  <script src="./script/home.js"></script>
</head>

<body>


 

 

    <?php
 
    ?>
  
  <div class="container1">



    <section class="card1">

      <div class="card1-1">
        <?php
        echo "<div class='team-logo'><img  class='team-logo-img' src='$image' alt='club logo'></div>";

        echo "<p> $clubName </p>";
//         echo $user_id;
// echo $tournament_id;
        ?>
        <br>

        <div class="user-registration-table">
          <table class="table-registration">
            <thead>

            </thead>
            <tbody>
            <tr>
                <td class="head-td">Tournament Name</td>
                <td class="child-td">
                  <?php echo $tournament_name; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Full Name</td>
                <td class="child-td">
                  <?php echo $full_name; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Age</td>
                <td class="child-td">
                  <?php echo $age; ?>
                </td>
              </tr>
              <tr>
                <td class="head-td">Phone Number</td>
                <td class="child-td">
                 <a href="https://wa.me/<?php echo $phone_number; ?>?text=Hello%20from%20your%20site" target="_blank">
                 <?php echo $phone_number; ?>
  </a>
                </td>
              </tr>
              <tr>
                <td class="head-td">Club</td>
                <td class="child-td">
                  <?php echo $clubName; ?>
                </td>
              </tr>
             
            </tbody>
          </table>
        </div>

       
      </div>

    </section>

   

  






  </div>


<?php
$conn->close();

?>
</body>

</html>