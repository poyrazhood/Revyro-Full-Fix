<?php

namespace App\Controllers;

use App\Models\clients;
use App\Models\orders;
use App\Models\service_api;
use App\Models\services;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Cron extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Europe/Istanbul');
        include FCPATH . "Glycon.php";
        $this->key = LISANCEKEY;
        $user = new \App\Models\clients();
        $settings = new \App\Models\settings();
        $this->settings = $settings->where('id', '1')->get()->getResultArray()[0];
        $getuser = $user->where('client_id', '1')->get()->getResultArray()[0];
        $getuser['access'] = json_decode($getuser['access'], true);
        $this->getuser = $getuser;
        $this->db = db_connect();
        helper('function');
    }

    function api_detail()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $service = new service_api();
        $apiler = $service->get()->getResultArray();
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        foreach ($apiler as $api) {
            $balance = $smmapi->action(array('key' => $api["api_key"], 'action' => 'balance'), $api["api_url"]);
            if (isset($balance->balance)) {
                $array = array(
                    'balance' => $balance->balance,
                    'currency' => $balance->currency,

                );
                if (isset($balance->software) && $balance->software == "Glycon") {
                    $array['software'] = "Glycon";
                }
                $duzenle = $service->set('api_json', json_encode($array))->set('update_date', date('Y-m-d H:i:s'))->where('id', $api['id'])->update();
            }
        }
    }

    function api_orders()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $fapi = new \socialsmedia_api();
        global $conn;
        global $_SESSION;
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if (empty($_GET["token"]) || $_GET["token"] != $this->key) :
            die;
        endif;
        $orders = $conn->prepare("SELECT *,services.service_id as service_id,services.service_api as api_id FROM orders
  INNER JOIN clients ON clients.client_id=orders.client_id
  INNER JOIN services ON services.service_id=orders.service_id
  LEFT JOIN categories ON categories.category_id=services.category_id
  INNER JOIN service_api ON service_api.id=services.service_api
  WHERE orders.dripfeed=:dripfeed && orders.subscriptions_type=:subs && orders.order_status=:statu && orders.order_error=:error && orders.order_detail=:detail ORDER BY rand() LIMIT 10 ");
        $orders->execute(array("dripfeed" => 1, "subs" => 1, "statu" => "pending", "detail" => "cronpending", "error" => "-"));
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);


        foreach ($orders as $order) {
            $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
            $user->execute(array("id" => $order["client_id"]));
            $user = $user->fetch(PDO::FETCH_ASSOC);
            $price = $order["order_charge"];
            $clientBalance = $user["balance"];
            $clientSpent = $user["spent"];
            $balance_type = $order["balance_type"];
            $balance_limit = $order["debit_limit"];
            $link = $order["order_url"];

            //$conn->beginTransaction();

            if ($order["api_type"] == 1) :
                ## Standart api başla ##
                if ($order["service_package"] == 1 || $order["service_package"] == 2) :
                    ## Standart başla ##
                    //$get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                    $bol = birlesme_bolme($order['order_quantity']);
                    if (!$order['birlestirme']) {
                        $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order['order_quantity']), $order["api_url"]);
                    } elseif ($order["birlestirme"]) {
                        $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $bol[0]), $order["api_url"]);

                        if (!$order['sirali_islem']) {

                            $api_v = new \App\Models\service_api();
                            $api_v_c = $api_v->where('id', $order['service_api2'])->get()->getResultArray()[0];
                            $order2 = $smmapi->action(array('key' => $order['api_key'], 'action' => 'add', 'service' => $order["api_service2"], 'link' => $order["order_url"], 'quantity' => $bol[1]), $order["api_url"]);
                            if (!isset($order2->order)) :
                                $error2 = json_encode($order2);
                                $order_id2 = "";
                                $proc = "fail";
                            else :
                                $error2 = "-";
                                $proc = "processing";
                                $order_id2 = $order2->order;

                            endif;

                            $last_id = $order['order_id'];

                            $api_v = new \App\Models\cift_servis();
                            $api_v_c = $api_v->set(['order_id' => $last_id, 'status' => $proc])->insert();
                        }
                    }
                    print_r($get_order);

                    if (!isset($get_order->order)) :
                        $error = json_encode($get_order);
                        $order_id = "";
                    else :
                        $error = "-";
                        $order_id = $get_order->order;
                    endif;
                ## Standart bitti ##

                elseif ($order["service_package"] == 3) :
                    ## Custom comments başla ##

                    $comments = json_decode($order["order_extras"], true);
                    $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'comments' => $comments["comments"]), $order["api_url"]);
                    if (!isset($get_order->order)) :
                        $error = json_encode($get_order);
                        $order_id = "";
                    else :
                        $error = "-";
                        $order_id = $get_order->order;
                    endif;
                ## Custom comments bitti ##

                endif;
                $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);

                $api_charge = $orderstatus->charge;
                if (!$api_charge) : $api_charge = 0;
                endif;
                $currency = $balance->currency;

                $currencycharge = 1;

            ## Standart api bitti ##
            elseif ($order["api_type"] == 3) :
                if ($order["service_package"] == 1 || $order["service_package"] == 2) :
                    ## Standart başla ##
                    if ($order["service_api"] != 0) :
                        $api_detail = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
                        $api_detail->execute(array("id" => $order["service_api"]));
                        $api_detail = $api_detail->fetch(PDO::FETCH_ASSOC);
                    endif;
                    $bol = birlesme_bolme($order['order_quantity']);
                    if (!$order['birlestirme']) {
                        $get_order = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $link, 'quantity' => $order['order_quantity']), $api_detail["api_url"]);
                    } elseif ($order["birlestirme"]) {
                        $get_order = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $link, 'quantity' => $bol[0]), $api_detail["api_url"]);
                        echo $order['sirali_islem'];
                        if (!$order['sirali_islem']) {

                            $api_v = new \App\Models\service_api();
                            $api_v_c = $api_v->where('id', $order['service_api2'])->get()->getResultArray()[0];
                            $order2 = $smmapi->action(array('key' => $api_v_c['api_key'], 'action' => 'add', 'service' => $order["api_service2"], 'link' => $link, 'quantity' => $bol[1]), $api_v_c["api_url"]);
                            if (!isset($order2->order)) :
                                $error2 = json_encode($order2);
                                $order_id2 = "";
                            else :
                                $error2 = "-";
                                $order_id2 = $order2->order;

                            endif;
                            echo $error2;
                            echo $order_id2;
                        }
                    }
                    $get_order = $fapi->query(array('cmd' => 'orderadd', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orders' => [['service' => $order["api_service"], 'amount' => $order["order_quantity"], 'data' => $order["order_url"]]]));
                    if (@!$get_order[0][0]['status'] == "error") :
                        $error = json_encode($get_order);
                        $order_id = "";
                        $api_charge = "0";
                        $currencycharge = 1;
                    else :
                        $error = "-";
                        $order_id = @$get_order[0][0]["id"];
                        $orderstatus = $fapi->query(array('cmd' => 'orderstatus', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orderid' => [$order_id]));
                        $balance = $fapi->query(array('cmd' => 'profile', 'token' => $order["api_key"], 'apiurl' => $order["api_url"]));
                        $api_charge = $orderstatus[$order_id]["order"]["price"];
                        $currency = "TRY";

                        $currencycharge = 1;

                    endif;

                ## Standart bitti ##
                endif;

            endif;

            $update_order = $conn->prepare("UPDATE orders SET order_start=:start, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit,api_orderid2=:orders2,order_error2=:errors2,order_detail2=:detail2  WHERE order_id=:id ");
            $update_order = $update_order->execute(array("start" => 0, "error" => $error, "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge, 'orders2' => isset($order_id2) ? $order_id2 : 0, 'errors2' => isset($error2) ? $error2 : 0, 'detail2' => isset($order2) ? json_encode($order2) : "",));

            /*if ($update_order) {
                $conn->commit();
            } else {
                $conn->rollBack();
            } */
        }
    }

    function site_orders()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        global $conn;
        global $_SESSION;
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if (empty($_GET["token"]) || $_GET["token"] != $this->key) :
            die;
        endif;

        $orders = $conn->prepare("SELECT *,services.service_id as service_id,services.service_api as api_id FROM orders
  INNER JOIN clients ON clients.client_id=orders.client_id
  INNER JOIN services ON services.service_id=orders.service_id
  LEFT JOIN categories ON categories.category_id=services.category_id
  INNER JOIN service_api ON service_api.id=orders.order_api
  WHERE orders.dripfeed=:dripfeed && orders.subscriptions_type=:subs && ( orders.order_status=:statu1 || orders.order_status=:statu2  || orders.order_status=:statu3 ) ORDER BY rand() LIMIT 100 ");
        $orders->execute(array("dripfeed" => 1, "subs" => 1, "statu1" => "pending", "statu2" => "inprogress", "statu3" => "processing"));
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as $order) :

            $extra = json_decode($order["order_error"]);
            if (@!$extra->error) :

                $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
                $user->execute(array("id" => $order["client_id"]));
                $user = $user->fetch(PDO::FETCH_ASSOC);
                $order["balance"] = $user["balance"];
                $clientBalance = $user["balance"];
                $orderid = $order["order_id"];

                if ($order["api_type"] == 1) :
                    ## Standart api başla ##

                    $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order["api_orderid"]), $order["api_url"]);
                    if (isset($orderstatus->error)) {
                        continue;
                    }
                    $api_charge = $orderstatus->charge;
                    $statu = str_replace(" ", "", strtolower($orderstatus->status));
                    $start = $orderstatus->start_count;
                    $remains = $orderstatus->remains;
                    $finish = 0;
                    if (!$api_charge) : $api_charge = 0;
                    endif;

                ## Standart api bitti ##
                endif;

                if (empty($start) || !$start) :
                    $start = 0;
                endif;

                if (empty($remains) || !$remains) :
                    $remains = 0;
                endif;

                if ($remains > $order["order_quantity"]) :
                    $remains = $order["order_quantity"];
                endif;

                if (0 > $remains) :
                    $remains = 0;
                endif;

                if ($statu == "canceled" || $statu == "cancel") :
                    $conn->beginTransaction();

                    $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                    $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $order["balance"] + $order["order_charge"], "spent" => $order["spent"] - $order["order_charge"]));

                    $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
                    $user->execute(array("id" => $order["client_id"]));
                    $user = $user->fetch(PDO::FETCH_ASSOC);

                    $report = $conn->prepare("INSERT INTO client_report SET client_id=:id, action=:action, report_ip=:ip, report_date=:date ");
                    $report = $report->execute(array("date" => date("Y-m-d H:i:s"), "id" => $order["client_id"], "action" => "#" . $orderid . " numaralı sipariş iptal edildi ve " . $order["order_charge"] . " TL ücret iade edildi Eski bakiye:" . $clientBalance . " / Yeni bakiye:" . $user["balance"], "ip" => "127.0.0.1"));

                    $update = $conn->prepare("UPDATE orders SET order_detail=:detail, order_start=:start, order_finish=:finish, order_remains=:remains, order_status=:status, order_charge=:charge, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("id" => $orderid, "start" => $start, "finish" => $finish, "detail" => json_encode($orderstatus), "remains" => $remains, "status" => "canceled", "charge" => 0, "api_charge" => 0, "order_profit" => 0));

                    if ($update && $update2 && $report) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                elseif ($statu == 'complete' || $statu == 'completed') :
                    $conn->beginTransaction();
                    if ($this->settings['site_currency'] == "TRY") :
                        if ($orderstatus->currency == "USD") :

                            $dolar = str_replace(",", ".", $this->settings['dolar']);
                            $dolar = floatval($dolar);
                            $api_charge = $orderstatus->charge * $dolar;
                        elseif ($orderstatus->currency == "EUR") :
                            $euro = str_replace(",", ".", $this->settings['euro']);
                            $euro = floatval($euro);
                            $api_charge = $orderstatus->charge * $euro;
                        else :
                            $api_charge = $orderstatus->charge;
                        endif;
                    endif;
                    $report = $conn->prepare("INSERT INTO client_report SET client_id=:id, action=:action, report_ip=:ip, report_date=:date ");
                    $report = $report->execute(array("date" => date("Y-m-d H:i:s"), "id" => $order["client_id"], "action" => "#" . $orderid . " numaralı sipariş tamamlandı.", "ip" => "127.0.0.1"));

                    $update = $conn->prepare("UPDATE orders SET order_start=:start, order_finish=:finish, order_remains=:remains, order_status=:status, order_remains=:remains, order_detail=:detail, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("start" => $start, "finish" => $finish, "remains" => 0, "status" => "completed", "detail" => json_encode($orderstatus), "id" => $orderid, "order_profit" => $order["api_currencycharge"] * $api_charge, "api_charge" => $api_charge, "remains" => $remains));

                    $client = new clients();
                    $cc = $client->where('client_id', $order['client_id'])->get()->getResultArray()[0];
                    if ($cc['referral'] != Null && $cc['referral'] != "") {
                        $ref_id = $cc['referral'];
                        $oran = $order['order_charge'] * $settings['ref_bonus'] / 100;
                        /* $this->db->table("referral")->set(array(
                            'client_id' => $ref_id,
                            'refferal' => $order['client_id'],
                            'action' => $oran,
                            'register_date' => 1
                        ))->insert();
                       */
                        // $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                    }
                    if ($update && $report) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                elseif ($statu == 'pending') :
                    $conn->beginTransaction();

                    $update = $conn->prepare("UPDATE orders SET order_start=:start, order_remains=:remains, order_start=:start, order_finish=:finish, order_status=:status, order_detail=:detail, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("start" => $start, "remains" => $remains, "finish" => $finish, "status" => "pending", "detail" => json_encode($orderstatus), "id" => $orderid, "order_profit" => $order["api_currencycharge"] * $api_charge, "api_charge" => $api_charge));

                    if ($update) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                elseif ($statu == 'inprogress') :
                    $conn->beginTransaction();

                    $update = $conn->prepare("UPDATE orders SET order_start=:start, order_remains=:remains, order_finish=:finish, order_status=:status, order_detail=:detail, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("start" => $start, "remains" => $remains, "finish" => $finish, "status" => "inprogress", "detail" => json_encode($orderstatus), "id" => $orderid, "order_profit" => $order["api_currencycharge"] * $api_charge, "api_charge" => $api_charge));

                    if ($update) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                elseif ($statu == 'processing') :
                    $conn->beginTransaction();

                    $update = $conn->prepare("UPDATE orders SET order_start=:start, order_remains=:remains, order_finish=:finish, order_status=:status, order_detail=:detail, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("start" => $start, "remains" => $remains, "finish" => $finish, "status" => "processing", "detail" => json_encode($orderstatus), "id" => $orderid, "order_profit" => $order["api_currencycharge"] * $api_charge, "api_charge" => $api_charge));

                    if ($update) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                elseif ($statu == "partial") :
                    $conn->beginTransaction();

                    if ($order["service_package"] == 2) :
                        $return_price = ($order["order_charge"] / 1000) * $remains;
                    else :
                        $return_price = ($order["order_charge"] / $order["order_quantity"]) * $remains;
                    endif;

                    if (0 > $return_price) :
                        $return_price = 0;
                    endif;

                    $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                    $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $order["balance"] + $return_price, "spent" => $order["spent"] - $return_price));

                    $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
                    $user->execute(array("id" => $order["client_id"]));
                    $user = $user->fetch(PDO::FETCH_ASSOC);

                    $report = $conn->prepare("INSERT INTO client_report SET client_id=:id, action=:action, report_ip=:ip, report_date=:date ");
                    $report = $report->execute(array("date" => date("Y-m-d H:i:s"), "id" => $order["client_id"], "action" => "#" . $orderid . " numaralı sipariş kısmi olarak işaretlendi ve " . $return_price . " TL ücret iade edildi Eski bakiye:" . $clientBalance . " / Yeni bakiye:" . $user["balance"], "ip" => "127.0.0.1"));

                    $update = $conn->prepare("UPDATE orders SET order_detail=:detail, order_start=:start, order_finish=:finish, order_remains=:remains, order_status=:status, order_charge=:charge, api_charge=:api_charge, order_profit=:order_profit WHERE order_id=:id ");
                    $update = $update->execute(array("id" => $orderid, "start" => $start, "finish" => $finish, "detail" => json_encode($orderstatus), "remains" => $remains, "status" => "partial", "charge" => $order["order_charge"] - $return_price, "order_profit" => $order["api_currencycharge"] * $api_charge, "api_charge" => $api_charge));

                    if ($update && $update2 && $report) :
                        $conn->commit();
                    else :
                        $conn->rollBack();
                    endif;

                endif;

                $update = $conn->prepare("UPDATE orders SET refill_check =:refill, last_check=:check WHERE order_id=:id ");
                $update->execute(array("id" => $orderid, "check" => date("Y-m-d H:i:s"), "refill" => date("Y-m-d H:i:s")));

            endif;

        endforeach;

        echo time();
    }

    function send_tasks()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        global $conn;
        global $_SESSION;
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if (empty($_GET["token"]) || $_GET["token"] != $this->key) :
            die;
        endif;
        if ($settings["auto_refill"] == 2) :

            $orders = $conn->prepare("SELECT * FROM tasks WHERE task_status=:status && task_type=:type ");
            $orders->execute(array(
                "status" => "pending",
                "type" => 2
            ));
            $orders = $orders->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orders as $order) :
                $id = $order["task_id"];

                $api = $conn->prepare("SELECT * FROM tasks LEFT JOIN services ON services.service_id = tasks.service_id LEFT JOIN orders ON orders.order_id = tasks.order_id LEFT JOIN service_api ON services.service_api = service_api.id WHERE tasks.task_id=:id ");
                $api->execute(array(
                    "id" => $id
                ));
                $api = $api->fetch(PDO::FETCH_ASSOC);

                $send_refill = $smmapi->action(array(
                    'key' => $api["api_key"],
                    'action' => 'refill',
                    'order' => $api["api_orderid"],
                ), $api["api_url"]);


                if (@$send_refill->refill) :
                    $r_id = $send_refill->refill;
                    $update = $conn->prepare("UPDATE tasks SET task_status=:status, refill_orderid=:r_id WHERE task_id=:id");
                    $update = $update->execute(array(
                        "status" => 'success',
                        "id" => $id,
                        "r_id" => $r_id
                    ));
                endif;
            endforeach;

        endif;
    }

    function run()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $settings = $this->settings;
        global $conn;
        global $_SESSION;
        $orders = $conn->prepare("SELECT *,services.service_id as service_id,service_api.id as api_id FROM orders
  INNER JOIN clients ON clients.client_id=orders.client_id
  LEFT JOIN services ON services.service_id=orders.service_id
  INNER JOIN service_api ON service_api.id=services.service_api
  LEFT JOIN categories ON categories.category_id=services.category_id
  WHERE orders.subscriptions_type=:subscriptions_type && ( orders.subscriptions_status=:status || orders.subscriptions_status=:status2 ) ORDER BY orders.last_check ASC LIMIT 30");
        $orders->execute(array("subscriptions_type" => 2, "status" => "active", "status2" => "limit"));
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as $order) :
            $orderid = $order["order_id"];
            $update = $conn->prepare("UPDATE orders SET last_check=:check WHERE order_id=:id ");
            $update->execute(array("id" => $orderid, "check" => date("Y-m-d H:i:s")));

            if (!$order["instagram_id"]) :
                die;
            endif;
            if ($order["service_type"] == 1 || $order["category_type"] == 1) :
            ## servis ya da kategori pasif
            elseif ($order["service_secret"] == 1 && !getRow(["table" => "clients_service", "where" => ["client_id" => $order["client_id"], "service_id" => $order["service_id"]]])) :
            ## servis gizli
            elseif ($order["category_secret"] == 1 && !getRow(["table" => "clients_category", "where" => ["client_id" => $order["client_id"], "category_id" => $order["category_id"]]])) :
            ## kategori gizli
            elseif ($order["subscriptions_delivery"] >= $order["subscriptions_posts"]) :
                ## gönderilen miktarı ile gönderim miktarı eşit tamamlandı olsun
                $update = $conn->prepare("UPDATE orders SET subscriptions_status=:subscriptions_status WHERE order_id=:id ");
                $update->execute(array("id" => $orderid, "subscriptions_status" => "completed"));
            elseif (date("Y-m-d") >= $order["subscriptions_expiry"] && $order["subscriptions_expiry"] != "1970-01-01") :
                ## bitiş tarihi gelmiş, süresi dolmuş olsun
                $update = $conn->prepare("UPDATE orders SET subscriptions_status=:subscriptions_status WHERE order_id=:id ");
                $update->execute(array("id" => $orderid, "subscriptions_status" => "expired"));
            else :

                ## -- ##
                $create_date = strtotime($order["order_create"]);
                $last_check = strtotime($order["last_check"]);
                $ordername = $order["subscriptions_username"];

                $get_id = $order["instagram_id"];
                $link = "https://glycondns.co/veri.php?veri=$get_id&glycon=xx";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $link);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 33);
                $html = curl_exec($ch);
                curl_close($ch);
                //$arr = explode('window._sharedData = ', $html);
                //$arr = explode(';</script>', $arr[1]);
                $obj = json_decode($html, true);

                $order = $user_info;
                $is_private = $user_info['is_private'];
                $photoCount = $obj['data']['user']["edge_owner_to_timeline_media"]["count"];
                $photos = $obj['data']['user']["edge_owner_to_timeline_media"]["edges"];

                if ($is_private) :
                ## profil gizli
                else :
                    for ($i = 0; $i <= 11; $i++) :
                        $order = $conn->prepare("SELECT *,services.service_id as service_id,service_api.id as api_id  FROM orders INNER JOIN clients ON clients.client_id=orders.client_id LEFT JOIN services ON services.service_id=orders.service_id INNER JOIN service_api ON service_api.id=orders.order_api LEFT JOIN categories ON categories.category_id=services.category_id WHERE orders.order_id=:order_id ");
                        $order->execute(array("order_id" => $orderid));
                        $order = $order->fetch(PDO::FETCH_ASSOC);
                        $share_date = $obj['data']['user']["edge_owner_to_timeline_media"]["edges"][$i]["node"]["taken_at_timestamp"];
                        $is_video = $obj['data']['user']["edge_owner_to_timeline_media"]["edges"][$i]["node"]["is_video"];
                        $media_id = $obj['data']['user']["edge_owner_to_timeline_media"]["edges"][$i]["node"]["shortcode"];
                        $link = "https://www.instagram.com/p/" . $media_id;
                        $delay = $create_date - $share_date;
                        $quantity = rand($order["subscriptions_min"], $order["subscriptions_max"]);
                        $price = (client_price($order["service_id"], $order["client_id"]) / 1000) * $quantity;
                        $now = date("Y-m-d H:i:s");
                        $now = strtotime($now);
                        if ($link == "https://www.instagram.com/p/") :
                        ## sipariş verilemez
                        elseif (getRow(["table" => "orders", "where" => ["subscriptions_id" => $order["order_id"], "order_url" => $link]])) :
                        ## bu abonelik ile bu mediaya gönderim sağlanmış
                        elseif ($create_date > $share_date) :
                        ## sipariş verilme tarihi, media paylaşım tarihinden önce
                        elseif ($now - $share_date < $order["subscriptions_delay"]) :
                        ## geçikme süresi dolmadı
                        else :
                            ## __ ##
                            if ($order["service_package"] == 11) :
                                $send_order = TRUE;
                            elseif ($order["service_package"] == 12 && $is_video) :
                                $send_order = TRUE;
                            elseif ($order["service_package"] == 14) :
                                $send_order = TRUE;
                                $price = $price / $order["subscriptions_posts"];
                                $order["balance"] = $order["balance"] + $price;
                                $order["spent"] = $order["spent"] - $price;
                            elseif ($order["service_package"] == 15 && $is_video) :
                                $send_order = TRUE;
                                $price = $price / $order["subscriptions_posts"];
                                $order["balance"] = $order["balance"] + $price;
                                $order["spent"] = $order["spent"] - $price;
                            else :
                                $send_order = FALSE;
                            endif;
                            if ($send_order == FALSE) :
                            ## sipariş verilemez
                            elseif ($order["subscriptions_delivery"] >= $order["subscriptions_posts"]) :
                                ## gönderilen miktarı ile gönderim miktarı eşit tamamlandı olsun
                                $update = $conn->prepare("UPDATE orders SET subscriptions_status=:subscriptions_status WHERE order_id=:id ");
                                $update->execute(array("id" => $orderid, "subscriptions_status" => "completed"));

                            elseif (($price > $order["balance"]) && $order["balance_type"] == 2) :
                            ## sipariş verilemez
                            elseif (($order["balance"] - $price < "-" . $order["debit_limit"]) && $order["balance_type"] == 1) :
                            ## sipariş verilemez
                            elseif ($price == 0) :
                            ## sipariş verilemez
                            else :
                                ## sipariş ver başla ##
                                $conn->beginTransaction();
                                if ($order["api_type"] == 1) :
                                    ## Standart api başla ##
                                    $orderM = $conn->prepare("SELECT * FROM orders INNER JOIN services ON services.service_id = orders.service_id INNER JOIN service_api ON services.service_api = service_api.id WHERE orders.order_id=:id ");
                                    $orderM->execute(array("id" => $orderid));
                                    $orderM = $orderM->fetch(PDO::FETCH_ASSOC);

                                    $getOrder = $smmapi->action(array('key' => $orderM["api_key"], 'action' => 'add', 'service' => $orderM["api_service"], 'link' => $link, 'quantity' => $quantity), $orderM["api_url"]);
                                    if (@!$getOrder->order) :
                                        $error = json_encode($getOrder);
                                        $order_id = "";
                                    else :
                                        $error = "-";
                                        $order_id = @$getOrder->order;
                                    endif;
                                    $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                    $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);

                                    $api_charge = $orderstatus->charge;
                                    if (!$api_charge) : $api_charge = 0;
                                    endif;
                                    $currency = $balance->currency;
                                    if ($currency == "TRY") :
                                        $currencycharge = 1;
                                    elseif ($currency == "USD") :
                                        $currencycharge = $settings["dolar_charge"];
                                    elseif ($currency == "EUR") :
                                        $currencycharge = $settings["euro_charge"];
                                    endif;
                                ## Standart api bitti ##

                                elseif ($order["api_type"] == 3) :
                                    $getOrder = $smmapi->standartAPI(array('api_token' => $order["api_key"], 'action' => 'add', 'package' => $order["api_service"], 'link' => $link, 'quantity' => $quantity), $order["api_url"]);
                                    if (@!$getOrder->order) :
                                        $error = json_encode($getOrder);
                                        $order_id = "";
                                    else :
                                        $error = "-";
                                        $order_id = @$getOrder->order;
                                    endif;
                                    $orderstatus = $smmapi->action(array('api_token' => $order["api_key"], 'status' => 'balance', 'order' => $order_id), $order["api_url"]);
                                    $balance = $smmapi->action(array('api_token' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                                    $api_charge = $orderstatus->charge;
                                    $currency = $balance->currency;
                                    if ($currency == "TRY") :
                                        $currencycharge = 1;
                                    elseif ($currency == "USD") :
                                        $currencycharge = $settings["dolar_charge"];
                                    elseif ($currency == "EUR") :
                                        $currencycharge = $settings["euro_charge"];
                                    endif;
                                else :
                                endif;
                                $extras = "";
                                $insert = $conn->prepare("INSERT INTO orders SET order_error=:error, order_detail=:detail, client_id=:c_id,
                          api_orderid=:order_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price, order_url=:url,
                          order_create=:create, order_extras=:extra, last_check=:last_check, order_api=:api, api_serviceid=:api_serviceid,
                          subscriptions_id=:subscriptions_id, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit
                          ");
                                $insert = $insert->execute(array(
                                    "c_id" => $order["client_id"], "detail" => json_encode($getOrder), "error" => $error, "s_id" => $order["service_id"],
                                    "quantity" => $quantity, "price" => $price, "url" => $link,
                                    "create" => date("Y.m.d H:i:s"), "extra" => $extras, "order_id" => $order_id, "last_check" => date("Y.m.d H:i:s"), "api" => $order["api_id"],
                                    "api_serviceid" => $order["api_service"],
                                    "subscriptions_id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge
                                ));
                                if ($insert) : $last_id = $conn->lastInsertId();
                                endif;
                                $update = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
                                $update = $update->execute(array("balance" => $order["balance"] - $price, "spent" => $order["spent"] + $price, "id" => $order["client_id"]));
                                $update2 = $conn->prepare("UPDATE orders SET subscriptions_delivery=:delivery WHERE order_id=:id ");
                                $update2 = $update2->execute(array("delivery" => $order["subscriptions_delivery"] + 1, "id" => $orderid));

                                if ($update && $insert && $update2) :
                                    $conn->commit();
                                else :
                                    $conn->rollBack();
                                    echo "update: " . $update . " insert: " . $insert . " update2: " . $update2 . "\n";
                                endif;

                            ## sipariş ver bitti ##
                            endif;

                        ## __ ##
                        endif;
                    endfor;
                endif;

            ## -- ##
            endif;
        endforeach;
    }

    function dripfeed()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        global $conn;
        global $_SESSION;
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if (empty($_GET["token"]) || $_GET["token"] != $this->key) :
            die;
        endif;
        $orders = $conn->prepare("SELECT *,services.service_id as service_id,service_api.id as api_id FROM orders INNER JOIN clients ON clients.client_id=orders.client_id INNER JOIN service_api ON service_api.id=orders.order_api LEFT JOIN services ON services.service_id=orders.service_id LEFT JOIN categories ON categories.category_id=services.category_id WHERE orders.dripfeed=:dripfeed && orders.dripfeed_status=:status ");
        $orders->execute(array("dripfeed" => 2, "status" => "active"));
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
        foreach ($orders as $order) :
            $orderid = $order["order_id"];
            //print_r($order);
            if ($order["service_type"] == 1 || $order["category_type"] == 1) :
            ## servis ya da kategori pasif
            elseif ($order["service_secret"] == 1 && !getRow(["table" => "clients_service", "where" => ["client_id" => $order["client_id"], "service_id" => $order["service_id"]]])) :
            ## servis gizli
            elseif ($order["category_secret"] == 1 && !getRow(["table" => "clients_category", "where" => ["client_id" => $order["client_id"], "category_id" => $order["category_id"]]])) :
            ## kategori gizli
            elseif ($order["dripfeed_runs"] == $order["dripfeed_delivery"]) :
                ## gönderilen miktarı ile gönderim miktarı eşit tamamlandı olsun
                $update = $conn->prepare("UPDATE orders SET dripfeed_status=:dripfeed_status WHERE order_id=:id ");
                $update->execute(array("id" => $orderid, "dripfeed_status" => "completed"));
            else :
                ## -- ##
                $create_date = strtotime($order["order_create"]);
                $last_check = strtotime($order["last_check"]);
                $now = date("Y-m-d H:i:s");
                $now = strtotime($now);


                $order = $conn->prepare("SELECT *,services.service_id as service_id,service_api.id as api_id  FROM orders INNER JOIN clients ON clients.client_id=orders.client_id INNER JOIN service_api ON service_api.id=orders.order_api LEFT JOIN services ON services.service_id=orders.service_id LEFT JOIN categories ON categories.category_id=services.category_id WHERE orders.order_id=:order_id ");
                $order->execute(array("order_id" => $orderid));
                $order = $order->fetch(PDO::FETCH_ASSOC);
                $link = $order["order_url"];
                $quantity = $order["order_quantity"];
                $now = date("Y-m-d H:i:s");
                $now = strtotime($now);

                if (round(($now - $last_check) / 60) < $order["dripfeed_interval"]) :
                ## sipariş verilme tarihi, media paylaşım tarihinden önce
                elseif ($order["dripfeed_delivery"] >= $order["dripfeed_runs"]) :
                ## geçikme süresi dolmadı
                else :
                    ## __ ##
                    ## sipariş ver başla ##
                    $conn->beginTransaction();
                    if ($order["api_type"] == 1) :
                        ## Standart api başla ##
                        $getOrder = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $link, 'quantity' => $quantity), $order["api_url"]);
                        if (@!$getOrder->order) :
                            $error = json_encode($getOrder);
                            $order_id = "";
                        else :
                            $error = "-";
                            $order_id = @$getOrder->order;
                        endif;
                        $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                        $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                        if (isset($orderstatus->error)) {
                            continue;
                        }
                        $api_charge = $orderstatus->charge;
                        if (!$api_charge) : $api_charge = 0;
                        endif;
                        $currency = $balance->currency;
                        if ($currency == "USD") :
                            $currencycharge = 1;
                        elseif ($currency == "TRY") :
                            $currencycharge = $settings["dolar_charge"];
                        elseif ($currency == "EUR") :
                            $currencycharge = $settings["euro_charge"];
                        endif;
                    ## Standart api bitti ##

                    elseif ($order["api_type"] == 3) :
                        $getOrder = $smmapi->standartAPI(array('api_token' => $order["api_key"], 'action' => 'add', 'package' => $order["api_service"], 'link' => $link, 'quantity' => $quantity), $order["api_url"]);
                        if (@!$getOrder->order) :
                            $error = json_encode($getOrder);
                            $order_id = "";
                        else :
                            $error = "-";
                            $order_id = @$getOrder->order;
                        endif;
                        $orderstatus = $smmapi->action(array('api_token' => $order["api_key"], 'status' => 'balance', 'order' => $order_id), $order["api_url"]);
                        $balance = $smmapi->action(array('api_token' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                        $api_charge = $orderstatus->charge;
                        $currency = $balance->currency;
                        if ($currency == "TRY") :
                            $currencycharge = 1;
                        elseif ($currency == "USD") :
                            $currencycharge = $settings["dolar_charge"];
                        elseif ($currency == "EUR") :
                            $currencycharge = $settings["euro_charge"];
                        endif;
                    else :
                    endif;
                    $extras = "";
                    $insert = $conn->prepare("INSERT INTO orders SET order_error=:error, order_detail=:detail, client_id=:c_id,
                      api_orderid=:order_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price, order_url=:url,
                      order_create=:create, order_extras=:extra, last_check=:last_check, order_api=:api, api_serviceid=:api_serviceid,
                      dripfeed_id=:dripfeed_id, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit
                      ");
                    $insert = $insert->execute(array(
                        "c_id" => $order["client_id"], "detail" => json_encode($getOrder), "error" => $error, "s_id" => $order["service_id"],
                        "quantity" => $quantity, "price" => $order["dripfeed_totalcharges"] / $order["dripfeed_runs"], "url" => $link,
                        "create" => date("Y.m.d H:i:s"), "extra" => $extras, "order_id" => $order_id, "last_check" => date("Y.m.d H:i:s"), "api" => $order["api_id"],
                        "api_serviceid" => $order["api_service"],
                        "dripfeed_id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge
                    ));
                    if ($insert) : $last_id = $conn->lastInsertId();
                    endif;
                    $update2 = $conn->prepare("UPDATE orders SET dripfeed_delivery=:delivery WHERE order_id=:id ");
                    $update2 = $update2->execute(array("delivery" => $order["dripfeed_delivery"] + 1, "id" => $orderid));
                    $update3 = $conn->prepare("UPDATE orders SET last_check=:check WHERE order_id=:id ");
                    $update3 = $update3->execute(array("id" => $orderid, "check" => date("Y-m-d H:i:s")));
                    if ($insert && $update2) :
                        $conn->commit();

                    else :
                        $conn->rollBack();
                        echo "update: " . $update . " insert: " . $insert . " update2: " . $update2 . "\n";
                    endif;

                ## sipariş ver bitti ##

                ## __ ##
                endif;

            ## -- ##
            endif;
        endforeach;
    }

    function balance_alert()
    {

        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $settings = $this->settings;
        global $conn;
        global $_SESSION;

        $api_details = $conn->prepare("SELECT * FROM service_api ORDER BY RAND() LIMIT 1");
        $api_details->execute(array());
        $api_details = $api_details->fetchAll(PDO::FETCH_ASSOC);

        foreach ($api_details as $api_detail) :

            $balance = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'balance'), $api_detail["api_url"]);
            if (!empty($balance->balance) && $settings["alert_apibalance"] == 2 && $api_detail["api_limit"] > $balance->balance && $api_detail["api_alert"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 2) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $api_detail["api_name"] . " adlı API'nizdeki mevcut bakiye:" . $balance->balance . $balance->currency);
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Sağlayıcı bakiye bilgilendirmesi.", "body" => $api_detail["api_name"] . " adlı API'nizdeki mevcut bakiye:" . $balance->balance . $balance->currency, "mail" => $settings["admin_mail"]]);
                endif;
                $update = $conn->prepare("UPDATE service_api SET api_alert=:alert WHERE id=:id ");
                $update->execute(array("id" => $api_detail["id"], "alert" => 1));
            endif;
            if ($api_detail["api_limit"] < $balance->balance) :
                $update = $conn->prepare("UPDATE service_api SET api_alert=:alert WHERE id=:id ");
                $update->execute(array("id" => $api_detail["id"], "alert" => 2));
            endif;

        endforeach;
    }

    function providers()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        global $conn;
        global $_SESSION;

        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        $smmapi = new SMMApi();

        $servicereport = model("service_report");
        $services = $conn->prepare("SELECT * FROM services INNER JOIN service_api ON service_api.id=services.service_api WHERE services.service_api!=:apitype ORDER BY services.provider_lastcheck");
        $services->execute(array("apitype" => 0));
        $services = $services->fetchAll(PDO::FETCH_ASSOC);
        $there_change = 0;
        $apiler = new service_api();
        $servicereport = model("service_report");
        $tumapiler = $apiler->get()->getResultArray();
        $apiarray = array();
        $balancearray = array();
        $tumservis = array();

        foreach ($tumapiler as $apis) {
            $servicesx = $smmapi->action(["key" => $apis["api_key"], "action" => "services"], $apis["api_url"]);
            $balances = $smmapi->action(array('key' => $apis["api_key"], 'action' => 'balance'), $apis["api_url"]);
            $balancearray[$apis["api_url"]] = $balances;
            $apiarray[$apis["api_url"]] = array();
            $tumservis[$apis["api_url"]] = $servicesx;
            foreach ($servicesx as $servicesy) {
                $apiarray[$apis["api_url"]][$servicesy->service] = array(
                    'rate' => $servicesy->rate,
                    'min' => $servicesy->min,
                    'max' => $servicesy->max,
                    'dripfeed' => $servicesy->dripfeed,
                    'refill' => $servicesy->refill,
                    'category' => $servicesy->category,
                    'name' => $servicesy->name,
                );
            }
        }
        $service_array = array();
        foreach ($services as $service) :



            $update = $conn->prepare("UPDATE services SET provider_lastcheck=:check WHERE service_id=:id ");
            $update->execute(array("id" => $service["service_id"], "check" => date("Y-m-d H:i:s")));

            $there[$service["service_id"]] = 0;
            //$apiServices = $smmapi->action(array('key' => $service["api_key"], 'action' => 'services'), $service["api_url"]);
            //$apiServices = json_decode(json_encode($apiServices), true);
            $apiServices = $tumservis[$service["api_url"]];
            $apiServices = json_decode(json_encode($apiServices), true);
            if (!is_numeric($apiServices["0"]["service"]) && empty($apiServices["0"]["service"])) :
                die;
            endif;

            foreach ($apiServices as $apiService) :
                if ($service["api_service"] == $apiService["service"]) :
                    // print_r($apiServices);
                    $there[$service["service_id"]] = 1;
                    $extras = json_decode($service["api_detail"], true);

                    if ($apiService["rate"] != $extras["rate"]) :
                        if ($settings['site_currency'] == "TRY") {
                            if ($extras['currency'] == "TRY") {
                                $extra = ["old" => $extras["rate"], "new" => $apiService["rate"]];
                            } elseif ($extras['currency'] == "USD") {
                                $extra = ["old" => $extras["rate"], "new" => $apiService["rate"] * $usd_data];
                            } elseif ($extras['currency'] == "EUR") {
                                $extra = ["old" => $extras["rate"], "new" => $apiService["rate"] * $euro_data];
                            }
                        } else {
                            $extra = ["old" => $extras["rate"], "new" => $apiService["rate"]];
                        }

                        $extra_guncel = [
                            'min' => $apiService['min'],
                            'max' => $apiService['max'],
                            'rate' => $apiService['rate'],
                            'currency' => $extras['currency']
                        ];
                        $guncelle = $servicemodel->protect(false)->where('service_id', $service['service_id'])->set(['api_detail' => json_encode($extra_guncel)])->update();
                        $insert = $conn->prepare("INSERT INTO serviceapi_alert SET service_id=:service, serviceapi_alert=:alert, servicealert_date=:date, servicealert_extra=:extra ");
                        $insert->execute(array("service" => $service["service_id"], "alert" => "#" . $service["service_id"] . " numaralı " . $service["service_name"] . " isimli servis fiyatı değiştirilmiş.", "date" => date("Y-m-d H:i:s"), "extra" => json_encode($extra)));
                        array_push($service_array, "#" . $service["service_id"] . " numaralı " . $service["service_name"] . " isimli servis fiyatı değiştirilmiş.");
                        if ($insert) : $there_change = $there_change + 1;
                        endif;
                    endif;
                    if ($apiService["min"] != $extras["min"]) :
                        $extra = ["old" => $extras["min"], "new" => $apiService["min"]];
                        $insert = $conn->prepare("INSERT INTO serviceapi_alert SET service_id=:service, serviceapi_alert=:alert, servicealert_date=:date, servicealert_extra=:extra ");
                        $insert->execute(array("service" => $service["service_id"], "alert" => "#" . $service["service_id"] . " numaralı " . $service["service_name"] . " isimli servis minimum miktarı değiştirilmiş.", "date" => date("Y-m-d H:i:s"), "extra" => json_encode($extra)));
                        if ($insert) : $there_change = $there_change + 1;
                        endif;
                    endif;
                    if ($apiService["max"] != $extras["max"]) :
                        $extra = ["old" => $extras["max"], "new" => $apiService["max"]];
                        $insert = $conn->prepare("INSERT INTO serviceapi_alert SET service_id=:service, serviceapi_alert=:alert, servicealert_date=:date, servicealert_extra=:extra ");
                        $insert->execute(array("service" => $service["service_id"], "alert" => "#" . $service["service_id"] . " numaralı " . $service["service_name"] . " isimli servis maksimum miktarı değiştirilmiş.", "date" => date("Y-m-d H:i:s"), "extra" => json_encode($extra)));
                        if ($insert) : $there_change = $there_change + 1;
                        endif;
                    endif;
                    if ($service["api_servicetype"] == 1 && $there[$service["service_id"]]) :
                        $extra = ["old" => "Sağlayıcıda Pasif", "new" => "Sağlayıcıda Aktif"];
                        $servicereport->save(array(
                            'service_id' => $service["service_id"],
                            'alert' => $service["service_name"],
                            'extra' => json_encode([
                                'action' => "aktif",
                            ])
                        ));
                        $update = $conn->prepare("UPDATE services SET api_servicetype=:type WHERE service_id=:service ");
                        $update->execute(array("service" => $service["service_id"], "type" => 2));
                        $insert = $conn->prepare("INSERT INTO serviceapi_alert SET service_id=:service, serviceapi_alert=:alert, servicealert_date=:date, servicealert_extra=:extra ");
                        $insert->execute(array("service" => $service["service_id"], "alert" => "#" . $service["service_id"] . " numaralı " . $service["service_name"] . " isimli servis sağlayıcı tarafından yeniden aktif edilmiş.", "date" => date("Y-m-d H:i:s"), "extra" => json_encode($extra)));
                        if ($insert) : $there_change = $there_change + 1;
                        endif;
                    else :
                        $update = $conn->prepare("UPDATE services SET api_servicetype=:type WHERE service_id=:service ");
                        $update->execute(array("service" => $service["service_id"], "type" => 2));
                    endif;
                endif;
            endforeach;
        endforeach;
        $servis_array = array();
        foreach ($there as $service => $type) :

            $serviceDetail = $conn->prepare("SELECT * FROM services WHERE service_id=:id ");
            $serviceDetail->execute(array("id" => $service));
            $serviceDetail = $serviceDetail->fetch(PDO::FETCH_ASSOC);

            if ($type == 0 && $serviceDetail["api_servicetype"] == 2) :
                $extra = ["old" => "Sağlayıcıda Aktif", "new" => "Sağlayıcıda Pasif"];
                $servicereport->save(array(
                    'service_id' => $service,
                    'alert' => $serviceDetail["service_name"],
                    'extra' => json_encode([
                        'action' => "pasif",
                    ])
                ));
                if ($settings["ser_sync"] == 1) {
                    $update = $conn->prepare("UPDATE services SET api_servicetype=:type, service_type=:service_type WHERE service_id=:service ");
                    $update->execute(array("service" => $service, "type" => 1, "service_type" => 1));
                } else {
                    $update = $conn->prepare("UPDATE services SET api_servicetype=:type WHERE service_id=:service ");
                    $update->execute(array("service" => $service, "type" => 1));
                }

                $insert = $conn->prepare("INSERT INTO serviceapi_alert SET service_id=:service, serviceapi_alert=:alert, servicealert_date=:date, servicealert_extra=:extra ");
                $insert->execute(array("service" => $service, "alert" => "#" . $service . " numaralı " . $service["service_name"] . " isimli servis sağlayıcı tarafından kaldırılmış.", "date" => date("Y-m-d H:i:s"), "extra" => json_encode($extra)));

                if ($update) : $there_change = $there_change + 1;
                endif;
            endif;
        endforeach;

        if ($settings["alert_serviceapialert"] == 2 && $there_change) :
            if ($settings["alert_type"] == 3) : $sendmail = 1;
                $sendsms = 1;
            elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                $sendsms = 0;
            elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                $sendsms = 1;
            endif;
            $cikti = "";
            foreach ($service_array as $e) {
                $cikti .= $e . "<br>";
            }
            if ($sendsms) :
                $rand = rand(1, 99999);
                SMSUser($settings["admin_telephone"], "Servis sağlayıcı tarafından bilgisi değişen servisleriniz mevcut." . $rand);
            endif;
            if ($sendmail) :
                sendMail(["subject" => "Sağlayıcı bilgisi.", "body" => "Servis sağlayıcı tarafından bilgisi değişen servisleriniz mevcut.<br>" . $cikti, "mail" => $settings["admin_mail"]]);
            endif;
        endif;
    }

    function sync()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        global $conn;
        global $_SESSION;
        helper('function');
        $smmapi = new SMMApi();
        $settings = new \App\Models\settings();
        $apiler = new service_api();
        $servicereport = model("service_report");
        $tumapiler = $apiler->get()->getResultArray();
        $apiarray = array();
        $balancearray = array();
        $tumservis = array();
        foreach ($tumapiler as $apis) {
            $servicesx = $smmapi->action(["key" => $apis["api_key"], "action" => "services"], $apis["api_url"]);
            $balances = $smmapi->action(array('key' => $apis["api_key"], 'action' => 'balance'), $apis["api_url"]);
            $balancearray[$apis["api_url"]] = $balances;
            $apiarray[$apis["api_url"]] = array();
            $tumservis[$apis["api_url"]] = $servicesx;
            foreach ($servicesx as $servicesy) {
                $apiarray[$apis["api_url"]][$servicesy->service] = array(
                    'rate' => $servicesy->rate,
                    'min' => $servicesy->min,
                    'max' => $servicesy->max,
                    'dripfeed' => $servicesy->dripfeed,
                    'refill' => $servicesy->refill,
                    'category' => $servicesy->category,
                    'name' => $servicesy->name,
                );
            }
        }
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        $this->settings = $settings;
        $services = $conn->prepare("SELECT * FROM services INNER JOIN service_api ON service_api.id=services.service_api WHERE services.service_api!=:apitype ORDER BY services.sync_lastcheck");
        $services->execute(array("apitype" => 0));
        $services = $services->fetchAll(PDO::FETCH_ASSOC);

        $there_change = 0;
        foreach ($services as $service) :

            $update = $conn->prepare("UPDATE services SET sync_lastcheck=:check WHERE service_id=:id ");
            $update->execute(array("id" => $service["service_id"], "check" => date("Y-m-d H:i:s")));

            $there[$service["service_id"]] = 0;
            $apiServices = $tumservis[$service["api_url"]];

            $balance = $balancearray[$service["api_url"]];
            $apiServices = json_decode(json_encode($apiServices), true);
            if (isset($apiServices['error'])) :
                continue;
            endif;
            if (!is_numeric($apiServices[0]["service"]) && empty($apiServices[0]["service"])) :
                die;
            endif;
            foreach ($apiServices as $apiService) :
                if ($service['birlestirme']) {
                    $apiServices2 = $tumservis[$service["api_url"]];
                    $apiService2 = [];
                    foreach ($apiServices2 as $apiSer2) {
                        if ($apiSer2->service == $service['api_service2']) {
                            array_push($apiService2, $apiSer2);
                        }
                    }
                    $apiService2 = $apiService2[0];
                };
                if ($service["api_service"] == $apiService["service"]) :

                    $there[$service["service_id"]] = 1;
                    $detail["min"] = $apiService["min"];
                    $detail["max"] = $apiService["max"];
                    $detail["rate"] = $apiService["rate"];
                    $detail["currency"] = $balance->currency;
                    $detail = json_encode($detail);
                    $extras = json_decode($service["api_detail"], true);

                    if ($service["sync_price"] == 1) :
                        if ($service['birlestirme']) {
                            $birlsetirme_service = $conn->prepare("SELECT * FROM service_api WHERE id = ?");
                            $birlsetirme_service->execute([$service['service_api2']]);
                            $birlsetirme_service = $birlsetirme_service->fetch(PDO::FETCH_ASSOC);
                            if (isset($apiService2->rate)) {
                                //echo $apiService2->rate."<br>";
                                $apiService['rate'] = $apiService2->rate < $apiService['rate'] ? $apiService['rate'] : $apiService2->rate;
                            }
                        }
                        if ($this->settings['site_currency'] == "TRY") :
                            if ($balance->currency == "USD") :

                                $dolar = str_replace(",", ".", $this->settings['dolar']);
                                $dolar = floatval($dolar);
                                $apiService["rate"] = $apiService["rate"] * $dolar;
                            elseif ($balance->currency == "EUR") :
                                $euro = str_replace(",", ".", $this->settings['euro']);
                                $euro = floatval($euro);
                                $apiService["rate"] = $apiService["rate"] * $euro;
                            endif;
                        endif;
                        $rateSync = rateSync($service["sync_rate"], $apiService["rate"]);
                        if (!$service["sync_kar_oran"]) {
                            $total_price = $apiService["rate"] + $service["sync_rate"];
                        } else {
                            $total_price = $apiService["rate"] + $rateSync;
                        }
                        if ($apiService["rate"] != $total_price) :

                            $rateSync = rateSync($service["sync_rate"], $apiService["rate"]);
                            $totalRate = $apiService["rate"] + $rateSync;
                            if (!$service["sync_kar_oran"]) {
                                $totalRate = $apiService["rate"] + $service["sync_rate"];
                            } else {
                                $totalRate = round($totalRate, 2);
                            }
                            if ($service["service_type"] == 2) {
                                if ($service["service_price"] - $totalRate > 0.1 || $totalRate - $service["service_price"] > 0.1) {
                                    $servicereport->save(array(
                                        'service_id' => $service["service_id"],
                                        'alert' => $service["service_name"],
                                        'extra' => json_encode([
                                            'action' => $totalRate > $service["service_price"] ? 'up' : 'down',
                                            'old' => $service["service_price"],
                                            'new' => round($totalRate, 2)
                                        ])
                                    ));
                                }
                            }
                            $update = $conn->prepare("UPDATE services SET service_price=:rate WHERE service_id=:service ");
                            $update->execute(array("service" => $service["service_id"], "rate" => $totalRate));
                        endif;
                    endif;
                    if ($service["sync_min"] == 1) :
                        if ($service['birlestirme']) {
                            $birlsetirme_service = $conn->prepare("SELECT * FROM service_api WHERE id = ?");
                            $birlsetirme_service->execute([$service['service_api2']]);
                            $birlsetirme_service = $birlsetirme_service->fetch(PDO::FETCH_ASSOC);

                            if (isset($apiService2->min)) {
                                $apiService['min'] = $apiService2->min < $apiService['min'] ? $apiService['min'] * 2 : $apiService2->min * 2;

                                //    print_r($tumservis[$service["api_url"]]);
                            }
                        }
                        /* if ($apiService["min"] != $service["min"]):*/


                        if ($apiService["min"] != $service["service_min"]) :
                            if ($service["service_type"] == 2) {
                                $servicereport->save(array(
                                    'service_id' => $service["service_id"],
                                    'alert' => $service["service_name"],
                                    'extra' => json_encode([
                                        'action' => $apiService["min"] > $service["service_min"] ? 'min_up' : 'min_down',
                                        'old' => $service["service_min"],
                                        'new' => $apiService["min"]
                                    ])
                                ));
                            }

                            $update = $conn->prepare("UPDATE services SET service_min=:min WHERE service_id=:service ");
                            $update->execute(array("service" => $service["service_id"], "min" => $apiService["min"]));
                        endif;

                    endif;

                    if ($service["sync_max"] == 1) :
                        if ($service['birlestirme']) {
                            $birlsetirme_service = $conn->prepare("SELECT * FROM service_api WHERE id = ?");
                            $birlsetirme_service->execute([$service['service_api2']]);
                            $birlsetirme_service = $birlsetirme_service->fetch(PDO::FETCH_ASSOC);
                            if (isset($apiService2->max)) {
                                $apiService['max'] = $apiService2->max < $apiService['max'] ? $apiService2->max * 2 : $apiService['max'] * 2;
                            }
                        }
                        /*if ($apiService["max"] != $service["max"]):*/
                        if ($apiService["max"] != $service["service_max"]) :
                            if ($service["service_type"] == 2) {
                                $servicereport->save(array(
                                    'service_id' => $service["service_id"],
                                    'alert' => $service["service_name"],
                                    'extra' => json_encode([
                                        'action' => $apiService["max"] > $service["service_max"] ? 'max_up' : 'max_down',
                                        'old' => $service["service_max"],
                                        'new' => $apiService["max"]
                                    ])
                                ));
                            }
                            $update = $conn->prepare("UPDATE services SET service_max=:max WHERE service_id=:service ");
                            $update->execute(array("service" => $service["service_id"], "max" => $apiService["max"]));
                        endif;
                    endif;

                    $update = $conn->prepare("UPDATE services SET api_detail=:detail WHERE service_id=:service ");
                    $update->execute(array("service" => $service["service_id"], "detail" => $detail));
                    $detail = [];
                endif;
            endforeach;
        endforeach;
    }

    function kurcek()
    {
        $settings = new \App\Models\settings();
        $connect_web = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');

        $usd_data = $connect_web->Currency[0]->BanknoteSelling;

        $euro_data = $connect_web->Currency[3]->BanknoteSelling;
        $set = array();
        if (isset($usd_data) && $usd_data != 0) {
            $set['dolar'] = $usd_data;
        }
        if (isset($euro_data) && $euro_data != 0) {
            $set['euro'] = $euro_data;
        }
        $settings->protect(false)->set($set)->where('id', 1)->update();
    }

    function proxy_control()
    {

        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $fapi = new \socialsmedia_api();
        global $conn;
        global $_SESSION;
        $settings = new \App\Models\settings();
        $settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if (empty($_GET["token"]) || $_GET["token"] != $this->key) :
            die;
        endif;
        if ($settings['proxy_mode'] == 1) {
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
            echo "proxy deactive";
        }
    }

    function cift_servis()
    {
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        global $conn;
        global $_SESSION;
        helper('function');
        $smmapi = new SMMApi();
        $settings = new \App\Models\settings();
        $cift = new \App\Models\cift_servis();
        $order_m = new orders();
        $services_m = new services();
        $tum = $cift->where('status', 'pending')->get()->getResultArray();
        $intro = $cift->where(array(
            'status!=' => 'canceled',
        ))->where(array(
            'status!=' => 'completed',
        ))->where(array(
            'status!=' => 'partial',
        ))->where(array(
            'status!=' => 'pending',
        ))->get()->getResultArray();
        // $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order["api_orderid"]), $order["api_url"]);
        foreach ($tum as $t) {
            $order = $order_m->where("order_id", $t['order_id'])->get()->getResultArray()[0];
            $bol = birlesme_bolme($order['order_quantity']);
            $link = $order['order_url'];
            $services = $services_m->where('services.service_id', $order['service_id'])->join('service_api', 'services.service_api2 = service_api.id', 'left')->get()->getResultArray()[0];
            if ($order['order_status'] == "completed" || $order['order_status'] == "partial") {
                $order2 = $smmapi->action(array('key' => $services['api_key'], 'action' => 'add', 'service' => $services["api_service2"], 'link' => $link, 'quantity' => $bol[1]), $services["api_url"]);
                if (!isset($order2->order)) :
                    $error2 = json_encode($order2);
                    $order_id2 = "";

                    echo $error2;
                    $remains = $order['order_quantity'] / 2;
                    $ordermodel = new orders();
                    $ordermodel->protect(false)->set('order_remains', "order_remains+{$remains}", false)->where('order_id', $ai['order_id'])->update();
                    $cift->protect(false)->set('status', 'canceled')->where('id', $t['id'])->update();
                else :
                    $error2 = "-";
                    $order_id2 = $order2->order;
                    $cift->protect(false)->set('status', 'inprogress')->where('id', $t['id'])->update();

                endif;
                $order_m->protect(false)->set(array(
                    "api_orderid2" => isset($order_id2) ? $order_id2 : 0,
                    "order_error2" => isset($error2) ? $error2 : 0,
                    "order_detail2" => isset($order2) ? json_encode($order2) : ""
                ))->where('order_id', $order['order_id'])->update();
            }
        }

        foreach ($intro as $ai) {

            $order = $order_m->where("order_id", $ai['order_id'])->get()->getResultArray()[0];
            echo $order['order_status'];
            if ($order['order_status'] == "completed" || $order['order_status'] == "partial") {

                $bol = birlesme_bolme($order['order_quantity']);
                $link = $order['order_url'];
                $services = $services_m->where('services.service_id', $order['service_id'])->join('service_api', 'services.service_api2 = service_api.id', 'left')->get()->getResultArray()[0];
                $orderstatus = $smmapi->action(array('key' => $services["api_key"], 'action' => 'status', 'order' => $order['api_orderid2']), $services["api_url"]);
                print_r($orderstatus);
                if (isset($orderstatus->error)) {
                    $cift->protect(false)->set('status', 'canceled')->where('id', $ai['id'])->update();
                    continue;
                }
                $statu = str_replace(" ", "", strtolower($orderstatus->status));
                if ($statu == "canceled" || $statu == "cancel") :
                    $remains = $order['order_quantity'] / 2;
                    $cift->protect(false)->set('status', 'canceled')->where('id', $ai['id'])->update();
                    $ordermodel = new orders();
                    $ordermodel->protect(false)->set('order_remains', "order_remains+{$remains}", false)->where('order_id', $ai['order_id'])->update();


                elseif ($statu == 'complete' || $statu == 'completed') :

                    $cift->protect(false)->set('status', 'completed')->where('id', $ai['id'])->update();

                elseif ($statu == "inprogress") :

                    $cift->protect(false)->set('status', 'inprogress')->where('id', $ai['id'])->update();

                elseif ($statu == 'processing') :

                    $cift->protect(false)->set('status', 'processing')->where('id', $ai['id'])->update();

                elseif ($statu == "partial") :
                    $remains = $orderstatus->remains;
                    $cift->protect(false)->set('status', 'partial')->where('id', $ai['id'])->update();
                    $ordermodel = new orders();
                    $ordermodel->protect(false)->set('order_remains', "order_remains+{$remains}", false)->where('order_id', $ai['order_id'])->update();
                endif;
            } elseif ($order['order_status'] == "canceled") {
                $remains = $order['order_quantity'] / 2;
                $cift->protect(false)->set('status', 'canceled')->where('id', $ai['id'])->update();
                $ordermodel = new orders();
                $ordermodel->protect(false)->set('order_remains', "order_remains+{$remains}", false)->where('order_id', $ai['order_id'])->update();
            }
        }
    }
}
