<?php
require 'app/core/config/config.php';

class Clickthrough {
    private $user_id;
    private $offer_id;
    private $date_time;
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

	function setDateTime($date_time)
	{
		$this->date_time = $date_time;
	}

	function getDateTime()
	{
		return $this->date_time;
	}

    function writeClickthrough ()
    {
       

        try {
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "CREATE TABLE IF NOT EXISTS clickthroughs(
                `click_id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `offer_id` int(11) NOT NULL,
                `date_time` datetime NOT NULL,
                PRIMARY KEY(`click_id`))";

                $this->connect->exec($sql) ? print("Created $table Table.\n") : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = "
		INSERT INTO clickthroughs (user_id, offer_id, date_time) 
		VALUES (:user_id, :offer_id, :date_time)";
		
        $statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		$statement->bindParam(':offer_id', $this->offer_id);

		$statement->bindParam(':date_time', $this->date_time);


		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}

    }

	function get_all_money() 
	{
		$query = "
		SELECT SUM(offers.offer_price) AS total FROM offers 
		JOIN clickthroughs
		ON clickthroughs.offer_id = offers.offer_id";
		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);


	}

	function get_money_by_advertisers() 
	{
		$query = "
		SELECT users.user_name, SUM(offers.offer_price) AS money
		FROM offers LEFT JOIN users 
		ON users.user_id = offers.offer_creator_id 
		JOIN clickthroughs 
		ON offers.offer_id = clickthroughs.offer_id 
		GROUP BY (users.user_name);";
		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);


	}

	function get_money_by_webmasters() 
	{
		$query = "
		SELECT users.user_name, SUM(offers.offer_price) AS money 
		FROM clickthroughs LEFT JOIN users 
		ON users.user_id = clickthroughs.user_id 
		JOIN offers ON offers.offer_id = clickthroughs.offer_id 
		GROUP BY (users.user_name);";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);


	}

	function get_offers_by_webmasters() 
	{
		$query = "
		SELECT clickthroughs.user_id, offers.offer_name, COUNT(offers.offer_name) AS visits, SUM(offers.offer_price) AS money 
		FROM clickthroughs JOIN offers 
		ON offers.offer_id = clickthroughs.offer_id GROUP BY clickthroughs.user_id, offers.offer_name;";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);


	}

	function get_money_by_offers() 
	{
		$query = "
		SELECT offers.offer_name, offers.offer_creator_id, COUNT(clickthroughs.offer_id) AS visits, SUM(offers.offer_price) AS money 
		FROM offers JOIN clickthroughs 
		ON offers.offer_id = clickthroughs.offer_id 
		GROUP BY offers.offer_name, offers.offer_creator_id;";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	

}