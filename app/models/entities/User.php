<?php
require 'app/core/config/config.php';
/*class User
{
    private $username;
    private $password;
    private $email;

    private $role;
    private $token;
    private $created;

    public function __contruct($entity = null)
    {
        $this->username = $entity->username;
        $this->password = $entity->username;
        $this->email = $entity->email;
     
        $this->token = $entity->token;
        $this->created = DateTime::createFromFormat('U', time());

        if (isset($entity->role)) {
            $this->role = $entity->role;
        } else {
            $this->role = 'user';
        }
    }
}*/

class User
{
	private $user_id;
	private $user_name;
	private $user_email;
	private $user_password;
	private $user_hash;
	//private $user_profile;
	private $user_status;
	private $user_created_on;
	//private $user_verification_code;
	//private $user_login_status;
	private $user_token;
	private $user_connection_id;
    private $user_role;
	public $connect;

	public function __construct()
	{
		require_once(DATA . 'db/Database_connection.php');

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setUserName($user_name)
	{
		$this->user_name = $user_name;
	}

	function getUserName()
	{
		return $this->user_name;
	}

	function setUserEmail($user_email)
	{
		$this->user_email = $user_email;
	}

	function getUserEmail()
	{
		return $this->user_email;
	}

	function setUserPassword($user_password)
	{
		$this->user_password = $user_password;
	}

	function getUserPassword()
	{
		return $this->user_password;
	}

	function setUserHash($user_hash)
	{
		$this->user_hash = $user_hash;
	}

	function getUserHash()
	{
		return $this->user_hash;
	}

    function setUserRole($user_role)
	{
		$this->user_role = $user_role;
	}

	function getUserRole()
	{
		return $this->user_role;
	}

	function setUserProfile($user_profile)
	{
		$this->user_profile = $user_profile;
	}

	function getUserProfile()
	{
		return $this->user_profile;
	}

	function setUserStatus($user_status)
	{
		$this->user_status = $user_status;
	}

	function getUserStatus()
	{
		return $this->user_status;
	}

	function setUserCreatedOn($user_created_on)
	{
		$this->user_created_on = $user_created_on;
	}

	function getUserCreatedOn()
	{
		return $this->user_created_on;
	}


	function setUserLoginStatus($user_login_status)
	{
		$this->user_login_status = $user_login_status;
	}

	function getUserLoginStatus()
	{
		return $this->user_login_status;
	}

	function setUserToken($user_token)
	{
		$this->user_token = $user_token;
	}

	function getUserToken()
	{
		return $this->user_token;
	}

	function get_user_data()
	{
		/*$query = "
		SELECT * FROM user_table 
		WHERE user_email = :user_email
		";*/

		$query = "
		SELECT * FROM users";

		$statement = $this->connect->query($query);
		$user_data = $statement->FetchAll(PDO::FETCH_ASSOC);

		//foreach($result as $user) {
		//	echo $user["name"]."<br>";
		//	}

		//$statement = $this->connect->prepare($query);

		/*$statement->bindParam(':user_email', $this->user_email);

		if($statement->execute())
		{
			$user_data = $statement->fetch(PDO::FETCH_ASSOC);
		}*/

		return $user_data;
	}

	function get_user_names()
	{
	

		$query = "
		SELECT user_name FROM users";

		$statement = $this->connect->query($query);
		$user_names = $statement->FetchAll(PDO::FETCH_ASSOC);
		$str = "";
		foreach ($user_names as $name) {
			$str = $str . $name["user_name"] . " ";
		}


		return $str;
	}

	function user_name_is_taken ($new_login) 
	{
		$answer = false;

		$query = "
		SELECT user_name FROM users";

		$statement = $this->connect->query($query);
		if ($statement) {
		$user_names = $statement->FetchAll(PDO::FETCH_ASSOC);
		foreach ($user_names as $name) {
			if ($name["user_name"] == $new_login)
				$answer = true;
		}
	
		return $answer;
	} else return 0;

	}

	function get_user_data_by_user_name()
	{
		$query = "
		SELECT * FROM users 
		WHERE user_name = :user_name
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		if($statement->execute())
		{
			$user_data = $statement->fetch(PDO::FETCH_ASSOC);
		}
		return $user_data;
	}

    function save_data()
	{
		$table = 'users';

        try {
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "CREATE TABLE IF NOT EXISTS users(
                `user_id` int(11) NOT NULL AUTO_INCREMENT,
                `user_name` varchar(50) NOT NULL,
                `user_email` varchar(250) NOT NULL,
                `user_password` varchar(150) NOT NULL,
				`user_hash` varchar(150),
				`user_role` varchar(20) NOT NULL,
				PRIMARY KEY(`user_id`))";

                $this->connect->exec($sql) ? print("Created $table Table.\n") : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = "
		INSERT INTO users (user_name, user_email, user_password, user_role) 
		VALUES (:user_name, :user_email, :user_password, :user_role)";
		
        $statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_password', $this->user_password);

		$statement->bindParam(':user_role', $this->user_role);


		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
	function save_hash() 
	{
		$query = "
		UPDATE users
		SET user_hash = :user_hash
		WHERE user_name = :user_name";
		
        $statement = $this->connect->prepare($query);

		$statement->bindParam(':user_hash', $this->user_hash);

		$statement->bindParam(':user_name', $this->user_name);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}


	}

	
	function is_authorized ($user_id, $user_hash)
	{
		$query = "
		SELECT * FROM users 
		WHERE user_id = :user_id
		";

		$statement = $this->connect->prepare($query);

		
		$statement->bindParam(':user_id', $user_id);

		if($statement->execute())
		{
			$user_data = $statement->fetch(PDO::FETCH_ASSOC);
		}

		if ($user_data["user_hash"] == $user_hash)
		{
			return $user_data;
		} else 
			return false;
	}


	function delete_user ($user_id)
	{
		$query = "
		DELETE FROM users 
		WHERE user_id = :user_id
		";

		$statement = $this->connect->prepare($query);
		
		$statement->bindParam(':user_id', $user_id);

		$statement->execute();

		$query_1 = "
		UPDATE offers
		SET offer_status = :offer_status_no
		WHERE offer_status = :offer_status_yes AND offer_creator_id = :user_id
		";

		$get_no = 'No';

		$get_yes = 'Yes';

		$statement_1 = $this->connect->prepare($query_1);

		$statement_1->bindParam(':offer_status_no', $get_no);
		
		$statement_1->bindParam(':offer_status_yes', $get_yes);
				
		$statement_1->bindParam(':user_id', $user_id);

		$statement_1->execute();

	}

}