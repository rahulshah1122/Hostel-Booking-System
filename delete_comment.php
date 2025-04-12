<?php
require_once('connect.php');
session_start();

// Check if the user is authenticated (you may customize this part based on your authentication logic)
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or another appropriate location
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];

    // Fetch comment details for the given comment_id using prepared statement
    $commentQuery = "SELECT * FROM ratings WHERE rating_id = ?";
    $stmt = mysqli_prepare($conn, $commentQuery);
    mysqli_stmt_bind_param($stmt, 'i', $comment_id);
    mysqli_stmt_execute($stmt);

    $commentResult = mysqli_stmt_get_result($stmt);

    if ($commentResult && mysqli_num_rows($commentResult) > 0) {
        $commentRow = mysqli_fetch_assoc($commentResult);

        // Check if the user has permission to delete this comment
        if ($_SESSION['user_id'] !== $commentRow['user_id']) {
            // echo "You do not have permission to delete this comment!";
            echo '<script>alert("You do not have permission to delete this comment!");</script>';
            exit();
            header('Location: productdetail.php');
        }
    } else {
        echo "Comment not found!";
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    // Redirect to the main page if the comment_id is not provided
    header('Location: productdetail.php');
    exit();
}

// Delete the comment from the database using prepared statement
$deleteQuery = "DELETE FROM ratings WHERE rating_id = ?";
$stmt = mysqli_prepare($conn, $deleteQuery);
mysqli_stmt_bind_param($stmt, 'i', $comment_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['delete_success'] = 'Comment deleted successfully!';
} else {
    echo "Error deleting comment: " . mysqli_error($conn);
}

// Redirect back to the main page
header('Location: index.php');
exit();
?>
