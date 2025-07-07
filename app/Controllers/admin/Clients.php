<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use http\Exception;
use PDO;
use SMMApi;

class Clients extends Ana_Controller
{

    function index()
    {

        global $conn;
        global $_SESSION;
        $user = new \App\Models\clients();
        $settings = $this->settings;
                if ($settings["sms_provider"] == "bizimsms"):
            include APPPATH . 'ThirdParty/SendSms/bizimsms.php';
        elseif ($settings["sms_provider"] == "netgsm"):
            include APPPATH . 'ThirdParty/SendSms/netgsm.php';
            else:
                
            include APPPATH . 'ThirdParty/SendSms/smsvitrini.php';
        endif;
        $referrer = base_url('admin/clients');
        if (!route(3)):
            $page = 1;
        elseif (is_numeric(route(3))):
            $page = route(2);
        elseif (!is_numeric(route(3))):
            $action = route(3);
        endif;
        if (empty($action)):
            $alluser = $user->orderBy('client_id', "DESC")->get()->getResultArray();
            $page = 1;
            $pageCount = 1;
            $ayar = array(
                'title' => 'Client',
                'user' => $this->getuser,
                'route' => $this->request->uri->getSegment(2),
                'settings' => $this->settings,
                'success' => 0,
                'error' => 0,
                'search_word' => '',
                'search_where' => 'username',
                'clients' => $alluser,
                'paginationArr' => ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1]
            );
            return view('admin/yeni_admin/clients', $ayar);
            return view('admin/clients', $ayar);
        elseif ($action == "new" && $action):
            $user = $this->getuser;
            if ($_POST):

                $isim = $_POST["first_name"];
                $soyisim = $_POST["last_name"];
                $email = $_POST["email"];
                $username = $_POST["username"];
                $pass = $_POST["password"];
                $tel = $_POST["telephone"];

                if ($settings["guard_roles_status"] == 2 && $settings["guard_system_status"] == 2) {

                    if ($settings["guard_roles_type"] == 2) {
                        guardDeleteAllRoles();

                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için tüm yetkileri alındı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                    } elseif ($settings["guard_roles_type"] == 1) {
                        guardLogout();
                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için üye oturumu sonlandırıldı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                    }

                } else {

                    if ($user["access"]["admins"]):
                        $access = $_POST["access"];
                        $admin = $_POST["access"]["admin_access"];
                    endif;

                }

                $debit = $_POST["balance_type"];
                $debit_limit = $_POST["debit_limit"];

