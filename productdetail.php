<?php
require_once('connect.php');
session_start();
// Fetch all hostels
$query = "SELECT * FROM hostel";
$result = mysqli_query($conn, $query);

// Check for GET parameter for detailed hostel view
if (isset($_GET['hostel_id'])) {
    // Get the hostel_id from the URL
    $hostel_id = $_GET['hostel_id'];

    // Prepare and execute the query to fetch details of the clicked hostel and its ratings
    $query = "
        SELECT h.*, r.rating
        FROM hostel h
        LEFT JOIN ratings r ON h.hostel_id = r.hostel_id
        WHERE h.hostel_id = $hostel_id
    ";

    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the details of the hostel and its ratings
        $row = mysqli_fetch_assoc($result);
    } else {
        // Handle the case where hostel details are not found
        echo "Hostel details not found!";
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
        <a href="index.php"><img src="img/logo.jpg" alt="logo"></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
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
    <section id="productdetails">
    <?php
        // Assuming you have three image columns: hostel_image1, hostel_image2, hostel_image3
        // Adjust column names as needed
        $image1 = base64_encode($row['hostel_image1']);
        $image2 = base64_encode($row['hostel_image2']);
        $image3 = base64_encode($row['hostel_image3']);
        ?>
        <!-- Slideshow container -->
        <div class="slideshow-container">

            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="data:image/;base64,<?php echo $image1; ?>" style="width:100%">
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="data:image/;base64,<?php echo $image2; ?>"style="width:100%">
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="data:image/;base64,<?php echo $image3; ?>"style="width:100%">
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <div id="details">
            <table id="tablecomponent" class="component">
                <tr>
                    <td style="height: 99px;">
                        <div class="tableHead"><i class="priceicon"></i>Monthly Fee</div>
                        <div class="component__details" id="">₹ <span id="pdPrice2"> <?php echo $row['hostel_rent']; ?></span>
                            
                        </div>
                    </td>
                    <td style="height: 99px;">
                        <div class="component__tableHead"><i class="addressicon"></i>Address</div>
                        <div class="component__details" id=""><?php echo $row['hostel_location']; ?></div>
                    </td>
                </tr>
                <tr>
                    <td style="height: 81px;">
                        <div class="component__tableHead"><i class="furnishingicon"></i>Furnishing
                        </div>
                        <div class="component__details" id=""><span id="furnishingLabel">Furnished</span></div>
                    </td>
                    <td style="height: 81px;">
                        <div class="component__tableHead"><i class="availableicon"></i>Available For</div>
                        <div class="component__details" id=""><span id="availableForLabel"><?php echo $row['gender']; ?></span></div>
                    </td>
                </tr>
                <tr>
                    <td style="height: 117px;">
                        <div class="component__tableHead"><i class="roomtypeicon"></i>Room Type
                        </div>
                        <div class="component__details" id=""><span id="agePossessionLbl"><?php echo $row['room_type']; ?></span>
                          
                        </div>
                    </td>
                    <td style="height: 117px;">
                        <div class="component__tableHead"><i class="configurationicon"></i>Configuration
                        </div>
                        <div class="component__details" id=""><span id="bedRoomNum"><?php echo $row['hostel_description']; ?></span></b></div>
                    </td>
                </tr>
                <tr>

                    <td style="height: 81px;">
                        <div class="component__tableHead"><i class="ownericon"></i>Hostel Name
                        </div>
                        <div class="component__details" id=""><span id="ownername"><?php echo $row['hostel_name']; ?></span>
                        </div>
                        <div class="component__detailsnumber" id=""><span id="ownernumber"><?php echo $row['hostel_contact']; ?></span>
                        </div>
                    </td>
                </tr>
            </table>

        </div>

    </section>

    <div id="facilities" name="facilitiesincluded">
    <div>
        <h2><?php echo $row['hostel_name']; ?></h2>

        <?php
        // Retrieve average rating for the current hostel ID
        $hostelId = $row['hostel_id'];
        $avgRatingQuery = "SELECT AVG(rating) AS avgRating FROM ratings WHERE hostel_id = $hostelId";
        $avgRatingResult = mysqli_query($conn, $avgRatingQuery);
        $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);

        // Calculate average rating
        $averageRating = $avgRatingRow['avgRating'];
        $maxStars = 5; // Maximum number of stars
        ?>

        <!-- Display star images based on the average rating -->
        <div id="ratings">
            <?php
            for ($i = 1; $i <= $maxStars; $i++) {
                if ($i <= $averageRating) {
                    echo '<img src="img/star_filled.png" alt="Filled Star" style="width: 30px; height: auto;">';
                } else {
                    echo '<img src="img/star_empty.png" alt="Empty Star" style="width: 30px; height: auto;">';
                }
            }
            ?>
        </div>

        <!-- Display existing comments with options to edit or delete -->
        <?php
