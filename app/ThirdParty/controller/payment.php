<?php

use Omnipay\Omnipay;
$method_name = route(2);

if (!countRow(["table" => "payment_methods", "where" => ["method_get" => $method_name]])) :
    header("Location:" . base_url());
    exit();
endif;

$method = $conn->prepare("SELECT * FROM payment_methods WHERE method_get=:get ");
$method->execute(array("get" => $method_name));
$method = $method->fetch(PDO::FETCH_ASSOC);
$extras = json_decode($method["method_extras"], true);


if ($method_name == "shopier") :
    ## Shopier başla ##
    $post = $_POST;
    $order_id = $post['platform_order_id'];
    $status = $post['status'];
    $payment_id = $post['payment_id'];
    $installment = $post['installment'];
    $random_nr = $post['random_nr'];
    $signature = base64_decode($_POST["signature"]);
    $expected = hash_hmac('SHA256', $random_nr . $order_id, $extras["apiSecret"], true);
    if ($signature != $expected) :
        header("Location:" . base_url());
    endif;
    if ($status == 'success') :
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :
            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;
            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");

                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;
            if ($update && $balance) :
                $conn->commit();
            else :
                $conn->rollBack();
            endif;
        else :
        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
    endif;
    ## shopier bitti ##
    header("Location:" . base_url());
elseif ($method_name == "paytr") :
    ## paytr başla ##

    if (!$_POST) :
        die("OK");
    endif;

    $post = $_POST;
    $order_id = $post['merchant_oid'];
    $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
    $payment->execute(array("orderid" => $order_id));
    $payment = $payment->fetch(PDO::FETCH_ASSOC);
    $method = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
    $method->execute(array("id" => $payment["payment_method"]));
    $method = $method->fetch(PDO::FETCH_ASSOC);
    $extras = json_decode($method["method_extras"], true);
    $merchant_key = $extras["merchant_key"];
    $merchant_salt = $extras["merchant_salt"];
    $hash = base64_encode(hash_hmac('sha256', $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'], $merchant_key, true));

    if ($hash != $post['hash'])
        die('PAYTR notification failed: bad hash');

    if ($post['status'] == 'success') :
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1, "payment_status" => 1]])) :
            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);

            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;

            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
            // Referans BAŞLANGIÇ
            if ($settings['referral'] == 2) {
                $refok = 0;
                if ($settings['ref_type'] == 0) {
                    $refok = 1;
                } else {
                    $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                    if ($rowc <= 1) {
                        $refok = 1;
                    }
                }
                if ($refok) {
                    $client = model("clients");
                    $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                    if ($cc['referral'] != Null && $cc['referral'] != "") {
                        $ref_id = $cc['referral'];
                        $oran = $amount * $settings['ref_bonus'] / 100;

                        $this->db->table("referral")->set(array(
                            'client_id' => $ref_id,
                            'refferal' => $payment["client_id"],
                            'action' => $oran,
                            'register_date' => 1
                        ))->insert();
                        $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                    }
                }
            }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;

            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
                die;
            else :
                $conn->rollBack();
                echo "OK";
                die;
            endif;
        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
    endif;

    echo "OK";
    die;
## paytr bitti ##
elseif ($method_name == "paywant") :
    ## paywant başla ##
    $apiSecret = $extras["apiSecret"];
    $SiparisID = $_POST["SiparisID"];
    $ExtraData = $_POST["ExtraData"];
    $UserID = $_POST["UserID"];
    $ReturnData = $_POST["ReturnData"];
    $Status = $_POST["Status"];
    $OdemeKanali = $_POST["OdemeKanali"];
    $OdemeTutari = $_POST["OdemeTutari"];
    $NetKazanc = $_POST["NetKazanc"];
    $Hash = $_POST["Hash"];
    $order_id = $_POST["ExtraData"];
    $hashKontrol = base64_encode(hash_hmac('sha256', "$SiparisID|$ExtraData|$UserID|$ReturnData|$Status|$OdemeKanali|$OdemeTutari|$NetKazanc" . $apiKey, $apiSecret, true));
    if ($Status == 100) :
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :
            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;
            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;
            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
            else :
                $conn->rollBack();
                echo "NO";
            endif;
        else :
            echo "NOO";
        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
        echo "NOOO";
    endif;
