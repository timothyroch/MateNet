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




$Post = new Post();
$error = "";
$likes = false;
if(isset($_GET['id']) && isset($_GET['type'])){
 
$likes = $Post->get_likes($_GET['id'], $_GET['type']);

}else{
  $error = "No information was found";
}






?>
<!DOCTYPE html>
<html>
<head>
  <title>People who liked | MyBook</title>
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


<?php

?>

  <!--Below Cover Area-->
        <div style="display: flex;">

          </div>
        
          <!--Posts Area-->
          <div style=" min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">
          <div style="border: solid thin #aaa; padding: 10px;background-color: white;">
<?php 

$User = new User();
$image_class = new Image();

if(is_array($likes)){
  foreach($likes as $row) {

  $friend_row = $User->get_user($row['userid']);

include("user.php");

  }

}

  ?><br style="clear:both;">
          </div>

          </div>
        </div>

</div>
</body>
</html>