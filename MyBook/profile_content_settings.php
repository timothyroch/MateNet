<div style=" min-height: 400px;padding-right: 0px;width:;background-color: white;text-align:center;">
<div style="padding:20px;max-width:350px;display:inline-block;">


<form method="post" enctype="multipart/form-data"> 


<?php


$settings_class = new Settings();
$settings = $settings_class->get_settings($_SESSION['mybook_userid']);

if(is_array($settings)){

  echo "<input type='text' id ='textbox' name='first_name' placeholder='First Name' value='" .htmlspecialchars($settings['first_name']). "'/>";
  echo "<input type='text' id ='textbox' name='last_name' placeholder='Last Name' value='" .htmlspecialchars($settings['last_name']). "'/>";
  
  echo "<select id ='textbox' name='gender' style='height:30px;' >
  
    <option>" .htmlspecialchars($settings['gender']). "</option>
  <option>Male</option>
  <option>Female</option>
  
  </select>";
  
  echo "<input type='text' id ='textbox' name='email' placeholder='Email' value='" .htmlspecialchars($settings['email']). "'/>";
  echo "<input type='password' id ='textbox' name='password' value='" .htmlspecialchars($settings['password']). "' placeholder='Pasword'/>";
  echo "<input type='password' id ='textbox' name='password2' value='" .htmlspecialchars($settings['password']). "' placeholder='Password'/>";
  echo "About me:<br>
  <textarea name='about' style='height:200px;' id ='textbox'>" .htmlspecialchars($settings['about']). "</textarea>";
  echo "<input type='submit' id='post_button' value='Save'><br>";
  

}




 

?>
</form>

</div>
</div>