## paywant bitti ##
elseif ($method_name == "shoplemo") :

    $APIKey = $extras["apiKey"];
    $secretKey = $extras["apiSecret"];


    if (!$_POST || $_POST['status'] != 'success') {
        die('Shoplemo.com');
    }

    $_data = json_decode($_POST['data']); // POST temizleme işlemi olduğu için geri düzelttik. 
    $hash = base64_encode(hash_hmac('sha256', $_data['progress_id'] . implode('|', $_data['payment']) . $APIKey, $secretKey, true));

    if ($hash != $_data['hash']) {
        die('Shoplemo: Calculated hashes doesn\'t match!');
    }


    if ($_data['payment']['payment_status'] == 'COMPLETED') {

        $custom_params = json_decode($_data['custom_params']);
        $order_id = $custom_params->payment_code;

        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :
            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;
            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
            // Referans BAŞLANGIÇ
            if ($settings['referral'] == 2) {
                $refok = 0;
                if ($settings['ref_type'] == 0) {
                    $refok = 1;
                } else {
                    $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                    if ($rowc <= 1) {
                        $refok = 1;
                    }
                }
                if ($refok) {
                    $client = model("clients");
                    $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                    if ($cc['referral'] != Null && $cc['referral'] != "") {
                        $ref_id = $cc['referral'];
                        $oran = $amount * $settings['ref_bonus'] / 100;

                        $this->db->table("referral")->set(array(
                            'client_id' => $ref_id,
                            'refferal' => $payment["client_id"],
                            'action' => $oran,
                            'register_date' => 1
                        ))->insert();
                        $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                    }
                }
            }
            // Referans BİTİŞ

            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;
            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
            else :
                $conn->rollBack();
                echo "NO";
            endif;
        else :
            if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 2]]))
                exit("OK");

            echo "NOO";
        endif;
    } else {
        exit("yükleme işlemi yok");
    }

