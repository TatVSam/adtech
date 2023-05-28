<?php
class Controller_Error extends Controller
{
    public function index()
    {
       
        $this->view->generate('error_view.php', 'template_view.phtml');
        
        
    }
}
