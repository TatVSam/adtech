<?php
class Database_connection
{
	function connect()
	{
		
    

 
      $servername = "localhost";
      $username = "root";
      $password = "root";

      try {
        $database_1 = 'adtech';
        $conn = new PDO("mysql:host=$servername", $username, $password);
  // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS `$database_1`";
  // use exec() because no results are returned
        $conn->exec($sql);
           //echo "Database created successfully<br>";
      } catch(PDOException $e) {
          echo $sql . "<br>" . $e->getMessage();
      }

      $conn = null;
      $connect = new PDO("mysql:host=localhost; dbname=adtech", "root", "root");

		return $connect;
	}
}