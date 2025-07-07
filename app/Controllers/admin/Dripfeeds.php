<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use PDO;
use SMMApi;

class Dripfeeds extends Ana_Controller
{

  function index()
  {
    global $conn;
    global $_SESSION;
    $referrer = "";
    include APPPATH . 'ThirdParty/SMMApi/SMMApi.php';
    $settings = $this->settings;
    if (route(3) && is_numeric(route(3))) :
      $page = route(3);
    else :
      $page = 1;
    endif;
    $search_where = "";
    $paginationArr = array('count' => 1);
    $statusList = ["all", "active", "paused", "completed", "canceled", "expired", "limit"];
    $statusList = ["all", "active", "paused", "completed", "canceled", "expired", "limit"];
    if (route(3) && in_array(route(3), $statusList)) :
      $status   = route(3);
    elseif (!route(3) || !in_array(route(3), $statusList)) :
      $status   = "all";
    endif;

    if ($_GET["search_type"] == "username" && $_GET["search"]) :
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $clients      = $conn->prepare("SELECT client_id FROM clients WHERE username LIKE '%" . $search_word . "%' ");
      $clients->execute(array());
      $clients      = $clients->fetchAll(PDO::FETCH_ASSOC);
      $id =  "(";
      foreach ($clients as $client) {
        $id .= $client["client_id"] . ",";
      }
      if (substr($id, -1) == ",") :  $id = substr($id, 0, -1);
      endif;
      $id .= ")";
      $search       = " orders.client_id IN " . $id;
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE {$search} && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count->execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE {$search} && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=" . $search_word . "&search_type=" . $search_where;
    elseif ($_GET["search_type"] == "order_id" && $_GET["search"]) :
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%" . $search_word . "%' && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count->execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE orders.order_id LIKE '%" . $search_word . "%'  && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=" . $search_word . "&search_type=" . $search_where;
    elseif ($_GET["search_type"] == "order_url" && $_GET["search"]) :
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_url LIKE '%" . $search_word . "%' && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count->execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE orders.order_url LIKE '%" . $search_word . "%'  && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=" . $search_word . "&search_type=" . $search_where;
    elseif ($status != "all") :
      $count          = $conn->prepare("SELECT * FROM orders WHERE dripfeed_status=:status && dripfeed=:dripfeed && subscriptions_type=:sub ");
      $count->execute(array("dripfeed" => 1, "sub" => 2, "status" => $status));
      $count          = $count->rowCount();
      $search         = "WHERE orders.dripfeed_status='" . $status . "' && orders.dripfeed='2' && orders.subscriptions_type='1' ";
    elseif ($status == "all") :
      $count          = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub ");
      $count->execute(array("dripfeed" => 2, "sub" => 1));
      $count          = $count->rowCount();
      $search         = "WHERE orders.dripfeed='2' && orders.subscriptions_type='1' ";
    endif;
    $to             = 50;
    $pageCount      = ceil($count / $to);
    if ($page > $pageCount) : $page = 1;
    endif;
    $where          = ($page * $to) - $to;
    $paginationArr  = ["count" => $pageCount, "current" => $page, "next" => $page + 1, "previous" => $page - 1];
    $orders         = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id=orders.client_id LEFT JOIN services ON services.service_id=orders.service_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
    $orders->execute(array());
    $orders         = $orders->fetchAll(PDO::FETCH_ASSOC);
    function orderStatu($statu)
    {

      switch ($statu) {
        case 'active':
          $statu  = "Aktif";
          break;
        case 'completed':
          $statu  = "Tamamland匕";
          break;
        case 'canceled':
          $statu  = "力ptal";
          break;
      }

      return $statu;
    }


    if (route(2) ==  "dripfeed_canceled") :
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
      $update->execute(array("status" => "canceled", "id" => route(3)));
      header("Location:" . site_url("admin/dripfeeds"));
    elseif (route(2) ==  "dripfeed_completed") :
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
      $update->execute(array("status" => "completed", "id" => route(3)));
      header("Location:" . site_url("admin/dripfeeds"));
    elseif (route(2) ==  "dripfeed_canceledbalance") :
      $id     = route(3);
      $order  = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
      $order->execute(array("id" => $id));
      $order  = $order->fetch(PDO::FETCH_ASSOC);
      $price  = ($order["dripfeed_totalcharges"] / $order["dripfeed_runs"]) * ($order["dripfeed_runs"] - $order["dripfeed_delivery"]); ## 力ade edilecek tutar
      $conn->beginTransaction();
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status, dripfeed_totalcharges=:charges, dripfeed_runs=:runs, dripfeed_totalquantity=:quantity WHERE order_id=:id ");
      $update = $update->execute(array("status" => "canceled", "id" => route(3), "charges" => $order["dripfeed_totalcharges"] - $price, "runs" => $order["dripfeed_delivery"], "quantity" => $order["dripfeed_delivery"] * $order["order_quantity"]));
      $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
      $update2 = $update2->execute(array("id" => $order["client_id"], "spent" => $order["spent"] - $price, "balance" => $order["balance"] + $price));
      if ($update && $update2) :
        $conn->commit();
      else :
        $conn->rollBack();
      endif;
      header("Location:" . site_url("admin/dripfeeds"));
    elseif (route(2) == "multi-action") :
      $orders   = $_POST["order"];
      $action   = $_POST["bulkStatus"];
      if ($action ==  "canceled") :
        foreach ($orders as $id => $value) :
          $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
          $update->execute(array("status" => "canceled", "id" => $id));
        endforeach;
      elseif ($action ==  "completed") :
        foreach ($orders as $id => $value) :
          $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
          $update->execute(array("status" => "completed", "id" => $id));
        endforeach;
      elseif ($action ==  "canceledbalance") :
        foreach ($orders as $id => $value) :
          $order  = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
          $order->execute(array("id" => $id));
          $order  = $order->fetch(PDO::FETCH_ASSOC);
          $price  = ($order["dripfeed_totalcharges"] / $order["dripfeed_runs"]) * ($order["dripfeed_runs"] - $order["dripfeed_delivery"]); ## 力ade edilecek tutar
          $conn->beginTransaction();
          $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status, dripfeed_totalcharges=:charges, dripfeed_runs=:runs, dripfeed_totalquantity=:quantity WHERE order_id=:id ");
          $update = $update->execute(array("status" => "canceled", "id" => $id, "charges" => $order["dripfeed_totalcharges"] - $price, "runs" => $order["dripfeed_delivery"], "quantity" => $order["dripfeed_delivery"] * $order["order_quantity"]));
          $update2 = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
          $update2 = $update2->execute(array("id" => $order["client_id"], "spent" => $order["spent"] - $price, "balance" => $order["balance"] + $price));
          if ($update && $update2) :
            $conn->commit();
          else :
            $conn->rollBack();
          endif;
        endforeach;
      endif;
      header("Location:" . site_url("admin/dripfeeds"));
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
    return view("admin/yeni_admin/dripfeeds", $ayar);
  }
}

