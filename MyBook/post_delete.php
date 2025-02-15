
<div id="post">
<div>
  <?php

  $image = "images/user_male.jpg";

  if($row_user['gender'] == "Female")
  {

      $image = "images/user_female.jpg";

  }

  if(file_exists($row_user['profile_image']))
  {

     $image_class = new Image();
      $image = $image_class->get_thumb_profile($row_user['profile_image']);
    

  }

?>
<img src="<?php echo $image ?>" style="width: 75px;margin-right: 4px; border-radius: 50%;">
</div>
<div style="width:100%;">
  <div style="font-weight: bold;color: #405d9b;">

    <?php

     echo htmlspecialchars($row_user['first_name']) . " " . htmlspecialchars($row_user['last_name']);

     if($row['is_profile_image'])
{
  $pronoun = "his";
  if($row_user['gender'] == "Female")
  {
    $pronoun = "her";
  }
  echo "<span style='font-weight:normal; color:#aaa;'> updated $pronoun profile image </span>";

} 


if($row['is_cover_image']){
  $pronoun = "his";
  if($row_user['gender'] == "Female")
  {
    $pronoun = "her";
  }
  echo "<span style='font-weight:normal; color:#aaa;'> updated $pronoun cover image </span>";

}

?>

</div>
  
<?php echo htmlspecialchars($row['post']) ?>
<br><br>


<?php
if (file_exists($row['image'])) {
    $post_image = $image_class->get_thumb_post($row['image']);
    echo "<img src='$post_image' style='width: 100%;' />";
}
?>




</div>
</div>

