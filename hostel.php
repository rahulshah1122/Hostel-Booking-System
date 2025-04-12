<?php
require_once('connect.php');

session_start();

// Default query
$query = "SELECT * FROM hostel";

// Filter by Hostel Name
if (isset($_GET['filterhname']) && !empty($_GET['filterhname'])) {
    $filterhname = mysqli_real_escape_string($conn, $_GET['filterhname']);
    $query .= " WHERE hostel_name LIKE '$filterhname%'";
}

// Filter by Address/Landmark
if (isset($_GET['filterhaddress']) && !empty($_GET['filterhaddress'])) {
    $filterhaddress = mysqli_real_escape_string($conn, $_GET['filterhaddress']);
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND hostel_location LIKE '$filterhaddress%'";
    } else {
        $query .= " WHERE hostel_location LIKE '$filterhaddress%'";
    }
}

// Filter by Gender
if (isset($_GET['filterhgender']) && $_GET['filterhgender'] !== 'Select Gender') {
    $filterhgender = mysqli_real_escape_string($conn, $_GET['filterhgender']);
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND gender = '$filterhgender'";
    } else {
        $query .= " WHERE gender = '$filterhgender'";
    }
}

// Filter by Price Range
if (isset($_GET['filterhprice']) && $_GET['filterhprice'] !== 'Select Price Range') {
    $filterhprice = mysqli_real_escape_string($conn, $_GET['filterhprice']);
    if ($filterhprice === 'Below Rs.8000') {
        if (strpos($query, 'WHERE') !== false) {
            $query .= " AND hostel_rent < 8000";
        } else {
            $query .= " WHERE hostel_rent < 8000";
        }
    } elseif ($filterhprice === 'Between Rs.8000-15000') {
        if (strpos($query, 'WHERE') !== false) {
            $query .= " AND hostel_rent BETWEEN 8000 AND 15000";
        } else {
            $query .= " WHERE hostel_rent BETWEEN 8000 AND 15000";
        }
    } elseif ($filterhprice === 'Above Rs.15000') {
        if (strpos($query, 'WHERE') !== false) {
            $query .= " AND hostel_rent > 15000";
        } else {
            $query .= " WHERE hostel_rent > 15000";
        }
    }
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
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <button id="lglogin" class="clickbutton"><a href="login.php">Login</a></button>
        <a href="#" id="close"><i class="closeicon"></i></a>
      
      </ul>
    </div>
    <div id="mobile">
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
        <li><a class="active" href="hostel.php">Hostels</a></li>
    </div>
    <div>
      <ul id="line">
        <hr>
      </ul>
      <ul id="searchbar">


      </ul>
    </div>
  </section>

  
  <div>
    <h1>Hostels</h1>

    <div>

    <section id="filters" class="section-p1">
        <div class="allfilter">
            <h5>Hostel Name</h5>
            <input type="text" name="filter" id="filterhname" value="" autocomplete="off" placeholder="Hostel Name">
        </div>
        <div class="allfilter">
            <h5>Address/Landmark</h5>
            <input type="text" name="filter" id="filterhaddress" value="" autocomplete="off"
                placeholder="Search place, Locality, name...">
        </div>
        <div class="allfilter">
            <h5>Gender</h5>
            <select name="filter" id="filterhgender">
                <option>Select Gender</option>
                <option>Boys</option>
                <option>Girls</option>
            </select>
        </div>
        <div class="allfilter">
            <h5>Price Range</h5>
            <select name="filter" id="filterhprice">
                <option>Select Price Range</option>
                <option>Below Rs.8000</option>
                <option>Between Rs.8000-15000</option>
                <option>Above Rs.15000</option>
            </select>
        </div>
    </section>

    <div class="filterbutton">
        <button id="filtersearch" class="hostelsearch">Search</button>
        <button id="filterset" class="hostelsearch"><a href="#">Reset</a></button>
    </div>
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

<div>

  <section id="newsletter" class="section-p1">
    <div class="newstext">
      <h4>Singup for Newsletter</h4>
      <p>Get all the updates and offers instantly</p>
    </div>
    <div class="form">
      <input type="text" placeholder="Your email address">
      <button>Subscribe</button>
      
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
            <a href="about.php">About Us</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="contact.php">Contact Us</a>
            
          </div>
        </footer>

        <script>
        document.getElementById('filtersearch').addEventListener('click', function () {
            // Get filter values
            var filterhname = document.getElementById('filterhname').value;
            var filterhaddress = document.getElementById('filterhaddress').value;
            var filterhgender = document.getElementById('filterhgender').value;
            var filterhprice = document.getElementById('filterhprice').value;

            // Construct the URL with filters
            var url = 'hostel.php?';
            if (filterhname) url += 'filterhname=' + filterhname + '&';
            if (filterhaddress) url += 'filterhaddress=' + filterhaddress + '&';
            if (filterhgender !== 'Select Gender') url += 'filterhgender=' + filterhgender + '&';
            if (filterhprice !== 'Select Price Range') url += 'filterhprice=' + filterhprice + '&';

            // Redirect to the new URL
            window.location.href = url;
        });

        document.getElementById('filterset').addEventListener('click', function () {
            // Reset all filters
            document.getElementById('filterhname').value = '';
            document.getElementById('filterhaddress').value = '';
            document.getElementById('filterhgender').value = 'Select Gender';
            document.getElementById('filterhprice').value = 'Select Price Range';

            // Redirect to the original URL
            window.location.href = 'hostel.php';
        });
    </script>

  <script src='script.js'></script>
</body>

</html>