<?php
require 'app/core/config/config.php';

class Subscription {
    private $user_id;
    private $offer_id;
    private $created_on;
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

    function setOfferId($offer_id)
	{
		$this->offer_id = $offer_id;
	}

	function getOfferId()
	{
		return $this->offer_id;
	}

	function setCreatedOn($created_on)
	{
		$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}

    function createSubscription ()
    {
       

        try {
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "CREATE TABLE IF NOT EXISTS subscriptions(
                `user_id` int(11) NOT NULL,
                `offer_id` int(11) NOT NULL,
                `created_on` datetime NOT NULL
            )";

                $this->connect->exec($sql) ? print("Created $table Table.\n") : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = "
		INSERT INTO subscriptions (user_id, offer_id, created_on) 
		VALUES (:user_id, :offer_id, :created_on)";
		
        $statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		$statement->bindParam(':offer_id', $this->offer_id);

		$statement->bindParam(':created_on', $this->created_on);


		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}

    }

    function deleteSubscription ()
    {
        $query = "
		DELETE FROM subscriptions 
		WHERE user_id = :user_id
        AND offer_id = :offer_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

        $statement->bindParam(':offer_id', $this->offer_id);

		if($statement->execute())
		{
			return true;
		}

        return false;
    }

    function is_subscribed ($user_id, $offer_id) 
    {
        $query = "
		SELECT * FROM subscriptions 
		WHERE user_id = :user_id
        AND offer_id = :offer_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $user_id);

        $statement->bindParam(':offer_id', $offer_id);

		if($statement->execute())
		{
			$user_data = $statement->fetch(PDO::FETCH_ASSOC);
		}

        $result = !empty($user_data);
		return $result;
    }

    function get_user_subscriptions($user_id)
    {
        
        $query = "
		SELECT offer_id FROM subscriptions
            WHERE user_id = :user_id
		";

       
		$statement = $this->connect->prepare($query);

        $statement->bindParam(':user_id', $user_id);

		$statement->execute();

        if($statement->execute())
		{
			$offers = $statement->fetchAll(PDO::FETCH_ASSOC);

            for ($i=0; $i<count($offers); $i++) {
                $result[$i] = $offers[$i]["offer_id"];
            }
            return $result;
		}

		return false;
    }

    function get_offer_data_for_user ($user_id)
    {
       
		$query = "
		SELECT * FROM offers";

		$statement = $this->connect->query($query);
		if ($statement)
		{
			$offer_data = $statement->FetchAll(PDO::FETCH_ASSOC);
		}

        $user_subscriptions = $this->get_user_subscriptions($user_id);
		
		if (!empty($user_subscriptions)) {
        for ($i = 0; $i < count($offer_data); $i++)
        {
            if (in_array($offer_data[$i]["offer_id"], $user_subscriptions)) {
                $offer_data[$i]["sub_status"] = true;
            } else {
                $offer_data[$i]["sub_status"] = false;
            }
        }
	} else {
		for ($i = 0; $i < count($offer_data); $i++) {
			$offer_data[$i]["sub_status"] = false;
		}
	}
		

        return $offer_data; 
		
	
    }

	function get_subscription_data ()
    {
        $query = "
		SELECT user_id, offer_id FROM subscriptions
		ORDER BY user_id";

		$statement = $this->connect->query($query);
		if ($statement)
		{
			$offer_data = $statement->FetchAll(PDO::FETCH_ASSOC);
		}

        

        return $offer_data;
    }


}

