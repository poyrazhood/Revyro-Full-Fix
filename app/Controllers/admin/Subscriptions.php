<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use PDO;
use SMMApi;

class Subscriptions extends Ana_Controller
{

    function index()
    {
        global $conn;
        global $_SESSION;
        $referrer = "";
        include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
        $settings = $this->settings;
        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;
        $search_where = "";
        $paginationArr = array('count' => 1);
        $statusList = ["all", "active", "paused", "completed", "canceled", "expired", "limit"];
        if (route(4) && in_array(route(4), $statusList)):
            $status = route(4);
        elseif (!route(4) || !in_array(route(4), $statusList)):
            $status = "all";
        endif;

        if (isset($_GET["search_type"]) && $_GET["search_type"] == "username" && $_GET["search"]):
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
            if ($id != "()"):
                $search = " orders.client_id IN " . $id;
                $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE {$search} && orders.dripfeed='1' && orders.subscriptions_type='2' ");

                $count->execute(array());
                $count = $count->rowCount();
                $search = "WHERE {$search} && orders.dripfeed='1' && orders.subscriptions_type='2' ";
                $search_link = "?search=" . $search_word . "&search_type=" . $search_where;

            else:
                $search = "WHERE orders.dripfeed='1' && orders.subscriptions_type='2' and orders.client_id = '-1'";
                $count = 0;
            endif;
        elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "order_id" && $_GET["search"]):
            $search_where = $_GET["search_type"];
            $search_word = urldecode($_GET["search"]);
            $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='2' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE orders.order_id LIKE '%" . $search_word . "%'  && orders.dripfeed='1' && orders.subscriptions_type='2' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "order_url" && $_GET["search"]):
            $search_where = $_GET["search_type"];
            $search_word = urldecode($_GET["search"]);
            $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_url LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='2' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE orders.order_url LIKE '%" . $search_word . "%'  && orders.dripfeed='1' && orders.subscriptions_type='2' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        elseif (isset($_GET["subscription"]) && $_GET["subscription"]):
            $subs_id = $_GET["subscription"];
            $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='2' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE orders.subscriptions_id='$subs_id'  && orders.dripfeed='1' && orders.subscriptions_type='2' ";
            $search_link = "?subscription=" . $_GET["subscription"];
        elseif (isset($_GET["dripfeed"]) && $_GET["dripfeed"]):
            $drip_id = $_GET["dripfeed"];
            $count = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='1' && orders.subscriptions_type='2' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE orders.dripfeed_id='$drip_id'  && orders.dripfeed='1' && orders.subscriptions_type='2' ";
            $search_link = "?dripfeed=" . $_GET["subscription"];
        elseif ($status != "all"):
            $count = $conn->prepare("SELECT * FROM orders WHERE subscriptions_status=:status && dripfeed=:dripfeed && subscriptions_type=:sub ");
            $count->execute(array("dripfeed" => 1, "sub" => 2, "status" => $status));
            $count = $count->rowCount();
            $search = "WHERE orders.subscriptions_status='" . $status . "' && orders.dripfeed='1' && orders.subscriptions_type='2' ";
        elseif ($status == "all"):
            $count = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub ");
            $count->execute(array("dripfeed" => 1, "sub" => 2));
            $count = $count->rowCount();
            $search = "WHERE orders.dripfeed='1' && orders.subscriptions_type='2' ";
        endif;
        $to = 50;
        $pageCount = ceil($count / $to);
        if ($page > $pageCount): $page = 1; endif;
        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        $orders = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id=orders.client_id LEFT JOIN services ON services.service_id=orders.service_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
        $orders->execute(array());
        $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
        function orderStatu($statu)
        {

            switch ($statu) {
                case 'limit':
                    $statu = "Süreli abonelik";
                    break;
                case 'active':
                    $statu = "Aktif";
                    break;
                case 'completed':
                    $statu = "Tamamlanan";
                    break;
                case 'paused':
                    $statu = "Durdurulmuş";
                    break;
                case 'canceled':
                    $statu = "İptal";
                    break;
                case 'processing':
                    $statu = "Gönderim Sırasında";
                    break;
                case 'expired':
                    $statu = "Süresi dolmuş";
                    break;
            }

            return $statu;
        }

        if ($_POST):

            if (route(3) == "set_expiry"):
                $id = route(4);
                $expiry = date("Y-m-d", strtotime(str_replace("/", "-", $_POST["expiry"])));
                $update = $conn->prepare("UPDATE orders SET subscriptions_expiry=:expiry WHERE order_id=:id && subscriptions_status!=:status ");
                $update->execute(array("id" => $id, "expiry" => $expiry, "status" => "limit"));
                header("Location:" . base_url("admin/subscriptions"));
            elseif (route(3) == "multi-action"):
                $orders = $_POST["order"];
                $action = $_POST["bulkStatus"];
                if ($action == "paused"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id  && subscriptions_status!=:not");
                        $update->execute(array("status" => "paused", "id" => $id, "not" => "limit"));
                    endforeach;
                elseif ($action == "completed"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not  ");
                        $update->execute(array("status" => "completed", "id" => $id, "not" => "limit"));
                    endforeach;
                elseif ($action == "active"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not ");
                        $update->execute(array("status" => "active", "id" => $id, "not" => "limit"));
                    endforeach;
                elseif ($action == "canceled"):
                    foreach ($orders as $id => $value):
                        $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not ");
                        $update->execute(array("status" => "canceled", "id" => $id, "not" => "limit"));
                    endforeach;
                endif;
                header("Location:" . base_url("admin/subscriptions"));
            endif;
            exit();
        endif;

        // require admin_view('subscriptions');

        if (route(3) == "subscriptions_pause" && route(3)):
            $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id  && subscriptions_status!=:not");
            $update->execute(array("status" => "paused", "id" => route(4), "not" => "limit"));
            header("Location:" . base_url("admin/subscriptions"));
        elseif (route(3) == "subscriptions_complete" && route(3)):
            $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not  ");
            $update->execute(array("status" => "completed", "id" => route(4), "not" => "limit"));
            header("Location:" . base_url("admin/subscriptions"));
        elseif (route(3) == "subscriptions_active" && route(3)):
            $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not ");
            $update->execute(array("status" => "active", "id" => route(4), "not" => "limit"));
            header("Location:" . base_url("admin/subscriptions"));
        elseif (route(3) == "subscriptions_canceled" && route(3)):
            $update = $conn->prepare("UPDATE orders SET subscriptions_status=:status WHERE order_id=:id && subscriptions_status!=:not ");
            $update->execute(array("status" => "canceled", "id" => route(4), "not" => "limit"));
            header("Location:" . base_url("admin/subscriptions"));
        endif;
        $ayar = array(
            'title' => 'Services',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'search_where' => $search_where,
            'status' => $status,
            'paginationArr' => $paginationArr,
            'orders' => $orders,
            'search_where' => 'username',
        );
        return view("admin/yeni_admin/subscriptions", $ayar);
    }

}