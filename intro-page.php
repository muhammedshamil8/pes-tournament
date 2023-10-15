<?php
// opening page

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seee.us</title>
    <link rel="stylesheet" href="styles/intro-page.css">
    <link rel="stylesheet" href="styles/fon-opening-pages.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
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
          <a href="#">About</a>
          <a href="#">Services</a>
          <a href="#">Contact</a>
      </nav>

  <div class="container-intro-page">

      <div class="card-flex-intro">

               <h1>Player Login
              <div class="underline"></div>

               </h1>

          <div class="button-flex-intro">
               <button onclick="
               window.location = 'login.php';
               ">Login</button>
               <button onclick="
               window.location = 'register.php';
               ">Register</button>
          </div>
           
      </div>

  </div>
   <script src="./script/header-opening.js"> </script>
</body>

</html>