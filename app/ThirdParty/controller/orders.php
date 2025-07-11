<?php

$title .= $languageArray["orders.title"];

$request = route(2);
$o_id = route(3);


if ($request == 'refill' && $o_id) {

    if (!countRow(['table' => 'tasks', 'where' => ['task_type' => 2, 'task_status' => 'pending', 'client_id' => $user["client_id"], 'order_id' => $o_id]])) {
        $orders = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:subs && client_id=:c_id && order_id=:id ");
        $orders->execute(array("c_id" => $user["client_id"], "dripfeed" => 1, "subs" => 1, "id" => $o_id));
        $orders = $orders->fetch(PDO::FETCH_ASSOC);

        $insert = $conn->prepare("INSERT INTO tasks SET client_id=:c_id, order_id=:o_id, service_id=:s_id, task_type=:type, task_date=:date ");
        $insert->execute(array("c_id" => $orders["client_id"], "o_id" => $orders["order_id"], "s_id" => $orders["service_id"], "type" => 2, "date" => date("Y-m-d H:i:s")));
        $order_update = $conn->prepare("UPDATE orders SET refill_check = ? WHERE order_id = ?");
        $order_update->execute([date("Y-m-d H:i:s"),$o_id]);
        $success = 1;
        $successText = $languageArray["error.order.refill"];

    }
    @header("Location: " . base_url("orders"));
    redirect()->to(base_url('orders'));
    echo "<script> window.location.href = '" . base_url('orders') . "';</script>";
    $route[1] = "all";

} elseif ($request == 'cancel' && $o_id) {

    if (!countRow(['table' => 'tasks', 'where' => ['task_type' => 1, 'task_status' => 'pending', 'client_id' => $user["client_id"], 'order_id' => $o_id]])) {

        $orders = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:subs && client_id=:c_id && order_id=:id ");
        $orders->execute(array("c_id" => $user["client_id"], "dripfeed" => 1, "subs" => 1, "id" => $o_id));
        $orders = $orders->fetch(PDO::FETCH_ASSOC);

        $insert = $conn->prepare("INSERT INTO tasks SET client_id=:c_id, order_id=:o_id, service_id=:s_id, task_type=:type, task_date=:date ");
        $insert->execute(array("c_id" => $orders["client_id"], "o_id" => $orders["order_id"], "s_id" => $orders["service_id"], "type" => 1, "date" => date("Y-m-d H:i:s")));

        $success = 1;
        $successText = $languageArray["error.order.cancel"];

    }
    @header("Location: " . base_url("orders"));
    redirect()->to(base_url('orders'));
    echo "<script> window.location.href = '" . base_url('orders') . "';</script>";
    $route[1] = "all";


}

$status_list = ["all", "pending", "inprogress", "completed", "partial", "processing", "canceled"];
$search_statu = route(2);
if (!route(2)):
    $route[1] = "all";
endif;
if (!in_array($search_statu, $status_list)):
    $route[1] = "all";
endif;
if (route(3)):
    $page = route(3);
else:
    $page = 1;
endif;
if (route(2) != "all" && !route(3)):
    $search = "&& order_status='" . route(2) . "'";
else:
    $search = "";
endif;
if (!empty($_GET["search"])):
    $search .= " && ( order_url LIKE '%" . $_GET["search"] . "%' || order_id LIKE '%" . $_GET["search"] . "%' ) ";
endif;
if (!empty($_GET["subscription"])):
    $search .= " && ( subscriptions_id LIKE '%" . $_GET["subscription"] . "%'  ) ";
endif;
if (!empty($_GET["dripfeed"])):
    $search .= " && ( dripfeed_id LIKE '%" . $_GET["dripfeed"] . "%'  ) ";
endif;
$c_id = $user["client_id"];
$to = 25;
$count = $conn->query("SELECT * FROM orders WHERE client_id='$c_id' && dripfeed='1' && subscriptions_type='1' $search ")->rowCount();

$pageCount = ceil($count / $to);
if ($page > $pageCount):
    $page = 1;
endif;
$where = ($page * $to) - $to;

$paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
$orders = $conn->prepare("SELECT * FROM orders INNER JOIN services WHERE services.service_id = orders.service_id && orders.dripfeed=:dripfeed && orders.subscriptions_type=:subs && orders.client_id=:c_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
$orders->execute(array("c_id" => $user["client_id"], "dripfeed" => 1, "subs" => 1));
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);

$ordersList = [];
foreach ($orders as $order) {

    if ($order["order_status"] == "completed" && $order["refill_type"] == 2 && !countRow(['table' => 'tasks', 'where' => ['task_type' => 2, 'task_status' => 'pending', 'client_id' => $user["client_id"], 'order_id' => $order["order_id"]]]
        )) {
        $now = $order['refill_time'];
        $time = strtotime("$now day", strtotime($order['order_create']));
        $new_time = date('Y.m.d', $time);
        $time2 = date("Y.m.d");

        if ($new_time > $time2 || $order["refill_time"] == 0) {
            $min_refill_time = $order['refill_min'];
            $refill_check = strtotime(date("Y:m:d H:i:s",strtotime($order['refill_check'])));
            $refill_check_2 = strtotime(date("Y:m:d H:i:s"));
            $fark = abs((($refill_check_2 - $refill_check) / 60) / 60);

            if($fark >= $min_refill_time){

            $o["refillButton"] = 1;
            }
        } else {
            $o["refillButton"] = false;
        }
    } else {
        $o["refillButton"] = false;
    }

    if ($order["cancel_type"] == 2 && ($order["order_status"] == 'pending' || $order["order_status"] == 'processing' || $order["order_status"] == 'inprogress') && !countRow(['table' => 'tasks', 'where' => ['task_type' => 1, 'task_status' => 'pending', 'client_id' => $user["client_id"], 'order_id' => $order["order_id"]]]) && !countRow(['table' => 'tasks', 'where' => ['task_type' => 1, 'task_status' => 'canceled', 'client_id' => $user["client_id"], 'order_id' => $order["order_id"]]])) {

        $o["cancelButton"] = true;
    } else {
        $o["cancelButton"] = false;
    }

    $o["id"] = htmlentities($order["order_id"]);
    $o["date"] = date("Y-m-d H:i:s", (strtotime($order["order_create"]) + $user["timezone"]));
    $o["link"] = htmlentities($order["order_url"]);
    $o["charge"] = htmlentities($order["order_charge"]);
    $o["start_count"] = htmlentities($order["order_start"]);
    $o["quantity"] = htmlentities($order["order_quantity"]);
    $o["service"] = htmlentities($order["service_name"]);
    $o["status"] = $languageArray["orders.status." . $order["order_status"]];
    if ($order["order_status"] == "completed" && substr($order["order_remains"], 0, 1) == "-"):
        $o["remains"] = "+" . substr($order["order_remains"], 1);
    else:
        $o["remains"] = htmlentities($order["order_remains"]);
    endif;
    array_push($ordersList, $o);
}