                if (!email_check($email)) {
                    $error = 1;
                    $errorText = "Lütfen geçerli email formatı girin.";
                    $icon = "error";
                } elseif (userdata_check("email", $email)) {
                    $error = 1;
                    $errorText = "Girdiğiniz email adresi kullanılıyor.";
                    $icon = "error";
                } elseif (!username_check($username)) {
                    $error = 1;
                    $errorText = "Kullanıcı adı içerisinde harf ve sayılar içeren en az 4 en fazla 32 karakterli olmalı.";
                    $icon = "error";
                } elseif (userdata_check("username", $username)) {
                    $error = 1;
                    $errorText = "Belirttiğiniz kullanıcı adı kullanılıyor.";
                    $icon = "error";
                } elseif ($settings["skype_area"] == 2 && empty($tel)) {
                    $error = 1;
                    $errorText = "Telefon numarası boş olamaz";
                    $icon = "error";
                } elseif (strlen($pass) < 8) {
                    $error = 1;
                    $errorText = "Parola en az 8 karakterli olmalı.";
                    $icon = "error";
                } else {
                    $apikey = CreateApiKey($_POST);
                    $conn->beginTransaction();
                    $insert = $conn->prepare("INSERT INTO clients SET first_name=:name, last_name=:lname, balance_type=:balance_type, debit_limit=:debit_limit,  username=:username, email=:email, password=:pass, telephone=:phone, register_date=:date, apikey=:key, access=:access ");
                    $insert = $insert->execute(array("name" => $isim, "lname" => $soyisim, "debit_limit" => $debit_limit, "balance_type" => $debit, "username" => $username, "email" => $email, "pass" => md5(sha1(md5($pass))), "phone" => $tel, "date" => date("Y.m.d H:i:s"), 'key' => $apikey, 'access' => json_encode($access)));
                    if ($insert):
                        $conn->commit();
                        $referrer = base_url("admin/clients");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                }
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "edit" && $action):
            $user = $this->getuser;
            $username = route(4);
            if (!countRow(["table" => "clients", "where" => ["username" => $username]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $client_detail = getRow(["table" => "clients", "where" => ["username" => $username]]);
            $client_access = json_decode($client_detail["access"], true);
            if ($_POST):
                $isim = $_POST["first_name"];
                $soyisim = $_POST["last_name"];
                $usernagme = $_POST["username"];
                $email = $_POST["email"];
                $tel = $_POST["telephone"];

                if ($settings["guard_roles_status"] == 2 && $settings["guard_system_status"] == 2) {

                    if ($settings["guard_roles_type"] == 2) {
                        guardDeleteAllRoles();

                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için tüm yetkileri alındı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                    } elseif ($settings["guard_roles_type"] == 1) {
                        guardLogout();
                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için üye oturumu sonlandırıldı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                    }

                } else {

                    if (isset($user["access"]["admins"])):
                        $access = isset($_POST["access"])?$_POST["access"]:0;
                        $admin =  isset($_POST["access"]["admin_access"])?$_POST["access"]["admin_access"]:0;
                        $access = $_POST["access"]["admin_access"] == 0 ?"":$access;
                    endif;

                }


                $debit = $_POST["balance_type"];
                $debit_limit = $_POST["debit_limit"];

                if (!email_check($email)) {
                    $error = 1;
                    $errorText = "Lütfen geçerli email formatı girin.";
                    $icon = "error";
                } elseif ($conn->query("SELECT * FROM clients WHERE username!='$username' && email='$email' ")->rowCount()) {
                    $error = 1;
                    $errorText = "Girdiğiniz email adresi kullanılıyor.";
                    $icon = "error";
                } elseif (!username_check($username)) {
                    $error = 1;
                    $errorText = "Kullanıcı adı içerisinde harf ve sayılar içeren en az 4 en fazla 32 karakterli olmalı.";
                    $icon = "error";
                    if (empty($phone)):
                        $error = 1;
                        $errorText = "Telefon numarası boş olamaz";
                        $icon = "error";
                    endif;
                } else {
                    $apikey = CreateApiKey($_POST);
                    $conn->beginTransaction();
                    $insert = $conn->prepare("UPDATE clients SET first_name=:name, last_name=:lname, username=:username, balance_type=:balance_type, debit_limit=:debit_limit,  email=:email, telephone=:phone, register_date=:date, access=:access WHERE username=:id ");
                    $insert = $insert->execute(array("id" => route(4), "name" => $isim, "lname" => $soyisim, "username" => $usernagme, "balance_type" => $debit, "debit_limit" => $debit_limit, "email" => $email, "phone" => $tel, "date" => date("Y.m.d H:i:s"), 'access' => json_encode($access)));

                    if ($insert):
                        $conn->commit();
                        $referrer = base_url("admin/clients");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                }
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "pass" && $action):
            $username = route(4);
            if (!countRow(["table" => "clients", "where" => ["username" => $username]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $client_detail = getRow(["table" => "clients", "where" => ["username" => $username]]);
            $client_access = json_decode($client_detail["access"], true);
            if ($_POST):
                $password = $_POST["password"];

                if (strlen($password) < 8) {
                    $error = 1;
                    $errorText = "Parola en az 8 karakterli olmalı.";
                    $icon = "error";
                } else {
                    $conn->beginTransaction();
                    $insert = $conn->prepare("UPDATE clients SET password=:pass WHERE username=:id ");
                    $insert = $insert->execute(array("id" => route(4), "pass" => md5(sha1(md5($password)))));
                    if ($insert):
                        $conn->commit();
                        $referrer = base_url("admin/clients");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                }
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "export" && $action):
            if ($_POST):
                $format = $_POST["format"]; // XML,CSV,JSON
                $export_status = $_POST["client_status"]; // Tümü (-1), Aktif (1), Pasif (0)
                $colums = $_POST["exportcolumn"]; //Üye bilgileri
                $export = array();

                $row = $conn->prepare("SELECT * FROM clients $where ORDER BY client_id DESC ");
                $row->execute(array());
                $row = $row->fetchAll(PDO::FETCH_OBJ);
                $rows = json_encode($row);


                if ($format == "json"):
                    $fp = fopen('users.json', 'w');
                    fwrite($fp, json_encode($row, JSON_PRETTY_PRINT));
                    fclose($fp);
                    force_download('users.json');
                    unlink('users.json');
                endif;

            endif;
        elseif ($action == "price" && $action):
            if ($_POST):
                $client = route(4);
                foreach ($_POST["price"] as $id => $price):
                    if ($price == null):
                        $delete = $conn->prepare("DELETE FROM clients_price WHERE client_id=:client && service_id=:service ");
                        $delete->execute(array("service" => $id, "client" => $client));
                    elseif (getRow(["table" => "clients_price", "where" => ["client_id" => $client, "service_id" => $id]])):
                        $update = $conn->prepare("UPDATE clients_price SET client_id=:client, service_price=:price WHERE service_id=:service && client_id=:clientt ");
                        $update->execute(array("service" => $id, "client" => $client, "clientt" => $client, "price" => $price));
                    else:
                        $insert = $conn->prepare("INSERT INTO clients_price SET client_id=:client, service_price=:price, service_id=:service ");
                        $insert->execute(array("service" => $id, "client" => $client, "price" => $price));
                    endif;
                endforeach;
                $error = 1;
                $errorText = "İşlem başarılı";
                $icon = "success";
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
                exit();
            endif;
            $username = route(4);
            if (!countRow(["table" => "clients", "where" => ["username" => $username]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $client_detail = getRow(["table" => "clients", "where" => ["username" => $username]]);
            $client_access = json_decode($client_detail["access"], true);
            $services = $conn->prepare("SELECT * FROM services ORDER BY service_id ASC ");
            $services->execute(array());
            $services = $services->fetchAll(PDO::FETCH_ASSOC);
            $serviceList = [];
            foreach ($services as $service) {
                $price = getRow(["table" => "clients_price", "where" => ["service_id" => $service["service_id"], "client_id" => $client_detail["client_id"]]]);
                $service["client_price"] = $price["service_price"];
                array_push($serviceList, $service);
            }
        elseif ($action == "active" && $action):

            $client_id = route(4);
            if (countRow(["table" => "clients", "where" => ["client_id" => $client_id, "client_type" => 2]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $update = $conn->prepare("UPDATE clients SET client_type=:type WHERE client_id=:id ");
            $update->execute(array("type" => 2, "id" => $client_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/clients"));
        elseif ($action == "deactive" && $action):
            $client_id = route(4);
            if (countRow(["table" => "clients", "where" => ["client_id" => $client_id, "client_type" => 1]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $update = $conn->prepare("UPDATE clients SET client_type=:type WHERE client_id=:id ");
            $update->execute(array("type" => 1, "id" => $client_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/clients"));
        elseif ($action == "del_price" && $action):
            $client_id = route(4);
            if (!countRow(["table" => "clients_price", "where" => ["client_id" => $client_id]])): $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "Üyeye ait fiyatlandırma bulunamadı.";
                header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $delete = $conn->prepare("DELETE FROM clients_price  WHERE client_id=:id ");
            $delete->execute(array("id" => $client_id));
            if ($delete):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/clients"));
        elseif ($action == "change_apikey" && $action):
            $client_id = route(4);
            $client_detail = getRow(["table" => "clients", "where" => ["client_id" => $client_id]]);
            $apikey = CreateApiKey(["email" => $client_detail["email"], "username" => $client_detail["username"]],$client_detail["email"],$client_detail["username"]);

            if (countRow(["table" => "clients", "where" => ["client_id" => $client_id, "client_type" => 1]])): header("Location:" . base_url("admin/clients"));
                exit(); endif;
            $update = $conn->prepare("UPDATE clients SET apikey=:key WHERE client_id=:id ");
            $update->execute(array("key" => $apikey, "id" => $client_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/clients"));

        elseif ($action == "login" && $action):
            $client_id = route(4);

            $client_detail = getRow(["table" => "clients", "where" => ["client_id" => $client_id]]);

            unset($_SESSION["neira_userid"]);
            unset($_SESSION["neira_userpass"]);
            unset($_SESSION["neira_userlogin"]);
            setcookie("u_id", $client_detail["client_id"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_password", $client_detail["password"], strtotime('+7 days'), '/', null, null, true);
            setcookie("u_login", 'ok', strtotime('+7 days'), '/', null, null, true);
            $session = \Config\Services::session();
            $session->set(array(
                "neira_userlogin" => 1,
                "neira_userid" => $client_detail["client_id"],
                "neira_userpass" => $client_detail["password"]
            ));

            $_SESSION["neira_userlogin"] = 1;
            $_SESSION["neira_userid"] = $client_detail["client_id"];
            $_SESSION["neira_userpass"] = $client_detail["password"];
            return redirect()->to(base_url());


        elseif ($action == "secret_category" && $action):
            $client = route(4);
            $type = $_GET["type"];
            $id = $_GET["id"];
            if ($type == "on"):
                $search = $conn->query("SELECT * FROM clients_category WHERE client_id='$client' && category_id='$id' ");
                if (!$search->rowCount()):
                    $insert = $conn->prepare("INSERT INTO clients_category SET client_id=:client, category_id=:c_id  ");
                    $insert->execute(array("client" => $client, "c_id" => $id));
                    if ($insert):
                        echo "1";
                    else:
                        echo "0";
                    endif;
                else:
                    echo "0";
                endif;
            elseif ($type == "off"):
                $search = $conn->query("SELECT * FROM clients_category WHERE client_id='$client' && category_id='$id' ");
                if ($search->rowCount()):
                    $delete = $conn->prepare("DELETE FROM clients_category WHERE client_id=:client && category_id=:c_id  ");
                    $delete->execute(array("client" => $client, "c_id" => $id));
                    if ($delete):
                        echo "1";
                    else:
                        echo "0";
                    endif;
                else:
                    echo "0";
                endif;
            endif;
        elseif ($action == "secret_service" && $action):
            $client = route(4);
            $type = $_GET["type"];
            $id = $_GET["id"];
            if ($type == "on"):
                $search = $conn->query("SELECT * FROM clients_service WHERE client_id='$client' && service_id='$id' ");
                if (!$search->rowCount()):
                    $insert = $conn->prepare("INSERT INTO clients_service SET client_id=:client, service_id=:c_id   ");
                    $insert->execute(array("client" => $client, "c_id" => $id));
                    if ($insert):
                        echo "1";
                    else:
                        echo "0";
                    endif;
                else:
                    echo "0";
                endif;
            elseif ($type == "off"):
                $search = $conn->query("SELECT * FROM clients_service WHERE client_id='$client' && service_id='$id' ");
                if ($search->rowCount()):
                    $delete = $conn->prepare("DELETE FROM clients_service WHERE client_id=:client && service_id=:c_id  ");
                    $delete->execute(array("client" => $client, "c_id" => $id));
                    if ($delete):
                        echo "1";
                    else:
                        echo "0";
                    endif;
                else:
                    echo "0";
                endif;
            endif;
        elseif ($action == "alert" && $action):
            if ($settings["guard_notify_status"] == 2 && $settings["guard_system_status"] == 2) {

                if ($settings["guard_notify_type"] == 2) {
                    guardDeleteAllRoles();

                    $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                    $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için tüm yetkileri alındı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                } elseif ($settings["guard_notify_type"] == 1) {
                    guardLogout();
                    $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                    $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Yetki Verme</strong> İşlemi yapıldığı için üye oturumu sonlandırıldı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                }

            } else {

                $subject = $_POST["subject"];
                $type = $_POST["alert_type"];
                $message = $_POST["message"];
                $user = $_POST["user_type"];
                $username = $_POST["username"];
                if ($user == "secret" && !getRow(["table" => "clients", "where" => ["username" => $username]])):
                    $error = 1;
                    $errorText = "Kullanıcı bulunamadı";
                    $icon = "error";
                elseif (empty($message)):
                    $error = 1;
                    $errorText = "Bildirim Mesajı boş olamaz";
                    $icon = "error";
                elseif ($type == "email" && $user == "all"):

                    ## tüm üyelerin bilgilerini aldık başla ##


                    $users = $conn->prepare("SELECT * FROM clients ");
                    $users->execute(array());
                    $users = $users->fetchAll(PDO::FETCH_ASSOC);
                    $email = array();

                    foreach ($users as $user):
                        $email[] = $user["email"];
                    endforeach;


                    ## tüm üyelerin bilgilerini aldık bitiş ##

                    ## mail gönder başla ##
                    sendMail(["subject" => $subject, "body" => $message, "mail" => $email]);
                    ## mail gönder bitiş ##

                    ## başarılı sonuç başla ##
                    $error = 1;
                    $errorText = "İşlem başarılı";
                    $icon = "success";
                ## başarılı sonuç bitiş ##

                elseif ($type == "email" && $user == "secret"):
                    $user = getRow(["table" => "clients", "where" => ["username" => $username]]);
                    if (sendMail(["subject" => $subject, "body" => $message, "mail" => $user["email"]])):
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                elseif ($type == "sms" && $user == "secret"):
                    $user = getRow(["table" => "clients", "where" => ["username" => $username]]);
                    $sms = SMSUser($user["telephone"], $message);
                    if ($sms):
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                elseif ($type == "sms" && $user == "all"):
                    $users = $conn->prepare("SELECT * FROM clients ");
                    $users->execute(array());
                    $users = $users->fetchAll(PDO::FETCH_ASSOC);
                    $tel = "";
                    foreach ($users as $user):
                        $tel .= "<no>" . $user["telephone"] . "</no>";
                    endforeach;
                    $sms = SMSToplu($tel, $message);
                    if ($sms):
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);


            }
        endif;


    }

    function detail()
    {
        if ($this->request->uri->getSegment(3)) {
            $id = $this->request->uri->getSegment(3);
            $clients = new \App\Models\clients();
            $ticket = new \App\Models\tickets();
            $order = new \App\Models\orders();
            $payments = new \App\Models\payments();
            if ($clients->where('client_id', $id)->countAllResults()) {
                $client_detail = $clients->where('client_id', $id)->get()->getResultArray()[0];
                $last5ticket = $ticket->where('client_id', $id)->limit(5)->orderBy('ticket_id', 'DESC')->get()->getResultArray();
                $last5order = $order->where('orders.client_id', $id)->join('services', 'orders.service_id = services.service_id')->limit(5)->orderBy('orders.order_id', 'DESC')->get()->getResultArray();
                $last5payments = $payments->select("*")
                    ->where('payments.client_id', $id)
                    ->join('payment_methods', 'payment_methods.id=payments.payment_method')
                    ->join('clients', 'clients.client_id=payments.client_id')->orderBy('payment_id', 'DESC')->limit(5)->get()->getResultArray();
                $ayar = array(
                    'title' => 'Üye Detay ' . $client_detail['username'],
                    'user' => $this->getuser,
                    'route' => $this->request->uri->getSegment(2),
                    'settings' => $this->settings,
                    'success' => 0,
                    'error' => 0,
                    'search_word' => '',
                    'search_where' => 'username',
                    'client' => $client_detail,
                    'ticket' => $last5ticket,
                    'order' => $last5order,
                    'payments' => $last5payments

                );
                return view('admin/yeni_admin/client-detail', $ayar);
            }
        }
    }
}