elseif ($method_name == 'coinpayments') :
    $merchant_id = $extras['merchant_id'];
    $secret = $extras['ipn_secret'];

    function errorAndDie($error_msg)
    {
        die('IPN Error: ' . $error_msg);
    }

    if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
        $ipnmode = $_POST['ipn_mode'];
        errorAndDie("IPN Mode is not HMAC $ipnmode");
    }

    if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
        errorAndDie("No HMAC signature sent");
    }

    $merchant = isset($_POST['merchant']) ? $_POST['merchant'] : '';
    if (empty($merchant)) {
        errorAndDie("No Merchant ID passed");
    }

    if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($merchant_id)) {
        errorAndDie('No or incorrect Merchant ID passed');
    }

    $request = file_get_contents('php://input');
    if ($request === FALSE || empty($request)) {
        errorAndDie("Error reading POST data");
    }

    $hmac = hash_hmac("sha512", $request, $secret);
    if ($hmac != $_SERVER['HTTP_HMAC']) {
        errorAndDie("HMAC signature does not match");
    }

    // HMAC Signature verified at this point, load some variables. 

    $status = intval($_POST['status']);
    $status_text = $_POST['status_text'];

    $txn_id = $_POST['txn_id'];
    $currency1 = $_POST['currency1'];

    $amount1 = floatval($_POST['amount1']);

    $order_currency = $settings['site_currency'];
    $order_total = $amount1;

    $subtotal = $_POST['subtotal'];
    $shipping = $_POST['shipping'];

    ///////////////////////////////////////////////////////////////

    // Check the original currency to make sure the buyer didn't change it. 
    if ($currency1 != $order_currency) {
        errorAndDie('Original currency mismatch!');
    }

    if ($amount1 < $order_total) {
        errorAndDie('Amount is less than order total!');
    }

    if ($status >= 100 || $status == 2) {
        $user = $conn->prepare("SELECT * FROM clients WHERE email=:email");
        $user->execute(array("email" => $_POST['email']));
        $user = $user->fetch(PDO::FETCH_ASSOC);
        if (countRow(['table' => 'payments', 'where' => ['client_id' => $user['client_id'], 'payment_method' => 8, 'payment_status' => 1, 'payment_delivery' => 1, 'payment_extra' => $_POST['txn_id']]])) {
            if ($status >= 100 || $status == 2) {
                $payment = $conn->prepare('SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_extra=:extra ');
                $payment->execute(['extra' => $_POST['txn_id']]);
                $payment = $payment->fetch(PDO::FETCH_ASSOC);
                $payment_bonus = $conn->prepare('SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1');
                $payment_bonus->execute(['method' => $method['id'], 'from' => $payment['payment_amount']]);
                $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
                if ($payment_bonus) {
                    $amount = $payment['payment_amount'] + (($payment['payment_amount'] * $payment_bonus['bonus_amount']) / 100);
                } else {
                    $amount = $payment['payment_amount'];
                }
                $conn->beginTransaction();

                $update = $conn->prepare('UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery WHERE payment_id=:id ');
                $update = $update->execute(['balance' => $payment['balance'], 'status' => 3, 'delivery' => 2, 'id' => $payment['payment_id']]);

                $balance = $conn->prepare('UPDATE clients SET balance=:balance WHERE client_id=:id ');
                $balance = $balance->execute(['id' => $payment['client_id'], 'balance' => $payment['balance'] + $amount]);

                $insert = $conn->prepare('INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ');
                            // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
                if ($payment_bonus) {
                    $insert = $insert->execute(['c_id' => $payment['client_id'], 'action' => 'New ' . $amount . ' ' . $settings["site_currency"] . ' payment has been made with ' . $method['method_name'] . ' and included %' . $payment_bonus['bonus_amount'] . ' bonus.', 'ip' => GetIP(), 'date' => date('Y-m-d H:i:s')]);
                } else {
                    $insert = $insert->execute(['c_id' => $payment['client_id'], 'action' => 'New ' . $amount . ' ' . $settings["site_currency"] . ' payment has been made with ' . $method['method_name'], 'ip' => GetIP(), 'date' => date('Y-m-d H:i:s')]);
                }
                if ($settings["alert_newpayment"] == 2) :
                    if ($settings["alert_type"] == 3) : $sendmail = 1;
                        $sendsms = 1;
                    elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                        $sendsms = 0;
                    elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                        $sendsms = 1;
                    endif;
                    if ($sendsms) :
                        SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                    endif;
                    if ($sendmail) :
                        sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                    endif;
                endif;

                if ($update && $balance) {
                    $conn->commit();
                    echo 'OK';
                } else {
                    $conn->rollBack();
                    echo 'NO';
                }
            } else {
                $update = $conn->prepare('UPDATE payments SET payment_status=:payment_status WHERE client_id=:client_id, payment_method=:payment_method, payment_delivery=:payment_delivery, payment_extra=:payment_extra');
                $update = $update->execute(['payment_status' => 2, 'client_id' => $user['client_id'], 'payment_method' => 6, 'payment_delivery' => 1, 'payment_extra' => $_POST['txn_id']]);
            }
        }
    }
    die('IPN OK');

elseif ($method_name == '2checkout') :
    /* Instant Payment Notification */
    $pass = "AABBCCDDEEFF";    /* pass to compute HASH */
    $result = "";                 /* string for compute HASH for received data */
    $return = "";                 /* string to compute HASH for return result */
    $signature = $_POST["HASH"];    /* HASH received */
    $body = "";
    /* read info received */
    ob_start();
    while (list($key, $val) = each($_POST)) {
        $$key = $val;
        /* get values */
        if ($key != "HASH") {
            if (is_array($val)) $result .= ArrayExpand($val);
            else {
                $size = strlen(StripSlashes($val)); /*StripSlashes function to be used only for PHP versions <= PHP 5.3.0, only if the magic_quotes_gpc function is enabled */
                $result .= $size . StripSlashes($val);  /*StripSlashes function to be used only for PHP versions <= PHP 5.3.0, only if the magic_quotes_gpc function is enabled */
            }
        }
    }
    $body = ob_get_contents();
    ob_end_flush();
    $date_return = date("YmdHis");
    $return = strlen($_POST["IPN_PID"][0]) . $_POST["IPN_PID"][0] . strlen($_POST["IPN_PNAME"][0]) . $_POST["IPN_PNAME"][0];
    $return .= strlen($_POST["IPN_DATE"]) . $_POST["IPN_DATE"] . strlen($date_return) . $date_return;
    function ArrayExpand($array)
    {
        $retval = "";
        for ($i = 0; $i < sizeof($array); $i++) {
            $size = strlen(StripSlashes($array[$i]));  /*StripSlashes function to be used only for PHP versions <= PHP 5.3.0, only if the magic_quotes_gpc function is enabled */
            $retval .= $size . StripSlashes($array[$i]);  /*StripSlashes function to be used only for PHP versions <= PHP 5.3.0, only if the magic_quotes_gpc function is enabled */
        }
        return $retval;
    }

    function hmac($key, $data)
    {
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*", md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;
        return md5($k_opad . pack("H*", md5($k_ipad . $data)));
    }

    $hash = hmac($pass, $result); /* HASH for data received */
    $body .= $result . "\r\n\r\nHash: " . $hash . "\r\n\r\nSignature: " . $signature . "\r\n\r\nReturnSTR: " . $return;

    if ($hash == $signature) :
        echo "Verified OK!";
        /* ePayment response */
        $result_hash = hmac($pass, $return);
        echo "<EPAYMENT>" . $date_return . "|" . $result_hash . "</EPAYMENT>";
    endif;

