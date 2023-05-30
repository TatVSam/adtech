<?php
require 'app/core/config/config.php';

class Offer
{
	private $offer_id;
	private $offer_name;
	private $offer_price;
	private $offer_url;
	private $offer_theme;
	private $offer_status;
    private $offer_creator_id;
	private $offer_created_on;
	
	public $connect;

    public function __construct()
	{
		require_once(DATA . 'db/Database_connection.php');

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}


    function setOfferName($offer_name)
	{
		$this->offer_name = $offer_name;
	}

	function getOfferName()
	{
		return $this->offer_name;
	}

    function setOfferPrice($offer_price)
	{
		$this->offer_price = $offer_price;
	}

	function getOfferPrice()
	{
		return $this->offer_price;
	}

    function setOfferUrl($offer_url)
	{
		$this->offer_url = $offer_url;
	}

	function getOfferTheme()
	{
		return $this->offer_theme;
	}

    function setOfferTheme($offer_theme)
	{
		$this->offer_theme = $offer_theme;
	}

	function getOfferUrl()
	{
		return $this->offer_url;
	}

    function setOfferStatus($offer_status)
	{
		$this->offer_status = $offer_status;
	}

	function getOfferStatus()
	{
		return $this->offer_status;
	}

    function setOfferCreatorId($offer_creator_id)
	{
		$this->offer_creator_id = $offer_creator_id;
	}

	function getOfferCreatorId()
	{
		return $this->offer_creator_id;
	}


    function setOfferCreatedOn($offer_created_on)
	{
		$this->offer_created_on = $offer_created_on;
	}

	function getOfferCreatedOn()
	{
		return $this->offer_created_on;
	}

    function save_data()
	{
		$table = 'offers';

        try {
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "CREATE TABLE IF NOT EXISTS offers(
                `offer_id` int(11) NOT NULL AUTO_INCREMENT,
                `offer_name` varchar(50) NOT NULL,
                `offer_price` decimal(15,2) NOT NULL,
                `offer_url` varchar(150) NOT NULL,
				`offer_theme` varchar(150) NOT NULL,
                `offer_status` varchar(150) NOT NULL,
                `offer_creator_id` int(11) NOT NULL,
				`offer_created_on` datetime NOT NULL,
				PRIMARY KEY(`offer_id`))";

                $this->connect->exec($sql) ? print("Created $table Table.\n") : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = "
		INSERT INTO offers (offer_name, offer_price, offer_url, offer_theme, offer_status, offer_creator_id, offer_created_on) 
		VALUES (:offer_name, :offer_price, :offer_url, :offer_theme, :offer_status, :offer_creator_id, :offer_created_on)";
		
        $statement = $this->connect->prepare($query);

		$statement->bindParam(':offer_name', $this->offer_name);

		$statement->bindParam(':offer_price', $this->offer_price);

        $statement->bindParam(':offer_url', $this->offer_url);

        $statement->bindParam(':offer_theme', $this->offer_theme);

        $statement->bindParam(':offer_status', $this->offer_status);

        $statement->bindParam(':offer_creator_id', $this->offer_creator_id);

        $statement->bindParam(':offer_created_on', $this->offer_created_on);


		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_all_offer_data() 
	{
		$query = "
		SELECT * FROM offers";

		$statement = $this->connect->query($query);
		if ($statement)
		{
			$offer_data = $statement->FetchAll(PDO::FETCH_ASSOC);
		}
		

	

		return $offer_data;
	}

	function update_offer_status($offer_updated_id) {
		$query = "
		SELECT offer_status FROM offers
		WHERE offer_id = :offer_id";

		$statement = $this->connect->prepare($query);
		$statement->bindParam(':offer_id', $offer_updated_id);
		if ($statement->execute())
		{
			$offer_data = $statement->FetchAll(PDO::FETCH_ASSOC);
			$offer_old_status = $offer_data[0]["offer_status"];
		}

	
		if ($offer_old_status == "No") {
			$offer_new_status = "Yes";
		} else {
			$offer_new_status = "No";
		}

	
		$query_2 = "
		UPDATE offers
		SET offer_status = :offer_status
		WHERE offer_id = :offer_id";

		
        $statement = $this->connect->prepare($query_2);

		$statement->bindParam(':offer_status', $offer_new_status);

		$statement->bindParam(':offer_id', $offer_updated_id);

		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function is_active ($offer_id) 
    {
        $query = "
		SELECT offer_status FROM offers 
		WHERE offer_id = :offer_id
		";

		$statement = $this->connect->prepare($query);

        $statement->bindParam(':offer_id', $offer_id);

		if($statement->execute())
		{
			$offer_data = $statement->fetch(PDO::FETCH_ASSOC);
		}

        //$result = !empty($user_data);
		if ($offer_data["offer_status"] == "Yes") {
			return true;
		} else {
		return false;
		}
    }

	function get_url ($offer_id) 
    {
        $query = "
		SELECT offer_url FROM offers 
		WHERE offer_id = :offer_id
		";

		$statement = $this->connect->prepare($query);

        $statement->bindParam(':offer_id', $offer_id);

		if($statement->execute())
		{
			$offer_data = $statement->fetch(PDO::FETCH_ASSOC);
		}

        
		return $offer_data["offer_url"];
		
    }

}