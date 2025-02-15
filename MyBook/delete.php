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


if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "delete.php")) {
  $_SESSION['return_to'] = $_SERVER['HTTP_REFERER'];
} 




$error = "";

if(isset($_GET['id'])){
 
$row = $Post->get_one_post($_GET['id']);

if(!$row){

  $error = "No such post was found";

} else{

  if($row['userid'] != $_SESSION['mybook_userid']){
    $error = "You cannot delete this post!";
  }
}

}else{
  $error = "No such post was found";
}


//if something was posted
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['postid'])){

  $Post->delete_post($_POST['postid']);
  header("Location: " . $_SESSION['return_to']);
  //die;
}



?>
<!DOCTYPE html>
<html>
<head>
  <title>Delete | MyBook</title>
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


<form method="post" action="">


<?php 

if($error != ""){
  echo $error;
}else{



if($row){

  echo"Are you sure you want to delete this post?<br><br>";
  $user = new User();
  $row_user = $user->get_user($row['userid']);
  
  
  include("post_delete.php");
  echo "<input name='postid' type='hidden' value='{$row['postid']}'>";

echo "<input type='submit' id='post_button' value='Delete'><br>";
}
}
  ?>





</form>
          </div>

          </div>
        </div>

</div>
</body>
</html>