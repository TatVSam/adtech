<?php
class View
{
	function generate($content_view, $template_view, $errors = null, $id = null)
	{
		
        if($template_view){
            include_once LAYOUT . $template_view;
        }
        /*
        if(!empty($errors))
        {
            include 
        }*/
        
        //include 'application/views/'.$template_view;
	}
}