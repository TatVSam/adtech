<?php

/*define("encryption_method", "AES-128-CBC");
define("key", "adtech_encryption");
function encrypt($data) {
    $key = key;
    $plaintext = $data;
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
    return $ciphertext;
}
function decrypt($data) {
    $key = key;
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac))
    {
        return $original_plaintext;
    }
}

function encrypt_subscription ($user_id, $offer_id)
{
    $string = $user_id . "&" . $offer_id;
    $encrypted_string = encrypt($string);
    return $encrypted_string;
}*/

function get_link ($user_id, $offer_id)
{
    $string = $user_id . "u" . $offer_id;
    //$encrypted_string = encrypt($string);
    //return "http://adtech/dashboard/redirect/?auos=" . $encrypted_string;
    return "http://adtech/dashboard/redirect/?auos=" . $string;
}

function get_user_offer($str)
{
    $param = explode("u", $str);
    $result["user_id"] = $param[0];
    $result["offer_id"] = $param[1];
    return $result;
}