elseif ($method_name == 'paytm') :
    require_once(APPPATH . 'ThirdParty/paytm/encdec_paytm.php');

    $paytmChecksum = "";
    $paramList = array();
    $isValidChecksum = "FALSE";

    $paramList = $_POST;
    $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg


    $isValidChecksum = verifychecksum_e($paramList, $extras['merchant_key'], $paytmChecksum); //will return TRUE or FALSE string.

    if ($isValidChecksum == "TRUE") :
        $getfrompay = $conn->prepare("SELECT * FROM payments WHERE payment_extra=:payment_extra");
        $getfrompay->execute(array("payment_extra" => $_POST['ORDERID']));
        $getfrompay = $getfrompay->fetch(PDO::FETCH_ASSOC);

        $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:client_id");
        $user->execute(array("client_id" => $getfrompay['client_id']));
        $user = $user->fetch(PDO::FETCH_ASSOC);
        if (countRow(['table' => 'payments', 'where' => ['client_id' => $user['client_id'], 'payment_method' => 12, 'payment_status' => 1, 'payment_delivery' => 1, 'payment_extra' => $_POST['ORDERID']]])) {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                $payment = $conn->prepare('SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_extra=:extra ');
                $payment->execute(['extra' => $_POST['ORDERID']]);
                $payment = $payment->fetch(PDO::FETCH_ASSOC);
                $payment_bonus = $conn->prepare('SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1');
                $payment_bonus->execute(['method' => $method['id'], 'from' => $payment['payment_amount']]);
                $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
                if ($payment_bonus) {
                    $amount = $payment['payment_amount'] + (($payment['payment_amount'] * $payment_bonus['bonus_amount']) / 100);
                } else {
                    $amount = $payment['payment_amount'];
                }
                $conn->beginTransaction();

                $update = $conn->prepare('UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery WHERE payment_id=:id ');
                $update = $update->execute(['balance' => $payment['balance'], 'status' => 3, 'delivery' => 2, 'id' => $payment['payment_id']]);

                $balance = $conn->prepare('UPDATE clients SET balance=:balance WHERE client_id=:id ');
                $balance = $balance->execute(['id' => $payment['client_id'], 'balance' => $payment['balance'] + $amount]);

                $insert = $conn->prepare('INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ');
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
                if ($payment_bonus) {
                    $insert = $insert->execute(['c_id' => $payment['client_id'], 'action' => 'New ' . $amount . ' ' . $settings["site_currency"] . ' payment has been made with ' . $method['method_name'] . ' and included %' . $payment_bonus['bonus_amount'] . ' bonus.', 'ip' => GetIP(), 'date' => date('Y-m-d H:i:s')]);
                } else {
                    $insert = $insert->execute(['c_id' => $payment['client_id'], 'action' => 'New ' . $amount . ' ' . $settings["site_currency"] . ' payment has been made with ' . $method['method_name'], 'ip' => GetIP(), 'date' => date('Y-m-d H:i:s')]);
                }
                if ($update && $balance) {
                    $conn->commit();
                    header('location:' . base_url());
                    echo 'OK';
                } else {
                    $conn->rollBack();
                    header('location:' . base_url());
                    echo 'NO';
                }
            } else {
                $update = $conn->prepare('UPDATE payments SET payment_status=:payment_status WHERE client_id=:client_id, payment_method=:payment_method, payment_delivery=:payment_delivery, payment_extra=:payment_extra');
                $update = $update->execute(['payment_status' => 2, 'client_id' => $user['client_id'], 'payment_method' => 12, 'payment_delivery' => 1, 'payment_extra' => $_POST['ORDERID']]);
            }
        }
    endif;

