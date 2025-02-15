
<div id="friends">
<?php
$image = "images/user_male.jpg";

if($friend_row['gender'] == "Female")
{

    $image = "images/user_female.jpg";

}
if(file_exists($friend_row['profile_image']))
{

    $image = $image_class->get_thumb_profile($friend_row['profile_image']);
  

}

?>
    
      <a href="profile.php?id=<?php echo $friend_row['userid']; ?>">
              <img src=<?php echo $image ?> id="friends_img"><br>
              <?php echo $friend_row['first_name'] . " " . $friend_row['last_name']  ?>
</a>
            </div>

          
      