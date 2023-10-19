<?php
// opening page

?>
<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seee.us</title>
    <link rel="stylesheet" href="styles/loading.css">
    <link rel="stylesheet" href="styles/fon-opening-pages.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body id="content">
  <header>
        <div>
            <h1 class="title" onclick="
            window.location = 'index.php';
            ">seeee<span>.</span>us</h1>
        </div>
        <div>
            <input type="checkbox" id="nav-toggle" class="input-nav">
            <label for="nav-toggle" class="nav-icon">
              <div class="line"></div>
              <div class="line middle-line"></div>
              <div class="line "></div>
            </label>
        </div>

  </header>

      <nav class="nav-content">
          <a href="#" active>Home</a>
          <a href="https://www.britannica.com/sports/football-soccer" target="_blank">About</a>
          <a href="#">Services</a>
          <a href="https://keralapolice.gov.in/" target="_blank">Contact</a>
          <a > 
          
        <!-- <input type="range" max="10" min="0" style=""/> -->
        <audio autoplay loop controls class="aduio-btn">
          <source src="/home/ajad/work/pes/images/pes.mp3" type="audio/mpeg">
          <source src="images/pes.mp3" type="audio/ogg">
        </audio></a>
      </nav>

  <div class="container">

      <div class="button-flex-index">
            <button onclick="loadDoc('intro-page.php')">player</button>
            <button onclick="window.location='admin.php';">organizer</button>
      </div>

  </div>

  
  <!-- <div id="loading-container">
  <div id="progress-bar">
    <div id="progress-text">0%</div>
  </div>
</div> 
<button id="start-loading-btn">Start Loading</button>
-->
<div id="loading-overlay">
  <div class="football">
  <img src="./images/football.jpg" alt="Football">
</div> 

   <script src="./script/header-opening.js"> </script>

</body>

</html>