elseif ($method_name == "weepay") :
    ## weepay başla ##
    $apiSecret = $extras["secret_key"];
    $status = $_POST["paymentStatus"];
    $status2 = $_POST["isSuccessful"];
    $code = $_POST["errorCode"];
    $secret = $_POST["secretKey"];
    $order_id = $_GET["token"];

    print_r($_POST);

    if (empty($code) && $status2 = true && $status == true) :
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :
            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;
            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
            // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;
            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
                header("Location:" . base_url());

            else :
                $conn->rollBack();
                echo "NO";
                header("Location:" . base_url());

            endif;
        else :
            echo "NOO";
            header("Location:" . base_url());

        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
        echo "NOOO";
        header("Location:" . base_url());

    endif;
## weepay bitti ##

## payizone başla ##
elseif ($method_name == "payizone") :


    //   $hash = hash("sha256","235|vakyildiz@gmail.com|1RE1KOOBWGWXHMNL|$code|true");
    //  $VerifyHash       = $_POST["VerifyHash"];

    $status = $_POST["status"];
    $resultCode = $_POST["resultCode"];
    $order_id = $_POST["otherCode"];

    $VerifyHash = hash("sha256", $extras['appid'] . "|" . $extras['apiSecret'] . "|" . $order_id . "|true");
    if ($resultCode == 200 && $status == true && $VerifyHash == $_POST['VerifyHash']) :
        echo "STEP 1";
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :
            echo "STEP 2";

            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);
            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;
            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
/*
            if ($settings["referral"] == 2) :
                if (!empty($payment["referral"])) :
                    if ($settings["ref_type"] == 0) :
                        if ($amount < $settings["ref_max"]) :
                            $refbonus = $amount * $settings["ref_bonus"] / 100;
                            $refclient = $conn->prepare("SELECT * FROM clients WHERE id=:id");
                            $refclient->execute(array("id" => $payment["referral"]));
                            $refclient = $refclient->fetch(PDO::FETCH_ASSOC);
                            $update = $conn->prepare("UPDATE clients SET balance=:balance, refchar=:refchar,reforder=:reforder WHERE id=:id ");
                            $update = $update->execute(array("id" => $payment["referral"], "balance" => $refclient["balance"] + $refbonus, "refchar" => $refclient["refchar"] + $refbonus, "reforder" => $refclient["reforder"] + 1));
                        endif;
                    else :
                        $paycontrol = $conn->prepare("SELECT * FROM payments WHERE client_id=:id, payment_status=:status ");
                        $paycontrol->execute(array("id" => $payment["client_id"], "status" => 3));
                        $payon = $paycontrol->rowCount();
                        if (!$payon) :
                            if ($amount < $settings["ref_max"]) :
                                $refbonus = $amount * $settings["ref_bonus"] / 100;
                                $refclient = $conn->prepare("SELECT * FROM clients WHERE id=:id");
                                $refclient->execute(array("id" => $payment["referral"]));
                                $refclient = $refclient->fetch(PDO::FETCH_ASSOC);
                                $update = $conn->prepare("UPDATE clients SET balance=:balance, refchar=:refchar,reforder=:reforder WHERE id=:id ");
                                $update = $update->execute(array("id" => $payment["referral"], "balance" => $refclient["balance"] + $refbonus, "refchar" => $refclient["refchar"] + $refbonus, "reforder" => $refclient["reforder"] + 1));
                            endif;
                        endif;
                    endif;
                endif;
            endif;
*/
                    // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;
            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
                header("Location:" . base_url());

            else :
                $conn->rollBack();
                echo "NO";
                header("Location:" . base_url());

            endif;
        else :
            echo "NOO";
            header("Location:" . base_url());

        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
        echo "NOOO";
        header("Location:" . base_url());

    endif;
## payizone bitti ##