$commentsQuery = "SELECT r.*, u.user_name FROM ratings r
                  INNER JOIN users u ON r.user_id = u.user_id
                  WHERE r.hostel_id = $hostelId
                  ORDER BY r.created_at DESC"; // Assuming you have a 'created_at' column in the ratings table

$commentsResult = mysqli_query($conn, $commentsQuery);

if (mysqli_num_rows($commentsResult) > 0) {
?>
    <div style="margin: 10px 10px;">
        <h3>Comments:</h3>
        <?php
        while ($commentRow = mysqli_fetch_assoc($commentsResult)) {
            $commentDate = date('F j, Y, g:i a', strtotime($commentRow['created_at']));
        ?>
           <div class="comments">
        
            <h3><?php echo $commentRow['user_name']; ?></h3>
            <hr style="width: 100%; color:#088178;">
            <p><?php echo $commentRow['user_comment']; ?></p>
            <hr style="width: 100%; color:#088178;">
            <p1>Commented on: <?php echo $commentDate; ?></p1>
        
        <div>
            <!-- options to edit or delete with the corresponding rating_id -->
            <button class="clickbuttonscomment"><a href="edit_comment.php?comment_id=<?php echo $commentRow['rating_id']; ?>" style="text-decoration: none; color: white;">Edit</a></button>
            <button class="clickbuttonscomment"><a href="delete_comment.php?comment_id=<?php echo $commentRow['rating_id']; ?>" style="text-decoration: none; color: white;">Delete</a></button>
        </div>
        </div>

        <?php
        }
        ?>
    </div>
<?php
}
?>


        <!-- Add a form for user ratings and comments -->
        <form style=" margin: 100px auto; padding: 20px;" action="process_rating.php" method="post">
    <div style="margin-bottom: 15px;">
        <label for="user_rating" style="font-weight: bold; display: block; margin-bottom: 5px;">Your Rating:</label>
        <select name="user_rating" required style="width: 100%; padding: 8px 18px; box-sizing: border-box;">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="user_comment" style="font-weight: bold; display: block; margin-bottom: 5px;">Your Comment:</label>
        <textarea name="user_comment" rows="4" cols="50" required style="width: 100%; padding: 8px; box-sizing: border-box; border-radius: 5px; resize: none;"></textarea>

    </div>
    <input type="hidden" name="hostel_id" value="<?php echo $hostelId; ?>">
    <input type="submit" class="clickbuttons" value="Submit Rating and Comment">
</form>

    </div>
</div>






    <div>
        <!-- <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d113088.9066425855!2d85.05342600326843!3d27.654595111268026!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2snp!4v1658606731214!5m2!1sen!2snp"
            width="92%" height="400" style="border:5; margin:50px 60px 100px 60px;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe> -->

            <?php
// Assuming $row contains the fetched hostel details including address

// Geocode the address to get latitude and longitude
$address = urlencode($row['hostel_location']);
$geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=YOUR_API_KEY";

$response = file_get_contents($geocode_url);
$json = json_decode($response);

// Extract latitude and longitude from the geocoding response
if ($json && $json->status == 'OK') {
    $latitude = $json->results[0]->geometry->location->lat;
    $longitude = $json->results[0]->geometry->location->lng;
} else {
    // Default coordinates if geocoding fails
    $latitude = 27.702871;
    $longitude = 85.318244;
}

// Construct the URL for Google Maps iframe
$maps_url = "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d113088.9066425855!2d$longitude!3d$latitude!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2snp!4v1658606731214!5m2!1sen!2snp";
?>

<iframe src="<?php echo $maps_url; ?>" width="92%" height="400" style="border:5; margin:50px 60px 100px 60px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

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