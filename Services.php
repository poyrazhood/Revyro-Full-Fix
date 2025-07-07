<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use PDO;
use SMMApi;

class Services extends Ana_Controller
{

    function index()
    {
        global $conn;
        global $_SESSION;
        $referrer = "";
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $settings = $this->settings;
        if (!route(3)):
            $page = 1;
        elseif (is_numeric(route(3))):
            $page = route(3);
        elseif (!is_numeric(route(3))):
            $action = route(3);
        endif;
        if (empty($action)):
            $services = new \App\Models\services();
            $servisler = $services->select("*")
                ->join('categories', 'categories.category_id = services.category_id', 'right')
                ->join('service_api', 'service_api.id = services.service_api', 'left')->get()->getResultArray();
            $serviceList = array_group_by($servisler, 'category_name');
            $query = $conn->query("SELECT * FROM settings", PDO::FETCH_ASSOC);
            if ($query->rowCount()):
                foreach ($query as $row):
                    if (isset($row['servis_siralama'])) {
                        $siraal = $row['servis_siralama'];
                    };
                endforeach;
            endif;


            $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id = services.category_id LEFT JOIN service_api ON service_api.id = services.service_api ORDER BY categories.category_line,services.service_line ");
            $services->execute(array());
            $services = $services->fetchAll(PDO::FETCH_ASSOC);
            $serviceList = array_group_by($services, 'category_name');
            $ayar = array(
                'title' => 'Services',
                'user' => $this->getuser,
                'route' => $this->request->uri->getSegment(2),
                'settings' => $this->settings,
                'success' => 0,
                'error' => 0,
                'search_word' => '',
                'serviceList' => $serviceList,
                'search_where' => 'username',
            );
            //return view('admin/services', $ayar);
            return view('admin/yeni_admin/services', $ayar);
        elseif ($action == "new-service" && $action):
            if ($_POST):
                $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default");
                $language->execute(array("default" => 1));
                $language = $language->fetch(PDO::FETCH_ASSOC);
                $speed = 4;
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }
                $cat = intval(@$_POST["category"]);

                if (!$cat) $cat = $category;
                $name = mb_convert_encoding($_POST["name"][$language["language_code"]], "UTF-8", "UTF-8");
                $multiName = json_encode($_POST["name"]);
                if ($package == 2): $max = $min; endif;
                if (empty($name)):
                    $error = 1;
                    $errorText = "Ürün adı boş olamaz";
                    $icon = "error";
                elseif (empty($package)):
                    $error = 1;
                    $errorText = "Ürün paketi boş olamaz";
                    $icon = "error";
                elseif (empty($category)):
                    $error = 1;
                    $errorText = "Ürün kategori boş olamaz";
                    $icon = "error";
                elseif (!is_numeric($min)):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif ($package != 2 && !is_numeric($max)):
                    $error = 1;
                    $errorText = "Maksimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif ($min > $max):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı maksimum sipariş miktarından fazla olamaz";
                    $icon = "error";
                elseif ($mode != 1 && empty($provider)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı boş olamaz";
                    $icon = "error";
                elseif ($mode != 1 && empty($service)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı servis bilgisi boş olamaz";
                    $icon = "error";
                elseif (empty($secret)):
                    $error = 1;
                    $errorText = "Servis gizliliği boş olamaz";
                    $icon = "error";
                elseif (empty($want_username)):
                    $error = 1;
                    $errorText = "Sipariş bağlantısı boş olamaz";
                    $icon = "error";
                elseif (!is_numeric($price)):
                    $error = 1;
                    $errorText = "Ürün fiyatı rakamlardan oluşmalı";
                    $icon = "error";
                else:
                    $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                    $api->execute(array("id" => $provider));
                    $api = $api->fetch(PDO::FETCH_ASSOC);
                    if ($mode == 1): $provider = 0;
                        $service = 0; endif;
                    if ($mode == 2 && $api["api_type"] == 1):
                        $smmapi = new SMMApi();
                        $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                        foreach ($services as $apiService):
                            if ($service == $apiService->service):
                                $detail["min"] = $apiService->min;
                                $detail["max"] = $apiService->max;
                                $detail["rate"] = $apiService->rate;
                                $detail["currency"] = $balance->currency;
                                $detail = json_encode($detail);
                            endif;
                        endforeach;
                    else:
                        $detail = "";
                    endif;
                    $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                    $conn->beginTransaction();
                    $insert = $conn->prepare("INSERT INTO services SET name_lang=:multiName, service_secret=:secret, service_api=:api, service_dripfeed=:dripfeed, api_service=:api_service, api_detail=:detail, category_id=:category, service_line=:line, service_type=:type, service_package=:package, service_name=:name, service_price=:price, service_min=:min, service_max=:max, want_username=:want_username, service_speed=:speed, cancel_type=:cancel_type, refill_type=:refill_type, refill_time=:refill_time,service_description=:descr ");

                    if (!isset($row["service_line"])) {
                        $row["service_line"] = 0;
                    }
                    $insert = $insert->execute(array("secret" => $secret, "multiName" => $multiName, "dripfeed" => $dripfeed, "api" => $provider, "api_service" => $service, "detail" => $detail, "category" => $category, "line" => $row["service_line"] + 1, "type" => 2, "package" => $package, "name" => $name, "price" => $price, "min" => $min, "max" => $max, "want_username" => $want_username, "speed" => $speed, "cancel_type" => $cancel_type, "refill_type" => $refill_type, "refill_time" => $refill_time, 'descr' => isset($_POST['aciklama']) ? $_POST['aciklama'] : ""));
                    $s_id = $conn->lastInsertId();
                    if ($this->request->getPost('ikiliislem')) {
                        $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                        $api->execute(array("id" => $this->request->getPost('provider_2')));
                        $api = $api->fetch(PDO::FETCH_ASSOC);
                        $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                        $detail2 = array();
                        foreach ($services as $apiService):
                            if ($service2 == $apiService->service):
                                $detail2["min"] = $apiService->min;
                                $detail2["max"] = $apiService->max;
                                $detail2["rate"] = $apiService->rate;
                                $detail2["currency"] = $balance->currency;
                                $detail2 = json_encode($detail2);
                            endif;
                        endforeach;
                        $update = $conn->prepare("UPDATE services SET service_api2=:service, api_service2=:api,birlestirme=:birles,sirali_islem=:sirali,api_detail2=:detail WHERE service_id=:id");
                        $update = $update->execute(array(
                            'id' => $s_id,
                            'service' => $provider_2,
                            'api' => $service2,
                            'detail' => $detail2,
                            'birles' => $this->request->getPost("ikiliislem") ? 1 : 0,
                            'sirali' => $this->request->getPost("siraliislem") ? 1 : 0,
                        ));
                    }
                    if ($insert):
                        $conn->commit();
                        $referrer = base_url("admin/services");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "edit-service" && $action):

            $service_id = route(4);

            if (!countRow(["table" => "services", "where" => ["service_id" => $service_id]])): header("Location:" . base_url("admin/services"));
                exit(); endif;

            if ($_POST):
                $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default");
                $language->execute(array("default" => 1));
                $language = $language->fetch(PDO::FETCH_ASSOC);
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }

                $cat = intval(@$_POST["category"]);
                $name = mb_convert_encoding($_POST["name"][$language["language_code"]], 'UTF-8', 'UTF-8');
                $multiName = json_encode($_POST["name"]);