## payhesap başla
elseif ($method_name == "payhesap") :

    if (!$_POST) :
        die("OK");
    endif;

    $post = $_POST;
    $order_id = $_POST['ORDER_REF_NUMBER'];
    $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
    $payment->execute(array("orderid" => $order_id));
    $payment = $payment->fetch(PDO::FETCH_ASSOC);
    $method = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
    $method->execute(array("id" => $payment["payment_method"]));
    $method = $method->fetch(PDO::FETCH_ASSOC);
    $hash = $_POST['HASH'];
    $ch = curl_init('https://www.payhesap.com/api/pay/checkOrder');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('hash' => $hash));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);;
    $result = json_decode(curl_exec($ch), true);

    if ($result['statusID'] == 1) :
        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1, "payment_status" => 1]])) :
            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);

            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;

            $extra = ($_POST);
            $extra = json_encode($extra);
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));
            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));
            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
            // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;

            if ($settings["alert_newpayment"] == 2) :
                if ($settings["alert_type"] == 3) : $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2) : $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1) : $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms) :
                    SMSUser($settings["admin_telephone"], $amount . "Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.");
                endif;
                if ($sendmail) :
                    sendMail(["subject" => "Yeni ödeme alındı.", "body" => $amount . " Tutarında " . $method["method_name"] . " aracılığı ile yeni bir ödeme yapıldı.", "mail" => $settings["admin_mail"]]);
                endif;
            endif;
            if ($update && $balance) :
                $conn->commit();
                echo "OK";
                die;
            else :
                $conn->rollBack();
                echo "OK";
                die;
            endif;
        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
    endif;

    echo "OK";
    die;
## payhesap bitir

elseif ($method_name == "payeer") :
    ## payeer başla ##


    if (isset($_POST['m_operation_id']) && isset($_POST['m_sign'])) :

        $err = false;
        $message = '';

        $order_id = $_POST["m_orderid"];

        $gatewayParams = $extra["mkey"];

        $sign_hash = strtoupper(hash('sha256', implode(":", array(
            $_POST['m_operation_id'],
            $_POST['m_operation_ps'],
            $_POST['m_operation_date'],
            $_POST['m_operation_pay_date'],
            $_POST['m_shop'],
            $_POST['m_orderid'],
            $_POST['m_amount'],
            $_POST['m_curr'],
            $_POST['m_desc'],
            $_POST['m_status'],
            $gatewayParams
        ))));


        if ($_POST["m_sign"] != $sign_hash) :

            $message .= " - do not match the digital signature\n";
            $err = true;
        endif;


        if (!$err && $_POST['m_status'] == "success" && countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :

            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);

            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;

            $extra = ($_POST);
            $extra = json_encode($extra);

            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));

            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));

            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                        // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;

            if ($update && $balance) :
                $conn->commit();
                echo $_POST['m_orderid'] . '|success';
            else :
                $conn->rollBack();
                echo $_POST['m_orderid'] . '|success';
            endif;
        else :
            $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
            $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
            echo $_POST['m_orderid'] . '|error';
        endif;
    endif;

## perfect bitti ##
elseif ($method_name == "paypal") :
    if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)):
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($extras["client"]);
        $gateway->setSecret($extras["secret"]);
        $gateway->setTestMode(true); //set it to 'false' when go live
        $transaction = $gateway->completePurchase(array(
            'payer_id' => $_GET['PayerID'],
            'transactionReference' => $_GET['paymentId'],
        ));
        $response = $transaction->send();
        if ($response->isSuccessful()):
            $arr_body = $response->getData();

            $payment_id = $arr_body['id'];
            $payer_id = $arr_body['payer']['payer_info']['payer_id'];
            $order_id = $payment_id;
            $payer_email = $arr_body['payer']['payer_info']['email'];
            $amount = $arr_body['transactions'][0]['amount']['total'];
            $currency = "USD";
            $payment_status = $arr_body['state'];
            if (!countRow(["table" => "payments", "where" => ["payment_privatecode" => $payment_id]])) :
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_status=:status, payment_delivery=:delivery,payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip ");
                $insert->execute(array("c_id" => $user["client_id"],"status" => 3, "delivery" => 2, "amount" => $amount, "code" => $payment_id, "method" => "19", "mode" => "Otomatik", "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
                $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
                $payment->execute(array("orderid" => $order_id));
                $payment = $payment->fetch(PDO::FETCH_ASSOC);

                $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
                $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
                $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);

                if ($payment_bonus) :
                    $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
                else :
                    $amount = $payment["payment_amount"];
                endif;

                $extra = ($_GET);
                $extra = json_encode($extra);

                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
                $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));

                $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:cid ");
                $balance = $balance->execute(array("cid" => $user["client_id"], "balance" => $user['balance'] + $payment["payment_amount"]));
                if($balance){
                    $conn->commit();
                }
                $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            endif;
        endif;
    endif;
    echo '<script>setInterval(function(){window.location="' . base_url() . '"},500)</script>';
