<?php
require_once('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_rating = $_POST['user_rating'];
    $user_comment = $_POST['user_comment'];
    $hostel_id = $_POST['hostel_id'];

    // Assuming you have a user_id stored in the session after the user logs in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if the user has already rated this hostel
    $checkRatingQuery = "SELECT * FROM ratings WHERE user_id = '$user_id' AND hostel_id = '$hostel_id'";
    $result = mysqli_query($conn, $checkRatingQuery);

    if (mysqli_num_rows($result) > 0) {
        // User has already rated this hostel, update the existing rating
        $updateQuery = "UPDATE ratings SET rating = '$user_rating' WHERE user_id = '$user_id' AND hostel_id = '$hostel_id'";
        
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['comment_success'] = 'Rating updated successfully!';
        } else {
            echo "Error updating rating: " . mysqli_error($conn);
        }
    }

    // Insert a new row for the new comment
    $insertQuery = "INSERT INTO ratings (rating, user_comment, hostel_id, user_id) VALUES ('$user_rating', '$user_comment', '$hostel_id', '$user_id')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['comment_success'] = 'New comment added successfully!';
    } else {
        echo "Error inserting rating: " . mysqli_error($conn);
    }

    header('Location: productdetail.php?hostel_id=' . $hostel_id);
    exit();
}
?>
