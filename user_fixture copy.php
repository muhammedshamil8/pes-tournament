<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require "connect_db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['tournament_id'])) {
    $_SESSION['tournament_id'] = $_POST['tournament_id'];
} else {
    header("Location: home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$tournament_id = $_SESSION['tournament_id'];

// Fetch and display matches with team details from the database
$matchesQuery = "SELECT matches.*, teams1.user_id AS user_id_team1, teams2.user_id AS user_id_team2,
                     teams1.club_id AS club_id_team1, teams2.club_id AS club_id_team2
                 FROM matches 
                 JOIN teams AS teams1 ON matches.team1_id = teams1.team_id 
                 JOIN teams AS teams2 ON matches.team2_id = teams2.team_id 
                 WHERE matches.tournament_id = ? 
                 AND (teams1.user_id = ? OR teams2.user_id = ?)";
$stmt = $conn->prepare($matchesQuery);
$stmt->bind_param("iii", $tournament_id, $user_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

// $matchesPerPage = 10;
// // Calculate total pages
// $totalMatchesQuery = "SELECT COUNT(*) AS total_matches FROM matches WHERE tournament_id = ? 
//                      AND (team1_id IN (SELECT team_id FROM teams WHERE user_id = ?) 
//                      OR team2_id IN (SELECT team_id FROM teams WHERE user_id = ?))";
// $totalStmt = $conn->prepare($totalMatchesQuery);
// $totalStmt->bind_param("iii", $tournament_id, $user_id, $user_id);
// $totalStmt->execute();
// $totalResult = $totalStmt->get_result();
// $totalMatches = $totalResult->fetch_assoc()['total_matches'];
// $totalPages = ceil($totalMatches / $matchesPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <style>
        /* Add your CSS styles for header and sidebar here */
        /* For example: */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 20px;
            text-align: center;
            background:red;
        }
        .sidebar {
          background:red;

            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-x: hidden;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .match-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .match-card .form-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .match-card .form-section input,
        .match-card .form-section textarea {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .match-card .form-section button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .match-card .form-section button:hover {
            background-color: #45a049;
        }
        .match-card .form-section .result-uploaded {
            color: green;
        }
        .countdown {
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Header</h1>
    </div>

    <!-- <div class="sidebar">
        <a href="#">Link 1</a>
        <a href="#">Link 2</a>
    </div> -->

    <div style="margin-left: 250px; padding: 20px;">
        <!-- Content area where you'll display matches and result form -->
        <h2>Matches</h2>
        <?php
        $position = "Pes";
        if ($result->num_rows > 0) {
          // $position ++;
            $row = $result->fetch_assoc(); // Fetch the first match
            echo '<div class="match-card">';
            echo '<h3>Current Match - Match #' . $position . '</h3>';
            echo '<p>Team 1 ID: ' . $row['team1_id'] . '</p>';
            echo '<p>Team 2 ID: ' . $row['team2_id'] . '</p>';
            if ($row['match_status'] == 1) {
                echo '<div class="form-section">';
                echo '<p class="result-uploaded">Result uploaded for this match. Match finished.</p>';
                echo '</div>';
            } else {
                echo '<div class="form-section">';
                echo '<form action="submit_result.php" method="post" enctype="multipart/form-data">';
                echo '<input type="hidden" name="match_id" value="' . $row["match_id"] . '">';
                echo '<input type="file" name="image" accept="image/*" required>';
                echo '<input type="number" name="your_score" placeholder="Your Score" required>';
                echo '<input type="number" name="opponent_score" placeholder="Opponent Score" required>';
                echo '<textarea name="message" placeholder="Message"></textarea>';
                echo '<button type="submit">Submit Result</button>';
                echo '</form>';
                echo '</div>';
            }
            echo '</div>';

            // Fetch the next match
            $row = $result->fetch_assoc();
            if ($row) {
                echo '<div class="match-card">';
                echo '<h3>Next Match - Match #' . $position . '</h3>';
                echo '<p>Team 1 ID: ' . $row['team1_id'] . '</p>';
                echo '<p>Team 2 ID: ' . $row['team2_id'] . '</p>';
                echo '<div class="form-section">';
                echo '<p>Upload the result for the next match after finishing the current match.</p>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>No more matches for this tournament.</p>';
            }
        } else {
            echo "No matches found for this tournament.";
        }
        ?>
    </div>
    <div class="countdown" id="countdown"></div>
    </div>

    <script>
        const matches = [
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '{ matchId: ' . $row['match_id'] . ', matchNumber: ' . $row['match_number'] . ', isFinished: ' . ($row['match_status'] == 1 ? 'true' : 'false') . ' },';
            }
            ?>
        ];

        const matchesContainer = document.getElementById('matches-container');
        const countdownElement = document.getElementById('countdown');
        let currentMatchIndex = 0;

        function displayMatch(match) {
            const matchCard = document.createElement('div');
            matchCard.classList.add('match-card');

            const matchHeading = document.createElement('h3');
            matchHeading.textContent = match.isFinished ? `Match #${match.matchNumber} - Finished` : `Match #${match.matchNumber}`;
            matchCard.appendChild(matchHeading);

            const team1Id = document.createElement('p');
            team1Id.textContent = `Team 1 ID: ${match.team1_id}`;
            matchCard.appendChild(team1Id);

            const team2Id = document.createElement('p');
            team2Id.textContent = `Team 2 ID: ${match.team2_id}`;
            matchCard.appendChild(team2Id);

            const formSection = document.createElement('div');
            formSection.classList.add('form-section');

            if (match.isFinished) {
                const resultUploaded = document.createElement('p');
                resultUploaded.classList.add('result-uploaded');
                resultUploaded.textContent = 'Result uploaded for this match. Match finished.';
                formSection.appendChild(resultUploaded);
            } else {
                const submitForm = document.createElement('form');
                submitForm.action = 'submit_result.php';
                submitForm.method = 'post';
                submitForm.enctype = 'multipart/form-data';

                const matchIdInput = document.createElement('input');
                matchIdInput.type = 'hidden';
                matchIdInput.name = 'match_id';
                matchIdInput.value = match.matchId;
                submitForm.appendChild(matchIdInput);

                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = 'image';
                fileInput.accept = 'image/*';
                fileInput.required = true;
                submitForm.appendChild(fileInput);

                const yourScoreInput = document.createElement('input');
                yourScoreInput.type = 'number';
                yourScoreInput.name = 'your_score';
                yourScoreInput.placeholder = 'Your Score';
                yourScoreInput.required = true;
                submitForm.appendChild(yourScoreInput);

                const opponentScoreInput = document.createElement('input');
                opponentScoreInput.type = 'number';
                opponentScoreInput.name = 'opponent_score';
                opponentScoreInput.placeholder = 'Opponent Score';
                opponentScoreInput.required = true;
                submitForm.appendChild(opponentScoreInput);

                const messageTextarea = document.createElement('textarea');
                messageTextarea.name = 'message';
                messageTextarea.placeholder = 'Message';
                submitForm.appendChild(messageTextarea);

                const submitButton = document.createElement('button');
                submitButton.type = 'submit';
                submitButton.textContent = 'Submit Result';
                submitForm.appendChild(submitButton);

                formSection.appendChild(submitForm);
            }

            matchCard.appendChild(formSection);
            matchesContainer.appendChild(matchCard);
        }

        function calculateTimeRemainingForDeadline(deadlineTime) {
            const differenceInSeconds = Math.floor((deadlineTime * 1000 - Date.now()) / 1000);
            if (differenceInSeconds < 0) {
                return { hours: 0, minutes: 0, seconds: 0 };  // Deadline has passed
            }

            const hours = Math.floor(differenceInSeconds / 3600);
            const minutes = Math.floor((differenceInSeconds % 3600) / 60);
            const seconds = differenceInSeconds % 60;

            return { hours, minutes, seconds };
        }

        function updateCountdown(deadlineTime) {
            const timeRemaining = calculateTimeRemainingForDeadline(deadlineTime);

            if (timeRemaining.hours === 0 && timeRemaining.minutes === 0 && timeRemaining.seconds === 0) {
                countdownElement.innerHTML = 'Deadline passed';
            } else {
                countdownElement.innerHTML = `Time until deadline: ${timeRemaining.hours}h ${timeRemaining.minutes}m ${timeRemaining.seconds}s`;
            }
        }

        function finishMatchAndShowNext() {
            currentMatchIndex = (currentMatchIndex + 1) % matches.length;
            matchesContainer.innerHTML = '';  // Clear the matches container
            displayMatch(matches[currentMatchIndex]);

            const deadlineTime = matches[currentMatchIndex].isFinished ? Date.now() : Date.now() + 24 * 3600 * 1000; // 24-hour deadline
            updateCountdown(deadlineTime);
        }

        // Initial display of matches and countdown
        displayMatch(matches[currentMatchIndex]);
        const deadlineTime = matches[currentMatchIndex].isFinished ? Date.now() : Date.now() + 24 * 3600 * 1000; // 24-hour deadline
        updateCountdown(deadlineTime);

        // Simulate finishing a match after 5 seconds (replace this with your actual logic)
        setTimeout(finishMatchAndShowNext, 5000);
    </script>
</body>
</html>