<div style=" min-height: 400px;padding-right: 0px;width:;background-color: white;text-align:center;">
<div style="padding:20px;max-width:350px;display:inline-block;">


<form method="post" enctype="multipart/form-data"> 


<?php


$settings_class = new Settings();
$settings = $settings_class->get_settings($_GET['id']);

if(is_array($settings)){

  
  echo "About me:<br>
  <div name='about' style='height:200px;border:none;' id ='textbox'>" .htmlspecialchars($settings['about']). "</div>";
  

}




 

?>
</form>

</div>
</div>