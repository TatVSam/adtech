<?php



function get_link ($user_id, $offer_id)
{
    $string = $user_id . "u" . $offer_id;
   
    return "http://adtech/dashboard/redirect/?auos=" . $string;
}

function get_user_offer($str)
{
    $param = explode("u", $str);
    $result["user_id"] = $param[0];
    $result["offer_id"] = $param[1];
    return $result;
}
