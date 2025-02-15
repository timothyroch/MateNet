<?php


include("classes/autoload.php");



//isset
$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

$USER = $user_data;

if(isset($_GET['id']) && is_numeric($_GET['id'])){
  $profile = new Profile();
  $profile_data = $profile->get_profile($_GET['id']);

  if(is_array($profile_data))
{
  $user_data = $profile_data[0];

}

  
}





//posting starts here
 if($_SERVER['REQUEST_METHOD'] == "POST")
 {
 
if(isset($_POST['first_name'])){

  $settings_class = new Settings();
  $settings_class->save_settings($_POST, $_SESSION['mybook_userid']);

}else{
  $post = new Post();
  $id = $_SESSION['mybook_userid'];
  
  $result = $post->create_post($id, $_POST, $_FILES);

      if($result == "")
      {
        header("Location: profile.php");
        die;
      }else
      {
        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
        echo "<br>The following errors occured:<br><br>";
        echo $result;
        echo "</div>";
      }

}


 }

 //collect posts

 $post = new Post();
 $id = $user_data['userid'];
 $posts = $post->get_posts($id);


//collect friends
$user = new User();
$id = $user_data['userid'];
$friends = $user->get_following($user_data['userid'], "user");

$image_class = new Image();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile | MyBook</title>
</head>
<style type="text/css">
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }



    #textbox {
        width: 100%;
        height: 36px;
        border-radius: 18px;
        border: 1px solid #dddfe2;
        padding: 8px 16px;
        font-size: 14px;
        margin: 10px 0;
    }



    #profile_pic {
        width: 150px;
        margin-top: -75px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
    }

    #menu_buttons {
        display: inline-block;
        padding: 10px 20px;
        margin: 2px;
        color: #555;
        text-align: center;
        background-color: #fff;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    #menu_buttons:hover {
        background-color: #e4e6eb;
        color: #333;
    }

    #friends_img {
        width: 75px;
        height: 75px;
        float: left;
        margin: 8px;
        border-radius: 50%;
        object-fit: cover;
    }

    #friends_bar {
        background-color: #fff;
        min-height: 400px;
        margin-top: 20px;
        color: #606770;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    #friends {
        clear: both;
        font-size: 14px;
        font-weight: bold;
        color: #1c1e21;
    }

    textarea {
        width: 100%;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        height: 80px;
        border: 1px solid #dddfe2;
        border-radius: 8px;
        padding: 10px;
        resize: none;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    textarea:focus {
        outline: none;
        border-color: #1877F2;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    #post_button {
        float: right;
        background-color: #1877F2;
        border: none;
        color: white;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #post_button:hover {
        background-color: #166fe5;
    }

    #post_bar {
        margin-top: 20px;
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }


</style>

<body style="font-family: tahoma; background-color: #d0d8e4;">

<?php

include("header.php");
?>



 <!--Cover Area-->

<div style="width: 800px;margin: auto;min-height: 400px;">
<div style="background-color: white;text-align: center;color: #405d9b;">

<?php

$image = "images/mountain.jpg";
if(file_exists($user_data['cover_image']))
{
    $image = $image_class->get_thumb_cover( $user_data['cover_image']);
}


  ?>

  <img src="<?php echo $image ?>" style="width: 100%;">


  <?php

$image = "";
if(file_exists($user_data['profile_image']))
{
    $image = $image_class->get_thumb_profile($user_data['profile_image']);
} else
{

  $image = "images/user_male.jpg";

  if($user_data['gender'] == "Female")
  {

      $image = "images/user_female.jpg";

  }

}


?>
  <span style="font-size:11px;"><img src="<?php echo $image ?>" id="profile_pic"><br>

  <?php
if ($user_data['userid'] == $_SESSION['mybook_userid']) {
    echo '<a href="change_profile_image.php?change=profile" style="text-decoration:none;color:#f0f;">Change Profile Image</a> |
    <a href="change_profile_image.php?change=cover" style="text-decoration:none;color:#f0f;">Change Cover</a>';
} else {
    echo '';
}
?>


</span><br>
  <div style="font-size: 20px;">
<a href="profile.php?id=<?php echo $user_data['userid'] ?>">
    <?php echo $user_data['first_name'] . " " . $user_data['last_name']  ?>
</a>
    <br>




<?php
// Display the number of followers independently
$mylikes = "";
if ($user_data['likes'] > 0) {
    $mylikes = "(" . $user_data['likes'] . " Followers)";
}
?>

<div style="font-size: 20px;">
    <a href="profile.php?id=<?php echo $user_data['userid'] ?>">
    </a>
    <br>
    <!-- Always display the follower count -->
    <span style="font-size: 14px; color: #606770;"><?php echo $mylikes; ?></span>
    <br><br>

    <?php
    // Conditionally display the follow button
    if ($user_data['userid'] != $_SESSION['mybook_userid']) {
        echo '<a href="like.php?type=user&id=' . $user_data['userid'] . '">
        <input id="post_button" type="button" value="Follow" style="margin-right: 10px; background-color:#9b409a; width: auto; min-width: 50px; cursor: pointer;">
        </a>';
    }
    ?>
</div>



</div><br><br>



<a href="timeline.php?id=<?php echo $user_data['userid']; ?>"><div id="menu_buttons">Timeline</div></a>
<a href="profile.php?section=about&id=<?php echo $user_data['userid']; ?>"><div id="menu_buttons">About</div></a>
<a href="profile.php?section=followers&id=<?php echo $user_data['userid']; ?>"><div id="menu_buttons">Followers</div></a>
<a href="profile.php?section=following&id=<?php echo $user_data['userid']; ?>"><div id="menu_buttons">Following</div></a>
<a href="profile.php?section=photos&id=<?php echo $user_data['userid']; ?>"><div id="menu_buttons">Photos</div></a>

<?php 
if($user_data['userid'] == $_SESSION['mybook_userid']){
    echo '<a href="profile.php?section=settings&id=' .$user_data['userid']. '"><div id="menu_buttons">Setting</div></a>';  
}
?>

  </div>
 
    <!--Below Cover Area-->

    <?php

$section = "default";

if(isset($_GET['section'])){
  $section = $_GET['section'];

}

if($section == "default"){
  include("profile_content_default.php");

}elseif($section == "photos"){
  include("profile_content_photos.php");

}elseif($section == "followers"){
  include("followers.php");

}elseif($section == "following"){
  include("following.php");

}elseif($section == "about"){
  include("profile_content_about.php");

}elseif($section == "settings"){
  include("profile_content_settings.php");

}

    ?>

</div>
</body>
</html>
