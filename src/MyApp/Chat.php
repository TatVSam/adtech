<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require "app/models/entities/User.php";
require "app/models/entities/Offer.php";
require "app/models/entities/Subscription.php";
require 'app/models/get_link.php';

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        
        $this->clients = new \SplObjectStorage;
 
        echo "Server Started\n";

    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
            $data = json_decode($msg, true);
           // $user_object = new \User;
            //$str = $user_object->get_user_names();
            //$str = $str . " " . $data["user_name"];
            //$data['str'] = $str;
            //var_dump($data);
            
            if ($data['command'] == 'create_offer_form') {
            
                $offer_object = new \Offer;

                $offer_object->setOfferName($data['offer_name']);

                $offer_object->setOfferPrice($data['offer_price']);

                 $offer_object->setOfferUrl($data['offer_url']);

                $offer_object->setOfferTheme($data['offer_theme']);

                $offer_object->setOfferStatus($data['offer_status'] ? 'Yes' : 'No');

                $offer_object->setOfferCreatorId($data['offer_creator_id']);

                $offer_object->setOfferCreatedOn(date("Y-m-d h:i:s"));

            
                $offer_object->save_data();
                $data["offer_data"] = $offer_object->get_all_offer_data();

                $sub_object = new \Subscription;
                $data["sub_data"] = $sub_object->get_subscription_data();

               
                for ($i = 0; $i < count($data["sub_data"]); $i++) {
                    $data["sub_data"][$i]["link_param"] = get_link($data["sub_data"][$i]["user_id"], $data["sub_data"][$i]["offer_id"]);
                }
            //$offer_data['current_user'] = $data['offer_creator_id'];

                foreach ($this->clients as $client) {
            //if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                
                    $client->send(json_encode($data));
                    var_dump($data);
            //}
            }
        }
        if ($data['command'] == 'update_offer_status') {
            $offer_object = new \Offer;
            $offer_object->update_offer_status($data['offer_updated_id']);

            $data["offer_data"] = $offer_object->get_all_offer_data();

            $sub_object = new \Subscription;
            $data["sub_data"] = $sub_object->get_subscription_data();

           
            for ($i = 0; $i < count($data["sub_data"]); $i++) {
                $data["sub_data"][$i]["link_param"] = get_link($data["sub_data"][$i]["user_id"], $data["sub_data"][$i]["offer_id"]);
            }
           
            foreach ($this->clients as $client) {
                //if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                   
                        $client->send(json_encode($data));
                    
                //}
            }
        }

        if ($data['command'] == 'sub_unsub') {
          
            $sub_object = new \Subscription;

            $sub_object->setUserId($data['current_user_id']);
            $sub_object->setOfferId($data['offer_sub_id']);
            
            
            if ($sub_object->is_subscribed($data['current_user_id'], $data['offer_sub_id']))
            {
                $sub_object->deleteSubscription();
            } else {
                $sub_object->setCreatedOn(date("Y-m-d h:i:s"));
                $sub_object->createSubscription();
            }
            
            $data["sub_data"] = $sub_object->get_subscription_data();

            
            for ($i = 0; $i < count($data["sub_data"]); $i++) {
                $data["sub_data"][$i]["link_param"] = get_link($data["sub_data"][$i]["user_id"], $data["sub_data"][$i]["offer_id"]);
            }

            $offer_object = new \Offer;
            //$offer_object->update_offer_status($data['offer_updated_id']);

            $data["offer_data"] = $offer_object->get_all_offer_data();
            //$data["offer_data"] = $sub_object->get_offer_data_for_user($data['current_user_id']);
           
            foreach ($this->clients as $client) {
                //if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                
                        $client->send(json_encode($data));
                     
                //}
            }
        }

        if ($data['command'] == 'delete_user') {
            echo "here";

      
            $user_object = new \User;
            $user_object->delete_user($data["user_delete_id"]);
            $offer_object = new \Offer;
            $data["offer_data"] = $offer_object->get_all_offer_data();

            foreach ($this->clients as $client) {
                //if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                
                        $client->send(json_encode($data));
                     
                //}
            }

        }    
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}