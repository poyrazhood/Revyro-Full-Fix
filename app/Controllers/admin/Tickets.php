<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use App\Models\ticket_reply;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Tickets extends Ana_Controller
{
    function index()
    {
        global $conn;
        global $_SESSION;
        $tickets = new \App\Models\tickets();

        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $c = 0;
        $page = 1;
        if (!route(3)):
            $page = 1;
        elseif (is_numeric(route(3)) && route(3)):
            $page = route(3);
        endif;
        if (route(3)):
            $action = route(3);
        endif;
        $count = 1;

        if ($this->request->getPost('action') && $this->request->getPost('action') == "addTicket") {
            $add = $this->request->getPost('action');
            $url = $this->request->getPost('api');
            $key = $this->request->getPost('key');
            $subject = $this->request->getPost('subject');
            $message = $this->request->getPost('message');
            $smmapi = new SMMApi();
            $ticket_add = $smmapi->action(array(
                'key' => $key,
                'action' => $add,
                'subject' => $subject,
                'message' => $message,

            ), $url);
            echo json_encode($ticket_add);
            return 1;
        }
        if ($this->request->getPost('action') && $this->request->getPost('action') == "replyTicket") {
            $add = $this->request->getPost('action');
            $url = $this->request->getPost('api');
            $key = $this->request->getPost('key');
            $message = $this->request->getPost('message');
            $id = $this->request->getPost('id');
            $smmapi = new SMMApi();
            $ticket_add = $smmapi->action(array(
                'key' => $key,
                'action' => $add,
                'id' => $id,
                'message' => $message,

            ), $url);
            echo json_encode($ticket_add);
            return 1;
        }
        if (1):
            $search = "";
            if (isset($_GET["search"]) && $_GET["search"] == "unread" && $_GET["search"]):
                $search = "client_new='2'";

                $count = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id WHERE {$search}");
                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search}";
                $search_link = "?search=unread";
            elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "client" && $_GET["search_type"] && countRow(["table" => "clients", "where" => ["username" => $_GET["search"]]])):
                $search_where = $_GET["search_type"];
                $search_word = urldecode($_GET["search"]);
                $clients = $conn->prepare("SELECT client_id FROM clients WHERE username LIKE '%" . $search_word . "%' ");
                $clients->execute(array());
                $clients = $clients->fetchAll(PDO::FETCH_ASSOC);
                $id = "(";
                foreach ($clients as $client) {
                    $id .= $client["client_id"] . ",";
                }
                if (substr($id, -1) == ","): $id = substr($id, 0, -1); endif;
                $id .= ")";
                $search = " tickets.client_id IN " . $id;
                $count = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id WHERE {$search}");
                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search}";
                $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
            elseif (isset($_GET["status"]) && $_GET["status"]):
                $search = " status='" . $_GET["status"] . "' ";
                $count = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id WHERE {$search}");
                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search}";
                $search_link = "?status=" . $_GET["status"];
            elseif (isset($_GET["search"]) && $_GET["search"] && countRow(["table" => "clients", "where" => ["username" => $_GET["search"]]])):
                $search_where = $_GET["search_type"];
                $search_word = urldecode($_GET["search"]);
                $search = $search_where . " LIKE '%" . $search_word . "%'";
                $count = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id WHERE {$search}");
                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search}";
                $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
            else:
                $count = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id");
                $count->execute(array());
                $count = $count->rowCount();
            endif;
            $to = 50;
            $pageCount = ceil($count / $to);
            if ($page > $pageCount): $page = 1; endif;
            $where = ($page * $to) - $to;
            $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
            $tickets = $conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id $search ORDER BY FIELD(status, 'pending', 'answered', 'closed'),lastupdate_time DESC LIMIT $where,$to ");
            $tickets->execute(array());
            $tickets = $tickets->fetchAll(PDO::FETCH_ASSOC);
        endif;
        if (empty($action)):
            $x = "";
        elseif (route(3) == "read" && route(3)):
            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            if ($_POST):
                $message = $_POST["message"];
                if (strlen($message) < 3):
                    $error = 1;
                    $errorText = "Mesajınız en az 3 karakterden oluşmalı";
                else:
                    $conn->beginTransaction();
                    $update = $conn->prepare("UPDATE tickets SET canmessage=:canmessage, status=:status, lastupdate_time=:time, support_new=:new WHERE ticket_id=:t_id ");
                    $update = $update->execute(array("t_id" => route(4), "time" => date("Y.m.d H:i:s"), "status" => "answered", "canmessage" => 2, "new" => 2));
                    $insert = $conn->prepare("INSERT INTO ticket_reply SET ticket_id=:t_id, time=:time, support=:support, message=:message ");
                    $insert = $insert->execute(array("t_id" => route(4), "time" => date("Y.m.d H:i:s"), "support" => 2, "message" => $message));
                    if ($insert && $update):
                        $conn->commit();
                        header("Location:" . base_url("admin/tickets/read/" . route(4)));
                        $_SESSION["client"]["data"]["success"] = 1;
                        $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
                    else:
                        $conn->rollBack();
                        header("Location:" . base_url("admin/tickets/read/" . route(4)));
                        $_SESSION["client"]["data"]["error"] = 1;
                        $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
                    endif;
                endif;
            endif;
            $update = $conn->prepare("UPDATE tickets SET client_new=:new WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "new" => 1));
            $ticketMessage = $conn->prepare("SELECT ticket_reply.*,tickets.subject,tickets.client_new,tickets.support_new,tickets.status,tickets.canmessage,tickets.client_id,clients.username  FROM ticket_reply INNER JOIN tickets ON ticket_reply.ticket_id = tickets.ticket_id INNER JOIN clients ON clients.client_id = tickets.client_id WHERE ticket_reply.ticket_id=:t_id ORDER BY ticket_reply.id DESC");
            $ticketMessage->execute(array("t_id" => route(4)));
            $ticketMessage = $ticketMessage->fetchAll(PDO::FETCH_ASSOC);
        elseif (route(3) == "unread" && route(3)):
            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            $update = $conn->prepare("UPDATE tickets SET client_new=:new WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "new" => 2));
            if ($update):
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
        elseif (route(3) == "lock" && route(3)):
            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            $update = $conn->prepare("UPDATE tickets SET canmessage=:can, client_new=:new WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "can" => 1, "new" => 1));
            if ($update):
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
        elseif (route(3) == "unlock" && route(3)):
            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            $update = $conn->prepare("UPDATE tickets SET canmessage=:can WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "can" => 2,));
            if ($update):
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
        elseif (route(3) == "readed" && route(3)):
            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            $update = $conn->prepare("UPDATE tickets SET client_new=:new WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "new" => 1,));
            if ($update):
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
        elseif (route(3) == "close" && route(3)):

            if (!countRow(["table" => "tickets", "where" => ["ticket_id" => route(4)]])): header("Location:" . base_url("admin/tickets"));
                exit(); endif;
            $update = $conn->prepare("UPDATE tickets SET status=:status, client_new=:new WHERE ticket_id=:t_id ");
            $update->execute(array("t_id" => route(4), "status" => "closed", "new" => 1));
            if ($update):
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["success"] = 1;
                $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";
            else:
                header("Location:" . base_url("admin/tickets"));
                $_SESSION["client"]["data"]["error"] = 1;
                $_SESSION["client"]["data"]["errorText"] = "İşlem başarısız";
            endif;
        elseif ($action == "multi-action" && $action):
            $tickets = $_POST["ticket"];
            $action = $_POST["bulkStatus"];
            if ($action == "unread"):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET client_new=:new WHERE ticket_id=:id ");
                    $update->execute(array("new" => 2, "id" => $id));
                endforeach;
            elseif ($action == "readed" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET client_new=:new WHERE ticket_id=:id ");
                    $update->execute(array("id" => $id, "new" => 1));
                endforeach;
            elseif ($action == "lock" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET canmessage=:can, client_new=:new WHERE ticket_id=:id ");
                    $update->execute(array("can" => 1, "id" => $id, "new" => 1));
                endforeach;
            elseif ($action == "unlock" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET canmessage=:can WHERE ticket_id=:id ");
                    $update->execute(array("can" => 2, "id" => $id,));
                endforeach;
            elseif ($action == "close" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET status=:status, canmessage=:can, client_new=:new WHERE ticket_id=:id ");
                    $update->execute(array("status" => "closed", "id" => $id, "can" => 2, "new" => 1));

                endforeach;
            elseif ($action == "pending" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET status=:status, canmessage=:can, WHERE ticket_id=:id ");
                    $update->execute(array("status" => "pending", "id" => $id, "can" => 2));
                endforeach;
            elseif ($action == "answered" && $action):
                foreach ($tickets as $id => $value):
                    $update = $conn->prepare("UPDATE tickets SET status=:status, canmessage=:can, client_new=:new WHERE ticket_id=:id ");
                    $update->execute(array("status" => "answered", "id" => $id, "can" => 2, "new" => 1));
                endforeach;
            endif;
            header("Location:" . base_url("admin/tickets"));
        elseif ($action == "new" && $action):
            if ($_POST):
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }
                $userRow = $conn->prepare("SELECT * FROM clients WHERE username=:username ");
                $userRow->execute(array("username" => $username));
                $userDetail = $userRow->fetch(PDO::FETCH_ASSOC);
                if (!$userRow->rowCount()):
                    $error = 1;
                    $errorText = "Kullanıcı bulunamadı";
                    $icon = "error";
                elseif (empty($subject)):
                    $error = 1;
                    $errorText = "Konu boş olamaz";
                    $icon = "error";
                elseif (empty($message)):
                    $error = 1;
                    $errorText = "Mesaj boş olamaz";
                    $icon = "error";
                else:
                    $conn->beginTransaction();
                    $insert = $conn->prepare("INSERT INTO tickets SET client_id=:c_id, subject=:subject, support_new=:support_new, client_new=:client_new, time=:time, lastupdate_time=:last_time ");
                    $insert = $insert->execute(array("c_id" => $userDetail["client_id"], "subject" => $subject, "support_new" => 2, "client_new" => 1, "time" => date("Y.m.d H:i:s"), "last_time" => date("Y.m.d H:i:s")));
                    if ($insert) {
                        $ticket_id = $conn->lastInsertId();
                    }
                    $insert2 = $conn->prepare("INSERT INTO ticket_reply SET ticket_id=:t_id, client_id=:c_id, support=:support, message=:message, time=:time ");
                    $insert2 = $insert2->execute(array("t_id" => $ticket_id, "c_id" => $userDetail["client_id"], "support" => 2, "message" => $message, "time" => date("Y.m.d H:i:s")));
                    if ($insert && $insert2):
                        $conn->commit();
                        $referrer = base_url("admin/tickets");
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
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon]);
                return 1;
            endif;
        elseif ($action == "edit" && $action):
            if ($_POST):
                foreach ($_POST as $key => $value) {
                    $$key = $value;
                }

                if (empty($description)):
                    $error = 1;
                    $errorText = "Mesaj boş olamaz";
                    $icon = "error";
                else:
                    $conn->beginTransaction();

                    $update = $conn->prepare("UPDATE ticket_reply SET message=:message WHERE id=:id ");
                    $update->execute(array("id" => route(4), "message" => $description));

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
                endif;
                $referrer = base_url("admin/tickets");
                echo json_encode(["t" => "error", "m" => $errorText, "s" => $icon, "r" => $referrer]);
                return 1;
            endif;
        endif;

        if (route(3) == "delete" && route(3)):
            $id = route(4);
            $delete = $conn->prepare("DELETE FROM ticket_reply WHERE id=:id ");
            $delete->execute(array("id" => $id));
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";

            header("Location:" . base_url("admin/tickets/"));
        endif;
        if (route(3) == "close" && route(3)):

            $id = route(4);

            $delete = $conn->prepare("DELETE FROM tickets WHERE ticket_id=:id ");
            $delete->execute(array("id" => $id));
            $_SESSION["client"]["data"]["success"] = 1;
            $_SESSION["client"]["data"]["successText"] = "İşlem başarılı";

            header("Location:" . base_url("admin/tickets/"));
        endif;
        $to = 50;
        $pageCount = ceil($count / $to);
        if ($page > $pageCount): $page = 1; endif;
        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        //$tickets = $tickets->join("clients", "clients.client_id = tickets.client_id")->get()->getResultArray();
        //$conn->prepare("SELECT * FROM tickets INNER JOIN clients ON clients.client_id = tickets.client_id $search ORDER BY FIELD(status, 'pending', 'answered', 'closed'),lastupdate_time DESC LIMIT $where,$to ");
        //$tickets->execute(array());
        //$tickets = $tickets->fetchAll(PDO::FETCH_ASSOC);

        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'tickets' => $tickets,
            'search_where' => 'client',
            "paginationArr" => $paginationArr,
            'search_read' => $this->request->getGet("search") ? $this->request->getGet("search") : "",
        );

        //return view('admin/tickets', $ayar);
        return view('admin/yeni_admin/tickets', $ayar);
    }

    function read()
    {
        global $conn;
        if (!countRow(["table" => "tickets", "where" => ["ticket_id" => $this->request->uri->getSegment(4)]])): header("Location:" . base_url("admin/tickets"));
            exit(); endif;
        $tickets = new ticket_reply();
        $ready = new \App\Models\ticket_ready();
        $ticket_ready = $ready->get()->getResultArray();
        $services_api = new service_api();
        $services_apis = $services_api->get()->getResultArray();
        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'ready' => $ticket_ready,
            'services_api' => $services_apis,
            'search_where' => 'username',
            'search_read' => $this->request->getGet("search") ? $this->request->getGet("search") : "",
        );
        if ($_POST):
            if ($this->request->getPost('durum')) {
                $id = $this->request->getPost('id');
                $durum = $this->request->getPost('durum');
                $tickets = new \App\Models\tickets();
                $duzenle = $tickets->set('status', $durum)->where('ticket_id', $id)->update();
                echo json_encode(["t" => "error", "m" => "okey", "s" => "x"]);
                return 1;
            }
            if ($this->request->getPost('kilit')) {
                $id = $this->request->getPost('id');
                $kilit = $this->request->getPost('kilit');
                $tickets = new \App\Models\tickets();
                if ($kilit == "on") {

                    $duzenle = $tickets->set('canmessage', '1')->where('ticket_id', $id)->update();
                } else {
                    $duzenle = $tickets->set('canmessage', '2')->where('ticket_id', $id)->update();

                }
                echo json_encode(["t" => "error", "m" => "okey", "s" => "x"]);
                return 1;
            }
            $message = $_POST["message"];
            if (strlen($message) < 3):
                $ayar['error'] = 1;
                $ayar['errorText'] = "Mesajınız en az 3 karakterden oluşmalı";
            else:
                $conn->beginTransaction();
                $update = $conn->prepare("UPDATE tickets SET canmessage=:canmessage, status=:status, lastupdate_time=:time, support_new=:new WHERE ticket_id=:t_id ");
                $update = $update->execute(array("t_id" => $this->request->uri->getSegment(4), "time" => date("Y.m.d H:i:s"), "status" => "answered", "canmessage" => 2, "new" => 2));
                $insert = $conn->prepare("INSERT INTO ticket_reply SET ticket_id=:t_id, time=:time, support=:support, message=:message ");
                $insert = $insert->execute(array("t_id" => $this->request->uri->getSegment(4), "time" => date("Y.m.d H:i:s"), "support" => 2, "message" => $message));
                if ($insert && $update):
                    $conn->commit();
                    /********************************************************************************/
                    $ticket_model = model('tickets');
                        $find_ticket = $ticket_model->where("ticket_id",$this->request->uri->getSegment(4))->get()->getResultArray()[0];
                        $client_model = model('clients');
                        $find_client = $client_model->where('client_id',$find_ticket['client_id'])->get()->getResultArray()[0];
                        
                        $send = sendMail(["subject"=>"Destek Talebin Cevaplandı.","body"=>"Merhaba ".$find_client['username'].",<br>Destek Talebini Cevapladık<br>Talebi görmek için aşağıdaki bağlantıya tıklayabilirsin.<br>".base_url("tickets/".$this->request->uri->getSegment(4)),"mail"=>$find_client["email"]]);
                       
                    /********************************************************************************/
                    header("Location:" . base_url("admin/tickets/read/" . $this->request->uri->getSegment(4)));
                    $ayar["success"] = 1;
                    $ayar["successText"] = "İşlem başarılı";
                else:
                    $conn->rollBack();
                    header("Location:" . base_url("admin/tickets/read/" . $this->request->uri->getSegment(4)));
                    $ayar["error"] = 1;
                    $ayar["errorText"] = "İşlem başarısız";
                endif;
            endif;
        endif;

        $ticketMessage = $tickets->select("ticket_reply.*,tickets.panel_id,tickets.subject,tickets.lastupdate_time,tickets.client_new,tickets.support_new,tickets.status,tickets.canmessage,tickets.client_id,clients.spent,clients.username,clients.balance")->join("tickets", "ticket_reply.ticket_id = tickets.ticket_id")->join("clients", "clients.client_id = tickets.client_id")->where("ticket_reply.ticket_id", $this->request->uri->getSegment(4))->orderBy('ticket_reply.id', "DESC")->get()->getResultArray();
        $ayar['ticketMessage'] = $ticketMessage;
        $orders = new \App\Models\orders();
        $total_order = $orders->protect(false)->where('client_id', $ticketMessage[0]['client_id'])->countAllResults();
        $ayar['total_order'] = $total_order;

        //return view('admin/tickets_read', $ayar);
        return view('admin/yeni_admin/tickets_read', $ayar);

    }

    function ajax()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        if ($this->request->isAJAX()) {
            if ($this->request->getPost('ticket')) {
                $ticket = new \App\Models\tickets();
                $ticket_count = $ticket
                    ->where('status', 'pending')
                    ->countAllResults();
                if ($ticket_count) {
                    $result = json_encode(array(
                        'status_code' => 202,
                        'support_new' => 1
                    ));
                    return $this->response->setStatusCode(202)->setBody($result);
                } else {
                    $result = json_encode(array(
                        'status_code' => 202,
                        'support_new' => 0
                    ));
                    return $this->response->setStatusCode(202)->setBody($result);
                }
            } else {

                $result = json_encode(array(
                    'status_code' => 400,
                    'support_new' => 0
                ));
                return $this->response->setStatusCode(400)->setBody($result);
            }
        } else {
            $result = json_encode(array(
                'status_code' => 400,
                'support_new' => 0
            ));
            return $this->response->setStatusCode(400)->setBody($result);

        }
    }

    function saglayici_ajax()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        if ($this->request->getPost('ticket_id') && $this->request->getPost('current_id')):
            $t_id = $this->request->getPost('ticket_id');
            $c_id = $this->request->getPost('current_id');
            $saglayici_adres = $this->request->getPost('saglayici_adres');

            $ticket = new \App\Models\tickets();
            $cek = $ticket->where('ticket_id', $c_id)->get()->getResultArray()[0];
            if (isset($cek['ticket_id'])):
                $saglayicilar = json_decode($cek['panel_id'], true);
                $saglayicilar[$saglayici_adres] = $t_id;
            endif;
            $ticket->protect(false)->where('ticket_id', $c_id)->set('panel_id', json_encode($saglayicilar))->update();
            $result = json_encode(array(
                'status' => 'success',
                'completed' => 'Başarıyla Eklendi',
            ));
            return $this->response->setStatusCode(202)->setBody($result);
        endif;
    }

    function ready()
    {
        if ($this->request->getPost('content') && $this->request->getPost('title')) {
            $icerik = $this->request->getPost('content');
            $title = $this->request->getPost('title');
            $ready = new \App\Models\ticket_ready();
            $ready->save(array(
                'content' => $icerik,
                'title' => $title
            ));
            echo json_encode(["t" => "error", "m" => "okey", "s" => "x"]);
            return 1;
        }
    }

    function getReady()
    {
        if ($this->request->getPost('id')) {

            $id = $this->request->getPost('id');
            if ($id != "-1") {
                $ready = new \App\Models\ticket_ready();
                $content = $ready->where('id', $id)->get()->getResultArray()[0];
                echo json_encode(["status" => "200", "content" => $content['content']]);
                return 1;
            }
        }
    }

    function deleteReady()
    {

        if ($this->request->getPost('id')) {

            $id = $this->request->getPost('id');
            if ($id != "-1") {
                $ready = new \App\Models\ticket_ready();
                $ready->delete(['id' => $id]);
                echo json_encode(["status" => "200", "content" => "Başarıyla Silindi"]);

                return 1;
            }
        }
    }
}