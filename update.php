<?php
require_once('connect.php');


session_start();

// Check if the user is logged in and has admin access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Check if the user is logged in as a user, redirect to index.php
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
        header("Location: index.php");
        exit();
    } else {
        // Redirect to signup.php for other cases
        header("Location: signup.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $hostelname = mysqli_real_escape_string($conn, $_POST['hostelname']);
    $hostellocation = mysqli_real_escape_string($conn, $_POST['hostellocation']);
    $hostelgender = mysqli_real_escape_string($conn, $_POST['hostelgender']);
    $hostelrent = mysqli_real_escape_string($conn, $_POST['hostelrent']);
    $roomtype = mysqli_real_escape_string($conn, $_POST['roomtype']);
    $hostelcontact = mysqli_real_escape_string($conn, $_POST['hostelcontact']);
    $hosteldescription = mysqli_real_escape_string($conn, $_POST['hosteldescription']);

    // Handle image uploads
    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');

    // Check if all images were uploaded successfully
    if ($image1 !== null && $image2 !== null && $image3 !== null) {
        // Your SQL query for insertion with BLOB data type
        $sql = "INSERT INTO hostel (hostel_name, hostel_location, gender, hostel_rent, room_type, hostel_contact, hostel_description, hostel_image1, hostel_image2, hostel_image3)
                VALUES ('$hostelname', '$hostellocation', '$hostelgender', '$hostelrent', '$roomtype', '$hostelcontact', '$hosteldescription', ?, ?, ?)";

        // Prepare the SQL statement
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters and send BLOB data
        mysqli_stmt_bind_param($stmt, "sss", $image1, $image2, $image3);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo 'Insertion successful';
            header("Location: update.php");
            exit();
        } else {
            echo 'Insertion failed: ' . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo 'Image upload failed for one or more images.';
    }
}

function uploadImage($inputName) {
    $targetDir = "uploads/"; // Specify your upload directory

    $originalFileName = $_FILES[$inputName]["name"];
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    // Generate a unique identifier for the file name
    $uniqueIdentifier = uniqid();

    // Constructing the new file name by appending the unique identifier and a timestamp
    $newFileName = $uniqueIdentifier . '_' . time() . '.' . $fileExtension;

    $targetFile = $targetDir . $newFileName;
    $uploadOk = 1;

    // Checking file size
    if ($_FILES[$inputName]["size"] > 5000000) { // Adjust the size limit as needed
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allowing certain file formats
    $allowedExtensions = ["jpg", "jpeg", "png", "gif","webp"];
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Sorry, only JPG, JPEG, WEBP, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Checking if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return null;
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
            return file_get_contents($targetFile); // Read the file content for BLOB
        } else {
            echo "Sorry, there was an error uploading your file.";
            return null;
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

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
</head>

<body>
  <section id="header">
    <a href="index.php"><img src="img/logo.jpg" alt="logo"></a>
    <div>
      <ul id="navbar">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a class="active" href="contact.php">Contact Us</a></li>
        <button id="lglogin" class="clickbutton"><a href="Login.php">Login</a></button>
        <a href="#" id="close"><i class="closeicon"></i></a>

      </ul>
    </div>
    <div id="mobile">
      <button class="clickbuttons"><a href="Login.html">Login</a></button>
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
    <h1>ADD HOSTEL</h1>
  </div>

  <!-- for form to contact -->
  <section id="contact" class="section-p1">
  <form method="post" enctype="multipart/form-data">
    <div class="contactme">
        <h5>Hostel Name</h5>
        <input type="text" name="hostelname" id="hostelname" value="" autocomplete="off" placeholder="Hostel Name" required>
    </div>

    <div class="contactme">
        <h5>Hostel Location</h5>
        <input type="text" name="hostellocation" id="hostellocation" value="" autocomplete="off" placeholder="Hostel Location" required>
    </div>

    <div class="contactme">
        <h5>Gender</h5>
        <select name="hostelgender" id="hostelgender" required>
            <option>Select Gender</option>
            <option>Boys</option>
            <option>Girls</option>
        </select>
    </div>

    <div class="contactme">
        <h5>Hostel Rent</h5>
        <input type="number" name="hostelrent" id="hostelrent" value="" autocomplete="off" placeholder="Hostel Rent" required>
    </div>

    <div class="contactme">
        <h5>Room Type</h5>
        <input type="text" name="roomtype" id="roomtype" value="" autocomplete="off" placeholder="Room Type"  required>
    </div>

    <div class="contactme">
        <h5>Hostel Contact</h5>
        <input type="tel" name="hostelcontact" id="hostelcontact" value="" autocomplete="off" placeholder="Hostel Contact" pattern="[0-9]{10}" required>
    </div>

    <div class="contactme">
        <h5>Hostel Description</h5>
        <input type="textarea" name="hosteldescription" id="hosteldescription" value="" autocomplete="off" placeholder="Hostel Description" required>
    </div>

    <div class="contactme">
      <h5>Upload Images</h5>
      <!-- Container for three file inputs -->
      <div class="image-container">
        <input type="file" name="image1" accept="image/*" required>
        <input type="file" name="image2" accept="image/*" required>
        <input type="file" name="image3" accept="image/*" required>
      </div>
    </div>

    <div class="contactme" id="contactsubmit">
        <button class="clickbuttons" onclick="submitForm()">Submit</button>
    </div>
  </form>
</section>

<script>
  function submitForm() {
  // Get input values
  var hostelName = document.getElementById('hostelname').value;
  var hostelLocation = document.getElementById('hostellocation').value;
  var gender = document.getElementById('hostelgender').value;
  var hostelRent = document.getElementById('hostelrent').value;
  var roomType = document.getElementById('roomtype').value;
  var hostelContact = document.getElementById('hostelcontact').value;
  var hostelDescription = document.getElementById('hosteldescription').value;

  // Check if the form is already submitted
  var isFormSubmitted = document.getElementById('contactsubmit').classList.contains('submitted');

  if (!isFormSubmitted) {
    // Create a FormData object to send data to the PHP script
    var formData = new FormData();
    formData.append('hostelname', hostelName);
    formData.append('hostellocation', hostelLocation);
    formData.append('hostelgender', gender);
    formData.append('hostelrent', hostelRent);
    formData.append('roomtype', roomType);
    formData.append('hostelcontact', hostelContact);
    formData.append('hosteldescription', hostelDescription);

    // Append image files
    formData.append('image1', document.querySelector('input[name="image1"]').files[0]);
    formData.append('image2', document.querySelector('input[name="image2"]').files[0]);
    formData.append('image3', document.querySelector('input[name="image3"]').files[0]);

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the PHP script URL
    var url = 'insert_update.php'; // Update this with your PHP script URL

    // Configure the request
    xhr.open('POST', url, true);

    // Set up the onload and onerror callback functions
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Successfully inserted into the database
        console.log(xhr.responseText);
        // You can handle the response here if needed

        // Mark the form as submitted
        document.getElementById('contactsubmit').classList.add('submitted');

        // Clear input fields
        document.getElementById('hostelname').value = '';
        document.getElementById('hostellocation').value = '';
        document.getElementById('hostelgender').value = 'Select Gender';
        document.getElementById('hostelrent').value = '';
        document.getElementById('roomtype').value = '';
        document.getElementById('hostelcontact').value = '';
        document.getElementById('hosteldescription').value = '';
      } else {
        // Failed to insert into the database
        console.error('Error:', xhr.statusText);
      }
    };

    xhr.onerror = function () {
      // Network error
      console.error('Network Error');
    };

    // Send the FormData to the PHP script
    xhr.send(formData);
  }
}

</script>


  


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