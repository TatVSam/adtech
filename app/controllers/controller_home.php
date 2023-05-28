<?php


class Controller_Home extends Controller
{
    public function index()
    {
       
        $this->view->generate('home_view.phtml', 'template_view.phtml');
        
        
    }
}
