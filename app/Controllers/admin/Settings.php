<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Settings extends Ana_Controller
{
    function index()
    {

        global $conn;
        global $_SESSION;
        $access = 0;

        $route[2] = "";
        if (!route(3)) :
            $route[2] = "pages";
        endif;
        $settings = $this->settings;

        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => !route(3) ? 'general' : route(3),
            'route4' => "",
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
            'timezones' => timezones(),

        );


        unset($_SESSION["client"]);
        $menuList = ["Genel" => "general", "Sağlayıcılar" => "providers", "Ödeme Yöntemleri" => "payment-methods", "Modüller" => "modules", "Entegrasyonlar" => "integrations", "Bildirimler" => "alert", "Bonuslar" => "payment-bonuses",];
        if ((route(3) == "general" && route(3)) || !route(3)) :

            $access = $this->getuser["access"]["general_settings"];
            if ($access) :

                if ($_POST) :

                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    if ($_FILES["logo"] && ($_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif")) :
                        $logo_name = $_FILES["logo"]["name"];
                        $uzanti = substr($logo_name, -4, 4);
                        $logo_newname = "images/" . md5(rand(10, 999)) . ".png";
                        $avatar = $this->request->getFile('logo');
                        $logo_newname = $avatar->getRandomName();
                        $avatar->move('assets/uploads/sites', $logo_newname);
                    elseif ($settings["site_logo"] != "") :
                        $logo_newname = $settings["site_logo"];
                    else :
                        $logo_newname = "";
                    endif;
                    if (isset($_FILES["favicon"]) && ($_FILES["favicon"]["type"] == "image/jpeg" || $_FILES["favicon"]["type"] == "image/jpg" || $_FILES["favicon"]["type"] == "image/png" || $_FILES["favicon"]["type"] == "image/gif")) :
                        $favicon_name = $_FILES["favicon"]["name"];
                        $uzanti = substr($favicon_name, -4, 4);
                        $fv_newname = "images/" . md5(rand(10, 999)) . ".png";
                        $avatars = $this->request->getFile('favicon');
                        $fv_newname = $avatars->getRandomName();
                        $avatars->move('assets/uploads/sites', $fv_newname);

                        $update = $conn->prepare("UPDATE settings SET 
			            favicon=:fv WHERE id=:id ");
                        $update->execute(array(
                            "id" => 1,
                            "fv" => $fv_newname,
                        ));
                    else :
                        $fv_newname = $settings["favicon"];
                    endif;
                    if (!$this->request->getPost('name')) :
                        $errorText = "Panel adı boş olamaz";
                        $error = 1;
                        exit();
                    else :

                        $update = $conn->prepare("UPDATE settings SET 
            ser_sync=:sync,
			site_maintenance=:site_maintenance,
			resetpass_page=:resetpass_page,
			site_name=:name,
			site_logo=:logo,
			site_timezone=:timezone,
			site_currency=:site_currency,
		    terms_checkbox=:terms_checkbox,
			max_ticket=:max_ticket,
			name_secret=:name_secret,
			skype_area=:skype_area,
			ticket_system=:ticket_system, 
			register_page=:registration_page, 
			neworder_terms=:neworder_terms,  
			service_list=:service_list, 
			auto_refill=:auto_refill,
            avarage=:avarage, 
            sms_verify=:sms_verify,
            mail_verify=:mail_verify,
			custom_header=:custom_header, 
			custom_footer=:custom_footer,
			 google_ads_odeme=:google_ads_odeme,
			 google_ads_all=:google_ads_all,
			 ref_bonus=:ref_b,
			 ref_max=:ref_m,
			 up_limiti=:up_limiti,
             proxy_mode=:proxy_mode
			 WHERE id=:id ");
                        $update->execute(array(
                            "id" => 1,
                            "sync" => $ser_sync,
                            "site_maintenance" => $site_maintenance,
                            "resetpass_page" => $resetpass,
                            "name" => $name,
                            "max_ticket" => $max_ticket,
                            "logo" => $logo_newname,
                            "timezone" => $timezone,
                            "site_currency" => $site_currency,
                            "terms_checkbox" => $terms_checkbox,
                            "name_secret" => $name_secret,
                            "skype_area" => $skype_area,
                            "ticket_system" => $ticket_system,
                            "registration_page" => $registration_page,
                            "neworder_terms" => $neworder_terms,
                            "service_list" => $service_list,
                            "auto_refill" => $auto_refill,
                            "avarage" => $avarage,
                            "sms_verify" => $sms_verify,
                            "mail_verify" => $mail_verify,
                            "custom_footer" => $custom_footer,
                            "custom_header" => $custom_header,
                            "google_ads_odeme" => $google_ads_odeme,
                            "google_ads_all" => $google_ads_all,
                            "ref_b" => $this->request->getPost('ref_bonus'),
                            "ref_m" => $this->request->getPost('ref_max'),
                            "up_limiti" => $up_limiti,
                            "proxy_mode" => $proxy_mode
                        ));

                        if ($update) :

                            if ($proxy_mode == "1") {
                                $find = array('www.');
                                $replace = '';
                                $output_url = str_replace($find, $replace, $_SERVER['SERVER_NAME']);


                                $ch = curl_init();
                                $fields = array(
                                    'type' => "control",
                                    'domain' => $output_url
                                );
                                $postvars = '';
                                foreach ($fields as $key => $value) {
                                    $postvars .= $key . "=" . $value . "&";
                                }
                                $url = "https://i62.net/classes/proxy.php";
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                                $response = curl_exec($ch);
                                //print "curl response is:" . $response;
                                curl_close($ch);

                                if ($response == true) {
                                    $veri = json_decode($response, true);
                                    $update = $conn->prepare("UPDATE proxy SET user =:user, pass= :pass, ip= :ip, port= :port  WHERE id=:id");
                                    $update->execute(array("user" => $veri['username'], "pass" => $veri['password'], "ip" => $veri['ip'], "port" => $veri['port'], "id" => 1));
                                } else {
                                    $update = $conn->prepare("UPDATE proxy SET user =:user, pass= :pass, ip= :ip, port= :port  WHERE id=:id");
                                    $update->execute(array("user" => "proxydeactive", "pass" => "proxydeactive", "ip" => "proxydeactive", "port" => "proxydeactive", "id" => 1));
                                    $updates = $conn->prepare("UPDATE settings SET proxy_mode =:proxy_mode  WHERE id=:id");
                                    $updates->execute(array("proxy_mode" => 0, "id" => 1));
                                }
                            } else {
                                $update = $conn->prepare("UPDATE proxy SET user =:user, pass= :pass, ip= :ip, port= :port  WHERE id=:id");
                                $update->execute(array("user" => "proxydeactive", "pass" => "proxydeactive", "ip" => "proxydeactive", "port" => "proxydeactive", "id" => 1));
                            }

                            header("Location:" . base_url("admin/settings/general"));
                            echo "<script>window.location.href = '" . base_url('admin/settings/general') . "'; </script>";
                            $_SESSION["client"]["data"]["success"] = 1;
                            $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
                        else :
                            $errorText = "İşlem başarısız";
                            $error = 1;
                        endif;
                    endif;
                endif;
                if (route(4) == "delete-logo" && route(4)) :

                    $update = $conn->prepare("UPDATE settings SET site_logo=:type WHERE id=:id ");
                    $update->execute(array("type" => "", "id" => 1));
                    if ($update) :
                        unlinks($settings["site_logo"]);
                    endif;
                    header("Location:" . base_url("admin/settings/general"));

                    echo "<script>window.location.href = '" . base_url('admin/settings/general') . "'; </script>";
                elseif (route(4) == "delete-favicon" && route(4)) :
                    $update = $conn->prepare("UPDATE settings SET favicon=:type WHERE id=:id ");
                    $update->execute(array("type" => "", "id" => 1));
                    if ($update) :
                        unlinks($settings["favicon"]);
                    endif;

                    echo "<script>window.location.href = '" . base_url('admin/settings/general') . "'; </script>";
                    header("Location:" . base_url("admin/settings/general"));
                endif;
            endif;
        elseif (route(3) == "payment-methods" && route(3)) :
            $titleAdmin = "Payment Methods";
            $access = $this->getuser["access"]["general_settings"];
            if ($access) :
                if (route(4) == "edit" && $_POST && route(4)) :
                    $id = route(5);
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    if (!countRow(["table" => "payment_methods", "where" => ["method_get" => $id]])) :
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli ödeme methodu seçin";
                    else :
                        $update = $conn->prepare("UPDATE payment_methods SET method_min=:min, method_max=:max, method_type=:type, method_extras=:extras WHERE method_get=:id ");
                        $update->execute(array("id" => $id, "min" => $min, "max" => $max, "type" => $method_type, "extras" => json_encode($_POST)));
                        if ($update) :
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                        else :
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon]);
                    exit();
                elseif (strstr(route(4), "type") && route(4)) :

                    $id = $_GET["id"];
                    $type = $_GET["type"];
                    if ($type == "off") : $type = 1;
                    elseif ($type == "on") : $type = 2;
                    endif;
                    $update = $conn->prepare("UPDATE payment_methods SET method_type=:type WHERE id=:id ");
                    $update->execute(array("id" => $id, "type" => $type));
                    if ($update) :
                        echo "1";
                    else :
                        echo "0";
                    endif;
                    exit();
                endif;
                $methodList = $conn->prepare("SELECT * FROM payment_methods ORDER BY method_line ");
                $methodList->execute(array());
                $methodList = $methodList->fetchAll(PDO::FETCH_ASSOC);
                $ayar['methodList'] = $methodList;
            endif;
            if (route(4)) : echo "<script>window.location.href = '" . base_url('admin/settings/payment-methods') . "'; </script>";
            endif;
            if (route(4)) : header("Location:" . base_url("admin/settings/payment-methods"));
            endif;

        elseif (route(3) == "payment-bonuses" && route(3)) :
            $titleAdmin = "Payment Bonuses";
            $access = $this->getuser["access"]["general_settings"];
            if ($access) :
                if (route(4) == "new" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    if (empty($method_type)) :
                        $error = 1;
                        $errorText = "Method boş olamaz";
                        $icon = "error";
                    elseif (empty($amount)) :
                        $error = 1;
                        $errorText = "Bonus tutarı boş olamaz";
                        $icon = "error";
                    elseif (empty($from)) :
                        $error = 1;
                        $errorText = "İtibaren olamaz";
                        $icon = "error";
                    else :
                        $conn->beginTransaction();
                        $insert = $conn->prepare("INSERT INTO payments_bonus SET bonus_method=:method, bonus_from=:from, bonus_amount=:amount, bonus_type=:type ");
                        $insert = $insert->execute(array("method" => $method_type, "from" => $from, "amount" => $amount, "type" => 2));
                        if ($insert) :
                            $conn->commit();
                            $referrer = base_url("admin/settings/payment-bonuses");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "edit" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $id = route(5);
                    if (empty($method_type)) :
                        $error = 1;
                        $errorText = "Method boş olamaz";
                        $icon = "error";
                    elseif (empty($amount)) :
                        $error = 1;
                        $errorText = "Bonus tutarı boş olamaz";
                        $icon = "error";
                    elseif (empty($from)) :
                        $error = 1;
                        $errorText = "İtibaren olamaz";
                        $icon = "error";
                    else :
                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE payments_bonus SET bonus_method=:method, bonus_from=:from, bonus_amount=:amount WHERE bonus_id=:id ");
                        $update = $update->execute(array("method" => $method_type, "from" => $from, "amount" => $amount, "id" => $id));
                        if ($update) :
                            $conn->commit();
                            $referrer = base_url("admin/settings/payment-bonuses");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "delete" && route(4)) :
                    $id = route(5);
                    if (!countRow(["table" => "payments_bonus", "where" => ["bonus_id" => $id]])) :
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli ödeme bonusu seçin";
                    else :
                        $delete = $conn->prepare("DELETE FROM payments_bonus WHERE bonus_id=:id ");
                        $delete->execute(array("id" => $id));

                        if ($delete) :
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/settings/payment-bonuses");
                        else :
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 0]);
                    exit();
                elseif (!route(4)) :
                    $bonusList = $conn->prepare("SELECT * FROM payments_bonus INNER JOIN payment_methods WHERE payment_methods.id = payments_bonus.bonus_method ORDER BY payment_methods.id DESC ");
                    $bonusList->execute(array());
                    $bonusList = $bonusList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['bonusList'] = $bonusList;
                else :

                    echo "<script>window.location.href = '" . base_url('admin/settings/payment-bonuses') . "'; </script>";
                    header("Location:" . base_url("admin/settings/payment-bonuses"));
                endif;
            endif;
        elseif (route(3) == "providers" && route(3)) :
            $titleAdmin = "Providers";

            $access = $this->getuser["access"]["providers"];
            if ($access) :

                if (route(4) == "new" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    if (empty($url)) :
                        $error = 1;
                        $errorText = "Sağlayıcı API URL boş olamaz";
                        $icon = "error";
                    elseif (empty($key)) :
                        $error = 1;
                        $errorText = "Sağlayıcı API Key boş olamaz";
                        $icon = "error";
                    else :

                        $name = str_replace('https://', '', $url);
                        $name = str_replace('/api/v2', '', $name);


                        $conn->beginTransaction();
                        $insert = $conn->prepare("INSERT INTO service_api SET api_name=:name, api_key=:key, api_url=:url, api_limit=:limit, api_type=:type, api_alert=:alert ");
                        $insert = $insert->execute(array("name" => $name, "key" => $key, "url" => $url, "limit" => "0", "type" => "1", "alert" => 2));
                        if ($insert) :
                            $find = array('www.');
                            $replace = '';
                            $output_url = str_replace($find, $replace, $_SERVER['SERVER_NAME']);

                            $ch = curl_init();
                            $fields = array(
                                'providers' => $url,
                                'domain' => $output_url
                            );
                            $postvars = '';
                            foreach ($fields as $key => $value) {
                                $postvars .= $key . "=" . $value . "&";
                            }
                            $urls = "https://i62.net/url/addproviders";
                            curl_setopt($ch, CURLOPT_URL, $urls);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                            $response = curl_exec($ch);
                            //print "curl response is:" . $response;
                            curl_close($ch);
                            if ($response == true) :
                                $conn->commit();
                                $referrer = base_url("admin/settings/providers");
                                $error = 1;
                                $errorText = "İşlem başarılı";
                                $icon = "success";
                            else :
                                $conn->commit();
                                $referrer = base_url("admin/settings/providers");
                                $error = 1;
                                $errorText = "İşlem başarılı";
                                $icon = "success";
                            endif;
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "edit" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $id = route(5);

                    if (empty($url)) :
                        $error = 1;
                        $errorText = "Sağlayıcı API URL boş olamaz";
                        $icon = "error";
                    elseif (empty($name)) :
                        $error = 1;
                        $errorText = "Sağlayıcı adı boş olamaz";
                        $icon = "error";
                    elseif (empty($apikey)) :
                        $error = 1;
                        $errorText = "Sağlayıcı API Key boş olamaz";
                        $icon = "error";
                    else :

                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE service_api SET api_name=:name, api_key=:key, api_url=:url, api_limit=:limit WHERE id=:id ");
                        $update = $update->execute(array("name" => $name, "key" => $apikey, "url" => $url, "limit" => $limit, "id" => $id));
                        if ($update) :
                            $conn->commit();
                            $referrer = base_url("admin/settings/providers");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "suggestion" && route(4)) :

                    $kaynak = file_get_contents("https://i62.net/classes/apis.php?soft=3");
                    $data = json_decode($kaynak, true);
                    $ayar['datacik'] = $data;

                elseif (route(4) == "delete" && route(4)) :
                    $id = route(5);
                    if (!countRow(["table" => "service_api", "where" => ["id" => $id]])) :
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli sağlayıcı seçin";
                    else :
                        $delete = $conn->prepare("DELETE FROM service_api WHERE id=:id ");
                        $delete->execute(array("id" => $id));
                        if ($delete) :
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/settings/providers");
                        else :
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 0]);
                    exit();
                elseif (!route(4)) :
                    $providersList = $conn->prepare("SELECT * FROM service_api ");
                    $providersList->execute(array());
                    $providersList = $providersList->fetchAll(PDO::FETCH_ASSOC);
                else :
                    echo "<script>window.location.href = '" . base_url('admin/settings/providers') . "'; </script>";
                    header("Location:" . base_url("admin/settings/providers"));
                endif;
            endif;
            if (route(6)) : header("Location:" . base_url("admin/settings/providers"));
            endif;
        elseif (route(3) == "bank-accounts" && route(3)) :
            $access = $this->getuser["access"]["bank_accounts"];
            if ($access) :
                if (route(4) == "new" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    if (empty($bank_name)) :
                        $error = 1;
                        $errorText = "Banka adı boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_alici)) :
                        $error = 1;
                        $errorText = "Alıcı boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_sube)) :
                        $error = 1;
                        $errorText = "Şube no boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_hesap)) :
                        $error = 1;
                        $errorText = "Hesap no boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_iban)) :
                        $error = 1;
                        $errorText = "IBAN boş olamaz";
                        $icon = "error";
                    else :
                        $conn->beginTransaction();
                        $insert = $conn->prepare("INSERT INTO bank_accounts SET bank_name=:name, bank_sube=:sube, bank_hesap=:hesap, bank_iban=:iban, bank_alici=:alici ");
                        $insert = $insert->execute(array("name" => $bank_name, "sube" => $bank_sube, "hesap" => $bank_hesap, "iban" => $bank_iban, "alici" => $bank_alici));
                        if ($insert) :
                            $conn->commit();
                            $referrer = base_url("admin/settings/bank-accounts");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "edit" && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $id = route(5);
                    if (empty($bank_name)) :
                        $error = 1;
                        $errorText = "Banka adı boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_alici)) :
                        $error = 1;
                        $errorText = "Alıcı boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_sube)) :
                        $error = 1;
                        $errorText = "Şube no boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_hesap)) :
                        $error = 1;
                        $errorText = "Hesap no boş olamaz";
                        $icon = "error";
                    elseif (empty($bank_iban)) :
                        $error = 1;
                        $errorText = "IBAN boş olamaz";
                        $icon = "error";
                    else :
                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE bank_accounts SET bank_name=:name, bank_sube=:sube, bank_hesap=:hesap, bank_iban=:iban, bank_alici=:alici WHERE id=:id ");
                        $update = $update->execute(array("name" => $bank_name, "sube" => $bank_sube, "hesap" => $bank_hesap, "iban" => $bank_iban, "alici" => $bank_alici, "id" => $id));
                        if ($update) :
                            $conn->commit();
                            $referrer = base_url("admin/settings/bank-accounts");
                            $error = 1;
                            $errorText = "İşlem başarılı";
                            $icon = "success";
                        else :
                            $conn->rollBack();
                            $error = 1;
                            $errorText = "İşlem başarısız";
                            $icon = "error";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                elseif (route(4) == "delete" && route(4)) :
                    $id = route(5);
                    if (!countRow(["table" => "bank_accounts", "where" => ["id" => $id]])) :
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli ödeme bonusu seçin";
                    else :
                        $delete = $conn->prepare("DELETE FROM bank_accounts WHERE id=:id ");
                        $delete->execute(array("id" => $id));
                        if ($delete) :
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/settings/bank-accounts");
                        else :
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;
                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 0]);
                    exit();
                elseif (!route(4)) :
                    $bankList = $conn->prepare("SELECT * FROM bank_accounts ");
                    $bankList->execute(array());
                    $bankList = $bankList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['bankList'] = $bankList;
                else :

                    echo "<script>window.location.href = '" . base_url('admin/settings/bank-accounts') . "'; </script>";
                    header("Location:" . base_url("admin/settings/bank-accounts"));
                endif;
            endif;
            if (route(6)) : header("Location:" . base_url("admin/settings/bank-accounts"));
            endif;
        elseif (route(3) == "alert" && route(3)) :
            $titleAdmin = "Bildirimler";
            $access = $this->getuser["access"]["alert_settings"];
            if ($access) :

                if ($_POST) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET 
    admin_mail=:mail,
    admin_telephone=:telephone,
    alert_type=:alert_type,
    resetpass_sms=:resetsms,
    resetpass_email=:resetmail,
    sms_provider=:sms_provider,
    sms_title=:sms_title,
    sms_user=:sms_user,
    sms_pass=:sms_pass,
    smtp_user=:smtp_user,
    smtp_pass=:smtp_pass,
    smtp_server=:smtp_server,
    smtp_port=:smtp_port,
    smtp_protocol=:smtp_protocol,
    smtp_type=:smtp_type
    WHERE id=:id ");
                    $update = $update->execute(array(
                        "id" => 1,
                        "mail" => $admin_mail,
                        "telephone" => $admin_telephone,
                        "alert_type" => $alert_type,
                        "resetsms" => $resetsms,
                        "resetmail" => $resetmail,
                        "sms_provider" => $sms_provider,
                        "sms_title" => $sms_title,
                        "sms_user" => $sms_user,
                        "sms_pass" => $sms_pass,
                        "smtp_user" => $smtp_user,
                        "smtp_pass" => $smtp_pass,
                        "smtp_server" => $smtp_server,
                        "smtp_port" => $smtp_port,
                        "smtp_protocol" => $smtp_protocol,
                        "smtp_type" => $smtp_type
                    ));

                    if ($update) :
                        $conn->commit();

                        echo "<script>window.location.href = '" . base_url('admin/settings/alert') . "'; </script>";
                        header("Location:" . base_url("admin/settings/alert"));
                        $_SESSION["client"]["data"]["success"] = 1;
                        $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
                    else :
                        $conn->rollBack();
                        $ayar['error'] = 1;
                        $ayar['errorText'] = "İşlem başarısız";
                    endif;
                endif;

                if (route(4) == 'on' && route(4)) {

                    $get = !route(5) ? '1' : route(5);
                    $update = $conn->prepare("UPDATE settings SET $get=:get WHERE id=:id ");
                    $update = $update->execute(array("id" => 1, 'get' => 2));
                } elseif (route(4) == 'off' && route(4)) {
                    $get = route(5);
                    $update = $conn->prepare("UPDATE settings SET $get=:$get WHERE id=:id ");
                    $update = $update->execute(array("id" => 1, "$get" => 1));
                }

            endif;
            if (route(4)) :
                echo "<script>window.location.href = '" . base_url('admin/settings/alert') . "'; </script>";
            endif;
            if (route(4)) : header("Location:" . base_url("admin/settings/alert"));
            endif;

        elseif (route(3) == "modules" && route(3)) :
            $access = $this->getuser["access"]["modules"];
            if ($access) :

                if (route(4) == "module_child" && $_POST) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET panel_selling=:panel_selling, panel_price=:panel_price WHERE id=:id ");
                    $update = $update->execute(array("panel_selling" => $panel_selling, "panel_price" => $panel_price, "id" => 1));

                    if ($panel_selling == 1) :
                        $update2 = $conn->prepare("UPDATE modules SET status=:status WHERE id=:id ");
                        $update2 = $update2->execute(array("status" => 1, "id" => 2));
                    endif;

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/modules");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();

                elseif (route(4) == "module_balance" && $_POST) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET free_balance=:free, free_amount=:amount WHERE id=:id ");
                    $update = $update->execute(array("free" => $free_balance, "amount" => $free_amount, "id" => 1));

                    if ($free_balance == 1) :
                        $update2 = $conn->prepare("UPDATE modules SET status=:status WHERE id=:id ");
                        $update2 = $update2->execute(array("status" => 1, "id" => 3));
                    endif;

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/modules");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();

                elseif (route(4) == "module_cache" && $_POST) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET cache=:cache, cache_time=:cache_time WHERE id=:id ");
                    $update = $update->execute(array("cache" => $cache, "cache_time" => $cache_time, "id" => 1));

                    if ($cache == 1) :
                        $update2 = $conn->prepare("UPDATE modules SET status=:status WHERE id=:id ");
                        $update2 = $update2->execute(array("status" => 1, "id" => 7));
                    endif;

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/modules");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();

                elseif (route(4) == "ref" && $_POST) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET referral=:referral, ref_bonus=:ref_bonus, ref_max=:ref_max, ref_type=:ref_type WHERE id=:id ");
                    $update = $update->execute(array("referral" => $referral, "ref_bonus" => $ref_bonus, "ref_max" => $ref_max, "ref_type" => $ref_type, "id" => 1));

                    if ($referral == 1) :
                        $update2 = $conn->prepare("UPDATE modules SET status=:status WHERE id=:id ");
                        $update2 = $update2->execute(array("status" => 1, "id" => 1));
                    endif;

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/modules");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();


                endif;
                $id = route(5);

                if ($id) :

                    if (route(4) == "enable") :
                        $status = 2;
                    elseif (route(4) == "disable") :
                        $status = 1;
                    endif;

                    if ($id == 2 && $status == 2) :
                        $update = $conn->prepare("UPDATE settings SET panel_selling=:panel_selling WHERE id=:id ");
                        $update = $update->execute(array("panel_selling" => 2, "id" => 1));
                    elseif ($id == 2 && $status == 1) :
                        $update = $conn->prepare("UPDATE settings SET panel_selling=:panel_selling WHERE id=:id ");
                        $update = $update->execute(array("panel_selling" => 1, "id" => 1));
                    elseif ($id == 3 && $status == 2) :
                        $update = $conn->prepare("UPDATE settings SET free_balance=:free_balance WHERE id=:id ");
                        $update = $update->execute(array("free_balance" => 2, "id" => 1));
                    elseif ($id == 3 && $status == 1) :
                        $update = $conn->prepare("UPDATE settings SET free_balance=:free_balance WHERE id=:id ");
                        $update = $update->execute(array("free_balance" => 1, "id" => 1));
                    elseif ($id == 1 && $status == 2) :
                        $update = $conn->prepare("UPDATE settings SET referral=:referral WHERE id=:id ");
                        $update = $update->execute(array("referral" => 2, "id" => 1));
                    elseif ($id == 1 && $status == 1) :
                        $update = $conn->prepare("UPDATE settings SET referral=:referral WHERE id=:id ");
                        $update = $update->execute(array("referral" => 1, "id" => 1));
                    elseif ($id == 7 && $status == 2) :
                        $update = $conn->prepare("UPDATE settings SET cache=:cache WHERE id=:id ");
                        $update = $update->execute(array("cache" => 2, "id" => 1));
                    elseif ($id == 7 && $status == 1) :
                        $update = $conn->prepare("UPDATE settings SET cache=:cache WHERE id=:id ");
                        $update = $update->execute(array("cache" => 1, "id" => 1));
                    elseif ($id == 6 && $status == 2) :
                        $update = $conn->prepare("UPDATE settings SET guard_system_status=:guard_system_status WHERE id=:id ");
                        $update = $update->execute(array("guard_system_status" => 2, "id" => 1));
                    elseif ($id == 6 && $status == 1) :
                        $update = $conn->prepare("UPDATE settings SET guard_system_status=:guard_system_status WHERE id=:id ");
                        $update = $update->execute(array("guard_system_status" => 1, "id" => 1));
                    endif;

                    $update = $conn->prepare("UPDATE modules SET status=:status WHERE id=:id");
                    $update = $update->execute(array("id" => $id, "status" => $status));

                endif;
                $active_modules = $conn->prepare("SELECT * FROM modules WHERE modules.status=:statu && modules.mod_sec=:mod");
                $active_modules->execute(array("statu" => "2", "mod" => 1));
                $active_modules = $active_modules->fetchAll(PDO::FETCH_ASSOC);
                $ayar['active_modules'] = $active_modules;
                $passive_modules = $conn->prepare("SELECT * FROM modules WHERE modules.status=:statu && modules.mod_sec=:mod");
                $passive_modules->execute(array("statu" => "1", "mod" => 1));
                $passive_modules = $passive_modules->fetchAll(PDO::FETCH_ASSOC);
                $ayar['passive_modules'] = $passive_modules;

            endif;

            if (route(4)) :
                echo "<script>window.location.href = '" . base_url('admin/settings/modules') . "'; </script>";
            endif;
            if (route(4)) : header("Location:" . base_url("admin/settings/modules"));
            endif;

        elseif (route(3) == "integrations" && route(3)) :
            $access = $this->getuser["access"]["modules"];
            if ($access) :

                if (route(4) == "edit" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE integrations SET code=:code, visibility=:visibility WHERE id=:id ");
                    $update = $update->execute(array("code" => $code, "visibility" => $visibility, "id" => route(5)));

                    if ($code == "") :
                        $update2 = $conn->prepare("UPDATE integrations SET status=:status WHERE id=:id ");
                        $update2 = $update2->execute(array("status" => 1, "id" => route(5)));
                    endif;

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/integrations");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                endif;

                if (route(4) == "seo" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET site_title=:title, site_keywords=:site_keywords, site_description=:site_description WHERE id=:id ");
                    $update = $update->execute(array("title" => $title, "site_keywords" => $keywords, "site_description" => $description, "id" => '1'));


                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/integrations");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                endif;

                if (route(4) == "google" && $_POST && route(4)) :
                    foreach ($_POST as $key => $value) {
                        $$key = $value;
                    }

                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE settings SET recaptcha_key=:key, recaptcha_secret=:secret WHERE id=:id ");
                    $update = $update->execute(array("key" => $pwd, "secret" => $secret, "id" => 1));

                    if ($update) :
                        $conn->commit();
                        $referrer = base_url("admin/settings/integrations");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else :
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;

                    echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer, "time" => 1]);
                    exit();
                endif;
                if (route(4) == "enabled" && route(4)) {
                    if (route(5) == 13) {
                        $update = $conn->prepare("UPDATE settings SET recaptcha=:recaptcha WHERE id=:id ");
                        $update = $update->execute(array("recaptcha" => 2, "id" => 1));
                    }
                    $update = $conn->prepare("UPDATE integrations SET status=:status WHERE id=:id ");
                    $update = $update->execute(array("status" => 2, "id" => route(5)));
                    echo "<script>window.location.href = '" . base_url('admin/settings/integrations') . "'; </script>";
                    header("Location:" . base_url("admin/settings/integrations"));
                }

                if (route(4) == "disabled" && route(4)) {
                    if (route(5) == 13) {
                        $update = $conn->prepare("UPDATE settings SET recaptcha=:recaptcha WHERE id=:id ");
                        $update = $update->execute(array("recaptcha" => 1, "id" => 1));
                    }
                    $update = $conn->prepare("UPDATE integrations SET status=:status WHERE id=:id ");
                    $update = $update->execute(array("status" => 1, "id" => route(5)));
                    echo "<script>window.location.href = '" . base_url('admin/settings/integrations') . "'; </script>";
                    header("Location:" . base_url("admin/settings/integrations"));
                }

                $active = $conn->prepare("SELECT * FROM integrations WHERE status=:status");
                $active->execute(array("status" => "2"));
                $active = $active->fetchAll(PDO::FETCH_ASSOC);
                $ayar['active'] = $active;
                $other = $conn->prepare("SELECT * FROM integrations WHERE status=:status");
                $other->execute(array("status" => "1"));
                $other = $other->fetchAll(PDO::FETCH_ASSOC);
                $ayar['other'] = $other;


            endif;
        //  if( route(4) ): header("Location:".base_url("admin/settings/integrations")); endif;


        elseif (route(3) == "subject" && route(3)) :

            $access = $this->getuser["access"]["subject"];
            if ($access) :

                if (route(4) == "edit" && route(4)) :
                    if ($_POST) :
                        $id = route(5);
                        foreach ($_POST as $key => $value) {
                            $$key = $value;
                        }

                        if (empty($subject)) :
                            $error = 1;
                            $errorText = "Lütfen başlık yazınız.";
                            $icon = "error";
                            $referrer = base_url("admin/settings/subject/edit/" . $id);
                        else :
                            $update = $conn->prepare("UPDATE ticket_subjects SET subject=:subject, content=:content, auto_reply=:auto_reply WHERE subject_id=:id ");
                            $update->execute(array("id" => $id, "subject" => $subject, "content" => $content, "auto_reply" => $auto_reply));
                            if ($update) :
                                $success = 1;
                                $successText = "İşlem başarılı";
                                $referrer = base_url("admin/settings/subject/edit/" . $id);
                            else :
                                $error = 1;
                                $errorText = "İşlem başarısız";
                                $referrer = base_url("admin/settings/subject/edit/");
                            endif;
                        endif;
                    endif;
                    $post = $conn->prepare("SELECT * FROM ticket_subjects WHERE subject_id=:id");
                    $post->execute(array("id" => route(5)));
                    $post = $post->fetch(PDO::FETCH_ASSOC);
                    $ayar['post'] = $post;

                    if (!$post) : header("Location:" . base_url("admin/settings/subject"));

                        echo "<script>window.location.href = '" . base_url('admin/settings/subject') . "'; </script>";
                    endif;

                elseif (!route(4)) :


                    if ($_POST) :

                        foreach ($_POST as $key => $value) {
                            $$key = $value;
                        }


                        if (empty($subject)) :
                            $error = 1;
                            $errorText = "Lütfen başlık yazınız.";
                            $icon = "error";
                        else :


                            $insert = $conn->prepare("INSERT INTO ticket_subjects SET subject=:subject, content=:content, auto_reply=:auto_reply");

                            $insert = $insert->execute(array("subject" => $subject, "content" => $content, "auto_reply" => $auto_reply));

                            if ($insert) :
                                $success = 1;
                                $successText = "İşlem başarılı";
                                $referrer = base_url("admin/settings/subject");
                            else :
                                $error = 1;
                                $errorText = "İşlem başarısız";
                            endif;
                        endif;
                    endif;


                    $subjectList = $conn->prepare("SELECT * FROM ticket_subjects ORDER BY subject_id DESC ");
                    $subjectList->execute(array());
                    $subjectList = $subjectList->fetchAll(PDO::FETCH_ASSOC);
                    $ayar['subjectList'] = $subjectList;

                elseif (route(4) == "delete" && route(4)) :
                    $id = route(5);
                    if (!countRow(["table" => "ticket_subjects", "where" => ["subject_id" => $id]])) :
                        $error = 1;
                        $icon = "error";
                        $errorText = "Lütfen geçerli ödeme bonusu seçin";
                    else :
                        $delete = $conn->prepare("DELETE FROM ticket_subjects WHERE subject_id=:id ");
                        $delete->execute(array("id" => $id));

                        if ($delete) :
                            $error = 1;
                            $icon = "success";
                            $errorText = "İşlem başarılı";
                            $referrer = base_url("admin/settings/subject");
                        else :
                            $error = 1;
                            $icon = "error";
                            $errorText = "İşlem başarısız";
                        endif;
                    endif;

                    echo "<script>window.location.href = '" . base_url('admin/settings/subject') . "'; </script>";
                    header("Location:" . base_url("admin/settings/subject"));
                    exit();
                else :

                    echo "<script>window.location.href = '" . base_url('admin/settings/subject') . "'; </script>";
                    header("Location:" . base_url("admin/settings/subject"));
                endif;
            endif;
            if (route(6)) : header("Location:" . base_url("admin/settings/subject"));

                echo "<script>window.location.href = '" . base_url('admin/settings/subject') . "'; </script>";
            endif;

        endif;
        $ayar['menuList'] = $menuList;
        $ayar['access'] = $access;
        $settins_s = new \App\Models\settings();
        $ayar['settings'] = $settins_s->where('id', '1')->get()->getResultArray()[0];
        //return view('admin/settings', $ayar);
        return view('admin/yeni_admin/settings', $ayar);
    }

    function api_balance()
    {

        global $conn;
        global $_SESSION;
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();

        $api_details = $conn->prepare("SELECT * FROM service_api");
        $api_details->execute(array());
        $api_details = $api_details->fetchAll(PDO::FETCH_ASSOC);

        $ayar = array(
            'user' => $this->getuser,
            'settings' => $this->settings,
            'smmapi' => $smmapi,
            'api_details' => $api_details
        );
        return view('admin/yeni_admin/api_balance', $ayar);
        return view('admin/api_balance', $ayar);
    }
}
