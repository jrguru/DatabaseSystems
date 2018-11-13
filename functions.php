<?php //functions.php
  $dbhost  = '127.0.0.1';    // Unlikely to require changing
  $dbname  = 'ucf';   // Modify these...
  $dbuser  = 'root';   // ...variables according
  $dbpass  = 'password';   // ...to your installation

  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if ($connection->connect_error) die("Fatal Error");

  
  function createDatabase($name){
	  queryMysql("CREATE DATABASE $name");
	  queryMysql("USE $name");
  }
  function createTable($name, $query)
  {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
  }
  
  //create the foreign keys for our tables
  function alterTable($name, $foreign_key, $primary_table, $primary_key )
  {
	  queryMysql("ALTER TABLE $name ADD FOREIGN KEY ($foreign_key) REFERENCES $primary_table($primary_key) ON DELETE RESTRICT ON UPDATE CASCADE");
	  echo "Table '$name' altered.<br>";
  }

  
	
	
  function queryMysql($query)
  {
    global $connection;
    $result = $connection->query($query);
	//echo "$result";
    if (!$result) die("Fatal Error...");
    return $result;
  }
  function queryMysqlnodie($query)
  {
    global $connection;
    $result = $connection->query($query);
	//echo "$result";
    
    return $result;
  }

  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    if (get_magic_quotes_gpc())
      $var = stripslashes($var);
    return $connection->real_escape_string($var);
  }

  function showProfile($user)
  {
    if (file_exists("$user.jpg"))
      echo "<img src='$user.jpg' style='float:left;'>";

    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

    if ($result->num_rows)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
    else echo "<p>Nothing to see here, yet</p><br>";
  }
?>
