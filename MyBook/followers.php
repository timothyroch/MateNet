<div style=" min-height: 400px;padding-right: 0px;width:;background-color: white;text-align:center;">
<div style="padding:20px;">
<?php



  $image_class = new Image();
  $post_class = new Post();
  $user_class = new User();
  $followers = $post_class->get_likes($user_data['userid'], "user");
  if(is_array($followers)){

    foreach($followers as $follower){

      $friend_row = $user_class->get_user($follower['userid']);
include("user.php");
    }


  }else{

    echo "No followers were found!";
  }


?>
</div>