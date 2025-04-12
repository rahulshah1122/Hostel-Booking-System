<?php
require_once('connect.php');
session_start();

// Check if the user is logged in and has admin access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
  // Check if the user is logged in as a user, redirect to index.php
  if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
      header("Location: index.php");
      exit();
  } 
  // else {
  //     // Redirect to signup.php for other cases
  //     header("Location: signup.php");
  //     exit();
  // }
}
else {
  // Redirect to signup.php for other cases
  header("Location: signup.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists
    $check_duplicate_email = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result_duplicate_email = $conn->query($check_duplicate_email);

    if ($result_duplicate_email->num_rows > 0) {
        // Email already exists, throw an error
        header("Location: signup.php?error=User with this email already exists.");
          exit();
    } else {
        // Email is unique, proceed with registration
        // Assign default user type
        $default_user_type = 'user';

        // Insert user data into the 'users' table with default user type
        $sql = "INSERT INTO users (user_name, user_email, user_password, user_type) VALUES ('$name', '$email', '$password', '$default_user_type')";

        if ($conn->query($sql) === TRUE) {
            echo "User registered successfully!";

            // Redirect to the login page
            header("Location: login.php");
            exit(); // Ensure script stops execution after redirection
        } else {
            // Other errors during registration
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
      <button class="clickbuttons"><a href="Login.php">Login</a></button>
      <i id="bar" class="baricon"></i>
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
    <h1>SignUp</h1>
  </div>

  <section id="contact" class="section-p1">
  <form action="signup.php" method="post">

        <div class="contactme">
                <h5>Name</h5>
                <input type="text" name="name" id="contactgmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" autocomplete="off" placeholder="Your Name" required>
            </div>

            <div class="contactme">
                <h5>Gmail</h5>
                <input type="email" name="email" id="contactgmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" autocomplete="off" placeholder="Your email" required>
            </div>

            <div class="contactme">
                <h5>Password</h5>
                <input type="password" name="password" id="password" value="" autocomplete="off" placeholder="Enter Your Password" required>
            </div>

            <div class="contactme" id="contactsubmit">
                <button type="submit" class="clickbuttons">Signup</button>
                <button class="clickbuttons">
    <a href="login.php" style="text-decoration: none; color: inherit;">SignIn</a>
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