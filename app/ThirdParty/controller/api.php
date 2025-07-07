<?php

if (isset($_SESSION["neira_userlogin"]) && $_SESSION["neira_userlogin"] == 1):
    if ($settings["sms_verify"] == 2 && $user["sms_verify"] != 2) {
        header("Location:" . base_url('verify/sms'));
    }
    if ($settings["mail_verify"] == 2 && $user["mail_verify"] != 2) {
        header("Location:" . base_url('verify/mail'));
    }
endif;
$title .= $languageArray["api.title"];
if(isset($user["apikey"])):
$user["apikey"] = isset($user["apikey"]) ? private_str($user["apikey"], 10, 12) : "";
else:
    $user = array();
$user["apikey"] = "0";
    endif;