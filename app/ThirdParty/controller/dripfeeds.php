<?php

$title .= $languageArray["dripfeed.title"];



    $status_list = ["all", "active", "completed", "canceled"];
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
    if (route(2) != "all"):
        $search = "&& dripfeed_status='" . route(2) . "'";
    else:
        $search = "";
    endif;
    if (!empty($_GET["search"])):
        $search.= " && ( order_url LIKE '%" . $_GET["search"] . "%' || order_id LIKE '%" . $_GET["search"] . "%' ) ";
    endif;
    $c_id = $user["client_id"];
    $to = 25;
    $count = $conn->query("SELECT * FROM orders WHERE client_id='$c_id' && dripfeed='2' && subscriptions_type='1' $search ")->rowCount();
    $pageCount = ceil($count / $to);
    if ($page > $pageCount):
        $page = 1;
    endif;
    $where = ($page * $to) - $to;
    $paginationArr = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
    $orders = $conn->prepare("SELECT * FROM orders INNER JOIN services WHERE services.service_id = orders.service_id && orders.dripfeed=:dripfeed && orders.subscriptions_type=:subs && orders.client_id=:c_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
    $orders->execute(array("c_id" => $user["client_id"], "dripfeed" => 2, "subs" => 1));
    $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
    $ordersList = [];
    foreach ($orders as $order) {
        $o["id"] = $order["order_id"];
        $o["date"] = $order["order_create"];
        $o["runs"] = $order["dripfeed_runs"];
        $o["link"] = $order["order_url"];
        $o["total_charges"] = $order["dripfeed_totalcharges"];
        $o["delivery"] = $order["dripfeed_delivery"];
        $o["total_quantity"] = $order["dripfeed_totalquantity"];
        $o["service"] = $order["service_name"];
        $o["quantity"] = $order["order_quantity"];
        $o["status"] = $order["dripfeed_status"];
        $o["interval"] = $order["dripfeed_interval"];
        array_push($ordersList, $o);
    }
    