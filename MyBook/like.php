<?php
include("classes/autoload.php");

// Check if user is logged in
$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

// Determine where to return after processing the like
if (isset($_SERVER['HTTP_REFERER'])) {
    $return_to = $_SERVER['HTTP_REFERER'];
} else {
    $return_to = "profile.php";
}

// Check if the necessary parameters are set
if (isset($_GET['type']) && isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $allowed = ['post', 'user', 'comment'];

        // Corrected the typo here
        if (in_array($_GET['type'], $allowed)) {
            $post = new Post();
            $user_class = new User();

            $post->like_post($_GET['id'], $_GET['type'], $_SESSION['mybook_userid']);
            if($_GET['type'] == "user"){
               $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION['mybook_userid']);

            }

        }
    }
}

// Redirect back to the previous page or profile
header("Location: " . $return_to);
die;
