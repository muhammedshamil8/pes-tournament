<?php
// edit_user.php

// Include necessary files and initialize the session
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once "../connect_db.php"; // Connect to your database

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "Unauthorized access. Please log in as an admin.";
    header("Location: ../admin.php");
    exit();
}

// Get the user ID from the URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Check if a valid user ID is provided
if ($user_id === null) {
    echo "Invalid user ID.";
    exit();
}

// Fetch user details based on the user ID
$sql = "SELECT * FROM registration WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // User details
    $username = $row['name'];
    $name = $row['full_name'];
    $phone_number = $row['phone_number'];
    $club = $row['club'];
} else {
    echo "User not found.";
    exit();
}

// Check if the form is submitted for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect updated details from the form
    $new_username = $_POST['username'];
    $new_name = $_POST['name'];
    $new_phone_number = $_POST['phone_number'];
    $new_club = $_POST['club'];

    // Update the user details
    $update_sql = "UPDATE registration SET name='$new_username', full_name='$new_name', phone_number='$new_phone_number', club='$new_club' WHERE user_id=$user_id";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect back to the manage_users.php page after updating
        header("Location: manage_users.php?tournament_id={$row['tournament_id']}");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
    <link rel="stylesheet" href="../styles/general.css"> <!-- Your custom CSS file -->
</head>
<body>
    <header>
        <h1>Edit User Profile</h1>
    </header>

    <main>
        <form action="" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <label for="username">User Name:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br><br>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br><br>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required><br><br>

            <label for="club">Club:</label>
            <input type="text" id="club" name="club" value="<?php echo $club; ?>" required><br><br>

            <button type="submit">Save Changes</button>
        </form>
    </main>
</body>
</html>
