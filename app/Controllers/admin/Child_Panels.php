<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use PDO;
use SMMApi;

class Child_Panels extends Ana_Controller
{
    function index()
    {
        global $conn;
        global $_SESSION;

        if (route(3) && is_numeric(route(3))):
            $page = route(3);
        else:
            $page = 1;
        endif;

        $statusList = ["all", "active", "pending", "completed", "canceled", "expired"];
        if (route(4) && in_array(route(4), $statusList)):
            $status = route(4);
        elseif (!route(4) || !in_array(route(4), $statusList)):
            $status = "all";
        endif;

        /* Sorgulama */

        /* All */
        if (isset($_GET["status"]) && $_GET["status"] == "all" && $_GET["status"]):
            $status = $_GET["status"];
            $search_link = "?status=pending";

        /* Active */
        elseif (isset($_GET["status"]) && $_GET["status"] == "active" && $_GET["status"]):
            $status = $_GET["status"];
            $search = " panel_status='active' ";
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE {$search}");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search}";
            $search_link = "?status=active";

        /* Pending */
        elseif (isset($_GET["status"]) && $_GET["status"] == "pending" && $_GET["status"]):
            $status = $_GET["status"];
            $search = " panel_status='pending' ";
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE {$search}");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search}";
            $search_link = "?status=pending";

        /* Completed */
        elseif (isset($_GET["status"]) && $_GET["status"] == "frozen" && $_GET["status"]):
            $status = $_GET["status"];
            $search = " panel_status='frozen' ";
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE {$search}");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search}";
            $search_link = "?status=frozen";

        /* Expired */
        elseif (isset($_GET["status"]) && $_GET["status"] == "expired" && $_GET["status"]):
            $status = $_GET["status"];
            $search = " panel_status='expired' ";
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE {$search}");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search}";
            $search_link = "?status=expired";

        /* Canceled */
        elseif (isset($_GET["status"]) && $_GET["status"] == "canceled" && $_GET["status"]):
            $status = $_GET["status"];
            $search = " panel_status='canceled' ";
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE {$search}");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search}";
            $search_link = "?status=canceled";


        /* Sorgulama Kısmı */

        elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "username" && $_GET["search"] && countRow(["table" => "clients", "where" => ["username" => $_GET["search"]]])):

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
            $search = " child_panels.client_id IN " . $id;
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id=child_panels.client_id WHERE {$search} ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE {$search} ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;

        elseif (isset($_GET["search_type"]) && $_GET["search_type"] == "domain" && $_GET["search"] && countRow(["table" => "child_panels", "where" => ["panel_domain" => $_GET["search"]]])):
            $search_where = $_GET["search_type"];

            $search_word = urldecode($_GET["search"]);
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id=child_panels.client_id WHERE child_panels.panel_domain LIKE '%" . $search_word . "%' ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "WHERE child_panels.panel_domain LIKE '%" . $search_word . "%' ";
            $search_link = "?search=" . $search_word . "&search_type=" . $search_where;
        else:
            $count = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id=child_panels.client_id ");
            $count->execute(array());
            $count = $count->rowCount();
            $search = "";
        endif;

        $to = 50;
        $pageCount = ceil($count / $to);
        if ($page > $pageCount): $page = 1; endif;
        $where = ($page * $to) - $to;
        $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
        $panels = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id=child_panels.client_id $search ORDER BY child_panels.id DESC LIMIT $where,$to ");
        $panels->execute(array());
        $panels = $panels->fetchAll(PDO::FETCH_ASSOC);

        if (route(3) == "cancel" && route(3)):

            $id = route(4);
            $panel = $conn->prepare("SELECT * FROM child_panels INNER JOIN clients ON clients.client_id = child_panels.client_id WHERE child_panels.id=:id ");
            $panel->execute(array("id" => $id));
            $panel = $panel->fetch(PDO::FETCH_ASSOC);
            $balance = $panel["balance"] + $panel["panel_price"];
            $spent = $panel["spent"] - $panel["panel_price"];
            $conn->beginTransaction();

            $update = $conn->prepare("UPDATE child_panels SET panel_status=:status, panel_price=:price WHERE id=:id ");
            $update = $update->execute(array("price" => 0, "status" => "canceled", "id" => $id));

            $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
            $update2 = $update2->execute(array("id" => $panel["client_id"], "balance" => $balance, "spent" => $spent));

            if ($update && $update2):
                $conn->commit();
                unset($_SESSION["data"]);
                $success = 1;
                $successText = "İşlem Başarılı";

            else:
                $conn->rollBack();
                $error = 1;
                $errorText = "İşlem Başarısız";
            endif;
        endif;
        $ayar = array(
            'title' => 'ChildPanels',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'successText' => isset($successText)?$successText:0,
            'success' => isset($success)?$success:0,
            'error' => isset($error)?$error:0,
            'errorText' => isset($errorText)?$errorText:0,
            'search_word' => '',
            'search_where' => 'username',
            'status' => $status,
            'panels' => $panels,
            'paginationArr' => $paginationArr,
        );
        echo view('admin/yeni_admin/child-panels', $ayar);
    }
}