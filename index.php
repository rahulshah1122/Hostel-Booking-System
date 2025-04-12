<?php
require_once('connect.php');

// Check if user or admin is logged in
session_start();


if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $query = "SELECT * FROM hostel WHERE hostel_name LIKE '$keyword%' OR hostel_location LIKE '$keyword%'";
} else {
    $query = "SELECT * FROM hostel";
}

$result = mysqli_query($conn, $query);
?>





<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Homstall</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
</head>

<body>
  <section id="header">
    <a href="index.php"><img src="img/logo.jpg" alt="logo"></a>
    <div>
      <ul id="navbar">
        <li><a class="active" href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <button id="lglogin" class="clickbutton"><a href="Logout.php">Login</a></button>
        <a href="#" id="close"><i class="closeicon"></i></a>
      
      </ul>
    </div>
    <div id="mobile">
      <!-- <button class="clickbuttons"><a href="Logout.php">Login</a></button>
      <i id="bar" class="baricon"></i> -->

      <button class="clickbuttons" id="loginButton"><a href="#">Login</a></button>
            <i id="bar" class="baricon"></i>

 <!-- to set login logout session dynamically -->
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check the user's login status
            var isLoggedIn = <?php echo isset($_SESSION['user_type']) ? 'true' : 'false'; ?>;
            var loginButton = document.getElementById('lglogin');
            var mobileLoginButton = document.getElementById('loginButton');

            // Set the initial text based on the login status
            loginButton.innerText = isLoggedIn ? 'Logout' : 'Login';
            mobileLoginButton.innerText = isLoggedIn ? 'Logout' : 'Login';

            // Add a click event listener to toggle between login and logout
            loginButton.addEventListener('click', function () {
                // Update the button text
                loginButton.innerText = isLoggedIn ? 'Login' : 'Logout';
                mobileLoginButton.innerText = isLoggedIn ? 'Login' : 'Logout';

                // Redirect to the appropriate action (Login.php or Logout.php)
                var redirectUrl = isLoggedIn ? 'Logout.php' : 'Login.php';
                window.location.href = redirectUrl;
            });

            mobileLoginButton.addEventListener('click', function () {
                // Update the button text
                loginButton.innerText = isLoggedIn ? 'Login' : 'Logout';
                mobileLoginButton.innerText = isLoggedIn ? 'Login' : 'Logout';

                // Redirect to the appropriate action (Login.php or Logout.php)
                var redirectUrl = isLoggedIn ? 'Logout.php' : 'Login.php';
                window.location.href = redirectUrl;
            });
        });
    </script>






    </div>
  </section>
  <section id="cover">
    <div>
      <h4>Come and get your dream accommodation.</h4>
      <p>No matter where you're, we take care all your needs.</p>
      <p>We are professional for your safe and easy settlement.</p>
    </div>
    <div>
      <ul id="search">
        <li><a href="hostel.php">Hostels</a></li>
    </div>
    <div>
      <ul id="line">
        <hr>
      </ul>
      <ul id="searchbar">
        <li class="searchicon"></li>
        <form method="GET" action="index.php">
            <input type="text" name="keyword" id="allsearch" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" autocomplete="off" placeholder="Search place, Locality, name...">
            <button type="submit" class="clickbuttons">Search</button>
            <button id="filterset" class="hostelsearch"><a href="hostel.php">Reset</a></button>
        </form>
    </ul>
    </div>
  </section>
  <div>
    <h1>Featured</h1>
    <h2>Hostels</h2>
    <section id="feature" class="section-p1">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <?php
        // Assuming you have three image columns: hostel_image1, hostel_image2, hostel_image3
        // Adjust column names as needed
        $image1 = base64_encode($row['hostel_image1']);
        $image2 = base64_encode($row['hostel_image2']);
        $image3 = base64_encode($row['hostel_image3']);
        ?>
            <div class="fe-box">
                <a href="productdetail.php?hostel_id=<?php echo $row['hostel_id']; ?>" style="text-decoration: none;">
                    <!-- <img src="img/feature/fea.jpg" alt="feature photo"> -->
                    <img src="data:image/;base64,<?php echo $image1; ?>" alt="feature photo">

                    <div id="fe-box-text">

                        <h5 class="textcolor">Name: <?php echo $row['hostel_name']; ?></h5>
                        <h5 class="textcolor">Gender: <?php echo $row['gender']; ?></h5>

                        <p1>Rs: <?php echo $row['hostel_rent']; ?></p1>
                        <h5 class="textcolor">Location: <?php echo $row['hostel_location']; ?></h5>

                    </div>
                </a>
            </div>
    <?php
        }
    } else {
        echo '<p>No results found.</p>';
    }
    ?>
</section>

  </div>


  <h1>Our Services</h1>

  <div>
  <Section id="services" class="section-p1">
  <div class="verified">
    <img src="img/Services/verified.png" alt="verified">
    <h2>VERIFIED</h2>
    <p2> We present you the verified deals for your convenience</p2>
    
  </div>
  <div class="verified">
    <img src="img/Services/saving.png" alt="verified">
    <h2>TOTALLY FREE OF COST</h2>
    <p2> We are operating completely on zero commission</p2>
    
  </div>
  <div class="verified">
    <img src="img/Services/operational.png" alt="verified">
    <h2>ALL IN ONE</h2>
    <p2> We have brought solutions to all Residential problems</p2>
    
  </div>
  
  <div class="verified">
    <img src="img/Services/anywhere.png" alt="verified">
    <h2>ALL OVER NEPAL</h2>
    <p2> We are operational all over the country on your demand</p2>   
  </div>
</Section>
</div>

<div>

  <section id="newsletter" class="section-p1">
    <div class="newstext">
      <h4>Singup for Newsletter</h4>
      <p>Get all the updates and offers instantly</p>
    </div>
    <div class="form">
      <input type="text" placeholder="Your email address">
      <button>Suscribe</button>
      
    </div>
    
  </section>
</div>

   <div>

     <hr class="line">
    </div> 
        <div>

          <img class="logo" src="img/logo.jpg" alt="logo">
        </div>
        <footer class="section-p1 section-m1">
          <div class="col">
            <h5>Contact</h5>
            <p2><strong>Address:</strong>Kathmandu Nepal </p2>
            <p2><strong>Phone:</strong>9888888888</p2>
            <p2><strong>Hours:</strong>24/7</p2>
            <p2> &copy 2024, Homstall </p2>
            
          </div>
          
          <div class="col">
            <h5>About</h5>
            <a href="about.html">About Us</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="contact.html">Contact Us</a>
            
          </div>
        </footer>

  <script src='script.js'></script>
</body>

</html>