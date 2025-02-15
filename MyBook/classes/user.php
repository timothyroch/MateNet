<?php

class User 
{

public function get_data($id)
{

$query = "select * from users where userid = '$id' limit 1";

$DB = new Database();
$result = $DB->read($query);

if($result)
{

  $row = $result[0];
  return $row;

}else{
    return false;
}

}

public function get_user($id)
{

  $query = "select * from users where userid = '$id' limit 1";
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



public function get_friends($id)
{

  $query = "select * from users where userid != '$id' ";
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






public function get_following($id, $type) {
  $DB = new Database();
  $type = addsLashes($type);

  if (is_numeric($id)) {
      $sql = "SELECT following FROM likes WHERE type='$type' AND contentid = '$id' LIMIT 1";
      $result = $DB->read($sql);

      if (is_array($result) && !empty($result)) {
          $following = json_decode($result[0]['following'], true);
          return is_array($following) ? $following : [];
      }
  }
  return [];
}






public function follow_user($id, $type, $mybook_userid) { 
  $DB = new Database(); 
  $type = addslashes($type);

  // Fetch the existing follow data
  $sql = "SELECT following FROM likes WHERE type='$type' AND contentid = '$mybook_userid' LIMIT 1"; 
  $result = $DB->read($sql); 

  if (is_array($result) && !empty($result)) { 
      $following = json_decode($result[0]['following'], true); 

      // Ensure $following is an array
      if (!is_array($following)) {
          $following = [];
      }

      $user_ids = array_column($following, "userid"); 

      if (!in_array($id, $user_ids)) { 
          // Follow the user
          $arr = ["userid" => $id, "date" => date("Y-m-d H:i:s")]; 
          $following[] = $arr; 
          $following_string = json_encode($following); 

          $sql = "UPDATE likes SET following = '$following_string' WHERE type='$type' AND contentid = '$mybook_userid' LIMIT 1"; 
          $DB->save($sql); 

      } else { 
          $key = array_search($id, $user_ids);
          if ($key !== false) { 
              unset($following[$key]); 
              $following = array_values($following);

              if (empty($following)) { 
                  // If the following list is empty, remove the entire row
                  $sql = "DELETE FROM likes WHERE type='$type' AND contentid = '$mybook_userid' LIMIT 1"; 
              } else {
                  // Otherwise, update the row with the new following list
                  $following_string = json_encode(array_values($following)); 
                  $sql = "UPDATE likes SET following = '$following_string' WHERE type='$type' AND contentid = '$mybook_userid' LIMIT 1";
              }
              $DB->save($sql);
          }
      }
  } else {
      // No existing record, so create a new one for following
      $arr = ["userid" => $id, "date" => date("Y-m-d H:i:s")];
      $following = json_encode([$arr]);
      $sql = "INSERT INTO likes (type, contentid, following) VALUES ('$type', '$mybook_userid', '$following')";
      $DB->save($sql);
  }
}






}




