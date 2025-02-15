  <div style="display: flex;">



<!--Friends Area-->



<div style=" min-height: 400px;flex: 1;">

  <div id="friends_bar">
    Following<br>

    <?php

if($friends)
{

foreach ($friends as $friend) {

  $friend_row = $user->get_user($friend['userid']);
include("user.php");
}

}

?>

 
</div>
</div>





<!--Posts Area-->
<div style="min-height: 400px; flex: 2.5; padding: 20px; padding-right: 0px;">
        <div style="border: solid thin #aaa; padding: 10px; background-color: white;">
<?php
if ($user_data['userid'] == $_SESSION['mybook_userid']) {
    echo '
            <form method="post" enctype="multipart/form-data"> 
                <textarea name="post" placeholder="What\'s on your mind?"></textarea>
                <input type="file" name="file">
                <input type="submit" id="post_button" value="Post"><br style="clear:both;">
            </form>
        </div>
        <!--Posts-->
        <div id="post_bar">';
} else {
    echo '';
}
?>




<?php

if($posts)
{

foreach ($posts as $row) {

$user = new User();
$row_user = $user->get_user($row['userid']);

include("post.php");
}

}

  ?>
  




 
  </div>
</div>
</div>
</div>