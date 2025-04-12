<?php
require_once('connect.php');
session_start();

// Check if the user is authenticated (you may customize this part based on your authentication logic)
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or another appropriate location
    header('Location: login.php');
    exit();
}

// Initialize $comment_id
$comment_id = null;

// Check if comment_id is provided in the GET request
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

        // Check if the logged-in user has permission to edit this comment
        if ($_SESSION['user_id'] !== $commentRow['user_id']) {
            echo "You do not have permission to edit this comment!";
            exit();
        }
    } else {
        echo "Comment not found!";
        exit();
    }
    mysqli_stmt_close($stmt);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    // Handle form submission for updating the comment
    $comment_id = $_POST['comment_id'];
    $updated_comment = $_POST['updated_comment'];

    // Update the comment in the database using prepared statement
    $updateQuery = "UPDATE ratings SET user_comment = ? WHERE rating_id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'si', $updated_comment, $comment_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['edit_success'] = 'Comment updated successfully!';
        header('Location: index.php'); // Redirect back to the main page
        exit();
    } else {
        echo "Error updating comment: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    // Redirect to the main page if the comment_id is not provided
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Edit Comment</h2>
    <form action="edit_comment.php" method="post">
        <label for="updated_comment">Updated Comment:</label>
        <textarea name="updated_comment" rows="4" cols="50" required><?php echo $commentRow['user_comment']; ?></textarea>
        <br>
        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
        <input type="submit" class="clickbuttons" value="Update Comment">
    </form>
</body>

</html>
