<?php

class Post 
{
private  $error = "";
  public function create_post($userid, $data, $files)
  {

    if(!empty($data['post']) || !empty($files['file']['name']) || isset($data['is_profile_image']) || isset($data['is_cover_image']))
    {

        $myimage = "";
        $has_image = 0;
        $is_cover_image = 0;
        $is_profile_image = 0;

        if(isset($data['is_profile_image']) || isset($data['is_cover_image']))
        {

          $myimage = $files;
          $has_image = 1;
          if(isset($data['is_cover_image']))
          {
            $is_cover_image = 1;

          }
          if(isset($data['is_profile_image']))
          {
            $is_profile_image = 1;

          }


        }else {

        
          if(!empty($files['file']['name']))
          {
  
  
            $folder = "uploads/" . $userid . "/";
  
            //create folder
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true); //file permission
                file_put_contents($folder, "index.php", "");
            }

  
            $image_class = new Image();
            $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
            move_uploaded_file($_FILES['file']['tmp_name'], $myimage);
  
            $image_class->resize_image($myimage, $myimage, 1500, 1500);
  
            $has_image = 1;

        }
      }

        $post = "";
      if(isset($data['post']))
      {
        $post = addsLashes($data['post']);
      }

        $postid = $this->create_postid();
        $parent = 0;
        $DB = new Database();

        if(isset($data['parent']) && is_numeric($data['parent'])){
          $parent = $data['parent'];

          $sql = "update posts set comments = comments + 1 where postid = '$parent' limit 1";
          $DB->save($sql);

      }
      

        
        $query = "insert into posts (userid,postid,post,image,has_image,is_profile_image,is_cover_image,parent) values ('$userid','$postid','$post', '$myimage', '$has_image', '$is_profile_image', '$is_cover_image', '$parent')";

