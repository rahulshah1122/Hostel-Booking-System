<?php
require_once('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve user inputs
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate user credentials using prepared statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["user_password"])) {
        // Successful login
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['user_id'] = $row['user_id'];
        

        // Redirect based on user type
        if ($row['user_type'] == 'admin') {
            // Redirect to update.php for admin
            header("Location: update.php");
            exit(); // Ensure script stops execution after redirection
        } elseif ($row['user_type'] == 'user') {
            // Redirect to index.php for user
            header("Location: index.php");
            exit(); // Ensure script stops execution after redirection
        } else {
            // Redirect to signup.php for other user types
            header("Location: signup.php");
            exit(); // Ensure script stops execution after redirection
        }
    } else {
        // Invalid password
        header("Location: login.php?error=Invalid password");
        exit();
    }
} else {
    // User not found
    header("Location: login.php?error=User not found");
    exit();
}


 
}

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
    <a href="#"><img src="img/logo.jpg" alt="logo"></a>
    <div>
      <ul id="navbar">
      <li><a class="active" href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <button id="lglogin" class="clickbutton"><a href="Login.php">Login</a></button>
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
      <li><a href="hostel.php">Hostels</a></li>
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
    <h1>Login</h1>
  </div>

  <section id="contact" class="section-p1">
        <form action="login.php" method="post">
            <div class="contactme">
                <h5>Gmail</h5>
                <input type="email" name="email" id="contactgmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" autocomplete="off" placeholder="Your email" required>
            </div>

            <div class="contactme">
                <h5>Password</h5>
                <input type="password" name="password" id="password" value="" autocomplete="off" placeholder="Enter Your Password">
            </div>

            <div class="contactme" id="contactsubmit">
                <button type="submit" class="clickbuttons">Submit</button>
                <button class="clickbuttons">
    <a href="signup.php" style="text-decoration: none; color: inherit;">SignUp</a>
</button>
            </div>
        </form>

        <?php
        // Display error message if exists
        if (isset($_GET['error'])) {
            echo '<p style="color: red; font-weight: bold; font-size: medium;">' . $_GET['error'] . '</p>';
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
      <button>Suscribe</button>
      
    </div>
    
  </section>
</div>

 
   <div>
<section id="registration">

    <hr class="line">
</section>
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