<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Logs extends Ana_Controller
{
    function index()
    {
        global $conn;
        global $_SESSION;
        $search_add = "";
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;
        $ayar = array(
            'title' => 'Logs',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
        );
        if (isset($_GET["search_type"]) && $_GET["search_type"] == "username" && $_GET["search"]):
            $search_where = $_GET["search_type"];
            $search_word = urldecode($_GET["search"]);
            $clients = $conn->prepare("SELECT client_id FROM clients WHERE username LIKE '%" . $search_word . "%' ");
            $clients->execute(array());
            $clients = $clients->fetchAll(PDO::FETCH_ASSOC);
            if (isset($clients[0])):
                $id = "(";
                foreach ($clients as $client) {
                    $id .= $client["client_id"] . ",";
                }
                if (substr($id, -1) == ","): $id = substr($id, 0, -1); endif;
                $id .= ")";
                $search = " client_report.client_id IN " . $id;
                $count = $conn->prepare("SELECT * FROM client_report INNER JOIN clients ON clients.client_id=client_report.client_id WHERE {$search} ");
                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search} ";
                $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
            else:
                $search = "WHERE id = 0";
                $count = 0;
            endif;
        elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "action" && $_GET["search"]):
            $search_where = $_GET["search_type"];

            $search_word = urldecode($_GET["search"]);
            $count = $conn->prepare("SELECT * FROM client_report INNER JOIN clients ON clients.client_id=client_report.client_id WHERE client_report.action LIKE '%" . $search_word . "%' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE client_report.action LIKE '%" . $search_word . "%' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        else:
            $count = $conn->prepare("SELECT * FROM client_report INNER JOIN clients ON clients.client_id=client_report.client_id ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "";
        endif;

        $to = 50;
        $pageCount = ceil($count / $to);
        if ($page > $pageCount): $page = 1; endif;
        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        $logs = $conn->prepare("SELECT * FROM client_report INNER JOIN clients ON clients.client_id=client_report.client_id $search ORDER BY client_report.id DESC LIMIT $where,$to ");
        $logs->execute(array());
        $logs = $logs->fetchAll(PDO::FETCH_ASSOC);
        $ayar['logs'] = $logs;
        if (route(3) == "delete" && route(3)):
            $id = route(4);
            $delete = $conn->prepare("DELETE FROM client_report WHERE id=:id ");
            $delete->execute(array("id" => $id));
            header("Location:" . base_url("admin/logs"));
        elseif (route(3) == "multi-action" && route(3)):
            $logs = $_POST["log"];
            $action = $_POST["bulkStatus"];
            foreach ($logs as $id => $value):
                $delete = $conn->prepare("DELETE FROM client_report WHERE id=:id ");
                $delete->execute(array("id" => $id));
            endforeach;
            header("Location:" . base_url("admin/logs"));
        endif;

        return view("admin/yeni_admin/logs", $ayar);
        return view("admin/logs", $ayar);
    }

    function provider()
    {
        global $conn;
        global $_SESSION;
        $search_add = "";
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;
        $ayar = array(
            'title' => 'Logs',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
        );
        $logs = $conn->prepare("SELECT * FROM serviceapi_alert ORDER BY id DESC ");
        $logs->execute(array());
        $logs = $logs->fetchAll(PDO::FETCH_ASSOC);
        $ayar['logs'] = $logs;

        if (route(3) == "delete" && route(3)):
            $id = route(4);
            $delete = $conn->prepare("DELETE FROM serviceapi_alert WHERE id=:id ");
            $delete->execute(array("id" => $id));
            header("Location:" . base_url("admin"));
        elseif (route(3) == "multi-action" && route(3)):
            $logs = $_POST["log"];
            $action = $_POST["bulkStatus"];
            foreach ($logs as $id => $value):
                $delete = $conn->prepare("DELETE FROM serviceapi_alert WHERE id=:id ");
                $delete->execute(array("id" => $id));
            endforeach;
            header("Location:" . base_url("admin/provider_logs"));
        endif;

        return view("admin/yeni_admin/provider_logs", $ayar);
    }

    function guard()
    {
        global $conn;
        global $_SESSION;
        $search_add = "";
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;
        $ayar = array(
            'title' => 'Logs',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => 'username',
        );
        $logs = $conn->prepare("SELECT * FROM guard_log INNER JOIN clients ON clients.client_id=guard_log.client_id ORDER BY guard_log.id DESC ");
        $logs->execute(array());
        $logs = $logs->fetchAll(PDO::FETCH_ASSOC);
        $ayar['logs'] = $logs;
        if (route(3) == "delete" && route(3)):
            $id = route(4);
            $delete = $conn->prepare("DELETE FROM guard_log WHERE id=:id ");
            $delete->execute(array("id" => $id));
            header("Location:" . base_url("admin"));
        elseif (route(3) == "multi-action" && route(3)):
            $logs = $_POST["log"];
            $action = $_POST["bulkStatus"];
            foreach ($logs as $id => $value):
                $delete = $conn->prepare("DELETE FROM guard_log WHERE id=:id ");
                $delete->execute(array("id" => $id));
            endforeach;
            header("Location:" . base_url("admin/guard_logs"));
        endif;

        return view("admin/yeni_admin/guard_logs", $ayar);
    }

    function tasks()
    {
        global $conn;
        global $_SESSION;
        $settings = $this->settings;
        include APPPATH . 'ThirdParty/dil/tr.php';
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $user = $this->getuser;
        $smmapi = new SMMApi();
        $search = "";
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;
        $errorText = 0;
        $error = 0;
        if (route(3) && route(3) == "no") {

            $id = route(4);

            $update = $conn->prepare("UPDATE tasks SET task_status=:status WHERE task_id=:id");
            $update = $update->execute(array("status" => 'canceled', "id" => $id));

            header("Location:" . base_url("admin/tasks"));

        } elseif (route(3) == "success" && route(3)) {

            $id = route(4);

            if ($settings["auto_refill"] != 2):
                $update = $conn->prepare("UPDATE tasks SET task_status=:status WHERE task_id=:id");
                $update = $update->execute(array("status" => 'success', "id" => $id));
            else:
                $smmapi = new SMMApi();

                $order = $conn->prepare("SELECT * FROM tasks LEFT JOIN services ON services.service_id = tasks.service_id LEFT JOIN orders ON orders.order_id = tasks.order_id LEFT JOIN service_api ON services.service_api = service_api.id WHERE tasks.task_id=:id ");
                $order->execute(array("id" => $id));
                $order = $order->fetch(PDO::FETCH_ASSOC);
                if (isset($order['api_key'])):
                    $send_refill = $smmapi->action(array('key' => $order["api_key"], 'action' => 'refill', 'order' => $order["api_orderid"]), $order["api_url"]);

                    if (@$send_refill->refill):
                        $success = 1;
                        $successText = "Refill talebiniz sağlayıcınıza gönderilmiştir.";
                        $r_id = $send_refill->refill;
                        $update = $conn->prepare("UPDATE tasks SET task_status=:status, refill_orderid=:r_id WHERE task_id=:id");
                        $update = $update->execute(array("status" => 'success', "id" => $id, "r_id" => $r_id));
                    else:
                        $send_refill = json_encode($send_refill, true);
                        $error = 1;
                        $errorText = "Refill talebiniz gönderilemedi. <code>" . $send_refill . "</code>";
                    endif;
                endif;
            endif;

        } elseif (route(3) && route(3) == "canceled" && route(3)) {

            $id = route(4);

            $order = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
            $order->execute(array("id" => $id));
            $order = $order->fetch(PDO::FETCH_ASSOC);

            $balance = $order["balance"] + $order["order_charge"];
            $spent = $order["spent"] - $order["order_charge"];
            $order["order_quantity"] = $order["order_quantity"];

            $update = $conn->prepare("UPDATE orders SET api_charge=:api_charge, order_profit=:order_profit, order_status=:status, order_error=:error, order_charge=:price, order_quantity=:quantity, order_remains=:remains WHERE order_id=:id ");
            $update = $update->execute(array("api_charge" => 0, "order_profit" => 0, "status" => "canceled", "price" => 0, "error" => "-", "quantity" => 0, "remains" => $order["order_quantity"], "id" => $id));

            $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
            $update2 = $update2->execute(array("id" => $order["client_id"], "balance" => $balance, "spent" => $spent));

            $update3 = $conn->prepare("UPDATE tasks SET task_status=:status WHERE order_id=:id");
            $update3 = $update3->execute(array("status" => 'success', "id" => $id));

            header("Location:" . base_url("admin/tasks"));

        }

        if (isset($_GET["search_type"]) && $_GET["search_type"] == "order_id" && $_GET["search"]):
            $search_where = $_GET["search_type"];
            $search_word = urldecode($_GET["search"]);

            $search = "WHERE tasks.order_id LIKE '%" . $search_word . "%' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        endif;

        $count = $conn->prepare("SELECT * FROM tasks");
        $count->execute(array());
        $count = $count->rowCount();
        $to = 50;
        $pageCount = ceil($count / $to);

        if ($page > $pageCount):
            $page = 1;
        endif;

        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        $orders = $conn->prepare("SELECT * FROM tasks LEFT JOIN clients ON clients.client_id=tasks.client_id LEFT JOIN orders ON orders.order_id=tasks.order_id LEFT JOIN services ON services.service_id=tasks.service_id $search ORDER BY tasks.task_id DESC LIMIT $where,$to ");
        $orders->execute(array());
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);

        $ayar = array(
            'user' => $user,
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => $error,
            'errorText' => $errorText,
            'search_word' => isset($search_word) ? $search_word : "",
            'search_where' => isset($search_where) ? $search_where : "",
            'orders' => isset($orders) ? $orders : 0,
        );
        return view('admin/yeni_admin/tasks', $ayar);
    }
}