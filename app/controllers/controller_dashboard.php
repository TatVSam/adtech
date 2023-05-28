<?php
include 'app/models/entities/User.php';
include 'app/models/entities/Subscription.php';
include 'app/models/entities/Offer.php';
include 'app/models/entities/Clickthrough.php';
include 'app/models/get_link.php';

class Controller_Dashboard extends Controller
{
    function check()
    {
        session_start();
    
        
 
    if (isset($_COOKIE['user_id']) and isset($_COOKIE['user_hash']))
    {
            
        

            $user_object = new User;
            $is_authorized = $user_object->is_authorized($_COOKIE['user_id'], $_COOKIE['user_hash']);
            if (!$is_authorized)
            {
                setcookie("user_id", "", time() - 3600*24*30*12, "/");
                setcookie("user_hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
            }
            return $user_object->is_authorized($_COOKIE['user_id'], $_COOKIE['user_hash']);
        }
        
        return false;
    }

    public function create_offer()
    {
        
        
            
            
            $click_object = new Clickthrough;
            
          
        //$click_object->get_money_by_advertisers();
           echo"<pre>"; var_dump($click_object->get_money_by_offers());echo"</pre>"; 

        
           
        
    }

    public function redirect() 
    {
        
       
        $params = get_user_offer($_GET["auos"]);

       
        $sub_object = new Subscription;
        $offer_object = new Offer;
        if (!$offer_object->is_active($params["offer_id"])) {
            echo "Оффер не активен";
        } elseif (!$sub_object->is_subscribed($params["user_id"],$params["offer_id"])) {
            echo "Вебмастер не подписан на оффер";
        } else {
            $url = $offer_object->get_url($params["offer_id"]);
            $click_object = new Clickthrough;
            $click_object->setOfferId($params["offer_id"]);
            $click_object->setUserId($params["user_id"]);

            $click_object->setDateTime(date("Y-m-d h:i:s"));
            $click_object->writeClickthrough();
            header("Location: $url");
        }
        
        

        //echo encrypt("2&5");
        //echo encrypt("2&5");
        //echo decrypt ("A9dOyS3N/riEwhbGfLdivZBJ8HX3rJ 9/GfgXyUu8PRiXJfbQd7w5KmJRzpFyMjFjskTmKmhRtyv6mMsGDxz7g==");
    }
    
    public function index()
    {
        $user_data = $this->check();
        if ($user_data) 
        {
            $_SESSION['user_name'] = $user_data['user_name']; 
            $_SESSION['auth'] = true;
            $_SESSION['user_role'] = $user_data['user_role'];
            $_SESSION['user_id'] = $user_data['user_id'];
        }
        $this->view->generate('dashboard/index.phtml', 'template_view.phtml');
        
        
    }
}
