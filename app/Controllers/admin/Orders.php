<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Orders extends Ana_Controller
{
    function index()
    {
        global $conn;
        global $_SESSION;
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $smmapi = new SMMApi();
        $search_add = "";
        $settings = $this->settings;
        if (route(3) == "counter" && route(3)):
            $count = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub $search_add ");
            $count->execute(array("dripfeed" => 1, "sub" => 1));
            $count = $count->rowCount();
            $services = $conn->prepare("SELECT * FROM services");
            $services->execute(array());
            $services = $services->fetchAll(PDO::FETCH_ASSOC);
            $active = $_POST["active"];
            echo '<li';
            if (!$active): echo ' class="active"'; endif;
            echo '>
            <a href="/admin/orders/all">All Orders (' . $count . ')</a>
          </li>';
            foreach ($services as $service):
                echo '<li';
                if ($service["service_id"] == $active): echo ' class="active"'; endif;
                echo '>
                <a ';
                if ($service["service_type"] == 1): echo ' style="color: #c1c1c1;"'; endif;
                echo ' href="admin/orders/all?service_id=' . $service["service_id"] . '"><span class="label-id">' . $service["service_id"] . '</span> ' . $service["service_name"] . ' (' . countRow(["table" => "orders", "where" => ["service_id" => $service["service_id"]]]) . ')</a>
              </li>';
            endforeach;
            exit();
        endif;
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;

        $statusList = ["all", "pending", "inprogress", "completed", "partial", "canceled", "processing", "fail", "cronpending"];
        if (route(4) && in_array(route(4), $statusList)):
            $status = route(4);
        elseif (!route(4) || !in_array(route(4), $statusList)):
            $status = "all";
        endif;


        function orderStatu($statu, $error, $cron)
        {
            if ($cron == "cronpending"):
                $statu = "Cron pending";
            elseif ($error == "-"):
                switch ($statu) {
                    case 'pending':
                        $statu = "Sipariş Alındı";
                        break;
                    case 'inprogress':
                        $statu = "Yükleniyor";
                        break;
                    case 'completed':
                        $statu = "Tamamlandı";
                        break;
                    case 'partial':
                        $statu = "Kısmen Tamamlandı, Eksik İade Edildi";
                        break;
                    case 'canceled':
                        $statu = "İptal Edildi";
                        break;
                    case 'processing':
                        $statu = "Gönderim Sırasında";
                        break;
                }
            else:
                $statu = "Fail";
            endif;
            return $statu;
        }

        if ($_POST):

            if (route(3) == "set_orderurl"):
                $id = route(4);
                $url = $_POST["url"];
                $update = $conn->prepare("UPDATE orders SET order_url=:url WHERE order_id=:id ");
                $update->execute(array("id" => $id, "url" => $url));
                return redirect()->to(base_url("admin/orders"));
            elseif (route(3) == "set_startcount"):
                $id = route(4);
                $start = $_POST["start"];
                $update = $conn->prepare("UPDATE orders SET order_start=:start WHERE order_id=:id ");
                $update->execute(array("id" => $id, "start" => $start));
                return redirect()->to(base_url("admin/orders"));
            elseif (route(3) == "set_partial"):
                $id = route(4);
                $remains = $_POST["remains"];
                $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
                $order->execute(array("id" => $id));
                $order = $order->fetch(PDO::FETCH_ASSOC);
                $referrer = base_url("admin/orders");
                if (empty($remains) || !is_numeric($remains)):
                    $error = 1;
                    $errorText = "Gitmeyen miktar boş olamaz";
                    $icon = "error";
                elseif ($order["order_quantity"] < $remains):
                    $error = 1;
                    $errorText = "Gitmeyen miktar, sipariş miktarından fazla olamaz";
                    $icon = "error";
                else:
                    $price = $order["order_charge"] / $order["order_quantity"]; ## 1 adet kaç TL
                    $return = $price * $remains; ## İade edilecek para
                    $balance = $order["balance"] + $return; ## Üye yeni bakiye
                    $order["order_quantity"] = $order["order_quantity"] - $remains; ## Yeni sipariş miktarı
                    $charge = $order["order_charge"] - $return; ## Sipariş yeni tutar
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE orders SET order_remains=:remains, order_status=:statu, order_charge=:charge, order_quantity=:quantity WHERE order_id=:id ");
                    $update = $update->execute(array("id" => $id, "remains" => $remains, "statu" => "partial", "charge" => $charge, "quantity" => $order["order_quantity"]));
                    $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                    $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $order["spent"] - $return));
                    if ($update && $update2):
                        $conn->commit();
                        $error = 1;
                        $errorText = "İşlem başarılı";
                        $icon = "success";
                        $referrer = base_url("admin/orders");
                    else:
                        $conn->rollBack();
                        $error = 1;
                        $errorText = "İşlem başarısız";
                        $icon = "error";
                        $referrer = base_url("admin/orders");
                    endif;
                endif;
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            elseif (route(3) == "multi-action"):
                $orders = $_POST["order"];
                $action = $_POST["bulkStatus"];
                if ($action == "pending"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                        $update->execute(array("status" => "pending", "id" => $id));
                    endforeach;
                elseif ($action == "inprogress"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
                        $update->execute(array("status" => "inprogress", "id" => $id));
                    endforeach;
                elseif ($action == "completed"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET order_status=:status, order_error=:error WHERE order_id=:id ");
                        $update->execute(array("status" => "completed", "error" => "-", "id" => $id));
                    endforeach;
                elseif ($action == "canceled"):
                    foreach ($orders as $id => $value):
                        $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
                        $order->execute(array("id" => $id));
                        $order = $order->fetch(PDO::FETCH_ASSOC);
                        $balance = $order["balance"] + $order["order_charge"];
                        $spent = $order["spent"] - $order["order_charge"];
                        $order["order_quantity"] = $order["order_quantity"];
                        $conn->beginTransaction();
                        $update = $conn->prepare("UPDATE orders SET api_charge=:api_charge, order_profit=:order_profit, order_status=:status, order_error=:error, order_charge=:price, order_quantity=:quantity, order_remains=:remains WHERE order_id=:id ");
                        $update = $update->execute(array("api_charge" => 0, "order_profit" => 0, "status" => "canceled", "price" => 0, "quantity" => 0, "remains" => $order["order_quantity"], "error" => "-", "id" => $id));
                        $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
                        $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $spent));
                        if ($update && $update2):
                            $conn->commit();
                        else:
                            $conn->rollBack();
                        endif;
                    endforeach;
                elseif ($action == "resend"):
                    foreach ($orders as $id => $value):
                        $order = $conn->prepare("SELECT * FROM orders INNER JOIN services ON services.service_id = orders.service_id INNER JOIN service_api ON services.service_api = service_api.id WHERE orders.order_id=:id ");
                        $order->execute(array("id" => $id));
                        $order = $order->fetch(PDO::FETCH_ASSOC);

                        /* API SİPARİŞİ GEÇ BAŞLA */
                        if ($order["service_package"] == 6 || $order["service_package"] == 5):
                            redirect()->to(base_url('admin/orders'));
                            echo "<script> window.location.href = '" . base_url('admin/orders') . "';</script>";
                            exit();
                        endif;
                        if ($order["api_type"] == 1 && $order["service_package"] != 5 && $order["service_package"] != 6):

                            ## Standart api başla ##
                            if ($order["service_package"] == 1 || $order["service_package"] == 2 || $order["service_package"] == 11 || $order["service_package"] == 12):
                                ## Standart başla ##
                                $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                                if (@!$get_order->order):
                                    $error = json_encode($get_order);
                                    $order_id = "";
                                else:
                                    $error = "-";
                                    $order_id = @$get_order->order;
                                endif;
                            ## Standart bitti ##
                            elseif ($order["service_package"] == 3):
                                ## Custom comments başla ##
                                $arr = json_decode($order["order_extras"], true);
                                $comments = $arr["comments"];
                                $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'comments' => $comments), $order["api_url"]);
                                if (@!$get_order->order):
                                    $error = json_encode($get_order);
                                    $order_id = "";
                                else:
                                    $error = "-";
                                    $order_id = @$get_order->order;
                                endif;

                            ## Custom comments bitti ##
                            else:
                            endif;
                            $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                            $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                            $api_charge = $orderstatus->charge;
                            if (!$api_charge): $api_charge = 0; endif;
                            $currency = $balance->currency;
                            if ($currency == "USD"):
                                $currencycharge = 1;
                            elseif ($currency == "TRY"):
                                $currencycharge = $settings["dolar_charge"];
                            elseif ($currency == "EUR"):
                                $currencycharge = $settings["euro_charge"];
                            endif;
                        ## Standart api bitti ##
                        elseif ($order["api_type"] == 3):
                            if ($order["service_package"] == 1 || $order["service_package"] == 2):
                                ## Standart başla ##
                                $get_order = $fapi->query(array('cmd' => 'orderadd', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orders' => [['service' => $order["api_service"], 'amount' => $order["order_quantity"], 'data' => $order["order_url"]]]));
                                if (@!$get_order[0][0]['status'] == "error"):
                                    $error = json_encode($get_order);
                                    $order_id = "";
                                    $api_charge = "0";
                                    $currencycharge = 1;
                                else:
                                    $error = "-";
                                    $order_id = @$get_order[0][0]["id"];
                                    $orderstatus = $fapi->query(array('cmd' => 'orderstatus', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orderid' => [$order_id]));
                                    $balance = $fapi->query(array('cmd' => 'profile', 'token' => $order["api_key"], 'apiurl' => $order["api_url"]));
                                    $api_charge = $orderstatus[$order_id]["order"]["price"];
                                    $currency = "TRY";
                                    if ($currency == "TRY"):
                                        $currencycharge = 1;
                                    elseif ($currency == "USD"):
                                        $currencycharge = $settings["dolar_charge"];
                                    elseif ($currency == "EUR"):
                                        $currencycharge = $settings["euro_charge"];
                                    endif;
                                endif;
                                ## Standart bitti ##
                            endif;
                        else:
                        endif;
                        /* API SİPARİŞ GEÇ BİTTİ */
                        $update = $conn->prepare("UPDATE orders SET order_api=:api, api_serviceid=:serviceid, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit  WHERE order_id=:id ");
                        $update->execute(array("error" => $error, "api" => $order["id"], "serviceid" => $order["api_service"], "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge));
                    endforeach;
                endif;
                return redirect()->to(base_url("admin/orders"));
            endif;
            exit();
        endif;

        if (route(3) == "order_cancel" && route(3)):
            $id = route(4);
            $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
            $order->execute(array("id" => $id));
            $order = $order->fetch(PDO::FETCH_ASSOC);
            $balance = $order["balance"] + $order["order_charge"];
            $spent = $order["spent"] - $order["order_charge"];
            $order["order_quantity"] = $order["order_quantity"];
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE orders SET api_charge=:api_charge, order_profit=:order_profit, order_status=:status, order_error=:error, order_charge=:price, order_quantity=:quantity, order_remains=:remains WHERE order_id=:id ");
            $update = $update->execute(array("api_charge" => 0, "order_profit" => 0, "status" => "canceled", "price" => 0, "error" => "-", "quantity" => 0, "remains" => $order["order_quantity"], "id" => $id));
            $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
            $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $spent));
            if ($update && $update2):
                $conn->commit();
            else:
                $conn->rollBack();
            endif;
            return redirect()->to(base_url("admin/orders"));
        elseif (route(3) == "order_complete" && route(3)):
            $id = route(4);
            $update = $conn->prepare("UPDATE orders SET order_status=:status, order_error=:error WHERE order_id=:id ");
            $update->execute(array("status" => "completed", "error" => "-", "id" => $id));
            return redirect()->to(base_url("admin/orders"));
        elseif (route(3) == "order_inprogress" && route(3)):
            $id = route(4);
            $update = $conn->prepare("UPDATE orders SET order_status=:status WHERE order_id=:id ");
            $update->execute(array("status" => "inprogress", "id" => $id));
            return redirect()->to(base_url("admin/orders"));
        elseif (route(3) == "order_resend" && route(3)):
            $id = route(4);
            $order = $conn->prepare("SELECT * FROM orders INNER JOIN services ON services.service_id = orders.service_id INNER JOIN service_api ON services.service_api = service_api.id WHERE orders.order_id=:id ");
            $order->execute(array("id" => $id));
            $order = $order->fetch(PDO::FETCH_ASSOC);

            /* API SİPARİŞİ GEÇ BAŞLA */
            if ($order["service_package"] == 6 || $order["service_package"] == 5):
                $extra = json_decode($order['order_extras'], true);
                if ($order["service_package"] == 6):
                    $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"], "answer_number" => $extra['answer_number']), $order["api_url"]);
                    if (@!$get_order->order):
                        $error = json_encode($get_order);
                        $order_id = "";
                    else:
                        $error = "-";
                        $order_id = @$get_order->order;
                    endif;
                elseif ($order["service_package"] == 5):
                    $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"], "username" => $extra['username']), $order["api_url"]);
                    if (@!$get_order->order):
                        $error = json_encode($get_order);
                        $order_id = "";
                    else:
                        $error = "-";
                        $order_id = @$get_order->order;
                    endif;
                endif;
                redirect()->to(base_url('admin/orders'));
                echo "<script> window.location.href = '" . base_url('admin/orders') . "';</script>";
                exit();
            endif;
            if ($order["api_type"] == 1 && $order["service_package"] != 5 && $order["service_package"] != 6):
                ## Standart api başla ##
                if ($order["service_package"] == 1 || $order["service_package"] == 2 || $order["service_package"] == 11 || $order["service_package"] == 12):
                    ## Standart başla ##
                    $bol = birlesme_bolme($order['order_quantity']);
                    if (!$order['birlestirme'] && $order['order_error'] != "-") {
                        $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order['order_quantity']), $order["api_url"]);
                    } elseif ($order["birlestirme"] && $order['order_error'] != "-") {
                        $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $bol[0]), $order["api_url"]);
                        if ($order['order_error2'] != "-") {
                            if (!$order['sirali_islem']) {

                                $api_v = new \App\Models\service_api();
                                $api_v_c = $api_v->where('id', $order['service_api2'])->get()->getResultArray()[0];
                                $order2 = $smmapi->action(array('key' => $order['api_key'], 'action' => 'add', 'service' => $order["api_service2"], 'link' => $order["order_url"], 'quantity' => $bol[1]), $order["api_url"]);
                                if (!isset($order2->order)):
                                    $error2 = json_encode($order2);
                                    $order_id2 = "";
                                    $proc = "fail";
                                else:
                                    $error2 = "-";
                                    $proc = "processing";
                                    $order_id2 = $order2->order;

                                endif;

                                $last_id = $order['order_id'];

                                $api_v = new \App\Models\cift_servis();
                                $apisil = $api_v->where(['order_id' => $last_id])->delete();
                                $api_v_c = $api_v->set(['order_id' => $last_id, 'status' => $proc])->insert();
                                $update = $conn->prepare("UPDATE orders SET api_orderid2=:orders2,order_error2=:errors2,order_detail2=:detail2 WHERE order_id=:id ");
                                $update->execute(array("id" => $order["order_id"], 'orders2' => isset($order_id2) ? $order_id2 : 0, 'errors2' => isset($error2) ? $error2 : 0, 'detail2' => isset($order2) ? json_encode($order2) : "",));
                                

                            }
                        }
                    } elseif ($order["birlestirme"] && $order['order_error2'] != "-") {


                        if (!$order['sirali_islem']) {

                            $api_v = new \App\Models\service_api();
                            $api_v_c = $api_v->where('id', $order['service_api2'])->get()->getResultArray()[0];
                            $order2 = $smmapi->action(array('key' => $order['api_key'], 'action' => 'add', 'service' => $order["api_service2"], 'link' => $order["order_url"], 'quantity' => $bol[1]), $order["api_url"]);
                            if (!isset($order2->order)):
                                $error2 = json_encode($order2);
                                $order_id2 = "";
                                $proc = "fail";
                            else:
                                $error2 = "-";
                                $proc = "processing";
                                $order_id2 = $order2->order;

                            endif;

                            $last_id = $order['order_id'];

                            $api_v = new \App\Models\cift_servis();
                            $apisil = $api_v->where(['order_id' => $last_id])->delete();
                            $api_v_c = $api_v->set(['order_id' => $last_id, 'status' => $proc])->insert();
                            $update = $conn->prepare("UPDATE orders SET api_orderid2=:orders2,order_error2=:errors2,order_detail2=:detail2 WHERE order_id=:id ");
                            $update->execute(array("id" => $order["order_id"], 'orders2' => isset($order_id2) ? $order_id2 : 0, 'errors2' => isset($error2) ? $error2 : 0, 'detail2' => isset($order2) ? json_encode($order2) : "",));
                            return redirect()->to(base_url("admin/orders"));

                        }
                    }
                    //$get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'quantity' => $order["order_quantity"]), $order["api_url"]);
                    if (@!$get_order->order):
                        $error = json_encode($get_order);
                        $order_id = "";
                    else:
                        $error = "-";
                        $order_id = @$get_order->order;
                    endif;
                ## Standart bitti ##
                elseif ($order["service_package"] == 3):
                    ## Custom comments başla ##
                    $arr = json_decode($order["order_extras"], true);
                    $comments = $arr["comments"];
                    $get_order = $smmapi->action(array('key' => $order["api_key"], 'action' => 'add', 'service' => $order["api_service"], 'link' => $order["order_url"], 'comments' => $comments), $order["api_url"]);
                    if (@!$get_order->order):
                        $error = json_encode($get_order);
                        $order_id = "";
                    else:
                        $error = "-";
                        $order_id = @$get_order->order;
                    endif;
                ## Custom comments bitti ##
                else:
                endif;
                $orderstatus = $smmapi->action(array('key' => $order["api_key"], 'action' => 'status', 'order' => $order_id), $order["api_url"]);
                $balance = $smmapi->action(array('key' => $order["api_key"], 'action' => 'balance'), $order["api_url"]);
                $api_charge = $orderstatus->charge;
                if (!$api_charge): $api_charge = 0; endif;
                $currency = $balance->currency;
                if ($currency == "USD"):
                    $currencycharge = 1;
                elseif ($currency == "TRY"):
                    $currencycharge = $settings["dolar_charge"];
                elseif ($currency == "EUR"):
                    $currencycharge = $settings["euro_charge"];
                endif;
            ## Standart api bitti ##
            elseif ($order["api_type"] == 3):
                if ($order["service_package"] == 1 || $order["service_package"] == 2):
                    ## Standart başla ##
                    $get_order = $fapi->query(array('cmd' => 'orderadd', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orders' => [['service' => $order["api_service"], 'amount' => $order["order_quantity"], 'data' => $order["order_url"]]]));
                    if (@!$get_order[0][0]['status'] == "error"):
                        $error = json_encode($get_order);
                        $order_id = "";
                        $api_charge = "0";
                        $currencycharge = 1;
                    else:
                        $error = "-";
                        $order_id = @$get_order[0][0]["id"];
                        $orderstatus = $fapi->query(array('cmd' => 'orderstatus', 'token' => $order["api_key"], 'apiurl' => $order["api_url"], 'orderid' => [$order_id]));
                        $balance = $fapi->query(array('cmd' => 'profile', 'token' => $order["api_key"], 'apiurl' => $order["api_url"]));
                        $api_charge = $orderstatus[$order_id]["order"]["price"];
                        $currency = "TRY";
                        if ($currency == "TRY"):
                            $currencycharge = 1;
                        elseif ($currency == "USD"):
                            $currencycharge = $settings["dolar_charge"];
                        elseif ($currency == "EUR"):
                            $currencycharge = $settings["euro_charge"];
                        endif;
                    endif;
                    ## Standart bitti ##
                endif;
            else:
            endif;
            /* API SİPARİŞ GEÇ BİTTİ */
            if ($order['order_error'] == "-") {
                return redirect()->to(base_url("admin/orders"));
            }
            $update = $conn->prepare("UPDATE orders SET order_api=:api, api_serviceid=:serviceid, order_error=:error, api_orderid=:orderid, order_detail=:detail, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit WHERE order_id=:id ");
            $update->execute(array("error" => $error, "api" => $order["id"], "serviceid" => $order["api_service"], "orderid" => $order_id, "detail" => json_encode($get_order), "id" => $order["order_id"], "profit" => $api_charge * $currencycharge, "api_charge" => $api_charge, "api_currencycharge" => $currencycharge));
            return redirect()->to(base_url("admin/orders"));
        endif;
        $cift = new \App\Models\cift_servis();
        $arama = "";
        $subs = "";
        if ($this->request->getGet('subscription')) {
            $subs = $this->request->getGet('subscription');
        }
        if ($this->request->getGet('client')) {
            $arama = $this->request->getGet('client');
        }
        $ayar = array(
            'title' => 'Orders',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
            'arama' => $arama,
            'status' => $status,
            'cift' => $cift,
            'failCount' => isset($failCount) ? $failCount : 0,
            'orders' => isset($orders) ? $orders : 0,
            'paginationArr' => isset($paginationArr) ? $paginationArr : 0,
            'subs' => $subs
        );
        //echo view('admin/orders', $ayar);
        echo view('admin/yeni_admin/orders', $ayar);
    }

    public function detail()
    {
        if ($this->request->uri->getSegment(3)) {
            $id = $this->request->uri->getSegment(3);
            $orders = new \App\Models\orders();


            if ($this->request->getPost('id') && $this->request->getPost('order_status')) {
                $status = $this->request->getPost('order_status');
                $id = $this->request->getPost('id');
                $orders->protect(false)->set('order_status', $status)->where('order_id', $id)->update();
            }
            if ($orders->where('order_id', $id)->countAllResults()) {
                $order_detail = $orders
                    ->select("orders.order_id,services.service_name,orders.api_charge,service_api.api_json,orders.order_remains,clients.username,orders.order_detail,orders.order_quantity,orders.order_charge,orders.order_url,orders.order_status,orders.order_create,orders.last_check,service_api.api_name,service_api.id as api_id,orders.api_orderid,service_api.api_url")
                    ->where('orders.order_id', $id)
                    ->join('services', 'orders.service_id = services.service_id', 'left')
                    ->join('clients', ' orders.client_id = clients.client_id', 'left')
                    ->join('service_api', 'orders.order_api = service_api.id', 'left')
                    ->get()
                    ->getResultArray()[0];

                $ayar = array(
                    'title' => 'Order Detail ' . $order_detail['order_id'],
                    'user' => $this->getuser,
                    'route' => $this->request->uri->getSegment(2),
                    'settings' => $this->settings,
                    'success' => 0,
                    'error' => 0,
                    'search_word' => '',
                    'search_where' => 'username',
                    'order' => $order_detail
                );
                return view('admin/yeni_admin/order-detail', $ayar);
            }
        }

    }
}