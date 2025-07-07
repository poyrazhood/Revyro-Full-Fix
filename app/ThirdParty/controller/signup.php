<?php

$title .= $languageArray["signup.title"];
//print_r($_POST);

$referral = isset($_SESSION['referral']) ? $_SESSION['referral'] : "";
/*if (isset($settings["register_page"]) && $settings["register_page"] == 1) {
    include 'themes/404.php';
    die();
}*/
if (isset($_POST['username'])) {
    
    $session = \Config\Services::session();
    $first_name = isset($_POST["first_name"]) ? htmlentities($_POST["first_name"]) : 0;
    $last_name = isset($_POST["last_name"]) ? htmlentities($_POST["last_name"]) : 0;
    $email = isset($_POST["email"]) ? mb_strtolower(htmlentities($_POST["email"])) : 0;
    $username = isset($_POST["username"]) ? mb_strtolower(htmlentities($_POST["username"])) : 0;
    $phone = isset($_POST["telephone"]) ? htmlentities($_POST["telephone"]) : 0;
    $pass = isset($_POST["password"]) ? htmlentities($_POST["password"]) : 0;
    $pass_again = isset($_POST["password_again"]) ? htmlentities($_POST["password_again"]) : 0;
    $terms = isset($_POST["terms"]) ? htmlentities($_POST["terms"]) : 0;
   
    $googlesecret = $settings["recaptcha_secret"];
    if(isset($_SESSION["recaptcha"])){
    $captcha = $_POST['g-recaptcha-response'] ;
        
    if ($captcha) {
        $captcha_control = robot("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        $captcha_control = json_decode($captcha_control);
    }

    }
    if ($settings["recaptcha"] == 2 && (!isset($captcha_control->success)) && isset($_SESSION["recaptcha"])) {
        $error = 1;
        $errorText = $languageArray["error.signup.recaptcha"];
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
    } elseif ($settings["name_secret"] == 2 && empty($first_name)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;        
        $error = 1;
        $errorText = $languageArray["error.signup.name"];
    } elseif ($settings["name_secret"] == 2 && empty($last_name)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.name"];
    } elseif (!email_check($email)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.email"];
    } elseif (userdata_check("email", $email)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.email.used"];
    } elseif (!username_check($username)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.username.character"];
    } elseif (userdata_check("username", $username)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.username.used"];
    } elseif ($settings["skype_area"] == 2 && empty($phone)) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.telephone"];
    } elseif (strlen($pass) < 8) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.password"];
    } elseif ($pass != $pass_again) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.password.notmatch"];
    } elseif ($settings["terms_checkbox"] == 2 && !$terms) {
        $session->set("recaptcha", true);
        $_SESSION["recaptcha"] = true;
        $error = 1;
        $errorText = $languageArray["error.signup.terms"];
    } else {
        $apikey = CreateApiKey($_POST);
        $cl = new \App\Models\clients();
        if (route(2)) {
            $ref_control = $cl->where('referral_code', route(2))->countAllResults();
            if ($ref_control) {
                $ref_code = $cl->where('referral_code', route(2))->get()->getResultArray()[0];
                $referral = $ref_code['client_id'];
            }
        }
        $referral_code = substr(md5(microtime()), rand(0, 26), 5);


        $conn->beginTransaction();
        $insert = $conn->prepare("INSERT INTO clients SET 
    first_name=:first_name,
    last_name=:last_name,
    username=:username,
    email=:email,
    password=:pass,
    lang=:lang,
    telephone=:phone,
    register_date=:date,
    apikey=:key,
    timezone=:timezone,
    referral=:referral,
    referral_code=:referral_code,
    sms_verify=:sms,
    mail_verify=:mail
    ");
        $insert = $insert->execute(array(
            "lang" => $settings["site_language"],
            "first_name" => $first_name,
            "last_name" => $last_name,
            "username" => $username,
            "email" => $email,
            "pass" => md5(sha1(md5($pass))),
            "phone" => $phone,
            "date" => date("Y.m.d H:i:s"),
            'key' => $apikey,
            "timezone" => $settings["site_timezone"],
            "referral" => $referral,
            "referral_code" => $referral_code,
            "sms" => $settings['sms_verify'] == 2 ? "1" : "2",
            "mail" => $settings['mail_verify'] == 2 ? "1" : "2",
        ));

        if ($insert): $client_id = $conn->lastInsertId(); endif;
        if ($referral): $ref = $referral; endif;

        if ($settings["free_balance"] == 2):
            $update = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $update->execute(array("id" => $client_id, "balance" => $settings["free_amount"]));
        endif;

        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert2 = $insert2->execute(array("c_id" => $client_id, "action" => "User registration made.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));


        if ($insert && $insert2):
            $conn->commit();
            unset($_SESSION["data"]);
            $success = 1;
            $successText = $languageArray["error.signup.success"];
            $_SESSION["neira_userlogin"] = 1;
            $_SESSION["neira_userid"] = $client_id;
            $_SESSION["neira_userpass"] = md5(sha1(md5($pass)));
            $_SESSION["recaptcha"] = false;
            if (isset($access["admin_access"])):
                $_SESSION["neira_adminlogin"] = 1;
            endif;

            if (isset($access["admin_access"])):
                setcookie("a_login", 'ok', strtotime('+7 days'), '/', null, null, true);
            endif;
            setcookie("u_id", $client_id, strtotime('+7 days'), '/', null, null, true);
            setcookie("u_password", md5(sha1(md5($pass))), strtotime('+7 days'), '/', null, null, true);
            setcookie("u_login", 'ok', strtotime('+7 days'), '/', null, null, true);


            redirect()->to(base_url(''));
            echo "<script> window.location.href = '" . base_url("") . "';</script>";
            exit();
        else:
            $conn->rollBack();
            $error = 1;
            $errorText = $languageArray["error.signup.fail"];
        endif;
    }

}
