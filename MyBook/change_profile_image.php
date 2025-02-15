<?php

session_start();

include("classes/connect.php");
include("classes/login.php");
include("classes/user.php");
include("classes/post.php");
include("classes/image.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] !== "") {

        if ($_FILES['file']['type'] == "image/jpeg") {

            // Allow 3MB maximum size
            $allowed_size = 3 * 1024 * 1024;
            if ($_FILES['file']['size'] <= $allowed_size) {
                // Everything is fine

                $folder = "uploads/" . $user_data['userid'] . "/";

                //create folder
                if(!file_exists($folder)) {
                    mkdir($folder, 0777, true); //file permission
                }

                $image = new Image();
                $filename = $folder . $image->generate_filename(15) . ".jpg";
                move_uploaded_file($_FILES['file']['tmp_name'], $filename);

                $change = "profile";

                //check for mode
                if(isset($_GET['change'])) {
                    $change = $_GET['change'];
                }

                if($change == "cover") {
                    if(file_exists($user_data['cover_image'])) {
                        unlink($user_data['cover_image']);
                    }
                    $image->resize_image($filename, $filename, 1500, 1500);
                } else {
                    if(file_exists($user_data['profile_image'])) {
                        unlink($user_data['profile_image']);
                    }
                    $image->resize_image($filename, $filename, 1500, 1500);
                }

                if (file_exists($filename)) {
                    $userid = $user_data['userid'];


                    if($change == "cover") {
                        $query = "UPDATE users SET cover_image = '$filename' WHERE userid = '$userid' LIMIT 1";
                        $_POST['is_cover_image'] = 1;

                    } else {
                        $query = "UPDATE users SET profile_image = '$filename' WHERE userid = '$userid' LIMIT 1";
                        $_POST['is_profile_image'] = 1;

                    }

                    

                    $DB = new Database();
                    $DB->save($query);

                    //create a post
                    $post = new Post();


                    $result = $post->create_post($userid, $_POST, $filename);
                

                    header("Location: profile.php");
                    die;
                }
            } else {
                echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                echo "<br>The following errors occurred:<br><br>";
                echo "Only images of size 3MB or lower are allowed!";
                echo "</div>";
            }
        } else {
            echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
            echo "<br>The following errors occurred:<br><br>";
            echo "Only JPEG images are allowed!";
            echo "</div>";
        }
    } else {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occurred:<br><br>";
        echo "Please select an image to upload!";
        echo "</div>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Profile Image | Timeline</title>
</head>
<style type="text/css">
#blue_bar {
    height: 50px;
    background-color: #405d9b;
    color: #d9dfeb;
}
#search_box {
    width: 400px;
    height: 20px;
    border-radius: 5px;
    border: none;
    padding: 4px;
    font-size: 14px;
    background-image: url(images/search.png);
    background-repeat: no-repeat;
    background-position: right;
}
textarea:focus {
    outline: none;
}
#post_button {
    float: right;
    background-color: #405d9b;
    border: none;
    color: white;
    padding: 4px;
    font-size: 14px;
    border-radius: 2px;
    width: auto;
}
#post_bar {
    margin-top: 20px;
    background-color: white;
    padding: 10px;
}
#post {
    padding: 4px;
    font-size: 13px;
    display: flex;
    margin-bottom: 20px;
}
</style>
<body style="font-family: tahoma; background-color: #d0d8e4;">
<?php include("header.php"); ?>

<!-- Cover Area -->
<div style="width: 800px; margin: auto; min-height: 400px;">

    <!-- Below Cover Area -->
    <div style="display: flex;">
        <!-- Posts Area -->
        <div style="min-height: 400px; flex: 2.5; padding: 20px; padding-right: 0px;">
            <div style="border: solid thin #aaa; padding: 10px; background-color: white;">

                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="file">
                    <input style="width:100px;" type="submit" id="post_button" value="Change">
                    <br>

                    

 <div style="text-align: center;">
    <br><br>
    <?php

// Check for mode
if (isset($_GET['change']) && $_GET['change'] == "cover") {
    $change = "cover";
    $img = $user_data['cover_image'];
    echo "<img src='$img' style='max-width: 500px;'>";
} else {
    $img = $user_data['profile_image'];
    echo "<img src='$img' style='max-width: 500px;'>";
}

?>

                </div>
                </form>

            </div>
        </div>
    </div>

</div>
</body>
</html>
