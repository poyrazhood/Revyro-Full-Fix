<?php

$title = $settings["site_title"];

if (!route(1)) {
    $route[1] = "login";
}
$route[1] = "login";
if ($settings['resetpass_page'] == 1) {
    $resetPage = false;
} elseif ($settings['resetpass_page'] == 2) {
    $resetPage = true;
}

if ($route[1] == "login" && $_POST) {
    $session = \Config\Services::session();
    $username = htmlentities($_POST["username"]);
    $pass = htmlentities($_POST["password"]);
    $remember = isset($_POST["remember"]) ? htmlentities($_POST["remember"]) : 0;
    
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
        $errorText = $languageArray["error.signin.recaptcha"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    } elseif (empty($username)) {
        $error = 1;
        $errorText = $languageArray["error.signin.username"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    } elseif (!userdata_check("username", $username)) {
        $error = 1;
        $errorText = $languageArray["error.signin.username"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    } elseif (!userlogin_check($username, $pass)) {
        $error = 1;
        $errorText = $languageArray["error.signin.notmatch"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    } elseif (countRow(["table" => "clients", "where" => ["username" => $username, "client_type" => 1]])) {
        $error = 1;
        $errorText = $languageArray["error.signin.deactive"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    } else {
        $row = $conn->prepare("SELECT * FROM clients WHERE username=:username && password=:password ");
        $row->execute(array("username" => $username, "password" => md5(sha1(md5($pass)))));
        $row = $row->fetch(PDO::FETCH_ASSOC);
        $access = json_decode($row["access"], true);

        unset($_SESSION["recaptcha"]);

        $_SESSION["neira_userlogin"] = 1;
        $_SESSION["neira_userid"] = $row["client_id"];
        $_SESSION["neira_userpass"] = md5(sha1(md5($pass)));
        $_SESSION["recaptcha"] = false;

        $session->set("neira_userlogin", 1);
        $session->set("neira_userid", $row["client_id"]);
        $session->set("neira_userpass", md5(sha1(md5($pass))));
        $session->set("recaptcha", false);
        if (isset($access["admin_access"])):
            $_SESSION["neira_adminlogin"] = 1;
            $session->set("neira_adminlogin", 1);
        endif;
        if ($remember) {
            if (isset($access["admin_access"])):
                setcookie("a_login", 'ok', strtotime('+7 days'), '/', null, null, true);
            endif;
            setcookie("u_id", $row["client_id"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_password", $row["password"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_login", 'ok', strtotime('+7 days'), '/', null, null, true);
        } else {
            setcookie("u_id", $row["client_id"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_password", $row["password"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_login", 'ok', strtotime('+7 days'), '/', null, null, true);
        }

        $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert->execute(array("c_id" => $row["client_id"], "action" => "Üye girişi yapıldı.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
        $update = $conn->prepare("UPDATE clients SET login_date=:date, login_ip=:ip WHERE client_id=:c_id ");
        $update->execute(array("c_id" => $row["client_id"], "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
        echo "<script>setTimeout(function(){ window.location.href = '" . base_url() . "';}, 0);</script>";
        //header("Location:" . base_url());
        exit();
    }


}
