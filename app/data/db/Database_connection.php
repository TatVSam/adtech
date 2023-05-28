<?php
class Database_connection
{
	function connect()
	{
		
       /* $database_1 = 'system';
        $sql = "CREATE DATABASE  IF NOT EXISTS $database_1";
        $db->exec($sql) ? print("Created $table Table.\n") : false;*/

        /*$table = 'users';

        try {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "CREATE TABLE IF NOT EXISTS $table(
                id INTEGER PRIMAY KEY,
                username VARCHAR( 50 ) NOT NULL,
                email VARCHAR( 250 ) NOT NULL,
                password VARCHAR( 150 ) NOT NULL,
                token VARCHAR( 150 ) NOT NULL,
                created VARCHAR( 150 ) NOT NULL);";

                $db->exec($sql) ? print("Created $table Table.\n") : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }*/
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