<?php

namespace App\Controllers\main;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use http\Exception;
use PDO;

use App\Models\service_report;
class Home extends BaseController
{
    function index()
    {
        ob_start(function($data) {
				$replace = [
					'/\>[^\S ]+/s'   => '>',
					'/[^\S ]+\</s'   => '<',
					'/([\t ])+/s'  => ' ',
					'/^([\t ])+/m' => '',
					'/([\t ])+$/m' => '',
					'~//[a-zA-Z0-9 ]+$~m' => '',
					'/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
					'/\>[\r\n\t ]+\</s'    => '><',
					'/}[\r\n\t ]+/s'  => '}',
					'/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
					'/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
					'/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
					'/\),[\r\n\t ]+/s'  => '),',
				];
				$data = preg_replace(array_keys($replace), array_values($replace), $data);
				$remove = ['</option>', '</li>', '</dt>', '</dd>', '</tr>', '</th>', '</td>'];
				$data = str_ireplace($remove, '', $data);
				return $data;
			});
        global $conn;
        global $_SESSION;
        $settings = $this->settings;
        $session = \Config\Services::session();
        $user = $this->getuser;
                $user = $this->getuser;
        if(get_cookie('u_id') && get_cookie('u_login') && get_cookie('u_password')){
            $usermodel = new \App\Models\clients();
            $getuser = $usermodel->where(['client_id'=>get_cookie('u_id'),'password'=>get_cookie('u_password')])->countAllResults();
            if($getuser){
                $getuser = $usermodel->where(['client_id'=>get_cookie('u_id'),'password'=>get_cookie('u_password')])->get()->getResultArray()[0];
                    $session->set("neira_userlogin", 1);
                    $session->set("neira_userid", $getuser["client_id"]);
                    $session->set("neira_userpass", $getuser["password"]);
                    $session->set("recaptcha", false);
                    $user = $getuser;
                    $user['access'] = json_decode($getuser['access'], true);
            }
        }
        if ($session->get('neira_userlogin')) {
            
        include APPPATH . 'ThirdParty/dil/'.$user["lang"].'.php';
        }else{
            include APPPATH . 'ThirdParty/dil/'.$settings["site_language"].'.php';
        }
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        
        if ($settings["sms_provider"] == "bizimsms"):
            include APPPATH . 'ThirdParty/SendSms/bizimsms.php';
        elseif ($settings["sms_provider"] == "netgsm"):
            include APPPATH . 'ThirdParty/SendSms/netgsm.php';
            else:
                
            include APPPATH . 'ThirdParty/SendSms/smsvitrini.php';
        endif;
        if ($user) {
            $uye_id = $user['client_id'];
            $ref_code = $user['referral_code'];
        }
        if ($settings["site_maintenance"] == 1) {
            if (isset($user) && isset($user['access']['admin_access'])) {

            } else {
                return view("maintenance");
            }
        }

        $servicereport = new service_report();
        $servicereps = $servicereport->orderBy('id','DESC')->get()->getResultArray();
        $servicerep = [];
        $limit=$settings["up_limiti"];
        $i=0; //start line counter
        foreach($servicereps as $service_rep){
            $i++;
            if($i == $limit+1){break;} 
            $service_rep['extra'] = json_decode($service_rep['extra'],true);
            array_push($servicerep,$service_rep);
        }
        $languages = $conn->prepare('SELECT * FROM languages WHERE language_type=:type');
        $languages->execute(array(
            'type' => 2
        ));

        $categories = "";
        $resetPage = "";
        $blogPage = "";
        $error = "";
        $errorText = "";
        $success = "";
        $serviceList = "";
        $registerPage = "";
        $panelSelling = false;
        $apiPage = "";
        $neworder_terms = "";
        $faqPage = "";
        $termsPage = "";
        $contactPage = "";
        $ticketPage = "";
        $resetType = "";
        $successText = "";
        $newsList = "";
        $ordersCount = "";
        $active_menu = "";
        $ticketList = "";
        $blogList = "";
        $messageList = "";
        $methodList = "";
        $bankPayment = "";
        $bankList = "";
        $ordersList = "";
        $paginationArr = "";
        $content = "";
        $headerCode = "";
        $verify = "";
        $timezones = "";
        $cancelButton = "";
        $refillButton = "";
        $timezones = "";
        $paymentsHistory = "";
        $headerCode = $settings['custom_header'];
        $timezones = [
            ['label' => '(UTC +3:00) Istanbul', 'timezone' => '0'],
            ['label' => '(UTC -12:00) Baker/Howland Island', 'timezone' => '-54000'],
            ['label' => '(UTC -11:00) Niue', 'timezone' => '-50400'],
            ['label' => '(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti', 'timezone' => '-46800'],
            ['label' => '(UTC -9:30) Marquesas Islands', 'timezone' => '-45000'],
            ['label' => '(UTC -9:00) Alaska Standard Time, Gambier Islands', 'timezone' => '-43200'],
            ['label' => '(UTC -8:00) Pacific Standard Time, Clipperton Island', 'timezone' => '-39600'],
            ['label' => '(UTC -7:00) Mountain Standard Time', 'timezone' => '-36000'],
            ['label' => '(UTC -6:00) Central Standard Time', 'timezone' => '-32400'],
            ['label' => '(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time', 'timezone' => '-28800'],
            ['label' => '(UTC -4:30) Venezuelan Standard Time', 'timezone' => '-27000'],
            ['label' => '(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time', 'timezone' => '-25200'],
            ['label' => '(UTC -3:30) Newfoundland Standard Time', 'timezone' => '-23400'],
            ['label' => '(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay', 'timezone' => '-21600'],
            ['label' => '(UTC -2:00) South Georgia/South Sandwich Islands', 'timezone' => '-18000'],
            ['label' => '(UTC -1:00) Azores, Cape Verde Islands', 'timezone' => '-14400'],
            ['label' => '(UTC) Greenwich Mean Time, Western European Time', 'timezone' => '-10800'],
            ['label' => '(UTC +1:00) Central European Time, West Africa Time', 'timezone' => '-7200'],
            ['label' => '(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time', 'timezone' => '-3600'],
            ['label' => '(UTC +3:00) Moscow Time, East Africa Time, Arabia Standard Time', 'timezone' => '0'],
            ['label' => '(UTC +3:30) Iran Standard Time', 'timezone' => '1800'],
            ['label' => '(UTC +4:00) Azerbaijan Standard Time, Samara Time', 'timezone' => '3600'],
            ['label' => '(UTC +4:30) Afghanistan', 'timezone' => '5400'],
            ['label' => '(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time', 'timezone' => '7200'],
            ['label' => '(UTC +5:30) Indian Standard Time, Sri Lanka Time', 'timezone' => '9000'],
            ['label' => '(UTC +5:45) Nepal Time', 'timezone' => '9900'],
            ['label' => '(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time', 'timezone' => '10800'],
            ['label' => '(UTC +6:30) Cocos Islands, Myanmar', 'timezone' => '12600'],
            ['label' => '(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam', 'timezone' => '14400'],
            ['label' => '(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time', 'timezone' => '18000'],
            ['label' => '(UTC +8:45) Australian Central Western Standard Time', 'timezone' => '20700'],
            ['label' => '(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time', 'timezone' => '21600'],
            ['label' => '(UTC +9:30) Australian Central Standard Time', 'timezone' => '23400'],
            ['label' => '(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time', 'timezone' => '25200'],
            ['label' => '(UTC +10:30) Lord Howe Island', 'timezone' => '27000'],
            ['label' => '(UTC +11:00) Srednekolymsk Time, Solomon Islands, Vanuatu', 'timezone' => '28800'],
            ['label' => '(UTC +11:30) Norfolk Island', 'timezone' => '30600'],
            ['label' => '(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time', 'timezone' => '32400'],
            ['label' => '(UTC +12:45) Chatham Islands Standard Time', 'timezone' => '35100'],
            ['label' => '(UTC +13:00) Samoa Time Zone, Phoenix Islands Time, Tonga', 'timezone' => '36000'],
            ['label' => '(UTC +14:00) Line Islands', 'timezone' => '39600'],
            
        ];
        $stylesheet = themeExtras('stylesheets');
        $languages = $languages->fetchAll(PDO::FETCH_ASSOC);
        $languagesL = [];
        foreach ($languages as $language) {
            $l['name'] = $language['language_name'];
            $l['code'] = $language['language_code'];
            if (isset($_SESSION['lang']) && $language['language_code'] == $_SESSION['lang']) {
                $selectedLang = $language['language_code'];
                $l['active'] = 1;
            } elseif (isset($_SESSION['lang']) && !$_SESSION['lang']) {
                $selectedLang = $language['language_code'];
                $l['active'] = $language['default_language'];
            } else {
                $selectedLang = $language['language_code'];
                $l['active'] = 0;
            }
            array_push($languagesL, $l);
        }
        if ($settings['service_list'] == 2):
            $serviceList = 1;
        endif;
        if ($settings['register_page'] == 2):
            $registerPage = 1;
        endif;

        if ($settings["site_currency"] == "TRY") {
            $currency = "₺";
        } elseif ($settings["site_currency"] == "USD") {
            $currency = "$";
        } elseif ($settings["site_currency"] == "EUR") {
            $currency = "€";
        }
        if ($settings['panel_selling'] == 1) {
            $panelSelling = false;
        } elseif ($settings['panel_selling'] == 2) {
            $panelSelling = true;
        }

        if ($settings['referral'] == 1) {
            $referral = false;
        } elseif ($settings['referral'] == 2) {
            $referral = true;
        }

        if ($settings['neworder_terms'] == 2) {
            $neworder_terms = true;
        } else {
            $neworder_terms = false;
        }
    $orderssss = $conn->prepare('SELECT * FROM orders WHERE order_error=:error ORDER BY order_id  DESC');
    $orderssss->execute(array(
        'error' => '-'
    ));
    $orderssss = $orderssss->fetch(PDO::FETCH_ASSOC);
    $ordersCount = $orderssss["order_id"];
$news = $conn->prepare("SELECT * FROM news ORDER BY news_date DESC");
$news->execute(array());
$news = $news->fetchAll(PDO::FETCH_ASSOC);
$newsList = [];
foreach ($news as $new) {
    foreach ($new as $key => $value) {
        $t[$key] = $value;
    }
    array_push($newsList, $t);
}

        
        if ($settings['ticket_system'] == 2):
            $ticketPage = 1;
        endif;
        //isset($_SESSION['neira_userlogin']
        $session = \Config\Services::session();
        $session->start();



        if ($session->get('neira_userlogin')) {
            $menuall = $conn->prepare('SELECT * FROM menu WHERE id>:id and public =:public');
        $menuall->execute(array(
            'id' => 6,
            'public' => 2
        ));
    
        }else{
            $menuall = $conn->prepare('SELECT * FROM menu WHERE id>:id and status=:status');
        $menuall->execute(array(
            'id' => 6,
            'status' => 2,
        ));
        }
        $menuall = $menuall->fetchAll();
        $menu = $conn->prepare('SELECT * FROM menu WHERE id=:id');
        $menu->execute(array(
            'id' => 2
        ));
        $menu = $menu->fetch(PDO::FETCH_ASSOC);

if ($session->get('neira_userlogin')) {
            if ($menu['public'] == 2) {
                $apiPage = true;
            } else {
                $apiPage = false;
            }
        } else {
            if ($menu['status'] == 2) {
                $apiPage = true;
            } else {
                $apiPage = false;
            }
        }

        ## Blog  Menü Başlangıç ###
        $menu = $conn->prepare('SELECT * FROM menu WHERE id=:id');
        $menu->execute(array(
            'id' => 5
        ));
        $menu = $menu->fetch(PDO::FETCH_ASSOC);

        if ($session->get('neira_userlogin')) {
            if (countRow(['table' => 'blogs']) > 0 && ($menu['public'] == 2)  ) {
                $blogPage = true;
            } else {
                $blogPage = false;
            }
        } else {
            if (countRow(['table' => 'blogs']) > 0 && $menu['status'] == 2) {
                $blogPage = true;
            } else {
                $blogPage = false;
            }
        }

## Blog Menü Bitiş ##

## SSS Menü Başlangıç ##
        $menu = $conn->prepare('SELECT * FROM menu WHERE id=:id');
        $menu->execute(array(
            'id' => 4
        ));
        $menu = $menu->fetch(PDO::FETCH_ASSOC);

        if ($session->get('neira_userlogin')) {
            if ($menu['public'] == 2) {
                $faqPage = true;
            } else {
                $faqPage = false;
            }
        } else {
            if ($menu['status'] == 2) {
                $faqPage = true;
            } else {
                $faqPage = false;
            }
        }

## SSS Menü Bitiş ##

        ## Terms Menü Başlangıç ##
        $menu = $conn->prepare('SELECT * FROM menu WHERE id=:id');
        $menu->execute(array(
            'id' => 3
        ));
        $menu = $menu->fetch(PDO::FETCH_ASSOC);

        if ($session->get('neira_userlogin')) {
            if ($menu['public'] == 2) {
                $termsPage = true;
            } else {
                $termsPage = false;
            }
        } else {
            if ($menu['status'] == 2) {
                $termsPage = true;
            } else {
                $termsPage = false;
            }
        }

## Terms Menü Bitiş ##
## Contact Menü Başlangıç ##
        $menu = $conn->prepare('SELECT * FROM menu WHERE id=:id');
        $menu->execute(array(
            'id' => 6
        ));
        $menu = $menu->fetch(PDO::FETCH_ASSOC);

        if ($session->get('neira_userlogin')) {
            if ($menu['public'] == 2) {
                $contactPage = true;
            } else {
                $contactPage = false;
            }
        } else {
            if ($menu['status'] == 2) {
                $contactPage = true;
            } else {
                $contactPage = false;
            }
        }

## Contact Menü Bitiş ##

        $active_menu = route(1)?route(1):"neworder";
        if (($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1) && ($user && $user['client_type'] == 1)) {
            $routes = "verify";
            $controllerroute = "verify";

        } elseif (route(1) == "" && ($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1)) {
            $routes = "neworder";
            $controllerroute = "neworder";

        } elseif (route(1) == "" && (!$session->get('neira_userlogin'))) {
            $routes = "login";
            $controllerroute = "auth";

        } else {
            $routes = route(1);
            $controllerroute = route(1);
        }
        if (route(1) == "tickets" && route(2)) {
            $routes = "viewticket";
        }
        if (route(1) == 'terms' || route(1) == 'contact' || route(1) == 'services' || route(1) == 'faq' || route(1) == 'addfunds' || route(1) == 'child-panels' || route(1) == 'affiliates'):
            $contentGet = route(1);
            $content = $conn->prepare('SELECT * FROM pages WHERE page_get=:get ');
            $content->execute(array(
                'get' => $contentGet
            ));
            $content = $content->fetch(PDO::FETCH_ASSOC);
            $content = $content['page_content'];
        elseif (route(1) == 'login'):
            $contentGet = "auth";
            $content = $conn->prepare('SELECT * FROM pages WHERE page_get=:get ');
            $content->execute(array(
                'get' => $contentGet
            ));
            $content = $content->fetch(PDO::FETCH_ASSOC);
            $content = $content['page_content'];
        endif;

        $title = "";
        $resetStep = "";
        $avarageTime = "";
        if (route(1) == "auth" && route(1)) {
            $routes = "login";
        }
        if(isset($_SESSION['theme'])):
           $themes = $_SESSION['theme'];
        else:
            $themes = $this->settings['site_theme'];
        endif;
        if (route(1) == "select-theme" && route(1)) {
            $controllerroute = "auth";
            $routes = "login";
            $stylesheet = themeExtras_switch(route(2));
            $themes = route(2);
            $_SESSION["theme"] = $themes;
            header('Location:' . base_url());
        }
        if(isset($_SESSION['theme'])){
            $themes = $_SESSION["theme"];
            $stylesheet = themeExtras_switch($_SESSION["theme"]);
        }
        if (route(1) == "ref" && route(1)) {

            $routes = "signup";
            $controllerroute = "signup";
            if (route(2)) {
                $ref = route(2);
                $refcontrol = $conn->prepare('SELECT * FROM clients WHERE referral_code=:code');
                $refcontrol->execute(array(
                    'code' => $ref
                ));
                $refcontrol = $refcontrol->rowCount();

                if (!isset($_SESSION['referral'])) {

                    $row = $conn->prepare('SELECT * FROM clients WHERE referral_code=:code');
                    $row->execute(array(
                        'code' => $ref
                    ));
                    $row = $row->fetch(PDO::FETCH_ASSOC);

                    $update = $conn->prepare("UPDATE clients SET total_click=:click WHERE referral_code=:code ");
                    $update->execute(array(
                        "code" => $ref,
                        "click" => $row["total_click"] + 1
                    ));
                }
            }
        }
        if (route(1) == "payment") {
                echo $settings['google_ads_odeme'];
           
        }
        include APPPATH . 'ThirdParty/controller/' . $controllerroute . ".php";

        if (route(1) == "payment") {
            exit();
        }

        foreach ($stylesheet['scripts'] as $key => $value) {
            if (strstr($value, "main.js")):
                $stylesheet['scripts'][$key] = str_replace("main.js", "main.js?n=" . rand(1000, 99999), $stylesheet['scripts'][$key]);
            endif;
        }
                if (isset($_SESSION['recaptcha'])) {
            $captcha = true;
        } else {
            $captcha = false;
        }
        if (route(1) != "auth") {
            if ($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1) {
                /*****************************************************************************************/
                if(route(1)!="verify" && $settings["mail_verify"] == 2 && $user["mail_verify"] != 2){
                    echo route(1);
                    exit();
                    //header("Location:".base_url('verify/mail'));
                }
                /*****************************************************************************************/
                $user['auth'] = 1;
                $uye_id = $user['client_id'];
                $_code = $user['referral_code'];

                $refCount = countRow(["table" => "clients", "where" => ["referral" => $user['client_id']]]);
                $dripfeedvarmi = $conn->query("SELECT * FROM orders WHERE client_id=$uye_id and dripfeed=2");
                if ($dripfeedvarmi->rowCount()) {
                    $dripfeedcount = 1;

                } else {
                    $dripfeedcount = 0;
                }

                $subscriptionsvarmi = $conn->query("SELECT * FROM orders WHERE client_id=$uye_id and subscriptions_type=2");
                if ($subscriptionsvarmi->rowCount()) {
                    $subscriptionscount = 1;
                } else {
                    $subscriptionscount = 0;
                }
                if ($settings['panel_selling'] == 1) {
                    $panelSelling = false;
                } elseif ($settings['panel_selling'] == 2) {
                    $panelSelling = true;
                }

                if ($settings['referral'] == 1) {
                    $referral = false;
                } elseif ($settings['referral'] == 2) {
                    $referral = true;
                }

                if ($settings['neworder_terms'] == 2) {
                    $neworder_terms = true;
                } else {
                    $neworder_terms = false;
                }

                echo $this->twig->render('main/' . $themes . '/' . $routes . '.twig', array(
                    'site' => ['url' => base_url(),
                        'favicon' => base_url('assets/uploads/sites/' . $settings['favicon']),
                        'logo' => base_url('assets/uploads/sites/' . $settings['site_logo']),
                        'site_name' => $settings['site_name'],
                        'currency' => $currency,
                        'languages' => $languagesL, 'subscriptionscount' => $subscriptionscount, 'dripfeedcount' => $dripfeedcount],
                    'styleList' => $stylesheet['stylesheets'],
                    'scriptList' => $stylesheet['scripts'],

                    'user' => $user,
                    'captchaKey' => $settings['recaptcha_key'],
                    'captcha' => $captcha,
                    'resetPage' => $resetPage,
                    'refCount' => $refCount,
                    'blogPage' => $blogPage,
                    'affiliates' => $referral,
                    'panelSelling' => $panelSelling,
                    'serviceCategory' => $categories,
                    'categories' => $categories,
                    'error' => $error,
                    'errorText' => $errorText,
                    'success' => $success,
                    'servicesPage' => $serviceList,
                    'registerPage' => 0,
                    'apiPage' => $apiPage,
                    'neworderTerms' => $neworder_terms,
                    'faqPage' => $faqPage,
                    'updateList' => $servicerep,
                    'termsPage' => $termsPage,
                    'contactPage' => $contactPage,
                    'resetStep' => $resetStep,
                    'ticketPage' => $ticketPage,
                    'resetType' => $resetType,
                    'successText' => $successText,
                    'title' => $title,
                    'keywords' => $settings['site_keywords'],
                    'description' => $settings['site_description'],
                    'menuall' => $menuall,
                    'data' => isset($_SESSION['data']) ? $_SESSION['data'] : "",
                    'newsList' => $newsList,
                    'ordersCount' => $ordersCount,
                    'settings' => $settings,
                    'search' => isset($_GET['search']) ? urldecode($_GET['search']) : "",
                    'active_menu' => $active_menu,
                    'ticketList' => $ticketList,
                    'blogList' => $blogList,
                    'messageList' => $messageList,
                    'ticketCount' => new_ticket($user['client_id']),
                    'avarageTime' => isset($avarageTime) ? $avarageTime : "",
                    'paymentsList' => $methodList,
                    'bankPayment' => isset($bankPayment['method_type']) ? $bankPayment['method_type'] : "",
                    'bankList' => $bankList,
                    'status' => route(2)?route(2):'all',
                    'orders' => $ordersList,
                    'pagination' => $paginationArr,
                    'contentText' => $content,
                    'headerCode' => $headerCode,
                    'footerCode' => $settings['custom_footer'],
                    'verify' => $verify,
                    'lang' => $languageArray,
                    'timezones' => $timezones,
                    'refillButton' => $refillButton,
                    'cancelButton' => $cancelButton,
                    "paymentHistory" => $paymentsHistory
                ));
            } else {
                $user = 0;
                $uye_id = 0;
                $ref_code = 0;
                $refCount = 0;
 if ($settings['service_list'] == 1):
            $serviceList = 0;
        endif;
                if ($settings['panel_selling'] == 1) {
                    $panelSelling = false;
                } elseif ($settings['panel_selling'] == 2) {
                    $panelSelling = true;
                }

                if ($settings['referral'] == 1) {
                    $referral = false;
                } elseif ($settings['referral'] == 2) {
                    $referral = true;
                }

                if ($settings['neworder_terms'] == 2) {
                    $neworder_terms = true;
                } else {
                    $neworder_terms = false;
                }


                if ($settings['ticket_system'] == 2):
                    $ticketPage = 1;
                endif;
                echo $this->twig->render('main/' . $themes . '/' . $routes . '.twig', array(
                    'site' => ['url' => base_url(),
                        'favicon' => $settings['favicon'],
                        'logo' => $settings['site_logo'] != "" ? base_url('assets/uploads/sites/' . $settings['site_logo']) : "xxx",
                        'site_name' => $settings['site_name'],
                        'currency' => $currency,
                        'languages' => $languagesL,
                        'dripfeedcount' => 0,
                        'subscriptionscount' => 0],
                    'styleList' => $stylesheet['stylesheets'],
                    'scriptList' => $stylesheet['scripts'],
                    'user' => $user,
                    'captchaKey' => $settings['recaptcha_key'],
                    'captcha' => $captcha,
                    'resetPage' => $resetPage,
                    'refCount' => $refCount,
                    'blogPage' => $blogPage,
                    'affiliates' => $referral,
                    'panelSelling' => $panelSelling,
                    'serviceCategory' => $categories,
                    'categories' => $categories,
                    'error' => $error,
                    'errorText' => $errorText,
                    'success' => $success,
                    'resetStep' => $resetStep,
                    'servicesPage' => $serviceList,
                    'registerPage' => $registerPage,
                    'apiPage' => $apiPage,
                    'neworderTerms' => $neworder_terms,
                    'menuall' => $menuall,
                    'faqPage' => $faqPage,
                    'termsPage' => $termsPage,
                    'contactPage' => $contactPage,
                    'ticketPage' => $ticketPage,
                    'resetType' => $resetType,
                    'successText' => $successText,
                    'title' => $title,
                    'keywords' => $settings['site_keywords'],
                    'description' => $settings['site_description'],
                    'data' => isset($_SESSION['data']) ? $_SESSION['data'] : "",
                    'newsList' => $newsList,
                    'ordersCount' => $ordersCount,
                    'settings' => $settings,
                    'search' => isset($_GET['search']) ? urldecode($_GET['search']) : "",
                    'active_menu' => $active_menu,
                    'ticketList' => $ticketList,
                    'blogList' => $blogList,
                    'updateList' => $servicerep,
                    'messageList' => $messageList,
                    'ticketCount' => 0,
                    'avarageTime' => isset($avarageTime) ? $avarageTime : "",
                    'paymentsList' => $methodList,
                    'bankPayment' => isset($bankPayment['method_type']) ? $bankPayment['method_type'] : "",
                    'bankList' => $bankList,
                    'status' => route(2),
                    'orders' => $ordersList,
                    'pagination' => $paginationArr,
                    'contentText' => $content,
                    'headerCode' => $headerCode,
                    'footerCode' => $settings['custom_footer'],
                    'verify' => $verify,
                    'lang' => $languageArray,
                    'timezones' => $timezones,
                    'refillButton' => $refillButton,
                    'cancelButton' => $cancelButton,
                    "paymentHistory" => $paymentsHistory
                ));
            }
        }
        if (!isset($_SESSION['neira_userlogin'])) {

            $int2 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
            $int2->execute(array(
                'status' => 2,
                'visibility' => 2
            ));

            $int2 = $int2->fetchAll();
            if ((isset($int2[0]['code']))) {
                foreach ($int2 as $int) {
                    echo $int['code'];
                }
            }
        } elseif (isset($_SESSION['neira_userlogin'])) {

            $int3 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
            $int3->execute(array(
                'status' => 2,
                'visibility' => 3
            ));

            $int3 = $int3->fetchAll();
            if ((isset($int3[0]['code']))) {
                foreach ($int3 as $int) {
                    echo $int['code'];
                }
            }
        }

        $int1 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
        $int1->execute(array(
            'status' => 2,
            'visibility' => 1
        ));
        $int1 = $int1->fetchAll();
        if (isset($int1[0]['code'])) {

            foreach ($int1 as $int) {
                if ($int['code'] != "") {
                    echo $int['code'];
                }
            }
        }
        
        echo $settings['google_ads_all'];
        return 1;
    }
}