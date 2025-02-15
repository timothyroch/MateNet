<!--Top Bar-->

<?php 
$corner_image="images/user_male.jpg";

if(isset($USER)){

 if(file_exists($USER['profile_image']))
  {
    $image_class = new Image();

  $corner_image = $image_class->get_thumb_profile($USER['profile_image']);
  }else{
if($USER['gender'] == "Female"){
  $corner_image = "images/user_female.jpg";
}
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }
    #blue_bar {
      height: 60px;
      background-color: #4a154b; /* Deep purple for a rich look */
      color: white;
      display: flex;
      align-items: center;
      padding: 0 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
    }
    #search_box {
      width: 300px;
      height: 36px;
      border-radius: 18px;
      border: 1px solid #dddfe2;
      padding: 8px 16px;
      font-size: 14px;
      background-image: url(images/search.png);
      background-repeat: no-repeat;
      background-position: 95%;
      background-size: 20px;
      transition: all 0.2s ease;
    }
    #search_box:focus {
      width: 350px;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
      border-color: #007bff;
    }
    .header-logo {
      font-size: 24px;
      font-weight: bold;
      color: #d9dfeb;
      text-decoration: none;
      margin-right: auto;
      margin-left: 20px;
    }
    .header-logo:hover {
      color: #e0e0e0;
    }
    .header-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-left: 20px;
    }
    .logout-link {
      font-size: 14px;
      color: white;
      margin-left: 20px;
      text-decoration: none;
      transition: color 0.2s ease;
    }
    .logout-link:hover {
      color: #e0e0e0;
    }
  </style>
</head>
<body>
  <div id="blue_bar">
    <form method="get" action="search.php" style="display: flex; align-items: center; width: 100%;">
      <a href="timeline.php" class="header-logo">MateNet</a>
      <input type="text" id="search_box" placeholder="Search for people" name="find">
      <a href="profile.php">
        <img src="<?php echo $corner_image; ?>" class="header-icon" alt="Profile Image">
      </a>
      <a href="logout.php" class="logout-link">Logout</a>
    </form>
  </div>
</body>
</html>

