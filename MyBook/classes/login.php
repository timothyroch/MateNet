<?php

class Login 
{

  private $error = "";

  public function evaluate($data)
  {

    $email = addslashes($data['email']);
    $password = addslashes($data['password']);

    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1"; 

    $DB = new Database();
    $result = $DB->read($query);

    if ($result)
    {
        $row = $result[0];

        if($this->hash_text($password) == $row['password'])
        {
            //create a session data
            $_SESSION['mybook_userid'] = $row['userid'];
        }
        else
        {
            $this->error .= "Wrong email or password<br>";
        }
    }
    else
    {
        $this->error .= "Wrong email or password<br>";
    }

    return $this->error;
  }


private function hash_text($text){

  $text = hash("sha1", $text);
  return $text;

}




  public function check_login($id)
  {

    if (is_numeric($id)) 
    {

    $query = "SELECT * FROM users WHERE userid = '$id' LIMIT 1";
    $DB = new Database();
    $result = $DB->read($query);

    if($result)
    {

      $user_data = $result[0];
        return $user_data;

    }else {
      header("Location: login.php");
      die;
  }


  
} else {
  header("Location: login.php");
  die;
}
  }







  

}