        $DB->save($query);

    }else
    {

      $this->error .= "Please type something to post!<br>";


    }

      return $this->error;

  }







  public function edit_post($data, $files)
  {

    if(!empty($data['post']) || !empty($files['file']['name']))
    {

        $myimage = "";
        $has_image = 0;

         

        
          if(!empty($files['file']['name']))
          {
  
  
            $folder = "uploads/" . $userid . "/";
  
            //create folder
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true); //file permission
                file_put_contents($folder, "index.php", "");
            }

  
            $image_class = new Image();
            $myimage = $folder . $image_class->generate_filename(15) . ".jpg";
            move_uploaded_file($_FILES['file']['tmp_name'], $myimage);
  
            $image_class->resize_image($myimage, $myimage, 1500, 1500);
  
            $has_image = 1;

        
      }

        $post = "";
      if(isset($data['post']))
      {
        $post = addsLashes($data['post']);
      }

        $postid = addsLashes($data['postid']);
        
        if($has_image){
          $query = "update posts set post = '$post', image = '$myimage' where postid = '$postid' limit 1";

        }else{
          $query = "update posts set post = '$post' where postid = '$postid' limit 1";

        }

        $DB = new Database();
        $DB->save($query);

    }else
    {

      $this->error .= "Please type something to post!<br>";


    }

      return $this->error;

  }




  public function get_posts($id)
  {



    $query = "select * from posts where parent = 0 and userid = '$id' order by id desc limit 10";

    $DB = new Database();
   $result = $DB->read($query);

   if($result)
   {

return $result;

   }else
   {
    return false;
   }

  }

  public function get_comments($id)
  {



    $query = "select * from posts where parent = '$id' order by id asc limit 10";

    $DB = new Database();
   $result = $DB->read($query);

   if($result)
   {

return $result;

   }else
   {
    return false;
   }

  }



  public function get_one_post($postid)
  {

if(!is_numeric($postid))
{
  return false;
}

    $query = "select * from posts where postid = '$postid' limit 1";

    $DB = new Database();
   $result = $DB->read($query);

   if($result)
   {

return $result[0];

   }else
   {
    return false;
   }

  }

  public function delete_post($postid)
  {

      if(!is_numeric($postid)) {
          return false;
      }

      $DB = new Database();
      $sql = "select parent from posts where postid = '$postid' limit 1";
      $result = $DB->read($sql);

      if(is_array($result)){

        if($result[0]['parent'] > 0){
          $parent = $result[0]['parent'];
  
          $sql = "update posts set comments = comments - 1 where postid = '$parent' limit 1";
          $DB->save($sql);
  
      }

      }
 
  
   
      $query = "DELETE FROM posts WHERE postid = '$postid' LIMIT 1";
  
      $result = $DB->save($query);
  }



  public function i_own_post($postid, $mybook_userid)
  {

      if(!is_numeric($postid)) {
          return false;
      }
  
      $DB = new Database();
   
    $query = "select * from posts where postid = '$postid' limit 1";
  
      $result = $DB->read($query);

      if(is_array($result)){

if($result[0]['userid'] == $mybook_userid){
  return true;
}
      }
      return false;
  }

  

  public function get_likes($id, $type){

    $DB = new Database();
    $type = addsLashes($type);

    if (is_numeric($id)) {

       // get like details
       $sql = "SELECT likes FROM likes WHERE type='$type' && contentid = '$id' LIMIT 1";
       $result = $DB->read($sql);

       if (is_array($result)) {

           // Decode the JSON likes data
           $likes = json_decode($result[0]['likes'], true);
           return $likes;
    }
  }
  return false;
  }

  public function like_post($id, $type, $mybook_userid) {
    $DB = new Database();

    // Retrieve likes details
    $sql = "SELECT likes FROM likes WHERE type='$type' AND contentid = '$id' LIMIT 1";
    $result = $DB->read($sql);

    if (is_array($result) && !empty($result)) {
        // Decode the JSON likes data
        $likes = json_decode($result[0]['likes'], true);

        if (!is_array($likes)) {
            $likes = [];
        }

        $user_ids = array_column($likes, "userid");

        if (!in_array($mybook_userid, $user_ids)) {
            // User hasn't liked the post yet
            $arr = [
                "userid" => $mybook_userid,
                "date" => date("Y-m-d H:i:s")
            ];

            $likes[] = $arr;
            $likes_string = json_encode($likes);

            // Update the likes in the database
            $sql = "UPDATE likes SET likes = '$likes_string' WHERE type='$type' AND contentid = '$id' LIMIT 1";
            $DB->save($sql);

            // Increment the likes count in the specific table
            $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1";
            $DB->save($sql);

        } else {
            // User already liked the post, so unlike it
            $key = array_search($mybook_userid, $user_ids);
            if ($key !== false) {
                unset($likes[$key]);
                $likes = array_values($likes); // Re-index the array

                if (empty($likes)) {
                    // If there are no likes left, remove the record from the database
                    $sql = "DELETE FROM likes WHERE type='$type' AND contentid='$id' LIMIT 1";
                    $DB->save($sql);
                } else {
                    // Otherwise, update the likes
                    $likes_string = json_encode($likes);
                    $sql = "UPDATE likes SET likes = '$likes_string' WHERE type='$type' AND contentid='$id' LIMIT 1";
                    $DB->save($sql);
                }

                // Decrement the likes count, but ensure it doesn't go below 0
                $sql = "UPDATE {$type}s SET likes = GREATEST(likes - 1, 0) WHERE {$type}id = '$id' LIMIT 1";
                $DB->save($sql);
            }
        }

    } else {
        // No likes record exists, so create one
        $arr = [
            "userid" => $mybook_userid,
            "date" => date("Y-m-d H:i:s")
        ];
        $arr2[] = $arr;

        $likes = json_encode($arr2);

        // Insert the new like into the database
        $sql = "INSERT INTO likes (type, contentid, likes) VALUES ('$type', '$id', '$likes')";
        $DB->save($sql);

        // Increment the likes count in the specific table
        $sql = "UPDATE {$type}s SET likes = likes + 1 WHERE {$type}id = '$id' LIMIT 1";
        $DB->save($sql);
    }
}







  private function create_postid()
{
    $length = rand(4, 19);
    $number = "";
    for ($i=0; $i < $length; $i++) { 
      $new_rand = rand(0, 9);
      $number = $number . $new_rand;
      
    }
    return $number;
}



}