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


   $post = new Post();
   $id = $_SESSION['mybook_userid'];
   
   $result = $post->create_post($id, $_POST, $_FILES);

       if($result == "")
       {
         header("Location: timeline.php");
         die;
       }else
       {
         echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
         echo "<br>The following errors occured:<br><br>";
         echo $result;
         echo "</div>";
       }

}

$image_class = new Image();

 //collect posts

 $post = new Post();
 $id = $user_data['userid'];
 $posts = $post->get_posts($id);


//collect friends
$user = new User();
$id = $user_data['userid'];
$friends = $user->get_friends($id);


?>
<!DOCTYPE html>
<html>
<head>
  <title>Profile | Timeline</title>
</head>
<style type="text/css">
#blue_bar{
  height: 50px;
  background-color: #405d9b;
  color: #d9dfeb;
}
#search_box{
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
#profile_pic{
width: 150px;
border-radius: 50%;
border: solid 2px white;
}
#menu_buttons{
  display: inline-block;
  width: 100px;
  margin: 2px;
}
#friends_img{
  width: 75px;
  float: left;
  margin: 8px;
}
#friends_bar{
  min-height: 400px;
  margin-top: 20px;
  padding: 8px;
  text-align: center;
  font-size: 20px;
  color: #405d9b;
}
#friends{
  clear: both;
  font-size: 12px;
  font-weight: bold;
  color: #405d9b;
}
textarea{
width: 100%;
font-family: tahoma;
font-size: 14px;
height: 60px;
border: none;
}
textarea:focus{
  outline: none;
}
#post_button{
  float: right;
  background-color: #405d9b;
  border: none;
  color: white;
  padding: 4px;
  font-size: 14px;
  border-radius: 2px;
  width: 50px;
}
#post_bar{
  margin-top: 20px;
  background-color: white;
  padding: 10px;
}
#post{
  padding: 4px;
  font-size: 13px;
  display: flex;
  margin-bottom: 20px;
}
</style>
<body style="font-family: tahoma; background-color: #d0d8e4;">
<?php

include("header.php");

?>


  <!--Cover Area-->
<div style="width: 800px;margin: auto;min-height: 400px;">

  <!--Below Cover Area-->
        <div style="display: flex;">
          <!--Friends Area-->
          <div style=" min-height: 400px;flex: 1;">
            <div id="friends_bar">   

                       

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
  <span style=""><img src="<?php echo $image ?>" id="profile_pic"><br>


                       <a href="profile.php" style="color:#405d9b;text-decoration:none;"> <?php echo $user_data['first_name'] . " " . $user_data['last_name']  ?> </a>
            </div>
          </div>
        
          <!--Posts Area-->
          <div style=" min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">
          <div style="border: solid thin #aaa; padding: 10px;background-color: white;">
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
          <!--Posts-->
          <div id="post_bar">
        
          <?php

$DB = new Database();
$user_class = new User();
$image_class = new Image();
$followers = $user_class->get_following($_SESSION['mybook_userid'], "user");
$follower_ids = false;
if(is_array($followers)){

  $follower_ids =array_column($followers, "userid");
  $follower_ids = implode("','", $follower_ids);


}

if($follower_ids){

  if($user_data['userid'] == $_SESSION['mybook_userid']){
    $myuserid = $_SESSION['mybook_userid'];
    $sql = "select * from posts where parent = 0 and userid = '$myuserid' || parent = 0 and userid in('" .$follower_ids. "') order by id desc limit 30";
    $posts = $DB->read($sql);
  
  }else{
    $myuserid = $user_data['userid'];
    $sql = "SELECT * FROM posts WHERE parent = 0 AND userid = '$myuserid' ORDER BY date DESC LIMIT 30";
    $posts = $DB->read($sql);


  }


}

if(isset($posts) && $posts)
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
        </div>

</div>
</body>
</html>