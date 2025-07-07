<?php


namespace App\Controllers\admin;

use App\Models\service_api;
use CodeIgniter\Controller;
use PDO;
use ZipArchive;
class Main extends Ana_Controller
{
    function __construct()
    {
        date_default_timezone_set('Europe/Istanbul');
        include FCPATH . "Glycon.php";
        $session = \Config\Services::session();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $session->start();
        $this->key = LISANCEKEY;
        $user = new \App\Models\clients();
        $settings = new \App\Models\settings();
        $this->settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if ($session->get('neira_userid')) {
            $getuser = $user->where('client_id', $session->get('neira_userid'))->get()->getResultArray()[0];
            $getuser['access'] = json_decode($getuser['access'], true);
            $this->getuser = $getuser;
        }
        $this->db = db_connect();

    }

    function index()
    {
        //last 5 ticket
        $ticket = new \App\Models\tickets();
        $last_ticket = $ticket->select('tickets.ticket_id,clients.email,tickets.subject,tickets.status')->orderBy('tickets.ticket_id', 'desc')->join('clients', 'clients.client_id = tickets.client_id')->limit(5)->get()->getResultArray();

        //actvie ticket
        $active_ticket = $ticket->where('status!=', 'closed')->countAllResults();


        //last 5 orders
        $order = new \App\Models\orders();
        $last_order = $order
            ->select('*')
            ->join('services', 'services.service_id = orders.service_id')
            ->orderBy('orders.order_id', 'desc')
            ->limit(5)
            ->get()->getResultArray();
        //istatislik


        // Api
        $api = new service_api();
        $service_api = $api
            ->get()
            ->getResultArray();

        // Ciro
        /*
        ->where(array("order_status!="=> "pending"))
        ->where(array("order_status!="=> "inprogress"))
        ->where(array("order_status!="=> "canceled"))
        ->where(array("order_status!="=> "processing"))
        */
        $todaydate    =date('Y-m-d');
        $order = new \App\Models\orders();
        $orders = $order->select("*")
        ->whereIn('order_status',['completed','partial'])
        ->where('order_create>=', $todaydate)
        ->get()->getResultArray();
        $toplam = 0;
        $kar = 0;
        foreach ($orders as $toplams) {

            $toplam += $toplams['order_charge'];
            $kar += $toplams['order_charge']-$toplams['order_profit'];
        }

        $fail = $order->join("services", "services.service_id = orders.service_id")->where("orders.order_error!=", "-")->where("services.service_api !=", 0)->countAllResults();
        $ayar = array(
            'title' => 'Main',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'tickets' => $last_ticket,
            'orders' => $last_order,
            'active_ticket' => $active_ticket,
            'ciro' => $toplam,
            'fail' => $fail,
            'api' => $service_api,
            "kar" => $kar
        );
        return view('admin/yeni_admin/main', $ayar);

    }

    function ajax()
    {
        if ($this->request->getPost('not')) {
            $not = $this->request->getPost('not');
            $settings = new \App\Models\settings();
            echo $not;
            $settings
                ->protect(false)
                ->set('notlar', $not)
                ->where('id', 1)
                ->update();
        }
    }

