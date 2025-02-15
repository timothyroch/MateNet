<?php

class Image
{

  public function generate_filename($length)
  {

    $array = array(0, 1, 1, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',);

$text = "";

for ($x = 0; $x < $length; $x++) {
  $random = rand(0, 61);
  $text .= $array[$random];
}

return $text;

  }



  public function crop_image($original_file_name, $cropped_file_name, $max_width, $max_height)
{
    if (file_exists($original_file_name)) {
        $original_image = imagecreatefromjpeg($original_file_name);
        if (!$original_image) {
            return false;
        }
        
        $original_width = imagesx($original_image);
        $original_height = imagesy($original_image);
        
        $aspect_ratio = $original_width / $original_height;
        $new_aspect_ratio = $max_width / $max_height;
        
        if ($aspect_ratio > $new_aspect_ratio) {
            // Wider than target aspect ratio
            $new_height = $max_height;
            $new_width = $original_width / ($original_height / $max_height);
        } else {
            // Taller than target aspect ratio
            $new_width = $max_width;
            $new_height = $original_height / ($original_width / $max_width);
        }
        
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        
        // Center the crop
        $x = ($new_width - $max_width) / 2;
        $y = ($new_height - $max_height) / 2;
        
        $final_image = imagecreatetruecolor($max_width, $max_height);
        imagecopyresampled($final_image, $new_image, 0, 0, $x, $y, $max_width, $max_height, $max_width, $max_height);
        
        imagejpeg($final_image, $cropped_file_name, 90);
        
        imagedestroy($original_image);
        imagedestroy($new_image);
        imagedestroy($final_image);
        
        return true;
    }
    return false;
}

  




//resize the image
  public function resize_image($original_file_name,$resized_file_name,$max_width,$max_height)
  {

    if(file_exists($original_file_name))
    {

$original_image = imagecreatefromjpeg($original_file_name);

$original_width = imagesx($original_image);
$original_height = imagesy($original_image);

if($original_height > $original_width)
{
  //make width equal to the max width`
  $ratio = $max_width / $original_width;

  $new_width = $max_width;
  $new_height = $original_height * $ratio;
}else
{
  //make width equal to the max width
  $ratio = $max_height / $original_height;

  $new_height = $max_height;
  $new_width = $original_width * $ratio;

}

    }

    //adjust in case max width and height are different
if($max_width != $max_height)
{
  if($max_height > $max_width)
  {
if($max_height > $new_height)
{
  $adjustment = ($max_height / $new_height);

}else
{
  $adjustment = ($new_height / $max_height);
}

$new_width = $new_width * $adjustment;
$new_height = $new_height * $adjustment;
  }else
  {
    if($max_width > $new_width)
    {
      $adjustment = ($max_width / $new_width);
    
    }else
    {
      $adjustment = ($new_width / $max_width);
    }
    
    $new_width = $new_width * $adjustment;
    $new_height = $new_height * $adjustment;
  }
}

    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

    imagedestroy($original_image);
   
  
    imagejpeg($new_image,$resized_file_name,90);
imagedestroy($new_image);
  }

  // create thumbnail for cover image
  public function get_thumb_cover($filename)
  {

    $thumbnail = $filename . "_cover_thumb.jpg";
    if(file_exists($thumbnail))
    {
      return $thumbnail;
    }
    $this->crop_image($filename, $thumbnail, 1366, 488);

    if(file_exists($thumbnail))
    {
      return $thumbnail;
    } else{
      return $filename;
    }

  }

   // create thumbnail for profile image
  public function get_thumb_profile($filename)
  {

    $thumbnail = $filename . "_profile_thumb.jpg";
    if(file_exists($thumbnail))
    {
      return $thumbnail;
    }
    $this->crop_image($filename, $thumbnail, 600, 600);

    if(file_exists($thumbnail))
    {
      return $thumbnail;
    } else{
      return $filename;
    }

  }

     // create thumbnail for post

  public function get_thumb_post($filename)
  {

    $thumbnail = $filename . "_post_thumb.jpg";
    if(file_exists($thumbnail))
    {
      return $thumbnail;
    }
    $this->crop_image($filename, $thumbnail, 600, 600);

    if(file_exists($thumbnail))
    {
      return $thumbnail;
    } else{
      return $filename;
    }

  }
}
