<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Payments extends Ana_Controller
{
    function index()
    {
        $page = 1;
        $payments = new \App\Models\payments();

        global $conn;
        global $_SESSION;
        if ($this->request->getGet("search_type") == "username" && $this->request->getGet("search") && countRow(["table" => "clients", "where" => ["username" => $this->request->getGet("search")]])):
            $search_where = $this->request->getGet("search_type");
            $search_word = urldecode($this->request->getGet("search"));
            $db = db_connect();
            $clients = $db->table("clients")->select("client_id")->like("username", $search_word)->get()->getResultArray();
            $id = "(";
            foreach ($clients as $client) {
                $id .= $client["client_id"] . ",";
            }
            if (substr($id, -1) == ","): $id = substr($id, 0, -1); endif;
            $id .= ")";
            $search = " payments.client_id IN " . $id;
            $count = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id = payments.client_id WHERE {$search} && payments.payment_method!='7' && payments.payment_status='3' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search} && payments.payment_method!='7' && payments.payment_status='3' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        else:

            $count = $payments->where("payment_method", 7)->where("payment_status", 3)->countAllResults();
            $search = "WHERE payments.payment_method!='7' && payments.payment_status='3' ";
        endif;
        $to = 50;
        $pageCount = ceil($count / $to);
        if ($page > $pageCount): $page = 1; endif;
        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        if (route(3) == "bank" && route(3)) {
            if (route(4) == "onayla" && route(4)) {
               
                if (route(5)) {
                    $payment_id = $payments->where('payment_id', route(5))->where('payment_status', 1)->orWhere('payment_status', 2)->get()->getResultArray();
                    if (isset($payment_id[0])) {
                        $payment_id = $payment_id[0];
                        $usermodel = new \App\Models\clients();
                        $usermodel->protect(false)->set('balance', "balance + ".$payment_id['payment_amount'],false)->where('client_id', $payment_id['client_id'])->update();
                        $payments->protect(false)
                            ->where('payment_id', route(5))
                            ->set('payment_status', 3)
                            ->set("client_balance","client_balance+".$payment_id['payment_amount'],false)
                            ->set("payment_amount",$payment_id['payment_amount']-2*$payment_id['payment_amount'])
                            ->update();
                    }
                }
            }elseif(route(4) == "iade" && route(4)) {
                if (route(5)) {
                    $payment_id = $payments->where('payment_id', route(5))->where('payment_status', 3)->get()->getResultArray();
                    if (isset($payment_id[0])) {
                        $payment_id = $payment_id[0];
                        $usermodel = new \App\Models\clients();
                        $usermodel->protect(false)->set('balance', "balance + " . $payment_id['payment_amount'], false)->where('client_id', $payment_id['client_id'])->update();
                        $payments->protect(false)
                            ->where('payment_id', route(5))
                            ->set('payment_status', 1)
                            ->set("client_balance", "client_balance+" . $payment_id['payment_amount'], false)
                            ->set("payment_amount", $payment_id['payment_amount'] + 2 * abs($payment_id['payment_amount']))
                            ->update();
                    }
                }
            }elseif(route(4) == "red" && route(4)) {
                if (route(5)) {
                    $payment_id = $payments->protect(false)->where('payment_id', route(5))->where('payment_status!=', 2)->set("payment_status",2)->update();

                }
            }
            $payments = $payments->select("*")
                ->where('payments.payment_method', '7')
                ->join('payment_methods', 'payment_methods.id=payments.payment_method')
                ->join('clients', 'clients.client_id=payments.client_id')->orderBy('payment_id', 'DESC')->get()->getResultArray();

        }elseif( route(3) == "edit-bank" && route(3)){
        $id       = route(4);
        $payment  = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payment_id=:id ");
        $payment -> execute(array("id"=>$id));
        $payment  = $payment->fetch(PDO::FETCH_ASSOC);
        foreach ($_POST as $key => $value) {
          $$key = $value;
        }
        if( empty($bank) ):
          $error    = 1;
          $errorText= "Banka boş olamaz";
          $icon     = "error";
        elseif( empty($status)  && $payment["payment_delivery"] == 1 ):
          $error    = 1;
          $errorText= "Ödeme durumu boş olamaz";
          $icon     = "error";
        else:
          if( $status == "3" && $payment["payment_delivery"] == 1  ):
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_bank=:bank, payment_delivery=:delivery, payment_note=:note, payment_update_date=:date, client_balance=:balance WHERE payment_id=:id ");
            $update = $update->execute(array("id"=>$id,"status"=>3,"delivery"=>2,"bank"=>$bank,"note"=>$note,"date"=>date("Y-m-d H:i:s"),"balance"=>$payment["balance"] ));
            $update2= $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
            $update2= $update2->execute(array("id"=>$payment["client_id"],"balance"=>$payment["payment_amount"]+$payment["balance"] ));
              if( $update2 && $update ):
                $conn->commit();
                $error    = 1;
                $errorText= "İşlem başarılı";
                $icon     = "success";
              else:
                $conn->rollBack();
                $error    = 1;
                $errorText= "İşlem başarısız";
                $icon     = "error";
              endif;
          else:
              if( !$status ): $status = $payment["payment_status"]; endif;
            $update = $conn->prepare("UPDATE payments SET payment_status=:status, payment_bank=:bank, payment_note=:note, payment_update_date=:date WHERE payment_id=:id ");
            $update = $update->execute(array("id"=>$id,"status"=>$status,"bank"=>$bank,"note"=>$note,"date"=>date("Y-m-d H:i:s") ));
            if( $update ):
              $error    = 1;
              $errorText= "İşlem başarılı";
              $icon     = "success";
            else:
              $error    = 1;
              $errorText= "İşlem başarısız";
              $icon     = "error";
            endif;
          endif;
        endif;
        return json_encode(["t"=>"error","m"=>$errorText,"s"=>$icon,"r"=>$referrer]);
        
        } elseif (route(3) == "new-bank" && route(3)) {
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($bank)):
                $error = 1;
                $errorText = "Banka boş olamaz";
                $icon = "error";
            elseif (empty($amount)):
                $error = 1;
                $errorText = "Tutar boş olamaz";
                $icon = "error";
            elseif (!countRow(["table" => "clients", "where" => ["username" => $username]])):
                $error = 1;
                $errorText = "Kullanıcı bulunamadı";
                $icon = "error";
            else:
                $user = $conn->prepare("SELECT * FROM clients WHERE username=:username ");
                $user->execute(array("username" => $username));
                $user = $user->fetch(PDO::FETCH_ASSOC);
                $conn->beginTransaction();
                $insert = $conn->prepare("INSERT INTO payments SET payment_status=:status, payment_mode=:mode, payment_amount=:amount, payment_bank=:bank, payment_method=:method, payment_delivery=:delivery, payment_note=:note, payment_update_date=:date, payment_create_date=:date2, client_id=:client_id, client_balance=:balance ");
                $insert = $insert->execute(array("status" => 3, "delivery" => 2, "bank" => $bank, "mode" => "Manuel", "amount" => $amount, "method" => 7, "note" => $note, "date" => date("Y-m-d H:i:s"), "date2" => date("Y-m-d H:i:s"), "balance" => $user["balance"], "client_id" => $user["client_id"]));
                $update2 = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
                $update2 = $update2->execute(array("id" => $user["client_id"], "balance" => $amount + $user["balance"]));
                if ($update2 && $insert):
                    $conn->commit();
                    $error = 1;
                    $errorText = "İşlem başarılı";
                    $icon = "success";
                    $referrer = site_url("admin/payments/bank");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "İşlem başarısız";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            return 1;
        } elseif (route(3) == "new-online" && route(3)) {
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($method)):
                $error = 1;
                $errorText = "Ödeme yöntemi boş olamaz";
                $icon = "error";
            elseif (empty($amount)):
                $error = 1;
                $errorText = "Tutar boş olamaz";
                $icon = "error";
            elseif ($amount < 0):
                $error = 1;
                $errorText = "Tutar negatif değer alamaz.";
                $icon = "error";
            elseif (!isset($_POST["add-remove"])):
                $error = 1;
                $errorText = "Ekleme veya çıkarma ayarı senin gibi boş olamaz.";
                $icon = "error";
            elseif (!countRow(["table" => "clients", "where" => ["username" => $username]])):
                $error = 1;
                $errorText = "Kullanıcı bulunamadı";
                $icon = "error";
            else:
                $user = $conn->prepare("SELECT * FROM clients WHERE username=:username ");
                $user->execute(array("username" => $username));
                $user = $user->fetch(PDO::FETCH_ASSOC);
                $conn->beginTransaction();
                $insert = $conn->prepare("INSERT INTO payments SET payment_status=:status, payment_mode=:mode, payment_amount=:amount, payment_method=:method, payment_delivery=:delivery, payment_note=:note, payment_update_date=:date, payment_create_date=:date2, client_id=:client_id, client_balance=:balance ");
                $newAmount = null;
                switch ($_POST["add-remove"]) {
                    case "add":
                        $newAmount = $amount;
                        break;

                    case "remove":
                        $newAmount = -$amount;
                        break;

                    default:
                        $newAmount = $amount;
                        break;
                }
                $insert = $insert->execute(array("status" => 3, "delivery" => 2, "mode" => "Manuel", "amount" => $newAmount, "method" => $method, "note" => $note, "date" => date("Y-m-d H:i:s"), "date2" => date("Y-m-d H:i:s"), "balance" => $user["balance"], "client_id" => $user["client_id"]));
                $update2 = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id ");
                $update2 = $update2->execute(array("id" => $user["client_id"], "balance" => $newAmount + $user["balance"]));
                if ($update2 && $insert):
                    $conn->commit();
                    $error = 1;
                    $errorText = "İşlem başarılı";
                    $icon = "success";
                    $referrer = site_url("admin/payments/online");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "İşlem başarısız";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            return 1;
        } elseif (route(3) == "edit-online" && route(3)) {
            $id = route(4);
            $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payment_id=:id ");
            $payment->execute(array("id" => $id));
            $payment = $payment->fetch(PDO::FETCH_ASSOC);
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($method)):
                $error = 1;
                $errorText = "Ödeme yöntemi boş olamaz";
                $icon = "error";
            else:
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE payments SET  payment_method=:method, payment_note=:note, payment_update_date=:date2 WHERE payment_id=:id ");
                $update = $update->execute(array("method" => $method, "note" => $note, "date2" => date("Y-m-d H:i:s"), "id" => $id));
                if ($update):
                    $conn->commit();
                    $error = 1;
                    $errorText = "İşlem başarılı";
                    $icon = "success";
                    $referrer = site_url("admin/payments/online");
                else:
                    $conn->rollBack();
                    $error = 1;
                    $errorText = "İşlem başarısız";
                    $icon = "error";
                endif;
            endif;
            echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
            return 1;
        } else {
            $payments = $payments->select("*")
                ->where('payments.payment_method!=', '7')
                ->where('payments.payment_status', '3')
                ->join('payment_methods', 'payment_methods.id=payments.payment_method')
                ->join('clients', 'clients.client_id=payments.client_id')->orderBy('payment_id', 'DESC')->get()->getResultArray();
        }
        $c = 0;
        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'paginationArr' => $paginationArr,
            "payments" => $payments,
            'search_word' => '',
            'search_where' => 'username',
        );
        //return view('admin/payments', $ayar);
        return view('admin/yeni_admin/payments', $ayar);
    }
}
