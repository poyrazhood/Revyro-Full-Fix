<?php

namespace App\Controllers\main;

use App\Controllers\BaseController;
use App\Models\ticket_reply;
use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Api extends BaseController
{
    function index()
    {

        global $conn;
        global $_SESSION;
        include APPPATH . 'ThirdParty/dil/tr.php';

        $settings = $this->settings;
        $languages = $conn->prepare('SELECT * FROM languages WHERE language_type=:type');
        $languages->execute(array(
            'type' => 2
        ));

        function servicePackage($type)
        {
            switch ($type) {
                case 1:
                    $service_type = "Default";
                    break;
                case 2:
                    $service_type = "Package";
                    break;
                case 3:
                    $service_type = "Custom Comments";
                    break;
                case 4:
                    $service_type = "Custom Comments Package";
                    break;
                default:
                    $service_type = "Subscriptions";
                    break;
            }
            return $service_type;
        }

        if (isset($_POST)) {
            if ((empty($_POST) || !$_POST) && $_GET):
                $_POST = $_GET;
                if (isset($_POST["link"])) {
                    $_POST["link"] = urldecode($_POST["link"]);
                }
            endif;

            $action = isset($_POST["action"]) ? htmlspecialchars($_POST["action"]) : 0;
            $key = isset($_POST["key"]) ? htmlspecialchars($_POST["key"]) : 0;
            if(isset($_POST["order"])){ $orderid = htmlspecialchars($_POST["order"]);}else{$ordersid  = htmlspecialchars($_POST["orders"]); $ordersid = $ordersid;}
            $serviceid = isset($_POST["service"]) ? htmlspecialchars($_POST["service"]) : 0;
            $quantity = isset($_POST["quantity"]) ? htmlspecialchars($_POST["quantity"]) : "";
            $link = isset($_POST["link"]) ? htmlspecialchars($_POST["link"]) : "";
            $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : "";
            $answer_number = isset($_POST["answer_number"]) ? htmlspecialchars($_POST["answer_number"]) : "";
            $posts = isset($_POST["posts"]) ? htmlspecialchars($_POST["posts"]) : "";
            $delay = isset($_POST["delay"]) ? htmlspecialchars($_POST["delay"]) : "";
            $otoMin = isset($_POST["min"]) ? htmlspecialchars($_POST["min"]) : "";
            $otoMax = isset($_POST["max"]) ? htmlspecialchars($_POST["max"]) : "";
            $comments = isset($_POST["comments"]) ? htmlspecialchars($_POST["comments"]) : "";
            $runs = isset($_POST["runs"]) ? htmlspecialchars($_POST["runs"]) : "";
            $interval = isset($_POST["interval"]) ? htmlspecialchars($_POST["interval"]) : "";
            $expiry = isset($_POST["expiry"]) ? date("Y.m.d", strtotime($_POST["expiry"])) : "";
            $subscriptions = 0;


            $client = $conn->prepare("SELECT * FROM clients WHERE apikey=:key ");
            $client->execute(array("key" => $key));
            $clientDetail = $client->fetch(PDO::FETCH_ASSOC);

            if (empty($action) || empty($key)):
                $output = array('error' => 'Incorrect request');
            elseif (!$client->rowCount()):
                $output = array('error' => 'API key hatalı', 'status' => "102");
            elseif ($clientDetail["client_type"] == 1):
                $output = array('error' => 'Hesabınız pasif', 'status' => "103");
            else:
                ## actionlar başla ##
                if ($action == "balance"):
                    $output = array('balance' => $clientDetail["balance"], 'currency' => $settings["site_currency"]);
                elseif( $action == "status" ):
          if(isset($_POST["order"])){
                $order        = $conn->prepare("SELECT * FROM orders WHERE order_id=:id && client_id=:client ");
                $order        -> execute(array("client"=>$clientDetail["client_id"],"id"=>$orderid ));
                $orderDetail  = $order->fetch(PDO::FETCH_ASSOC);
                if( $order->rowCount() ):
                  if( $orderDetail["subscriptions_type"] == 2 ):
                    $output    = array('status'=>ucwords($orderDetail["subscriptions_status"]),"posts"=>$orderDetail["subscriptions_posts"]);
                  elseif( $orderDetail["dripfeed"] != 1 ):
                    $output    = array('status'=>ucwords($orderDetail["subscriptions_status"]),"runs"=>$orderDetail["dripfeed_runs"]);
                  else:
                    $output    = array('charge'=>$orderDetail["order_charge"],"start_count"=>$orderDetail["order_start"],'status'=>ucfirst($orderDetail["order_status"]),"remains"=>$orderDetail["order_remains"],"currency"=>$settings["site_currency"]);
                  endif;
                else:
                  $output    = array('error'=>'Sipariş bulunamadı.','status'=>"104");
                endif;
          }else{

                foreach(explode(',',$ordersid) as $orderid){
                  $orderid = trim($orderid);
                  if(!empty($orderid)){
                      $order        = $conn->prepare("SELECT * FROM orders WHERE order_id=:id && client_id=:client LIMIT 1");
                      $order        -> execute(array("client"=>$clientDetail["client_id"],"id"=>$orderid )); 
                      $orderDetail  = $order->fetch(PDO::FETCH_ASSOC);
                      if( $order->rowCount() ):
                        if( $orderDetail["subscriptions_type"] == 2 ):
                          $output    = array('status'=>ucwords($orderDetail["subscriptions_status"]),"posts"=>$orderDetail["subscriptions_posts"]);
                        elseif( $orderDetail["dripfeed"] != 1 ):
                          $output    = array('status'=>ucwords($orderDetail["subscriptions_status"]),"runs"=>$orderDetail["dripfeed_runs"]);
                        else:
                          $output[$orderDetail["order_id"]] = array('order_id'=>$orderDetail["order_id"],'charge'=>$orderDetail["order_charge"],"start_count"=>$orderDetail["order_start"],'status'=>ucfirst($orderDetail["order_status"]),"remains"=>$orderDetail["order_remains"],"currency"=>$settings["site_currency"]);
                        endif;
                      else:
                        $output    = array('error'=>'Sipariş bulunamadı.','status'=>"104");
                      endif;
                  }
                }

          }
                elseif ($action == "services"):
                    $servicesRows = $conn->prepare("SELECT * FROM services INNER JOIN categories ON categories.category_id=services.category_id WHERE categories.category_type=:type2 && services.service_type=:type  ORDER BY categories.category_line,services.service_line ASC ");
                    $servicesRows->execute(array("type" => 2, "type2" => 2));
                    $servicesRows = $servicesRows->fetchAll(PDO::FETCH_ASSOC);

                    $services = [];
                    foreach ($servicesRows as $serviceRow) {
                        $search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id ");
                        $search->execute(array("service" => $serviceRow["service_id"], "c_id" => $clientDetail["client_id"]));
                        $search2 = $conn->prepare("SELECT * FROM clients_category WHERE category_id=:category && client_id=:c_id ");
                        $search2->execute(array("category" => $serviceRow["category_id"], "c_id" => $clientDetail["client_id"]));
                        if (($serviceRow["service_secret"] == 2 || $search->rowCount()) && ($serviceRow["category_secret"] == 2 || $search2->rowCount())):
                            $s["rate"] = client_price($serviceRow["service_id"], $clientDetail["client_id"]);
                            $s['service'] = $serviceRow["service_id"];
                            $s['category'] = $serviceRow["category_name"];
                            $s['name'] = $serviceRow["service_name"];
                            $s['type'] = servicePackage($serviceRow["service_package"]);
                            $s['min'] = $serviceRow["service_min"];
                            $s['max'] = $serviceRow["service_max"];
                            $s['description'] = $serviceRow["service_description"];
                            array_push($services, $s);
                        endif;
                    }
                    $output = $services;
                elseif ($action == "addTicket"):
                    if ($this->request->getPost('subject') && $this->request->getPost('message')):
                        $ticket = new \App\Models\tickets();
                        $reply = new ticket_reply();
                        $ticket->protect(false)->save(array(
                            'subject' => $this->request->getPost('subject'),
                            'status' => 'pending',
                            "time" => date("Y.m.d H:i:s"),
                            "lastupdate_time" => date("Y.m.d H:i:s"),
                            'client_id' => $clientDetail["client_id"],
                        ));
                        $ticketid = $ticket->getInsertID();
                        $reply->protect(false)->save(array(
                            'ticket_id' => $ticketid,
                            'client_id' => $clientDetail["client_id"],
                            'time' => date("Y.m.d H:i:s"),
                            'message' => $this->request->getPost('message'),
                        ));
                        $output = array('success' => "Başarıyla " . $ticketid . " idli " . $this->request->getPost('subject') . " başlıklı destek talebiniz açıldı", 'status' => 202, 'ticket_id' => $ticketid);
                    else:
                        $output = array('error' => "subject ve message zorunlu", 'status' => 115);
                    endif;
                elseif ($action == "add"):
                    $clientBalance = $clientDetail["balance"];
                    $serviceDetail = $conn->prepare("SELECT * FROM services INNER JOIN categories ON categories.category_id=services.category_id LEFT JOIN service_api ON service_api.id=services.service_api WHERE services.service_id=:id ");
                    $serviceDetail->execute(array("id" => $serviceid));
                    $serviceDetail = $serviceDetail->fetch(PDO::FETCH_ASSOC);

                    $search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id ");
                    $search->execute(array("service" => $serviceid, "c_id" => $clientDetail["client_id"]));
                    $search2 = $conn->prepare("SELECT * FROM clients_category WHERE category_id=:category && client_id=:c_id ");
                    $search2->execute(array("category" => $serviceDetail["category_id"], "c_id" => $clientDetail["client_id"]));

                    $link = $_POST["link"];

                    if (($serviceDetail["service_secret"] == 2 || $search->rowCount()) && $serviceDetail["category_type"] == 2 && $serviceDetail["service_type"] == 2 && ($serviceDetail["category_secret"] == 2 || $search2->rowCount())):
                        ## sipariş geç ##
                        if ($username != ""):
                            $likes = true;
                            $likes_s = $username;
                            array_push($where, array('order_url' => $likes_s));
                            $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]);
                        elseif ($answer_number != ""):
                            $poll = true;
                            $poll_s = $answer_number;
                            array_push($where, array('order_url' => $link));
                            $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]);
                        elseif ($serviceDetail["service_package"] == 2):
                            $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]);
                            $serviceDetail["service_min"] = 1;
                            $serviceDetail["service_max"] = 1;
                            $quantity = 1;
                        elseif ($serviceDetail["service_package"] == 3 || $serviceDetail["service_package"] == 4):
                            $comments = str_replace("\\n", "\n", $comments);
                            $quantity = count(explode("\n", $comments));// count custom comments
                            $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]) / 1000 * $quantity;
                            $extras = json_encode(["comments" => $comments]);
                            $subscriptions_status = "active";
                            $subscriptions = 1;
                        else:
                            $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]) / 1000 * $quantity;
                        endif;

                        if ($runs && $interval):
                            $dripfeed = 2;
                            $totalcharges = $price * $runs;
                            $totalquantity = $quantity * $runs;
                            $price = $price * $runs;
                        else:
                            $dripfeed = 1;
                            $totalcharges = "";
                            $totalquantity = "";
                        endif;

                        $price = abs($price);
                        $price = bakiye_format($price);
                        if (($runs && empty($interval)) || ($interval && empty($runs))):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif ($serviceDetail["service_package"] == 1 && (empty($link) || empty($quantity))):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif ($serviceDetail["service_package"] == 2 && empty($link)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] == 14 || $serviceDetail["service_package"] == 15) && empty($link)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif ($serviceDetail["service_package"] == 3 && (empty($link) || empty($comments))):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif ($serviceDetail["service_package"] == 4 && (empty($link) || empty($comments))):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] != 11 && $serviceDetail["service_package"] != 12 && $serviceDetail["service_package"] != 13) && (($dripfeed == 2 && $totalquantity < $serviceDetail["service_min"]) || ($dripfeed == 1 && $quantity < $serviceDetail["service_min"]))):
                            $output = array('error' => "Minimum sayıyı karşılayamadınız.", 'status' => 108);
                        elseif (($serviceDetail["service_package"] != 11 && $serviceDetail["service_package"] != 12 && $serviceDetail["service_package"] != 13) && (($dripfeed == 2 && $totalquantity > $serviceDetail["service_max"]) || ($dripfeed == 1 && $quantity > $serviceDetail["service_max"]))):
                            $output = array('error' => "Maksimum sayı aşıldı.", 'status' => 109);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && empty($username)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && empty($otoMin)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && empty($otoMax)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && empty($posts)):
                            $output = array('error' => "Gerekli alanları doldurmalısınız.", 'status' => 107);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && $otoMax < $otoMin):
                            $output = array('error' => "Minimum sayı Maksimum sayıdan büyük olamaz.", 'status' => 110);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && $otoMin < $serviceDetail["service_min"]):
                            $output = array('error' => "Minimum sayıyı karşılayamadınız.", 'status' => 111);
                        elseif (($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13) && $otoMax > $serviceDetail["service_max"]):
                            $output = array('error' => "Maksimum sayı aşıldı", 'status' => 112);
                        elseif (($price > $clientDetail["balance"]) && $clientDetail["balance_type"] == 2):
                            $output = array('error' => "Bakiyeniz yetersiz", 'status' => 113);
                        elseif (($clientDetail["balance"] - $price < "-" . $clientDetail["debit_limit"]) && $clientDetail["balance_type"] == 1):
                            $output = array('error' => "Bakiyeniz yetersiz", 'status' => 113);
                        elseif (0 > $price):
                            $output = array('error' => "Bakiyeniz yetersiz", 'status' => 114);
                        elseif (strstr($price, "-")):
                            $output = array('error' => "Bakiyeniz yetersiz", 'status' => 115);
                        else:
                            if (!$runs): $runs = 1; endif;

                            if ($runs < 1) {
                                $runs = 1;
                            }

                            if ($serviceDetail["service_package"] == 3 || $serviceDetail["service_package"] == 4):
                                $comments = str_replace("\\n", "\n", $comments);
                                $quantity = count(explode("\n", $comments));// count custom comments
                                $price = client_price($serviceDetail["service_id"], $clientDetail["client_id"]) / 1000 * $quantity;
                                $extras = json_encode(["comments" => $comments]);
                                $subscriptions_status = "active";
                                $subscriptions = 1;
                            elseif ($serviceDetail["service_package"] == 11 || $serviceDetail["service_package"] == 12 || $serviceDetail["service_package"] == 13):
                                $quantity = $otoMin . "-" . $otoMax; // Sipariş miktarı
                                $price = 0;
                                $extras = json_encode([]);
                                $subscriptions = 1;
                            elseif ($serviceDetail["service_package"] == 14 || $serviceDetail["service_package"] == 15):
                                $quantity = $serviceDetail["service_min"];
                                $price = service_price($service["service_id"]);
                                $posts = $serviceDetail["service_autopost"];
                                $delay = 0;
                                $time = '+' . $serviceDetail["service_autotime"] . ' days';
                                $expiry = date('Y-m-d H:i:s', strtotime($time));
                                $otoMin = $serviceDetail["service_min"];
                                $otoMax = $serviceDetail["service_min"];
                                $extras = json_encode([]);
                            else:
                                $posts = 0;
                                $delay = 0;
                                $expiry = "1970-01-01";
                                $extras = json_encode([]);
                                $subscriptions_status = "active";
                                $subscriptions = 1;
                            endif;
                            $order_ss = new \App\Models\orders();
                            if ($serviceDetail['rep_link'] == 2 && $order_ss->where(["client_id" => $clientDetail["client_id"], 'order_url' => $link, 'order_status!=' => 'completed', 'service_id' => $serviceDetail['service_id']])->countAllResults()) {
                                $output = array('error' => "İşlem bitmeden aynı linke sipariş veremezsiniz", 'status' => 116);

                            } else {
                                if ($serviceDetail["service_api"] == 0):
                                    /* manuel sipariş - başla */
                                    //$conn->beginTransaction();

                                    $insert = $conn->prepare("INSERT INTO orders SET order_where=:order_where, order_start=:count, order_profit=:profit, order_error=:error, client_id=:c_id, service_id=:s_id, order_extras=:extras,order_quantity=:quantity, order_charge=:price, order_url=:url, order_create=:create, last_check=:last ");
                                    $insert = $insert->execute(array("order_where" => "api", "count" => 0, "c_id" => $clientDetail["client_id"], "error" => "-", "s_id" => $serviceDetail["service_id"], "extras" => $extras, "quantity" => $quantity, "price" => $price, "profit" => $price, "url" => $link, "create" => date("Y.m.d H:i:s"), "last" => date("Y.m.d H:i:s")));

                                    if ($insert): $last_id = $conn->lastInsertId(); endif;

                                    $update = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
                                    $update = $update->execute(array("balance" => $clientDetail["balance"] - $price, "spent" => $clientDetail["spent"] + $price, "id" => $clientDetail["client_id"]));

                                    $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                                    $insert2 = $insert2->execute(array("c_id" => $clientDetail["client_id"], "action" => "API aracılığıyla " . $price . " TL tutarında yeni sipariş geçildi.", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));

                                    if ($insert && $update && $insert2):
                                        //$conn->commit();
                                        $output = array('status' => 100, 'order' => $last_id);
                                        if ($settings["alert_newmanuelservice"] == 2):
                                            if ($settings["alert_type"] == 3): $sendmail = 1;
                                                $sendsms = 1;
                                            elseif ($settings["alert_type"] == 2): $sendmail = 1;
                                                $sendsms = 0;
                                            elseif ($settings["alert_type"] == 1): $sendmail = 0;
                                                $sendsms = 1; endif;
                                            if ($sendsms):
                                                SMSUser($settings["admin_telephone"], "Websiteniz #" . $last_id . " idli yeni bir sipariş mevcut.");
                                            endif;
                                            if ($sendmail):
                                                sendMail(["subject" => "Yeni sipariş mevcut.", "body" => "Websiteniz #" . $last_id . " idli yeni bir sipariş mevcut.", "mail" => $settings["admin_mail"]]);
                                            endif;
                                        endif;
                                    else:
                                        //$conn->rollBack();
                                        $output = array('error' => "Siparişiniz verilirken hata oluştu", 'status' => 114);
                                    endif;
                                /* manuel sipariş - bitir */
                                else:
                                    /* api ile sipariş - başla */
                                    //$conn->beginTransaction();
                                    if ($serviceDetail["service_api"] != 0):
                                        $api_detail = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
                                        $api_detail->execute(array("id" => $serviceDetail["service_api"]));
                                        $api_detail = $api_detail->fetch(PDO::FETCH_ASSOC);
                                    endif;



                                    $insert = $conn->prepare("INSERT INTO orders SET order_where=:order_where, order_error=:error, order_detail=:detail, client_id=:c_id,
                    service_id=:s_id, order_quantity=:quantity, order_charge=:price, order_url=:url, order_create=:create, order_extras=:extra, last_check=:last_check,
                    order_api=:api, api_serviceid=:api_serviceid, subscriptions_status=:s_status,
                    subscriptions_type=:subscriptions, subscriptions_username=:username, subscriptions_posts=:posts, subscriptions_delay=:delay, subscriptions_min=:min,
                    subscriptions_max=:max, subscriptions_expiry=:expiry
                    ");
                                    $insert = $insert->execute(array("order_where" => "api", "c_id" => $clientDetail["client_id"], "detail" => "cronpending", "error" => "-",
                                        "s_id" => $serviceDetail["service_id"], "quantity" => $quantity, "price" => $price / $runs, "url" => $link,
                                        "create" => date("Y.m.d H:i:s"), "extra" => $extras, "last_check" => date("Y.m.d H:i:s"), "api" => $serviceDetail["id"],
                                        "api_serviceid" => $serviceDetail["api_service"], "s_status" => $subscriptions_status, "subscriptions" => $subscriptions, "username" => $username,
                                        'posts' => $posts,
                                        "delay" => $delay, "min" => $otoMin, "max" => $otoMax, "expiry" => $expiry));
                                    if ($insert): $last_id = $conn->lastInsertId(); endif;
                                    if ($serviceDetail["birlestirme"]) {

                                        if ($serviceDetail['sirali_islem']) {
                                            $api_v = new \App\Models\cift_servis();
                                            $api_v_c = $api_v->set('order_id', $last_id)->insert();
                                        }
                                    }
                                    $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                                    $insert2 = $insert2->execute(array("c_id" => $clientDetail["client_id"], "action" => "API aracılığıyla " . $price . " TL tutarında yeni sipariş geçildi #" . $last_id . " Eski Bakiye: " . $clientBalance . " / Yeni Bakiye:" . $clientDetail["balance"], "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));

                                    $update_client = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
                                    $update_client = $update_client->execute(array("balance" => $clientDetail["balance"] - $price, "spent" => $clientDetail["spent"] + $price, "id" => $clientDetail["client_id"]));


                                    if ($insert):
                                        //$conn->commit();
                                        $output = array('order' => $last_id);
                                    else:
                                        // $conn->rollBack();
                                        $output = array('error' => "Siparişiniz verilirken hata oluştu", 'status' => 114);
                                    endif;
                                    /* api ile sipariş - bitir */
                                endif;
                            }
                        endif;
                    ## sipariş geç  bitti ##
                    else:
                        $output = array('error' => 'Servis pasif ya da bulunamadı', 'status' => "105");
                    endif;
                endif;
                ## actionlar bitti ##
            endif;
            print_r(json_encode($output));
            die;
        }
    }

}