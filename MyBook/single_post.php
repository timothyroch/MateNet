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
       header("Location: single_post.php?id=$_GET[id]");
       die;
     }else
     {
       echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
       echo "<br>The following errors occured:<br><br>";
       echo $result;
       echo "</div>";
     }




}

$Post = new Post();
$error = "";
$row = false;
if(isset($_GET['id'])){
  
 

$row = $Post->get_one_post($_GET['id']);

}else{
  $error = "No post was found";
}






?>
<!DOCTYPE html>
<html>
<head>
  <title>Comment | MyBook</title>
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


<?php

?>

  <!--Below Cover Area-->
        <div style="display: flex;">

          </div>
        
          <!--Posts Area-->
          <div style=" min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">
          <div style="border: solid thin #aaa; padding: 10px;background-color: white;">
<?php 

$user = new User();
$image_class = new Image();

if(is_array($row)){

  $row_user = $user->get_user($row['userid']);
  include("post.php");

}

  ?>
  <br style="clear:both;">

<!--Posts Area-->
<div style="border: solid thin #aaa; padding: 10px;background-color: white;">
<form method="post" enctype="multipart/form-data"> 
<textarea name="post" placeholder="Post a comment">
</textarea>
<input type="hidden" name="parent" value="<?php echo $row['postid'] ?>">

<input type="file" name="file">

<input type="submit" id="post_button" value="Post"><br style="clear:both;">

</form>
</div>

<?php 

$comments = $Post->get_comments($row['postid']);

if(is_array($comments)){

  foreach($comments as $comment){
    include("comment.php");
  }
}

?>

          </div>

          </div>
        </div>

</div>
</body>
</html>