    function login()
    {

        global $conn;
        global $_SESSION;
        $error = 0;
        $errorText = 0;
        $session = \Config\Services::session();
        $settings = $this->settings;
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = htmlentities($_POST["username"]);
            $pass = htmlentities($_POST["password"]);
            $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : 0;
            $remember = isset($_POST["remember"]) ? htmlentities($_POST["remember"]) : 0;
            $googlesecret = $settings["recaptcha_secret"];
            $captcha_control = robot("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
            $captcha_control = json_decode($captcha_control);
            if ($settings["recaptcha"] == 2 && $captcha_control->success == false && isset($_SESSION["recaptcha"])) {
                $error = 1;
                $errorText = "Please verify that you are not a robot.";
                if ($settings["recaptcha"] == 2) {
                    $_SESSION["recaptcha"] = true;
                }
            } elseif (!userdata_check("username", $username)) {
                $error = 1;
                $errorText = "The username you entered was found on the system.";
                if ($settings["recaptcha"] == 2) {
                    $_SESSION["recaptcha"] = true;
                }
            } elseif (!userlogin_check($username, $pass)) {
                $error = 1;
                $errorText = "Your information does not match.";
                if ($settings["recaptcha"] == 2) {
                    $_SESSION["recaptcha"] = true;
                }
            } elseif (countRow(["table" => "clients", "where" => ["username" => $username, "client_type" => 1]])) {
                $error = 1;
                $errorText = "Your account is passive.";
                if ($settings["recaptcha"] == 2) {
                    $_SESSION["recaptcha"] = true;
                }
            } else {
                $row = $conn->prepare("SELECT * FROM clients WHERE username=:username && password=:password ");
                $row->execute(array("username" => $username, "password" => md5(sha1(md5($pass)))));
                $row = $row->fetch(PDO::FETCH_ASSOC);
                $access = json_decode($row["access"], true);


                if ($access["admin_access"]):
                    $_SESSION["neira_adminlogin"] = 1;
                    $_SESSION["neira_userlogin"] = 1;
                    $_SESSION["neira_userid"] = $row["client_id"];
                    $_SESSION["neira_userpass"] = md5(sha1(md5($pass)));
                    $_SESSION["recaptcha"] = false;

                    if ($remember):
                        if ($access["admin_access"]):
                            setcookie("a_login", 'ok', time() + (60 * 60 * 24 * 7), '/', null, null, true);
                        endif;
                        setcookie("u_id", $row["client_id"], time() + (60 * 60 * 24 * 7), '/', null, null, true);
                        setcookie("u_password", $row["password"], time() + (60 * 60 * 24 * 7), '/', null, null, true);
                        setcookie("u_login", 'ok', time() + (60 * 60 * 24 * 7), '/', null, null, true);
                    endif;
                    header('Location:' . base_url('admin'));
                    echo "<script>window.location.href = '" . base_url('admin') . "'; </script>";
                    redirect()->to(base_url('admin'));
                    return 1;
                    $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                    $insert->execute(array("c_id" => $row["client_id"], "action" => "Admin girişi yapıldı.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
                    $update = $conn->prepare("UPDATE clients SET login_date=:date, login_ip=:ip WHERE client_id=:c_id ");
                    $update->execute(array("c_id" => $row["client_id"], "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
                else:
                    $error = 1;
                    $errorText = "Administrator account registered with this information was found.";
                endif;


            }

        }

        $ayar = array(
            'error' => $error,
            'errorText' => $errorText,
            'settings' => $this->settings,
            'session' => $session
        );
        return view('admin/yeni_admin/login', $ayar);
    }

    function adminapi()
    {
        return view('admin/yeni_admin/Adminapi');
    }

    function version_check()
    {
        $site_version = $this->settings['version'];
//  Initiate curl
        $ch = curl_init();
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL, "www.version.glycon.net/version_check.php?key=" . $this->key . "");
// Execute
        $result = curl_exec($ch);
// Closing
        curl_close($ch);

// Will dump a beauty json :3
        $sonuc = json_decode($result, true);
        if (isset($sonuc['status']) && $sonuc['status'] == 200):
            $version = $sonuc['version'];
            if ($site_version != $version) {

                return json_encode([
                    'status' => 200,
                    'message' => "Güncelleme Gerekiyor",
                    "current_version" => $site_version,
                    "new_version" => $sonuc['version']
                ]);

            } else {
                return json_encode([
                    'status' => 200,
                    'message' => "Sistem Zaten Son Sürüm",
                    "current_version" => $site_version,
                    "new_version" => $sonuc['version']
                ]);
            }

        endif;
        return 1;
    }

    function update()
    {
        $site_version = $this->settings['version'];
//  Initiate curl
        $ch = curl_init();
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL, "www.version.glycon.net/version_check.php?key=" . $this->key . "");
// Execute
        $result = curl_exec($ch);
// Closing
        curl_close($ch);

// Will dump a beauty json :3
        $sonuc = json_decode($result, true);
        if (isset($sonuc['status']) && $sonuc['status'] == 200):
            $version = $sonuc['version'];
            if ($site_version != $version) {
                $file = $sonuc['file'];
                $yolcuk = FCPATH . "version/" . $file;
                $curl = curl_init("www.version.glycon.net/download_zip.php?key=" . $this->key . "");
                $fopen = fopen($yolcuk, 'w');

                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                curl_setopt($curl, CURLOPT_FILE, $fopen);

                curl_exec($curl);
                curl_close($curl);
                fclose($fopen);
                $filename = $sonuc['file'];
                $path = FCPATH . 'version/';
                $zip = new ZipArchive;
                $res = $zip->open($path . $filename);
                if ($res === TRUE) {
                    $zip->extractTo('version/' . str_replace(".", "_", $sonuc['version']));
                    $zip->close();

                    unlink($path . $filename);
                    $klasor = opendir(FCPATH); //klasörü aç
                    $ver = str_replace(".", "_", $sonuc['version']);
                    while ($dosya = readdir($klasor)) { //klasördeki dosyalar taranıyor
                        if (is_dir($dosya) && $dosya != ".." && $dosya != "." && $dosya != "backup") { //dosya değişkenindeki değer klasör değilse
                            echo $dosya . '<br>'; //alt alta yazdır
                            //copy_dir(FCPATH . $dosya, FCPATH."backup/" . $ver . "/" . $dosya, FCPATH."backup/" . $ver);
                        }
                    }
                    $klasor_g = opendir(FCPATH . "version/" . $ver . "/"); //klasörü aç

                    while ($dosya_g = readdir($klasor_g)) {
                        if (is_dir($dosya_g) && $dosya_g != ".." && $dosya_g != "." && $dosya_g != "backup") {
                            folder_bul($dosya_g, $ver);
                        } else {
                            copy_dir(FCPATH . $dosya_g, FCPATH . $dosya_g, FCPATH . "backup/");

                        }
                    }


                }

                $settings = new \App\Models\settings();
                $settings->protect(false)->set("version", $version)->where("id", 1)->update();
                return json_encode([
                    'status' => 202,
                    'message' => "Sitem Başarıyla Güncellendi"
                ]);
            }

        endif;
    }

    function update_admin()
    {

        if ($this->request->getGet("key") == md5("glyconv2-base_url")):
            $site_version = $this->settings['version'];
//  Initiate curl
            $ch = curl_init();
// Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
            curl_setopt($ch, CURLOPT_URL, "www.version.glycon.net/version_check.php?key=" . $this->key . "");
// Execute
            $result = curl_exec($ch);
// Closing
            curl_close($ch);

// Will dump a beauty json :3
            $sonuc = json_decode($result, true);
            if (isset($sonuc['status']) && $sonuc['status'] == 200):
                $version = $sonuc['version'];
                if ($site_version != $version) {
                    $settings = new \App\Models\settings();
                    $settings->protect(false)->set("version", $version)->where("id", 1)->update();
                    return json_encode([
                        'status' => 202,
                        'message' => "Sitem Başarıyla Güncellendi"
                    ]);
                    $file = $sonuc['file'];
                    $yolcuk = FCPATH . "version/" . $file;
                    $curl = curl_init("www.version.glycon.net/download_zip.php?key=" . $this->key . "");
                    $fopen = fopen($yolcuk, 'w');

                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                    curl_setopt($curl, CURLOPT_FILE, $fopen);

                    curl_exec($curl);
                    curl_close($curl);
                    fclose($fopen);
                    $filename = $sonuc['file'];
                    $path = FCPATH . 'version/';
                    $zip = new ZipArchive;
                    $res = $zip->open($path . $filename);
                    if ($res === TRUE) {
                        $zip->extractTo('version/' . str_replace(".", "_", $sonuc['version']));
                        $zip->close();

                        unlink($path . $filename);
                        $klasor = opendir(FCPATH); //klasörü aç
                        $ver = str_replace(".", "_", $sonuc['version']);
                        while ($dosya = readdir($klasor)) { //klasördeki dosyalar taranıyor
                            if (is_dir($dosya) && $dosya != ".." && $dosya != "." && $dosya != "backup") { //dosya değişkenindeki değer klasör değilse
                                echo $dosya . '<br>'; //alt alta yazdır
                                //copy_dir(FCPATH . $dosya, FCPATH."backup/" . $ver . "/" . $dosya, FCPATH."backup/" . $ver);
                            }
                        }
                        $klasor_g = opendir(FCPATH . "version/" . $ver . "/"); //klasörü aç

                        while ($dosya_g = readdir($klasor_g)) {
                            if (is_dir($dosya_g) && $dosya_g != ".." && $dosya_g != "." && $dosya_g != "backup") {
                                folder_bul($dosya_g, $ver);
                            } else {
                                copy_dir(FCPATH . $dosya_g, FCPATH . $dosya_g, FCPATH . "backup/");

                            }
                        }


                    }


                }

            endif;
        endif;
    }

    function create_db_update()
    {

        $migrate = \Config\Services::migrations();
        try {
            $migrate->latest();
        } catch (\Throwable $e) {
            // Do something with the error here...
        }

    }

    function mysql_backup()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        if ($this->request->getGet('table')):
            $tables = $this->request->getGet('table');
            if ($tables == "orders"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "orders";
                };
            elseif ($tables == "bank_accounts"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "bank_accounts";
                };
            elseif ($tables == "blogs"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "blogs";
                };
            elseif ($tables == "categories"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "categories";
                };
            elseif ($tables == "child_panels"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "child_panels";
                };
            elseif ($tables == "cift_servis"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "cift_servis";
                };
            elseif ($tables == "clients"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "clients";
                };
            elseif ($tables == "clients_category"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "clients_category";
                };
            elseif ($tables == "clients_price"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "clients_price";
                };
            elseif ($tables == "clients_service"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "clients_service";
                };
            elseif ($tables == "client_favorite"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "client_favorite";
                };
            elseif ($tables == "client_report"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "client_report";
                };
            elseif ($tables == "cron_status"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "cron_status";
                };
            elseif ($tables == "files"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "files";
                };
            elseif ($tables == "guard_log"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "guard_log";
                };
            elseif ($tables == "integrations"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "integrations";
                };
            elseif ($tables == "languages"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "languages";
                };
            elseif ($tables == "menu"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "menu";
                };
            elseif ($tables == "migrations"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "migrations";
                };
            elseif ($tables == "modules"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "modules";
                };
            elseif ($tables == "news"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "news";
                };
            elseif ($tables == "services"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "services";
                };
            elseif ($tables == "pages"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "pages";
                };
            elseif ($tables == "paket_kategori"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "paket_kategori";
                };
            elseif ($tables == "payments"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "payments";
                };
            elseif ($tables == "payments_bonus"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "payments_bonus";
                };
            elseif ($tables == "payment_methods"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "payment_methods";
                };
            elseif ($tables == "popup"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "popup";
                };
            elseif ($tables == "proxy"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "proxy";
                };
            elseif ($tables == "referral"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "referral";
                };
            elseif ($tables == "reset_log"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "reset_log";
                };
            elseif ($tables == "serviceapi_alert"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "serviceapi_alert";
                };
            elseif ($tables == "service_api"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "service_api";
                };
            elseif ($tables == "settings"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "settings";
                };
            elseif ($tables == "tasks"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "tasks";
                };
            elseif ($tables == "themes"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "themes";
                };
            elseif ($tables == "tickets"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "tickets";
                };
            elseif ($tables == "ticket_ready"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "ticket_ready";
                };
            elseif ($tables == "ticket_reply"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "ticket_reply";
                };
            elseif ($tables == "ticket_subjects"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "ticket_subjects";
                };
            elseif ($tables == "verify_log"):
                $model = new class extends \CodeIgniter\Model {
                    protected $table = "verify_log";
                };
            endif;
            $db = \Closure::bind(function ($model) {
                return $model->db;
            }, null, $model)($model);

            $util = (new \CodeIgniter\Database\Database())->loadUtils($db);
            echo $util->getXMLFromResult($model->get());
        endif;
    }

    function version_update_settings()
    {
        $ch = curl_init();
        $site_version = $this->settings['version'];
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL, "www.version.glycon.net/version_check.php?key=" . $this->key . "");
// Execute
        $result = curl_exec($ch);
// Closing
        curl_close($ch);

// Will dump a beauty json :3
        $sonuc = json_decode($result, true);
        if (isset($sonuc['status']) && $sonuc['status'] == 200):
            $version = $sonuc['version'];
            if ($site_version != $version) {
                $settings = new \App\Models\settings();
                $settings->protect(false)->set("version", $version)->where("id", 1)->update();
            }
        endif;
    }

    function popup_db_create()
    {
        $forge = \Config\Database::forge();
        $forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'icerik' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tur' => [
                'type' => 'INT',
                'null' => true,
            ],
            'zaman' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $forge->addKey('id', true);
        $forge->createTable('popup');
    }
}