<?php



class Database
{

private $host = "sql203.infinityfree.com";
private $username = "if0_37076343";
private $password = "l0T9AWwhyg";
private $db = "if0_37076343_mybook_db";


  function connect()
  {
    $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
    return $connection;
  }
  
  function read($query)
  {
   $conn = $this->connect();
   $result = mysqli_query($conn, $query);

    if(!$result)
    {
      return false;
    }else
    {
      $data = false;
      while($row = mysqli_fetch_assoc($result))
{
  $data[] = $row;

}
return $data;
    }
  }
  
  function save($query)
  {
    $conn = $this->connect();
    $result = mysqli_query($conn, $query);

    if(!$result)
    {
      return false;
    }else
    {
      return true;
    }
  }
}
?>








