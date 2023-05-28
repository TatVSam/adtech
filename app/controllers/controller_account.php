<?php
include 'app/models/entities/User.php';
include 'app/models/entities/Subscription.php';
include 'app/models/entities/Offer.php';
include 'app/models/entities/Clickthrough.php';
include 'app/models/get_link.php';

class Controller_Account extends Controller
{
    public function index()
    {
       
        $this->view->generate('account_view.phtml', 'template_view.phtml');
        
        
    }
}