                if ($package == 2): $max = $min; endif;
                $serviceInfo = $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id = services.service_api WHERE services.service_id=:id ");
                $serviceInfo->execute(array("id" => $service_id));
                $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);

                if (empty($name)):
                    $error = 1;
                    $errorText = "Ürün adı boş olamaz";
                    $icon = "error";
                elseif (empty($package)):
                    $error = 1;
                    $errorText = "Ürün paketi boş olamaz";
                    $icon = "error";
                elseif (empty($category)):
                    $error = 1;
                    $errorText = "Ürün kategori boş olamaz";
                    $icon = "error";
                elseif (!is_numeric($min)):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif ($package != 2 && !is_numeric($max)):
                    $error = 1;
                    $errorText = "Maksimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif ($min > $max):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı maksimum sipariş miktarından fazla olamaz";
                    $icon = "error";
                elseif ($mode != 1 && empty($provider)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı boş olamaz";
                    $icon = "error";
                elseif ($mode != 1 && empty($service)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı servis bilgisi boş olamaz";
                    $icon = "error";
                elseif (!is_numeric($price)):
                    $error = 1;
                    $errorText = "Ürün fiyatı rakamlardan oluşmalı";
                    $icon = "error";
                else:
                    $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                    $api->execute(array("id" => $provider));
                    $api = $api->fetch(PDO::FETCH_ASSOC);
                    if ($mode == 1): $provider = 0;
                        $service = 0; endif;
                    if ($mode == 2 && $api["api_type"] == 1):
                        $smmapi = new SMMApi();
                        $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                        foreach ($services as $apiService):
                            if ($service == $apiService->service):
                                $detail["min"] = $apiService->min;
                                $detail["max"] = $apiService->max;
                                $detail["rate"] = $apiService->rate;
                                $detail["currency"] = $balance->currency;
                                $detail = json_encode($detail);
                            endif;
                        endforeach;
                    else:
                        $detail = "";
                    endif;

                    if ($serviceInfo["category_id"] != $category):
                        $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                        $last_category = $serviceInfo["category_id"];
                        $last_line = $serviceInfo["service_line"];
                        $line = isset($row["service_line"]) ? $row["service_line"] + 1 : 1;

                    else: $line = $serviceInfo["service_line"]; endif;
                    if (isset($auto_min)) {
                        $auto_min = 1;
                    } else {
                        $auto_min = 0;
                    }

                    if (isset($auto_max)) {
                        $auto_max = 1;
                    } else {
                        $auto_max = 0;
                    }
                    if (isset($auto_price)) {
                        $auto_price = 1;
                        if ($this->settings['site_currency'] == "TRY"):
                            if ($balance->currency == "USD"):

                                $dolar = str_replace(",", ".", $this->settings['dolar']);
                                $dolar = floatval($dolar);
                                $price_api = $price_api * $dolar;
                            elseif ($balance->currency == "EUR"):
                                $euro = str_replace(",", ".", $this->settings['euro']);
                                $euro = floatval($euro);
                                $price_api = $price_api * $euro;
                            endif;
                        endif;
                        $yuzde = rateSync($sync_rate, $price_api);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);

                        $topla = $yuzde + $price_api;
                        $price = round($topla, 2);
                    } else {
                        $auto_price = 0;
                        $price = $price;
                    }


                    $conn->beginTransaction();

                    $update = $conn->prepare("UPDATE services SET api_detail=:detail, name_lang=:multiName, service_dripfeed=:dripfeed, api_servicetype=:type, service_api=:api, api_service=:api_service, category_id=:category, service_package=:package, service_name=:name, service_price=:price, service_min=:min,service_secret=:secret, service_max=:max, want_username=:want_username, service_speed=:speed , sync_min=:sync_min, sync_max=:sync_max, sync_rate=:sync_rate, sync_price=:auto_price, cancel_type=:cancel_type, refill_type=:refill_type, refill_time=:refill_time, service_description=:descr,rep_link=:rep_links WHERE service_id=:id ");
                    $update = $update->execute(array("id" => route(4), "multiName" => $multiName, "secret" => $secret, "type" => 2, "detail" => $detail, "dripfeed" => $dripfeed, "api" => $provider, "api_service" => $service, "category" => $category, "package" => $package, "name" => $name, "price" => $price, "min" => $min, "max" => $max, "want_username" => $want_username, "speed" => $speed, "sync_min" => $auto_min, "sync_max" => $auto_max, "sync_rate" => isset($sync_rate) ? $sync_rate : "", "auto_price" => $auto_price, "cancel_type" => $cancel_type, "refill_type" => $refill_type, "refill_time" => $refill_time, 'descr' => isset($_POST['aciklama']) ? $_POST['aciklama'] : "", 'rep_links' => $this->request->getPost('rep_link') ? $this->request->getPost('rep_link') : 1));

                    if ($update):
                        $conn->commit();
                        if ($this->request->getPost('ikiliislem')) {
                            $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                            $api->execute(array("id" => $this->request->getPost('provider_2')));
                            $api = $api->fetch(PDO::FETCH_ASSOC);
                            $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                            $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                            $detail2 = array();
                            foreach ($services as $apiService):
                                if ($service2 == $apiService->service):
                                    $detail2["min"] = $apiService->min;
                                    $detail2["max"] = $apiService->max;
                                    $detail2["rate"] = $apiService->rate;
                                    $detail2["currency"] = $balance->currency;
                                    $detail2 = json_encode($detail2);
                                endif;
                            endforeach;
                            $update = $conn->prepare("UPDATE services SET service_api2=:service, api_service2=:api,birlestirme=:birles,sirali_islem=:sirali,api_detail2=:detail WHERE service_id=:id");
                            $update = $update->execute(array(
                                'id' => route(4),
                                'service' => $provider_2,
                                'api' => $service2,
                                'detail' => $detail2,
                                'birles' => $this->request->getPost("ikiliislem") ? 1 : 0,
                                'sirali' => $this->request->getPost("siraliislem") ? 1 : 0,
                            ));
                        }

                        $rows = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_line>=:line ");
                        $rows->execute(array("c_id" => $serviceInfo["category_id"], "line" => $serviceInfo["service_line"]));
                        $rows = $rows->fetchAll(PDO::FETCH_ASSOC);


                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                        $referrer = base_url("admin/services");
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;

        elseif ($action == "edit-description" && $action):
            $service_id = route(4);
            if (!countRow(["table" => "services", "where" => ["service_id" => $service_id]])): header("Location:" . base_url("admin/services"));
                exit(); endif;
            if ($_POST):
                $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default");
                $language->execute(array("default" => 1));
                $language = $language->fetch(PDO::FETCH_ASSOC);
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }
                $description = $_POST["description"][$language["language_code"]];
                $multiDesc = json_encode($_POST["description"]);

                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE services SET service_description=:description, description_lang=:multi WHERE service_id=:id ");
                $update = $update->execute(array("id" => route(4), "multi" => $multiDesc, "description" => $description));
                if ($update):
                    $conn->commit();
                    $error = 1;
                    $errorText = "İşlem başarılı";
                    $icon = "success";
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "İşlem başarısız";
                    $icon = "error";
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon]);
            endif;
        elseif ($action == "new-category" && $action):
            if ($_POST):
                $name = $_POST["name"];
                $secret = $_POST["secret"];
                $icon = isset($_POST["icon"]) ? $_POST["icon"] : "";

                if (empty($name)):
                    $error = 1;
                    $errorText = "Kategori adı boş olamaz";
                    $icon = "error";
                else:
                    $row = $conn->query("SELECT * FROM categories ORDER BY category_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                    $conn->beginTransaction();
                    $insert = $conn->prepare("INSERT INTO categories SET category_name=:name, category_line=:line, category_secret=:secret  ");
                    $insert = $insert->execute(array("name" => $name, "secret" => $secret, "line" => isset($row["category_line"]) ? $row["category_line"] + 1 : 1));
                    if ($insert):
                        $conn->commit();
                        unset($_SESSION["data"]);
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                        $referrer = base_url("admin/services");
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "edit-category" && $action):
            $category_id = route(4);
            if (!countRow(["table" => "categories", "where" => ["category_id" => $category_id]])): header("Location:" . base_url("admin/services"));
                exit(); endif;
            $row = getRow(["table" => "categories", "where" => ["category_id" => $category_id]]);
            if ($_POST):
                $name = $_POST["name"];
                $secret = $_POST["secret"];
                $icon = isset($_POST["icon"]) ? $_POST["icon"] : "";

                if (empty($name)):
                    $error = 1;
                    $errorText = "Kategori adı boş olamaz";
                    $icon = "error";
                else:
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE categories SET category_name=:name, category_secret=:secret WHERE category_id=:id  ");
                    $update = $update->execute(array("name" => $name, "secret" => $secret, "id" => $category_id));
                    if ($update):
                        $conn->commit();
                        $referrer = base_url("admin/services");
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "new-subscription" && $action):
            if ($_POST):
                $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default");
                $language->execute(array("default" => 1));
                $language = $language->fetch(PDO::FETCH_ASSOC);
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }
                $cat = intval(@$_POST["category"]);
                if (!$cat) $cat = $category;
                $name = mb_convert_encoding($_POST["name"][$language["language_code"]], "UTF-8", "UTF-8");
                $multiName = json_encode($_POST["name"]);

                if (empty($name)):
                    $error = 1;
                    $errorText = "Ürün adı boş olamaz";
                    $icon = "error";
                elseif (empty($package)):
                    $error = 1;
                    $errorText = "Ürün paketi boş olamaz";
                    $icon = "error";
                elseif (empty($category)):
                    $error = 1;
                    $errorText = "Ürün kategori boş olamaz";
                    $icon = "error";
                elseif (empty($provider)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı boş olamaz";
                    $icon = "error";
                elseif (empty($service)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı servis bilgisi boş olamaz";
                    $icon = "error";
                elseif (empty($secret)):
                    $error = 1;
                    $errorText = "Servis gizliliği boş olamaz";
                    $icon = "error";
                elseif (($package == 11 || $package == 12) && !is_numeric($price)):
                    $error = 1;
                    $errorText = "Ürün fiyatı rakamlardan oluşmalı";
                    $icon = "error";
                elseif (($package == 11 || $package == 12) && !is_numeric($min)):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($package == 11 || $package == 12) && !is_numeric($max)):
                    $error = 1;
                    $errorText = "Maksimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($package == 11 || $package == 12) && $min > $max):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı maksimum sipariş miktarından fazla olamaz";
                    $icon = "error";
                elseif (($package == 14 || $package == 15) && !is_numeric($autopost)):
                    $error = 1;
                    $errorText = "Gönderi miktarı boş olamaz";
                    $icon = "error";
                elseif (($package == 14 || $package == 15) && !is_numeric($limited_min)):
                    $error = 1;
                    $errorText = "Sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($package == 14 || $package == 15) && !is_numeric($autotime)):
                    $error = 1;
                    $errorText = "Paket Süresi boş olamaz";
                    $icon = "error";
                else:
                    $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                    $api->execute(array("id" => $provider));
                    $api = $api->fetch(PDO::FETCH_ASSOC);
                    if ($mode == 1): $provider = 0;
                        $service = 0; endif;
                    if ($mode == 2 && $api["api_type"] == 1):
                        $smmapi = new SMMApi();
                        $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                        foreach ($services as $apiService):
                            if ($service == $apiService->service):
                                $detail["min"] = $apiService->min;
                                $detail["max"] = $apiService->max;
                                $detail["rate"] = $apiService->rate;
                                $detail["currency"] = $balance->currency;
                                $detail = json_encode($detail);
                            endif;
                        endforeach;
                    else:
                        $detail = "";
                    endif;
                    if ($package == 14 || $package == 15): $min = $limited_min;
                        $max = $min;
                        $price = $limited_price; endif;
                    $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                    $conn->beginTransaction();
                    $insert = $conn->prepare("INSERT INTO services SET name_lang=:multiName, service_speed=:speed, service_api=:api, api_service=:api_service, api_detail=:detail, category_id=:category, service_line=:line, service_type=:type, service_package=:package, service_name=:name, service_price=:price, service_min=:min, service_max=:max, service_autotime=:autotime, service_autopost=:autopost, service_secret=:secret ");
                    $insert = $insert->execute(array("api" => $provider, "multiName" => $multiName, "speed" => $speed, "detail" => $detail, "api_service" => $service, "category" => $cat, "line" => $row["service_line"] + 1, "type" => 2, "package" => $package, "name" => $name, "price" => $price, "min" => $min, "max" => $max, "autotime" => $autotime, "autopost" => $autopost, "secret" => $secret));
                    if ($insert):
                        $conn->commit();
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $referrer = base_url("admin/services");
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "edit-subscription" && $action):
            if ($_POST):
                $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default");
                $language->execute(array("default" => 1));
                $language = $language->fetch(PDO::FETCH_ASSOC);
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }
                // ismi değiştirdiği alan servicslerden
                $cat = intval(@$_POST["category"]);
                $name = $_POST["name"][$language["language_code"]];
                $multiName = json_encode($_POST["name"]);
                $serviceInfo = $conn->prepare("SELECT * FROM services INNER JOIN service_api ON service_api.id = services.service_api WHERE service_id=:id ");
                $serviceInfo->execute(array("id" => route(4)));
                $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
                if (empty($name)):
                    $error = 1;
                    $errorText = "Ürün adı boş olamaz";
                    $icon = "error";
                elseif (empty($category)):
                    $error = 1;
                    $errorText = "Ürün kategori boş olamaz";
                    $icon = "error";
                elseif (empty($provider)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı boş olamaz";
                    $icon = "error";
                elseif (empty($service)):
                    $error = 1;
                    $errorText = "Servis sağlayıcı servis bilgisi boş olamaz";
                    $icon = "error";
                elseif (empty($secret)):
                    $error = 1;
                    $errorText = "Servis gizliliği boş olamaz";
                elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($price)):
                    $error = 1;
                    $errorText = "Ürün fiyatı rakamlardan oluşmalı";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($min)):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && !is_numeric($max)):
                    $error = 1;
                    $errorText = "Maksimum sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 11 || $serviceInfo["service_package"] == 12) && $min > $max):
                    $error = 1;
                    $errorText = "Minimum sipariş miktarı maksimum sipariş miktarından fazla olamaz";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($autopost)):
                    $error = 1;
                    $errorText = "Gönderi miktarı boş olamaz";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($limited_min)):
                    $error = 1;
                    $errorText = "Sipariş miktarı boş olamaz";
                    $icon = "error";
                elseif (($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15) && !is_numeric($autotime)):
                    $error = 1;
                    $errorText = "Paket Süresi boş olamaz";
                    $icon = "error";
                else:
                    $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
                    $api->execute(array("id" => $provider));
                    $api = $api->fetch(PDO::FETCH_ASSOC);
                    if ($mode == 1): $provider = 0;
                        $service = 0; endif;
                    if ($mode == 2 && $api["api_type"] == 1):
                        $smmapi = new SMMApi();
                        $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
                        $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
                        foreach ($services as $apiService):
                            if ($service == $apiService->service):
                                $detail["min"] = $apiService->min;
                                $detail["max"] = $apiService->max;
                                $detail["rate"] = $apiService->rate;
                                $detail["currency"] = $balance->currency;
                                $detail = json_encode($detail);
                            endif;
                        endforeach;
                    else:
                        $detail = "";
                    endif;
                    if ($serviceInfo["service_package"] == 14 || $serviceInfo["service_package"] == 15): $min = $limited_min;
                        $max = $min;
                        $price = $limited_price; endif;
                    if ($serviceInfo["category_id"] != $category): $row = $conn->query("SELECT * FROM services WHERE category_id='$category' ORDER BY service_line DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);
                        $last_category = $serviceInfo["category_id"];
                        $last_line = $serviceInfo["service_line"];
                        $line = $row["service_line"] + 1;
                    else: $line = $serviceInfo["service_line"]; endif;
                    $conn->beginTransaction();
                    // abone update işlem yeri
                    $update = $conn->prepare("UPDATE services SET 
			service_speed=:speed, 
			service_api=:api,
			api_servicetype=:type, 
			api_service=:api_service, 
			api_detail=:detail,
			category_id=:category, 
			service_name=:name, 
			service_price=:price, 
			service_min=:min, 
			service_max=:max, 
			service_autotime=:autotime, 
			service_autopost=:autopost,
            name_lang=:name_lang,
			service_secret=:secret 
			WHERE service_id=:id ");
                    $update = $update->execute(array("id" => route(4), "type" => 2, "speed" => $speed, "detail" => $detail, "api" => $provider, "api_service" => $service, "category" => $category, "name" => $name, "price" => $price, "min" => $min, "max" => $max, "autotime" => $autotime, "autopost" => $autopost, "name_lang" => $multiName, "secret" => $secret));
                    if ($update):
                        $conn->commit();
                        $rows = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_line>=:line ");
                        $rows->execute(array("c_id" => $last_category, "line" => $last_line));
                        $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row):
                            $update = $conn->prepare("UPDATE services SET service_line=:line WHERE service_id=:id ");
                            $update->execute(array("line" => $row["service_line"] - 1, "id" => $row["service_id"]));
                        endforeach;
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $referrer = base_url("admin/services");
                        $icon = "success";
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            endif;
        elseif ($action == "service-active" && $action):
            $service_id = route(4);
            if (countRow(["table" => "services", "where" => ["service_id" => $service_id, "service_type" => 2]])): header("Location:" . base_url("admin/services"));
                exit(); endif;
            $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
            $update->execute(array("type" => 2, "id" => $service_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            header("Location:" . base_url("admin/services"));
        elseif ($action == "service-deactive" && $action):
            $service_id = route(4);
            if (countRow(["table" => "services", "where" => ["service_id" => $service_id, "service_type" => 1]])): header("Location:" . base_url("admin/services"));
                exit(); endif;
            $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
            $update->execute(array("type" => 1, "id" => $service_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            header("Location:" . base_url("admin/services"));
        elseif ($action == "service-copy" && $action):
            $service_id = route(4);
            if (countRow(["table" => "services", "where" => ["service_id" => $service_id, "service_type" => 1]])): header("Location:" . base_url("admin/services"));
                exit(); endif;
            $servisler = new \App\Models\services();
            $servis = $servisler->where('service_id', $service_id)->get()->getResultArray()[0];
            $where = array();
            foreach ($servis as $key => $value) {
                if ($key != "service_id") {
                    $where[$key] = $value;
                }
            }
            $servisler->protect(false)->set($where)->insert();
            return redirect()->to(base_url("admin/services"));

        elseif ($action == "del_price" && $action):
            $service_id = route(4);
            if (!countRow(["table" => "clients_price", "where" => ["service_id" => $service_id]])): $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "Servise ait fiyatlandırma bulunamadı.";
                header("Location:" . base_url("admin/services"));
                return redirect()->to(base_url("admin/services"));
                exit(); endif;
            $delete = $conn->prepare("DELETE FROM clients_price  WHERE service_id=:id ");
            $delete->execute(array("id" => $service_id));
            if ($delete):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            header("Location:" . base_url("admin/services"));

        elseif ($action == "del_cate" && $action):
            $cat_id = route(4);
            if (countRow(["table" => "services", "where" => ["category_id" => $cat_id]])):
                return redirect()->to(base_url("admin/services"));
            else:
                $delete = $conn->prepare("DELETE FROM categories WHERE category_id=:id ");
                $delete->execute(array("id" => $cat_id));
                return redirect()->to(base_url("admin/services"));
            endif;
        elseif ($action == "category-active" && $action):
            $category_id = route(4);
            $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id ");
            $update->execute(array("type" => 2, "id" => $category_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/services"));
        elseif ($action == "category-deactive" && $action):
            $category_id = route(4);
            $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id ");
            $update->execute(array("type" => 1, "id" => $category_id));
            if ($update):
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
            return redirect()->to(base_url("admin/services"));

        elseif ($action == "multi-action" && $action):
            $services = $_POST["service"];
            $action = $_POST["bulkStatus"];

            if ($action == "active"):
                foreach ($services as $id => $value):
                    $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
                    $update->execute(array("type" => 2, "id" => $id));
                endforeach;
            elseif ($action == "deactive"):
                foreach ($services as $id => $value):
                    $update = $conn->prepare("UPDATE services SET service_type=:type WHERE service_id=:id ");
                    $update->execute(array("type" => 1, "id" => $id));
                endforeach;
            elseif ($action == "secret"):
                foreach ($services as $id => $value):
                    $update = $conn->prepare("UPDATE services SET service_secret=:secret WHERE service_id=:id ");
                    $update->execute(array("secret" => 1, "id" => $id));
                endforeach;
            elseif ($action == "desecret"):
                foreach ($services as $id => $value):
                    $update = $conn->prepare("UPDATE services SET service_secret=:secret WHERE service_id=:id ");
                    $update->execute(array("secret" => 2, "id" => $id));
                endforeach;
            elseif ($action == "del_price"):
                foreach ($services as $id => $value):
                    $delete = $conn->prepare("DELETE FROM clients_price  WHERE service_id=:id ");
                    $delete->execute(array("id" => $id));
                endforeach;
            elseif ($action == "ayni_link_al"):
                foreach ($services as $id => $value):
                    $delete = $conn->prepare("UPDATE services SET rep_link=:rep  WHERE service_id=:id ");
                    $delete->execute(array("rep"=>1,"id" => $id));
                endforeach;
            elseif ($action == "ayni_link_alma"):
                foreach ($services as $id => $value):
                    $delete = $conn->prepare("UPDATE services SET rep_link=:rep  WHERE service_id=:id ");
                    $delete->execute(array("rep"=>2,"id" => $id));
                endforeach;
            elseif ($action == "del_service"):
                if ($settings["guard_services_status"] == 2 && $settings["guard_system_status"] == 2) {

                    if ($settings["guard_services_type"] == 2) {
                        guardDeleteAllRoles();
                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Servis silme</strong> İşlemi yapıldığı için tüm yetkileri alındı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));


                    } elseif ($settings["guard_services_type"] == 1) {
                        guardLogout();

                        $insert = $conn->prepare("INSERT INTO guard_log SET client_id=:c_id, action=:action, date=:date, ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "action" => "<strong>Servis silme</strong> İşlemi yapıldığı için oturumu sonlandırıldı.", "date" => date("Y-m-d H:i:s"), "ip" => GetIP()));

                    }

                } else {

                    foreach ($services as $id => $value):
                        $delete = $conn->prepare("DELETE FROM services WHERE service_id=:id ");
                        $delete->execute(array("id" => $id));
                    endforeach;

                }


            endif;
            return redirect()->to(base_url("admin/services"));
        elseif ($action == "get_services_add" && $action):
            $services = $_POST["servicesList"];
            $provider_id = $_POST["provider"];
            $smmapi = new SMMApi();
            $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
            $provider->execute(array("id" => $provider_id));
            $cat = intval(@$_POST["category"]);
            $provider = $provider->fetch(PDO::FETCH_ASSOC);
            $apiServices = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
            $balance = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'balance'), $provider["api_url"]);

            if (count($services)):
                foreach ($services as $service => $price):
                    foreach ($apiServices as $apiService):
                        if ($service == $apiService->service && $service != 0):
                            $detail["min"] = $apiService->min;
                            $detail["max"] = $apiService->max;
                            $detail["rate"] = $apiService->rate;
                            if(isset($apiService->description)){
                                $desc = $apiService->description;
                            }
                            $detail["currency"] = $balance->currency;
                            $siteLang = $settings["site_language"];
                            $package = serviceTypeGetList($apiService->type);
                            $name2 = mb_convert_encoding($apiService->name, "UTF-8", "auto");
                            $name3 = '{"' . $siteLang . '":"' . $name2 . '"}';

                            if ($package == 11):
                                $insert = $conn->prepare("INSERT INTO services SET 
                  service_api=:api,
                  api_service=:api_service,
                  category_id=:category,
                  service_line=:line,
                  service_type=:type,
                  service_package=:package,
                  service_name=:name,
                  name_lang=:lang,
                  service_price=:price,
                  service_min=:min,
                  service_max=:max,
                  sync_price=:sync_price,
                  sync_rate=:sync_rate,
                  sync_min=:sync_min,
                  sync_max=:sync_max,
                  service_description=:desc
                   ");
                                $insert = $insert->execute(array(
                                    "api" => $provider_id,
                                    "api_service" => $service,
                                    "detail" => json_encode($detail),
                                    "category" => $cat,
                                    "line" => 1,
                                    "type" => 2,
                                    "package" => $package,
                                    "name" => $name2,
                                    "lang" => $name3,
                                    "price" => $price,
                                    "min" => $apiService->min,
                                    "max" => $apiService->max,
                                    'sync_price' => (isset($_POST['percent']) && $_POST['percent'] != 0) ? 1 : 0,
                                    'sync_rate' => (isset($_POST['percent']) && $_POST['percent'] != 0) ? $_POST['percent'] : 0,
                                    'sync_min' => 1,
                                    'sync_max' => 1,
                                    'desc' => isset($desc)?$desc:""
                                ));
                            else:
                                $insert = $conn->prepare("INSERT INTO services SET 
                  service_api=:api,
                  api_service=:api_service,
                  api_detail=:detail,
                  category_id=:category,
                  service_line=:line,
                  service_type=:type,
                  service_package=:package,
                  service_name=:name,
                  name_lang=:lang,
                  service_price=:price,
                  service_min=:min,
                  service_max=:max,
                  sync_price=:sync_price,
                  sync_rate=:sync_rate,
                  sync_min=:sync_min,
                  sync_max=:sync_max,
                  service_description=:desc
                   ");
                                $insert = $insert->execute(array(
                                    "api" => $provider_id,
                                    "api_service" => $service,
                                    "detail" => json_encode($detail),
                                    "category" => $cat,
                                    "line" => 1,
                                    "type" => 2,
                                    "package" => $package,
                                    "name" => $apiService->name,
                                    "lang" => $name3,
                                    "price" => $price,
                                    "min" => $apiService->min,
                                    "max" => $apiService->max,
                                    'sync_price' => (isset($_POST['percent']) && $_POST['percent'] != 0) ? 1 : 0,
                                    'sync_rate' => (isset($_POST['percent']) && $_POST['percent'] != 0) ? $_POST['percent'] : 0,
                                    'sync_min' => 1,
                                    'sync_max' => 1,
                                    'desc' => isset($desc)?$desc:""
                                ));
                            endif;
                        endif;
                    endforeach;
                endforeach;
                echo json_encode(["t" => "error", "m" => "İşlem başarılı", "s" => "success", "r" => base_url("admin/services"), "time" => 0]);
            else:
                echo json_encode(["t" => "error", "m" => "Lütfen eklemek istediğiniz en az 1 servisi seçin", "s" => "error"]);
            endif;
        elseif ($action == "delete" && $action):
            $service_id = route(4);
            if (countRow(["table" => "clients_price", "where" => ["service_id" => $service_id]])): $_SESSION["client"]["data"]["error"] = 1;
                $delete = $conn->prepare("DELETE FROM clients_price  WHERE service_id=:id ");
                $delete->execute(array("id" => $service_id));
            endif;
            $delete = $conn->prepare("DELETE FROM services  WHERE service_id=:id ");
            $delete->execute(array("id" => $service_id));
            header("Location:" . base_url("admin/services"));
            return redirect()->to(base_url("admin/services"));
        endif;

    }

    function detail()
    {

        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        global $conn;
        global $_SESSION;
        $languages = $conn->prepare("SELECT * FROM languages WHERE language_type=:type");
        $languages->execute(["type" => 2]);
        $languages = $languages->fetchAll(PDO::FETCH_ASSOC);
        $settings = $this->settings;
        $user = $this->getuser;
        if ($this->request->uri->getSegment(3)) {
            $id = $this->request->uri->getSegment(3);
            $smmapi = new \SMMApi();
            $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
            $categories->execute([]);
            $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
            $serviceInfo = $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id=services.service_api WHERE services.service_id=:id ");
            $serviceInfo->execute(["id" => $id]);
            $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
            $providers = $conn->prepare("SELECT * FROM service_api");
            $providers->execute([]);
            $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
            $multiName = json_decode($serviceInfo["name_lang"], true);
            if (in_array($serviceInfo["service_package"], ["11", "12", "13", "14", "15"])) {
                $return = "<form class=\"form\" action=\"" . base_url("admin/services/edit-subscription/" . $serviceInfo["service_id"]) . "\" method=\"post\" data-xhr=\"true\">\r\n            <div class=\"modal-body\">";
                if (1 < count($languages)) {
                    $translationList = "<a class=\"other_services\"> Çeviriler (" . (count($languages) - 1) . ") </a>";
                } else {
                    $translationList = "";
                }
                foreach ($languages as $language) {
                    if ($language["default_language"]) {
                        $return .= "\r\n          <div class=\"form-group\">\r\n                    <label class=\"form-group__service-name\">Servis adı <span class=\"badge\">" . $language["language_name"] . "</span> " . $translationList . " </label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"name[" . $language["language_code"] . "]\" value=\"" . $multiName[$language["language_code"]] . "\">\r\n                  </div>";

                        if (1 < count($languages)) {
                            $return .= "<div class=\"hidden\" id=\"translationsList\">";
                        }
                    } else {
                        $return .= "<div class=\"form-group\">\r\n                    <label class=\"form-group__service-name\">Servis adı <span class=\"badge\">" . $language["language_name"] . "</span> </label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"name[" . $language["language_code"] . "]\" value=\"" . $multiName[$language["language_code"]] . "\">\r\n                  </div>";
                    }
                }
                if (1 < count($languages)) {
                    $return .= "</div>";
                }
                $return .= "<div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Servis Kategori</label>\r\n                  <select class=\"form-control\" name=\"category\">\r\n                        <option value=\"0\">Lütfen kategori seçin..</option>";
                foreach ($categories as $category) {
                    $return .= "<option value=\"" . $category["category_id"] . "\"";
                    if ($serviceInfo["category_id"] == $category["category_id"]) {
                        $return .= "selected";
                    }
                    $return .= ">" . $category["category_name"] . "</option>";
                }
                $return .= "</select>\r\n                </div>\r\n              </div>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Abonelik Tipi</label>\r\n                  <select class=\"form-control\" disabled  id=\"subscription_package\">\r\n                        <option value=\"11\"";
                if ($serviceInfo["service_package"] == 11) {
                    $return .= "selected";
                }
                $return .= ">Instagram Otomatik Beğeni - Sınırsız</option>\r\n                        <option value=\"12\"";
                if ($serviceInfo["service_package"] == 12) {
                    $return .= "selected";
                }
                $return .= ">Instagram Otomatik İzlenme - Sınırsız</option>\r\n                        <option value=\"14\"";
                if ($serviceInfo["service_package"] == 14) {
                    $return .= "selected";
                }
                $return .= ">Instagram Otomatik Beğeni - Süreli</option>\r\n                        <option value=\"15\"";
                if ($serviceInfo["service_package"] == 15) {
                    $return .= "selected";
                }
                $return .= ">Instagram Otomatik İzlenme - Süreli</option>\r\n                    </select>\r\n                </div>\r\n              </div>\r\n\r\n              \r\n\r\n              <div class=\"service-mode__wrapper\">\r\n\r\n                <div class=\"service-mode__block\">\r\n                  <div class=\"form-group\">\r\n                  <label>Mod</label>\r\n                    <select class=\"form-control\" name=\"mode\" id=\"serviceMode\">\r\n                          <option value=\"2\"";
                if ($serviceInfo["service_api"] != 0) {
                    $return .= "selected";
                }
                $return .= ">Otomatik (API)</option>\r\n                      </select>\r\n                  </div>\r\n                </div>\r\n\r\n\r\n                <div id=\"autoMode\" style=\"display: none\">\r\n                  <div class=\"service-mode__block\">\r\n                    <div class=\"form-group\">\r\n                    <label>Servis Sağlayıcısı</label>\r\n                      <select class=\"form-control\" name=\"provider\" id=\"provider\">\r\n                            <option value=\"0\">Servis sağlayıcı seçiniz...</option>";
                foreach ($providers as $provider) {
                    $return .= "<option value=\"" . $provider["id"] . "\"";
                    if ($serviceInfo["service_api"] == $provider["id"]) {
                        $return .= "selected";
                    }
                    $return .= ">" . $provider["api_name"] . "</option>";
                }
                $return .= "</select>\r\n                    </div>\r\n                  </div>\r\n                  <div id=\"provider_service\">";
                $services = $smmapi->action(["key" => $serviceInfo["api_key"], "action" => "services"], $serviceInfo["api_url"]);
                if ($serviceInfo["api_type"] == 1) {
                    $return .= "<div class=\"service-mode__block\">\r\n                      <div class=\"form-group\">\r\n                      <label>Servis</label>\r\n                        <select class=\"form-control\" name=\"service\">";
                    foreach ($services as $service) {
                        $return .= "<option value=\"" . $service->service . "\"";
                        if ($serviceInfo["api_service"] == $service->service) {
                            $return .= "selected";
                        }
                        $return .= ">" . $service->service . " - " . $service->name . " - " . $service->rate . "</option>";
                    }
                    $return .= "</select>\r\n                      </div>\r\n                    </div>";
                } else {
                    if ($serviceInfo["api_type"] == 3) {
                        $return .= "<div class=\"service-mode__block\">\r\n                      <div class=\"form-group\">\r\n                      <label>Servis</label>\r\n                        <input class=\"form-control\" value=\"" . $serviceInfo["api_service"] . "\" name=\"service\">\r\n                      </div>\r\n                    </div>";
                    }
                }
                $return .= "</div>\r\n                </div>\r\n              </div>\r\n\r\n              <div id=\"unlimited\">\r\n                <div class=\"form-group\">\r\n                  <label class=\"form-group__service-name\">Servis fiyatı (1000 adet)</label>\r\n                  <input type=\"text\" class=\"form-control\" name=\"price\" value=\"" . $serviceInfo["service_price"] . "\">\r\n                </div>\r\n\r\n                <div class=\"row\">\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Minimum sipariş</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"min\" value=\"" . $serviceInfo["service_min"] . "\">\r\n                  </div>\r\n\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Maksimum sipariş</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"max\" value=\"" . $serviceInfo["service_max"] . "\">\r\n                  </div>\r\n                </div>\r\n              </div>\r\n\r\n              <div id=\"limited\">\r\n                <div class=\"form-group\">\r\n                  <label class=\"form-group__service-name\">Servis fiyatı</label>\r\n                  <input type=\"text\" class=\"form-control\" name=\"limited_price\" value=\"" . $serviceInfo["service_price"] . "\">\r\n                </div>\r\n\r\n\r\n\r\n                <div class=\"row\">\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Gönderi miktarı</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"autopost\" value=\"" . $serviceInfo["service_autopost"] . "\">\r\n                  </div>\r\n\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Sipariş miktarı</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"limited_min\" value=\"" . $serviceInfo["service_min"] . "\">\r\n                  </div>\r\n                </div>\r\n                <div class=\"form-group\">\r\n                  <label class=\"form-group__service-name\">Paket Süresi <small>(gün)</small></label>\r\n                  <input type=\"text\" class=\"form-control\" name=\"autotime\" value=\"" . $serviceInfo["service_autotime"] . "\">\r\n                </div>\r\n              </div>\r\n\r\n              <hr>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Kişiye Özel Servis (Sadece seçtiğiniz kişiler görebilir.)</label>\r\n                  <select class=\"form-control\" name=\"secret\">\r\n                      <option value=\"2\"";
                if ($serviceInfo["service_secret"] == 2) {
                    $return .= "selected";
                }
                $return .= ">Hayır</option>\r\n                      <option value=\"1\"";
                if ($serviceInfo["service_secret"] == 1) {
                    $return .= "selected";
                }
                $return .= ">Evet</option>\r\n                  </select>\r\n                </div>\r\n              </div>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Servis Hızı (Servis listesinde sembol ve renk olarak gösterilir.)</label>\r\n                  <select class=\"form-control\" name=\"speed\">\r\n                      <option value=\"1\"";
                if ($serviceInfo["service_speed"] == 1) {
                    $return .= "selected";
                }
                $return .= ">Yavaş</option>\r\n                      <option value=\"2\"";
                if ($serviceInfo["service_speed"] == 2) {
                    $return .= "selected";
                }
                $return .= ">Bazen Yavaş</option>\r\n                      <option value=\"3\"";
                if ($serviceInfo["service_speed"] == 3) {
                    $return .= "selected";
                }
                $return .= ">Normal</option>\r\n                      <option value=\"4\"";
                if ($serviceInfo["service_speed"] == 4) {
                    $return .= "selected";
                }
                $return .= ">Hızlı</option>\r\n                  </select>\r\n                </div>\r\n              </div>\r\n\r\n            </div>\r\n\r\n              <div class=\"modal-footer\">\r\n                <button type=\"submit\" class=\"btn btn-primary\">Abonelik bilgilerini güncelle</button>\r\n                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Vazgeç</button>\r\n              </div>\r\n              </form>\r\n              <script type=\"text/javascript\">\r\n\r\n              var type = \$(\"#refill\").val();\r\n\r\n              if( type == 1 ){\r\n    \r\n                \$(\"#refill_day\").hide();\r\n    \r\n              } else{\r\n    \r\n                \$(\"#refill_day\").show();\r\n    \r\n              }\r\n    \r\n              \$(\"#refill\").change(function(){\r\n    \r\n                var type = \$(this).val();\r\n    \r\n                  if( type == 1 ){\r\n    \r\n                    \$(\"#refill_day\").hide();\r\n    \r\n                  } else{\r\n    \r\n                    \$(\"#refill_day\").show();\r\n    \r\n                  }\r\n    \r\n              });\r\n\r\n              \$(\".other_services\").click(function(){\r\n                var control = \$(\"#translationsList\");\r\n                if( control.attr(\"class\") == \"hidden\" ){\r\n                  control.removeClass(\"hidden\");\r\n                } else{\r\n                  control.addClass(\"hidden\");\r\n                }\r\n              });\r\n              var base_url  = \$(\"head base\").attr(\"href\");\r\n                \$(\"#provider\").change(function(){\r\n                  var provider = \$(this).val();\r\n                  getProviderServices(provider,base_url);\r\n                });\r\n\r\n                getProvider();\r\n                \$(\"#serviceMode\").change(function(){\r\n                  getProvider();\r\n                });\r\n\r\n                getSalePrice();\r\n                \$(\"#saleprice_cal\").change(function(){\r\n                  getSalePrice();\r\n                });\r\n\r\n                getSubscription();\r\n                \$(\"#subscription_package\").change(function(){\r\n                  getSubscription();\r\n                });\r\n                function getProviderServices(provider,base_url){\r\n                  if( provider == 0 ){\r\n                    \$(\"#provider_service\").hide();\r\n                  }else{\r\n                    \$.post(base_url+\"/admin/ajax_data\",{action:\"providers_list\",provider:provider}).done(function( data ) {\r\n                      \$(\"#provider_service\").show();\r\n                      \$(\"#provider_service\").html(data);\r\n                    }).fail(function(){\r\n                      alert(\"Hata oluştu!\");\r\n                    });\r\n                  }\r\n                }\r\n\r\n                function getProvider(){\r\n                  var mode = \$(\"#serviceMode\").val();\r\n                    if( mode == 1 ){\r\n                      \$(\"#autoMode\").hide();\r\n                    }else{\r\n                      \$(\"#autoMode\").show();\r\n                    }\r\n                }\r\n\r\n                function getSalePrice(){\r\n                  var type = \$(\"#saleprice_cal\").val();\r\n                    if( type == \"normal\" ){\r\n                      \$(\"#saleprice\").hide();\r\n                      \$(\"#servicePrice\").show();\r\n                    }else{\r\n                      \$(\"#saleprice\").show();\r\n                      \$(\"#servicePrice\").hide();\r\n                    }\r\n                }\r\n\r\n                function getSubscription(){\r\n                  var type = \$(\"#subscription_package\").val();\r\n                    if( type == \"11\" || type == \"12\" ){\r\n                      \$(\"#unlimited\").show();\r\n                      \$(\"#limited\").hide();\r\n                    }else{\r\n                      \$(\"#unlimited\").hide();\r\n                      \$(\"#limited\").show();\r\n                    }\r\n                }\r\n              </script>\r\n              ";
            } else {
                $return = "\r\n\r\n        <form class=\"form\" action=\"" . base_url("admin/services/edit-service/" . $serviceInfo["service_id"]) . "\" method=\"post\" data-xhr=\"true\">\r\n            <div class=\"modal-body\">";
                if (1 < count($languages)) {
                    $translationList = "<a class=\"other_services\"> Çeviriler (" . (count($languages) - 1) . ") </a>";
                } else {
                    $translationList = "";
                }
                foreach ($languages as $language) {
                    if (!isset($multiName[$language["language_code"]])) {
                        $multiName[$language["language_code"]] = "";
                    }

                    if ($language["default_language"]) {
                        $return .= "\r\n\t\t\t\t  <div class=\"form-group\">\r\n                    <label class=\"form-group__service-name\">Servis adı <span class=\"badge\">" . $language["language_name"] . "</span> " . $translationList . " </label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"name[" . $language["language_code"] . "]\" value=\"" . $multiName[$language["language_code"]] . "\">\r\n                  </div>";

                        if (1 < count($languages)) {
                            $return .= "<div class=\"hidden\" id=\"translationsList\">";
                        }
                    } else {
                        $return .= "<div class=\"form-group\">\r\n                    <label class=\"form-group__service-name\">Servis adı <span class=\"badge\">" . $language["language_name"] . "</span> </label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"name[" . $language["language_code"] . "]\" value=\"" . $multiName[$language["language_code"]] . "\">\r\n                  </div>";
                    }
                }
                if (1 < count($languages)) {
                    $return .= "</div>";
                }
                $return .= "<div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Servis Kategori</label>\r\n                  <select class=\"form-control\" name=\"category\">\r\n                        <option value=\"0\">Lütfen kategori seçin..</option>";
                foreach ($categories as $category) {
                    $return .= "<option value=\"" . $category["category_id"] . "\"";
                    if ($serviceInfo["category_id"] == $category["category_id"]) {
                        $return .= "selected";
                    }
                    $return .= ">" . $category["category_name"] . "</option>";
                }
                $return .= "</select>\r\n                </div>\r\n              </div>\r\n\r\n              <div class=\"service-mode__wrapper\">\r\n                <div class=\"service-mode__block\">\r\n                  <div class=\"form-group\">\r\n                  <label>Servis Tipi</label>\r\n                    <select class=\"form-control\" name=\"package\">\r\n                          <option value=\"1\"";
                if ($serviceInfo["service_package"] == 1) {
                    $return .= "selected";
                }
                $return .= ">Servis</option>\r\n                          <option value=\"2\"";
                if ($serviceInfo["service_package"] == 2) {
                    $return .= "selected";
                }
                $return .= ">Paket</option>\r\n                          <option value=\"3\"";
                if ($serviceInfo["service_package"] == 3) {
                    $return .= "selected";
                }
                $return .= ">Özel Yorum</option>\r\n                          <option value=\"4\"";
                if ($serviceInfo["service_package"] == 4) {
                    $return .= "selected";
                }
                $return .= ">Paket Yorum</option>\r\n                      </select>\r\n                  </div>\r\n                </div>\r\n                <div class=\"service-mode__block\">\r\n                  <div class=\"form-group\">\r\n                  <label>Mod</label>\r\n                    <select class=\"form-control\" name=\"mode\" id=\"serviceMode\">\r\n                          <option value=\"1\"";
                if ($serviceInfo["service_api"] == 0) {
                    $return .= "selected";
                }
                $return .= ">Manuel</option>\r\n                          <option value=\"2\"";
                if ($serviceInfo["service_api"] != 0) {
                    $return .= "selected";
                }
                $return .= ">Otomatik (API)</option>\r\n                      </select>\r\n                  </div>\r\n                </div>\r\n\r\n                <div id=\"autoMode\" style=\"display: none\">\r\n                  <div class=\"service-mode__block\">\r\n                    <div class=\"form-group\">\r\n                    <label>Servis Sağlayıcısı</label>\r\n                      <select class=\"form-control\" name=\"provider\" id=\"provider\">\r\n                            <option value=\"0\">Servis sağlayıcı seçiniz...</option>";
                foreach ($providers as $provider) {
                    $return .= "<option value=\"" . $provider["id"] . "\"";
                    if ($serviceInfo["service_api"] == $provider["id"]) {
                        $return .= "selected";
                    }
                    $return .= ">" . $provider["api_name"] . "</option>";
                }
                $return .= "</select>\r\n                    </div>\r\n                  </div>\r\n                  <div id=\"provider_service\">";
                $services = $smmapi->action(["key" => $serviceInfo["api_key"], "action" => "services"], $serviceInfo["api_url"]);
                $balance = $smmapi->action(array('key' => $serviceInfo["api_key"], 'action' => 'balance'), $serviceInfo["api_url"]);
                if ($serviceInfo["api_type"] == 1) {
                    $return .= "<div class=\"service-mode__block\">\r\n                        <div class=\"form-group\">\r\n                        <label>Servis</label>\r\n                          <select class=\"form-control\" name=\"service\">";

                    $return .= "</select>\r\n                        </div>\r\n                      </div>";
                } else {
                    if ($serviceInfo["api_type"] == 3) {
                        $return .= "<div class=\"service-mode__block\">\r\n                        <div class=\"form-group\">\r\n                        <label>Servis</label>\r\n                          <input class=\"form-control\" value=\"" . $serviceInfo["api_service"] . "\" name=\"service\">\r\n                        </div>\r\n                      </div>";
                    }
                }
                $return .= "</div>\r\n                  <div class=\"service-mode__block\">\r\n                    <div class=\"form-group\">\r\n                    <label>Dripfeed</label>\r\n                      <select class=\"form-control\" name=\"dripfeed\">\r\n                        <option value=\"1\"";
                if ($serviceInfo["service_dripfeed"] == 1) {
                    $return .= "selected";
                }
                $return .= ">Pasif</option>\r\n                        <option value=\"2\"";
                if ($serviceInfo["service_dripfeed"] == 2) {
                    $return .= "selected";
                }
                $return .= ">Aktif</option>\r\n                      </select>\r\n                    </div>\r\n                  </div>\r\n                </div>\r\n              </div>\r\n\r\n";
                if ($serviceInfo["service_api"] == 0) {
                    $return .= "\r\n    \r\n    \r\n                <div class=\"form-group\">\r\n                  <label class=\"form-group__service-name\">Servis fiyatı (1000 adet)</label>\r\n                  <input type=\"text\" class=\"form-control\" name=\"price\" value=\"" . $serviceInfo["service_price"] . "\">\r\n                </div>\r\n\r\n                <div class=\"row\">\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Minimum sipariş</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"min\" value=\"" . $serviceInfo["service_min"] . "\">\r\n                  </div>\r\n\r\n                  <div class=\"col-md-6 form-group\">\r\n                    <label class=\"form-group__service-name\">Maksimum sipariş</label>\r\n                    <input type=\"text\" class=\"form-control\" name=\"max\" value=\"" . $serviceInfo["service_max"] . "\">\r\n                  </div>\r\n                </div>\r\n          \r\n\r\n    \r\n    \r\n    ";
                } else {
                    $return .= "\r\n    \r\n    \r\n       <div class=\"form-group\">\r\n                                        <label class=\"form-group__service-name\" >";
                    if ($serviceInfo["sync_price"] == 1) {
                        $return .= "% Kaç arttırılsın?";
                    } else {
                        $return .= "1000 Adet Ücreti";
                    }
                    $return .= "</label>\r\n\r\n                  <div class=\"form-group\">\r\n                        <div class=\"input-group\">\r\n                            <input type=\"text\" ";
                    if ($serviceInfo["sync_price"] == 1) {
                        $return .= "style=\"display:none\"";
                    }
                    $return .= " class=\"form-control\" id=\"priceInput\" name=\"price\" value=\"" . $serviceInfo["service_price"] . "\">\r\n                            <div id=\"priceThreeInput\" ";
                    if ($serviceInfo["sync_price"] == 0) {
                        $return .= "style=\"display:none\"";
                    }
                    $return .= ">\r\n                            <div class=\"col-md-6\">\r\n                             <input type=\"text\" class=\"form-control\" style=\"border-radius:5px;\" id=\"priceInput\" name=\"sync_rate\" value=\"" . $serviceInfo["sync_rate"] . "\">\r\n                             </div>\r\n                             <div class=\"col-md-6\">\r\n                             \r\n                             <div class = \"input-group\">\r\n                            \t <span class = \"input-group-addon\">" . $settings["site_currency"] . "</span>\r\n                             \t<input type = \"text\" value=\"" . $serviceInfo["service_price"] . "\" readonly class =\" form-control\" placeholder=\"MajerPanel\">\r\n                             \r\n                     \t        <span class = \"input-group-addon\">";

                }
                $return .= "</span>\r\n                     \t    \r\n                     \t  </div>\r\n                     \t  \r\n                     \t  \r\n                                <input type=\"hidden\" name=\"price_api\" value=\"";

                $return .= "\">\r\n                                \r\n                             </div>\r\n\r\n                         </div>\r\n\r\n                            <div class=\"input-group-addon\">\r\n                            <label class=\"switch\"><input  id=\"priceCheckbox\"  type=\"checkbox\" name=\"auto_price\" ";
                if ($serviceInfo["sync_price"] == 1) {
                    $return .= "checked";
                }
                $return .= "/>\r\n                            <span class=\"slider round\"></span>\r\n                        </label>\r\n                         \r\n                     </div>\r\n                    </div>\r\n                    </div>\r\n                    \r\n              </div>\r\n\r\n              <div class=\"row\">\r\n                <div class=\"col-md-6 form-group\">\r\n                  <label class=\"form-group__service-name\">Minimum Sipariş</label>\r\n                \r\n                <div id=\"minText\" style=\"padding: 11px;margin-left: 4px;\" class=\"form-group__provider-value\">";

                $return .= "</div>\r\n                \r\n                     <div class=\"form-group\">\r\n                        <div class=\"input-group\">\r\n                            <input type=\"number\" id=\"minPriceInput\" style=\"height:43px;\" class=\"form-control\" name=\"min\" value=\"" . $serviceInfo["service_min"] . "\" ";
                if ($serviceInfo["sync_min"] == 1) {
                    $return .= "readonly";
                }
                $return .= ">\r\n                            <div class=\"input-group-addon\" >\r\n                            <label class=\"switch\"><input  id=\"minPriceCheckbox\"  type=\"checkbox\" name=\"auto_min\" ";
                if ($serviceInfo["sync_min"] == 1) {
                    $return .= "checked";
                }
                $return .= " />\r\n                            <span  class=\"slider round\"></span>\r\n                        </label>\r\n                     </div>\r\n                    </div>\r\n                    </div>\r\n                  </div>\r\n\r\n          \r\n                    \r\n                <div class=\"col-md-6 form-group\">\r\n                  <label class=\"form-group__service-name\">Maksimum Sipariş</label>\r\n                  \r\n                  <div id=\"maxText\" style=\"padding: 11px;margin-left: 4px;\" class=\"form-group__provider-value\">";

                $return .= "</div>\r\n                \r\n                    <div class=\"form-group\">\r\n                        <div class=\"input-group\">\r\n                            <input type=\"number\" id=\"maxPriceInput\" style=\"height:43px;\" class=\"form-control\" name=\"max\" value=\"" . $serviceInfo["service_max"] . "\" ";
                if ($serviceInfo["sync_max"] == 1) {
                    $return .= "readonly";
                }
                $return .= ">\r\n                            <div class=\"input-group-addon\" >\r\n                            <label class=\"switch\"><input id=\"maxPriceCheckbox\"  type=\"checkbox\" name=\"auto_max\" ";
                if ($serviceInfo["sync_max"] == 1) {
                    $return .= "checked";
                }
                $return .= "/>\r\n                            <span   class=\"slider round\"></span>\r\n                        </label>\r\n                    </div>\r\n                </div>\r\n              </div>\r\n              </div>\r\n              </div>\r\n\r\n    \r\n    \r\n    \r\n    ";
            }
            $return .= "\r\n    \r\n<hr>\r\n\r\n<div class=\"row\">\r\n<div class=\"form-group col-md-6\">\r\n<label>İptal butonu</label>\r\n  <select class=\"form-control\" name=\"cancel_type\">\r\n      <option value=\"2\"";
            if ($serviceInfo["cancel_type"] == 2) {
                $return .= "selected";
            }
            $return .= ">Aktif</option>\r\n      <option value=\"1\"";
            if ($serviceInfo["cancel_type"] == 1) {
                $return .= "selected";
            }
            $return .= ">Pasif</option>\r\n  </select>\r\n</div>\r\n\r\n\r\n<div class=\"form-group col-md-6\">\r\n<label>Refill Butonu</label>\r\n  <select class=\"form-control\" id=\"refill\" name=\"refill_type\">\r\n      <option value=\"2\"";
            if ($serviceInfo["refill_type"] == 2) {
                $return .= "selected";
            }
            $return .= ">Aktif</option>\r\n      <option value=\"1\"";
            if ($serviceInfo["refill_type"] == 1) {
                $return .= "selected";
            }
            $return .= ">Pasif</option>\r\n  </select>\r\n</div>\r\n</div>\r\n\r\n<div id=\"refill_day\" class=\"form-group\">\r\n<label>Refill Maksimum Gün <small>(Lifetime ise 0 yazınız)</small></label>\r\n  <input type=\"number\" class=\"form-control\" name=\"refill_time\" value=\"" . $serviceInfo["refill_time"] . "\">\r\n</div>\r\n\r\n\r\n              <hr>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Sipariş Bağlantı <small>(Yeni sipariş sayfasında gösterilir)</small></label>\r\n                  <select class=\"form-control\" name=\"want_username\">\r\n                      <option value=\"1\"";
            if ($serviceInfo["want_username"] == 1) {
                $return .= "selected";
            }
            $return .= ">Link</option>\r\n                      <option value=\"2\"";
            if ($serviceInfo["want_username"] == 2) {
                $return .= "selected";
            }
            $return .= ">Kullanıcı adı</option>\r\n                  </select>\r\n                </div>\r\n              </div>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Kişiye Özel Servis <small>(Sadece seçtiğiniz kişiler görebilir)</small></label>\r\n                  <select class=\"form-control\" name=\"secret\">\r\n                      <option value=\"2\"";
            if ($serviceInfo["service_secret"] == 2) {
                $return .= "selected";
            }
            $return .= ">Hayır</option>\r\n                      <option value=\"1\"";
            if ($serviceInfo["service_secret"] == 1) {
                $return .= "selected";
            }
            $return .= ">Evet</option>\r\n                  </select>\r\n                </div>\r\n              </div>\r\n\r\n              <div class=\"service-mode__block\">\r\n                <div class=\"form-group\">\r\n                <label>Servis Hızı <small>(Servis listesinde sembol ve renk olarak gösterilir)</small></label>\r\n                  <select class=\"form-control\" name=\"speed\">\r\n                      <option value=\"1\"";
            if ($serviceInfo["service_speed"] == 1) {
                $return .= "selected";
            }
            $return .= ">Yavaş</option>\r\n                      <option value=\"2\"";
            if ($serviceInfo["service_speed"] == 2) {
                $return .= "selected";
            }
            $return .= ">Bazen Yavaş</option>\r\n                      <option value=\"3\"";
            if ($serviceInfo["service_speed"] == 3) {
                $return .= "selected";
            }
            $return .= ">Normal</option>\r\n                      <option value=\"4\"";
            if ($serviceInfo["service_speed"] == 4) {
                $return .= "selected";
            }
            $return .= ">Hızlı</option>\r\n                  </select>\r\n                </div>\r\n              </div>\r\n\r\n            </div>\r\n\r\n              <div class=\"modal-footer\">\r\n                <button type=\"submit\" class=\"btn btn-primary\">Servis bilgilerini güncelle</button>\r\n                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Vazgeç</button>\r\n              </div>\r\n              </form>\r\n              <script type=\"text/javascript\">\r\n\r\n              var type = \$(\"#refill\").val();\r\n\r\n              if( type == 1 ){\r\n    \r\n                \$(\"#refill_day\").hide();\r\n    \r\n              } else{\r\n    \r\n                \$(\"#refill_day\").show();\r\n    \r\n              }\r\n    \r\n              \$(\"#refill\").change(function(){\r\n    \r\n                var type = \$(this).val();\r\n    \r\n                  if( type == 1 ){\r\n    \r\n                    \$(\"#refill_day\").hide();\r\n    \r\n                  } else{\r\n    \r\n                    \$(\"#refill_day\").show();\r\n    \r\n                  }\r\n    \r\n              });\r\n       \r\n              /* minprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */\r\n               \$(\"#minPriceCheckbox\").click(function(){\r\n                    var minPriceInput = \$(\"#minPriceInput\");  \r\n                    var minText = \$(\"#minText\");\r\n                   if(!this.checked){\r\n                    minPriceInput.removeAttr(\"readonly\",\"readonly\");\r\n                   }else{\r\n                    minPriceInput.attr(\"readonly\",\"readonly\");\r\n                    minPriceInput.val(minText.text());\r\n                   }\r\n                });\r\n       \r\n       \r\n              /* maxprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */\r\n               \$(\"#maxPriceCheckbox\").click(function(){\r\n                    var maxPriceInput = \$(\"#maxPriceInput\");  \r\n                    var maxText = \$(\"#maxText\");\r\n                   if(!this.checked){\r\n                    maxPriceInput.removeAttr(\"readonly\",\"readonly\");\r\n                   }else{\r\n                    maxPriceInput.attr(\"readonly\",\"readonly\");\r\n                    maxPriceInput.val(maxText.text());\r\n                   }\r\n                });\r\n\r\n       \r\n              /* maxprice checkbox eventıdır tıklandığı zaman minpriceinputunun readonly yapıyor check edildiği zaman edilmediği zaman ise input açık kalıyor */\r\n               \$(\"#priceCheckbox\").click(function(){\r\n                    var priceInput = \$(\"#priceInput\");  \r\n                    var priceThree = \$(\"#priceThreeInput\");\r\n                   if(this.checked){\r\n                        priceInput.css(\"display\",\"none\");\r\n                        priceThree.css(\"display\",\"block\");\r\n                   }else{\r\n                        priceThree.css(\"display\",\"none\");\r\n                        priceInput.css(\"display\",\"block\");\r\n                   }\r\n                });\r\n                \r\n               \$(\".other_services\").click(function(){\r\n                 var control = \$(\"#translationsList\");\r\n                 if( control.attr(\"class\") == \"hidden\" ){\r\n                   control.removeClass(\"hidden\");\r\n                 } else{\r\n                   control.addClass(\"hidden\");\r\n                 }\r\n               });\r\n              var base_url  = \$(\"head base\").attr(\"href\");\r\n                \$(\"#provider\").change(function(){\r\n                  var provider = \$(this).val();\r\n                  getProviderServices(provider,base_url);\r\n                });\r\n\r\n                getProvider();\r\n                \$(\"#serviceMode\").change(function(){\r\n                  getProvider();\r\n                });\r\n\r\n                getSalePrice();\r\n                \$(\"#saleprice_cal\").change(function(){\r\n                  getSalePrice();\r\n                });\r\n\r\n                getSubscription();\r\n                \$(\"#subscription_package\").change(function(){\r\n                  getSubscription();\r\n                });\r\n                function getProviderServices(provider,base_url){\r\n                  if( provider == 0 ){\r\n                    \$(\"#provider_service\").hide();\r\n                  }else{\r\n                    \$.post(base_url+\"/admin/ajax_data\",{action:\"providers_list\",provider:provider}).done(function( data ) {\r\n                      \$(\"#provider_service\").show();\r\n                      \$(\"#provider_service\").html(data);\r\n                    }).fail(function(){\r\n                      alert(\"Hata oluştu!\");\r\n                    });\r\n                  }\r\n                }\r\n\r\n                function getProvider(){\r\n                  var mode = \$(\"#serviceMode\").val();\r\n                    if( mode == 1 ){\r\n                      \$(\"#autoMode\").hide();\r\n                    }else{\r\n                      \$(\"#autoMode\").show();\r\n                    }\r\n                }\r\n\r\n                function getSalePrice(){\r\n                  var type = \$(\"#saleprice_cal\").val();\r\n                    if( type == \"normal\" ){\r\n                      \$(\"#saleprice\").hide();\r\n                      \$(\"#servicePrice\").show();\r\n                    }else{\r\n                      \$(\"#saleprice\").show();\r\n                      \$(\"#servicePrice\").hide();\r\n                    }\r\n                }\r\n\r\n                function getSubscription(){\r\n                  var type = \$(\"#subscription_package\").val();\r\n                    if( type == \"11\" || type == \"12\" ){\r\n                      \$(\"#unlimited\").show();\r\n                      \$(\"#limited\").hide();\r\n                    }else{\r\n                      \$(\"#unlimited\").hide();\r\n                      \$(\"#limited\").show();\r\n                    }\r\n                }\r\n              </script>\r\n              ";
            //echo json_encode(["content" => $return, "title" => "Servis düzenle (ID: " . $serviceInfo["service_id"] . ")"]);
            $smmapi = new SMMApi();
            $services_api = $serviceInfo["api_type"] == 1 ? $smmapi->action(["key" => $serviceInfo["api_key"], "action" => "services"], $serviceInfo["api_url"]) : 0;
            $service_api_n = new service_api();
            if ($serviceInfo['birlestirme']):
                $s_bul = $service_api_n->where('id', $serviceInfo['service_api2'])->get()->getResultArray()[0];
                $services_api2 = $serviceInfo["api_type"] == 1 ? $smmapi->action(["key" => $s_bul["api_key"], "action" => "services"], $s_bul["api_url"]) : 0;
            else:
                $services_api2 = 0;
            endif;
            $ayar = array(
                'title' => 'Services',
                'user' => $this->getuser,
                'route' => $this->request->uri->getSegment(2),
                'settings' => $this->settings,
                'success' => 0,
                'error' => 0,
                'search_word' => '',
                'search_where' => 'username',
                'categories' => $categories,
                'serviceInfo' => $serviceInfo,
                'providers' => $providers,
                'services' => $services,
                'languages' => $languages,
                'balance' => isset($balance) ? $balance : 0,
                'api_service' => $services_api,
                'api_service2' => $services_api2
            );

            return view('admin/yeni_admin/service-detail', $ayar);
        }
    }


    function new()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        global $conn;
        global $_SESSION;
        $languages = $conn->prepare("SELECT * FROM languages WHERE language_type=:type");
        $languages->execute(["type" => 2]);
        $languages = $languages->fetchAll(PDO::FETCH_ASSOC);
        $settings = $this->settings;
        $user = $this->getuser;
        $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
        $categories->execute([]);
        $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
        $providers = $conn->prepare("SELECT * FROM service_api");
        $providers->execute([]);
        $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
        $ayar = array(
            'title' => 'New Service',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
            'categories' => $categories,
            'providers' => $providers,
            'languages' => $languages
        );

        return view('admin/yeni_admin/service-new', $ayar);
    }

    function services_ajax()
    {
        global $conn;
        global $_SESSION;
        $referrer = "";
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $settings = $this->settings;
        if (!route(3)):
            $page = 1;
        elseif (is_numeric(route(3))):
            $page = route(3);
        elseif (!is_numeric(route(3))):
            $action = route(3);
        endif;
        if (empty($action)):
            $services = new \App\Models\services();
            $servisler = $services->select("*")
                ->join('categories', 'categories.category_id = services.category_id', 'right')
                ->join('service_api', 'service_api.id = services.service_api', 'left')->get()->getResultArray();
            $serviceList = array_group_by($servisler, 'category_name');
            $query = $conn->query("SELECT * FROM settings", PDO::FETCH_ASSOC);
            if ($query->rowCount()):
                foreach ($query as $row):
                    if (isset($row['servis_siralama'])) {
                        $siraal = $row['servis_siralama'];
                    };
                endforeach;
            endif;


            $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id = services.category_id LEFT JOIN service_api ON service_api.id = services.service_api ORDER BY categories.category_line,services.service_line ");
            $services->execute(array());
            $services = $services->fetchAll(PDO::FETCH_ASSOC);
            $serviceList = array_group_by($services, 'category_name');
            $ayar = array(
                'title' => 'Services',
                'user' => $this->getuser,
                'route' => $this->request->uri->getSegment(2),
                'settings' => $this->settings,
                'success' => 0,
                'error' => 0,
                'search_word' => '',
                'serviceList' => $serviceList,
                'search_where' => 'username',
            );
            //return view('admin/services', $ayar);
            return view('admin/yeni_admin/services-ajax', $ayar);

        endif;

    }
}