/*
namespace App\Controllers\admin;

use App\Models\service_api;
use CodeIgniter\Controller;
use App\Controllers\admin\Ana_Controller;
use PDO;
use SMMApi;

class Dripfeeds extends Ana_Controller
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
    $statusList = ["all","active","paused","completed","canceled","expired","limit"];
    if( route(4) && in_array(route(4),$statusList) ):
      $status   = route(4);
    elseif( !route(4) || !in_array(route(4),$statusList) ):
      $status   = "all";
    endif;

    if( $_GET["search_type"] == "username" && $_GET["search"] ):
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $clients      = $conn->prepare("SELECT client_id FROM clients WHERE username LIKE '%".$search_word."%' ");
      $clients     -> execute(array());
      $clients      = $clients->fetchAll(PDO::FETCH_ASSOC);
      $id=  "("; foreach ($clients as $client) { $id.=$client["client_id"].","; } if( substr($id,-1) == "," ):  $id = substr($id,0,-1); endif; $id.=")";
      $search       = " orders.client_id IN ".$id;
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE {$search} && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count        -> execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE {$search} && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=".$search_word."&search_type=".$search_where;
    elseif( $_GET["search_type"] == "order_id" && $_GET["search"] ):
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id LIKE '%".$search_word."%' && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count        -> execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE orders.order_id LIKE '%".$search_word."%'  && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=".$search_word."&search_type=".$search_where;
    elseif( $_GET["search_type"] == "order_url" && $_GET["search"] ):
      $search_where = $_GET["search_type"];
      $search_word  = urldecode($_GET["search"]);
      $count        = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_url LIKE '%".$search_word."%' && orders.dripfeed='2' && orders.subscriptions_type='1' ");
      $count        -> execute(array());
      $count        = $count->rowCount();
      $search       = "WHERE orders.order_url LIKE '%".$search_word."%'  && orders.dripfeed='2' && orders.subscriptions_type='1' ";
      $search_link  = "?search=".$search_word."&search_type=".$search_where;
    elseif( $status != "all" ):
      $count          = $conn->prepare("SELECT * FROM orders WHERE dripfeed_status=:status && dripfeed=:dripfeed && subscriptions_type=:sub ");
      $count        ->execute(array("dripfeed"=>1,"sub"=>2,"status"=>$status));
      $count          = $count->rowCount();
      $search         = "WHERE orders.dripfeed_status='".$status."' && orders.dripfeed='2' && orders.subscriptions_type='1' ";
    elseif( $status == "all" ):
      $count          = $conn->prepare("SELECT * FROM orders WHERE dripfeed=:dripfeed && subscriptions_type=:sub ");
      $count        ->execute(array("dripfeed"=>2,"sub"=>1));
      $count          = $count->rowCount();
      $search         = "WHERE orders.dripfeed='2' && orders.subscriptions_type='1' ";
    endif;
    $to             = 50;
    $pageCount      = ceil($count/$to); if( $page > $pageCount ): $page = 1; endif;
    $where          = ($page*$to)-$to;
    $paginationArr  = ["count"=>$pageCount,"current"=>$page,"next"=>$page+1,"previous"=>$page-1];
    $orders         = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id=orders.client_id LEFT JOIN services ON services.service_id=orders.service_id $search ORDER BY orders.order_id DESC LIMIT $where,$to ");
    $orders         -> execute(array());
    $orders         = $orders->fetchAll(PDO::FETCH_ASSOC);
    function orderStatu($statu){

      switch ($statu) {
        case 'active':
          $statu  = "Aktif";
        break;
        case 'completed':
          $statu  = "Tamamland匕";
        break;
        case 'canceled':
          $statu  = "力ptal";
        break;
      }

      return $statu;
    }

  if( route(4) ==  "dripfeed_canceled" && route(4)):
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
      $update->execute(array("status"=>"canceled","id"=>route(5)));
      header("Location:".base_url("admin/dripfeeds"));
  elseif( route(4) ==  "dripfeed_completed"  && route(4)):
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
      $update->execute(array("status"=>"completed","id"=>route(5)));
      header("Location:".base_url("admin/dripfeeds"));
  elseif( route(4) ==  "dripfeed_canceledbalance"  && route(4)):
    $id     = route(5);
    $order  = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
    $order ->execute(array("id"=>$id));
    $order  = $order->fetch(PDO::FETCH_ASSOC);
    $price  = ($order["dripfeed_totalcharges"]/$order["dripfeed_runs"])*($order["dripfeed_runs"]-$order["dripfeed_delivery"]); ## 力ade edilecek tutar
      $conn->beginTransaction();
      $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status, dripfeed_totalcharges=:charges, dripfeed_runs=:runs, dripfeed_totalquantity=:quantity WHERE order_id=:id ");
      $update = $update->execute(array("status"=>"canceled","id"=>route(5),"charges"=>$order["dripfeed_totalcharges"]-$price,"runs"=>$order["dripfeed_delivery"],"quantity"=>$order["dripfeed_delivery"]*$order["order_quantity"] ));
      $update2= $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
      $update2= $update2->execute(array("id"=>$order["client_id"],"spent"=>$order["spent"]-$price,"balance"=>$order["balance"]+$price ));
      if( $update && $update2 ):
        $conn->commit();
      else:
        $conn->rollBack();
      endif;
      header("Location:".base_url("admin/dripfeeds"));
  elseif( route(4) == "multi-action" && route(4) ):
    $orders   = $_POST["order"];
    $action   = $_POST["bulkStatus"];
    if( $action ==  "canceled" ):
      foreach ($orders as $id => $value):
        $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
        $update->execute(array("status"=>"canceled","id"=>$id));
      endforeach;
    elseif( $action ==  "completed"  && route(4)):
      foreach ($orders as $id => $value):
        $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status WHERE order_id=:id ");
        $update->execute(array("status"=>"completed","id"=>$id));
      endforeach;
    elseif( $action ==  "canceledbalance" && route(4) ):
      foreach ($orders as $id => $value):
        $order  = $conn->prepare("SELECT * FROM orders INNER JOIN clients ON clients.client_id = orders.client_id WHERE orders.order_id=:id ");
        $order ->execute(array("id"=>$id));
        $order  = $order->fetch(PDO::FETCH_ASSOC);
        $price  = ($order["dripfeed_totalcharges"]/$order["dripfeed_runs"])*($order["dripfeed_runs"]-$order["dripfeed_delivery"]); ## 力ade edilecek tutar
          $conn->beginTransaction();
          $update = $conn->prepare("UPDATE orders SET dripfeed_status=:status, dripfeed_totalcharges=:charges, dripfeed_runs=:runs, dripfeed_totalquantity=:quantity WHERE order_id=:id ");
          $update = $update->execute(array("status"=>"canceled","id"=>$id,"charges"=>$order["dripfeed_totalcharges"]-$price,"runs"=>$order["dripfeed_delivery"],"quantity"=>$order["dripfeed_delivery"]*$order["order_quantity"] ));
          $update2= $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id ");
          $update2= $update2->execute(array("id"=>$order["client_id"],"spent"=>$order["spent"]-$price,"balance"=>$order["balance"]+$price ));
          if( $update && $update2 ):
            $conn->commit();
          else:
            $conn->rollBack();
          endif;
        endforeach;
      endif;
    header("Location:".base_url("admin/dripfeeds"));
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
        return view("admin/yeni_admin/dripfeeds", $ayar);
    }

}*/