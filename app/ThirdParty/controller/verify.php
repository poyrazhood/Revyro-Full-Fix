<?php

$title .= $languageArray["verify.title"];


if (!route(2)) {
    if ($settings["sms_verify"] == 2 && $user["sms_verify"] != 2) {
        header("Location:" . base_url('verify/sms'));
    }
    if ($settings["mail_verify"] == 2 && $user["mail_verify"] != 2) {
        redirect()->to(base_url(''));
        echo "<script> window.location.href = '" . base_url() . "';</script>";
        exit();
        header("Location:" . base_url('verify/mail'));
    }
}

$search = $conn->prepare("SELECT * FROM verify_log WHERE token=:token && type=:type");
$search->execute(array("token" => route(2), "type" => 1));

if (route(2) == "mail" && $user["mail_verify"] != 2) {
    $verify = true;

} elseif (route(2) == "sms" && $user["sms_verify"] != 2) {
    $verify = false;
}
if (route(2) == "mail" && $_POST && $user["mail_verify"] != 2):
    
    $googlesecret = $settings["recaptcha_secret"];
    if(isset($_SESSION["recaptcha"])){
    $captcha = $_POST['g-recaptcha-response'];
        
    if ($captcha) {
        $captcha_control = robot("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        $captcha_control = json_decode($captcha_control);
    }

    }
    if ($settings["recaptcha"] == 2 && (!isset($captcha_control->success)) && isset($_SESSION["recaptcha"])) {
        $error = 1;
        $errorText = $languageArray["error.resetpassword.recaptcha"];
        if ($settings["recaptcha"] == 2) {
            $session->set("recaptcha", true);
            $_SESSION["recaptcha"] = true;
        }
    }elseif($settings["recaptcha"] == 1){
        $token = EmailCreateApiKey($_POST);
        $token .= substr(md5(microtime()), rand(0, 26), 5);

        $send = sendMail(["subject" => "Hesabınızı doğrulayın.", "body" => "Doğrulama linkiniz : " . base_url('verify/' . $token), "mail" => $user['email']]);

        if ($send):

            $insert = $conn->prepare("INSERT INTO verify_log SET client_id=:c_id, token=:token, type=:type, verify=:verify ");
            $insert->execute(array("c_id" => $user["client_id"], "token" => $token, "type" => 1, "verify" => "mail"));
            $success = 1;
            $successText = $languageArray["error.verify.success"];
        else:
            $error = 1;
            $errorText = $languageArray["error.verify.fail"];
        endif;
        
    }else{
        $token = EmailCreateApiKey($_POST);
        $token .= substr(md5(microtime()), rand(0, 26), 5);

        $send = sendMail(["subject" => "Hesabınızı doğrulayın.", "body" => "Doğrulama linkiniz : " . base_url('verify/' . $token), "mail" => $user['email']]);

        if ($send):

            $insert = $conn->prepare("INSERT INTO verify_log SET client_id=:c_id, token=:token, type=:type, verify=:verify ");
            $insert->execute(array("c_id" => $user["client_id"], "token" => $token, "type" => 1, "verify" => "mail"));
            $success = 1;
            $successText = $languageArray["error.verify.success"];
        else:
            $error = 1;
            $errorText = $languageArray["error.verify.fail"];
        endif;
    }

elseif (route(2) == "sms" && $_POST && $user["sms_verify"] != 2):

    $captcha = $_POST['g-recaptcha-response'];
    $googlesecret = $settings["recaptcha_secret"];
    $captcha_control = robot("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $captcha_control = json_decode($captcha_control);
    if ($settings["recaptcha"] == 2 && $captcha_control->success == false):
        $error = 1;
        $errorText = $languageArray["error.resetpassword.recaptcha"];
    else:

        $token = CreateApiKey($_POST);
        $token .= substr(md5(microtime()), rand(0, 26), 5);

        $send = SMSUser($user["telephone"], "Doğrulama linkiniz : " . base_url('verify/' . $token));

        if ($send):

            $insert = $conn->prepare("INSERT INTO verify_log SET client_id=:c_id, token=:token, type=:type, verify=:verify ");
            $insert->execute(array("c_id" => $user["client_id"], "token" => $token, "type" => 1, "verify" => "sms"));

            $success = 1;
            $successText = $languageArray["error.verify.success"];
        else:
            $error = 1;
            $errorText = 'Basarisiz' . $languageArray["error.verify.fail"];
        endif;

    endif;

elseif (route(2) == "edit" && $_POST):

    if (isset($_POST["telephone"])):

        $phone = htmlspecialchars($_POST["telephone"]);

        if (empty($phone)) {
            $error = 1;
            $errorText = $languageArray["error.verify.empty"];
        } elseif (userdata_check("telephone", $phone)) {
            $error = 1;
            $errorText = $languageArray["error.signup.telephone.used"];
        } else {
            $update = $conn->prepare("UPDATE clients SET telephone=:telephone WHERE client_id=:id ");
            $update = $update->execute(array("id" => $user["client_id"], "telephone" => $phone));
            header("Location:" . base_url("verify/sms"));
        }

    endif;

    if (isset($_POST["email"])):

        $email = htmlspecialchars($_POST["email"]);

        if (empty($email)) {
            $error = 1;
            $errorText = $languageArray["error.verify.empty"];
        } elseif (!email_check($email)) {
            $error = 1;
            $errorText = $languageArray["error.signup.email"];
        } elseif (userdata_check("email", $email)) {
            $error = 1;
            $errorText = $languageArray["error.signup.email.used"];
        } else {
            $update = $conn->prepare("UPDATE clients SET email=:email WHERE client_id=:id ");
            $update = $update->execute(array("id" => $user["client_id"], "email" => $email));
            header("Location:" . base_url("verify/mail"));
        }

    endif;

elseif (route(2) && $search->rowCount()):
    $search = $search->fetch(PDO::FETCH_ASSOC);


    if ($search["verify"] == "sms") {
        $update = $conn->prepare("UPDATE clients SET sms_verify=:sms_verify WHERE client_id=:id ");
        $update = $update->execute(array("id" => $search["client_id"], "sms_verify" => 2));
    } elseif ($search["verify"] == "mail") {
        $update = $conn->prepare("UPDATE clients SET mail_verify=:mail_verify WHERE client_id=:id ");
        $update = $update->execute(array("id" => $search["client_id"], "mail_verify" => 2));
    }

    $update = $conn->prepare("UPDATE verify_log SET type=:type WHERE token=:token ");
    $update->execute(array("type" => 2, "token" => route(2)));

    header("Location:" . base_url());

endif;