## perfect bitti ##
elseif ($method_name == "perfectmoney") :


    if (!$_POST) header("Location: " . base_url());


    $order_id = $_POST["PAYMENT_ID"];

    $string =
        $_POST['PAYMENT_ID'] . ':' . $_POST['PAYEE_ACCOUNT'] . ':' .
        $_POST['PAYMENT_AMOUNT'] . ':' . $_POST['PAYMENT_UNITS'] . ':' .
        $_POST['PAYMENT_BATCH_NUM'] . ':' .
        $_POST['PAYER_ACCOUNT'] . ':' . strtoupper(md5($extras["pid"])) . ':' .
        $_POST['TIMESTAMPGMT'];


    $hash = strtoupper(md5($string));

    if ($hash == $_POST['V2_HASH']) : // processing payment if only hash is valid

        if (countRow(["table" => "payments", "where" => ["payment_privatecode" => $order_id, "payment_delivery" => 1]])) :

            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_privatecode=:orderid ");
            $payment->execute(array("orderid" => $order_id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);

            $payment_bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_method=:method && bonus_from<=:from ORDER BY bonus_from DESC LIMIT 1 ");
            $payment_bonus->execute(array("method" => $method["id"], "from" => $payment["payment_amount"]));
            $payment_bonus = $payment_bonus->fetch(PDO::FETCH_ASSOC);

            if ($payment_bonus) :
                $amount = ($payment["payment_amount"] + ($payment["payment_amount"] * $payment_bonus["bonus_amount"] / 100));
            else :
                $amount = $payment["payment_amount"];
            endif;

            $extra = ($_POST);
            $extra = json_encode($extra);

            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET client_balance=:balance, payment_status=:status, payment_delivery=:delivery, payment_extra=:extra WHERE payment_id=:id ");
            $update = $update->execute(array("balance" => $payment["balance"], "status" => 3, "delivery" => 2, "extra" => $extra, "id" => $payment["payment_id"]));

            $balance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $balance = $balance->execute(array("id" => $payment["client_id"], "balance" => $payment["balance"] + $amount));

            $insert = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
                // Referans BAŞLANGIÇ
                // Referans BAŞLANGIÇ
                if ($settings['referral'] == 2) {
                    $refok = 0;
                    if ($settings['ref_type'] == 0) {
                        $refok = 1;
                    } else {
                        $payment_model = model('payments');
                    $rowc = $payment_model->where(['payment_status'=>3,'client_id'=> $payment['client_id']])->countAllResults();
                        if ($rowc <= 1) {
                            $refok = 1;
                        }
                    }
                    if ($refok) {
                        $client = model("clients");
                        $cc = $client->where('client_id', $payment["client_id"])->get()->getResultArray()[0];
                        if ($cc['referral'] != Null && $cc['referral'] != "") {
                            $ref_id = $cc['referral'];
                            $oran = $amount * $settings['ref_bonus'] / 100;

                            $this->db->table("referral")->set(array(
                                'client_id' => $ref_id,
                                'refferal' => $payment["client_id"],
                                'action' => $oran,
                                'register_date' => 1
                            ))->insert();
                            $client->protect(false)->set('balance', "balance+{$oran}", false)->set('refchar', "refchar+{$oran}", false)->where('client_id', $ref_id)->update();
                        }
                    }
                }
            // Referans BİTİŞ
            if ($payment_bonus) :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla %" . $payment_bonus["bonus_amount"] . " bonus dahil " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            else :
                $insert = $insert->execute(array("c_id" => $payment["client_id"], "action" => $method["method_name"] . " API aracılığıyla " . $amount . " TL tutarında bakiye yüklendi", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
            endif;

            if ($update && $balance) :
                $conn->commit();
            else :
                $conn->rollBack();
            endif;
        else :
        endif;
    else :
        $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_delivery=:delivery WHERE payment_privatecode=:code  ");
        $update = $update->execute(array("status" => 2, "delivery" => 1, "code" => $order_id));
    endif;


endif;
