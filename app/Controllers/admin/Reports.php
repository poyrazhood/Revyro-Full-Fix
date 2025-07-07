<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use PDO;

class Reports extends Ana_Controller
{
    function index()
    {
        global $conn;
        $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id = services.category_id LEFT JOIN service_api ON service_api.id = services.service_api ORDER BY categories.category_line,services.service_line ASC ");
        $services->execute(array());
        $services = $services->fetchAll(PDO::FETCH_ASSOC);
        $serviceList = array_group_by($services, 'category_name');
        $ayar = array(
            'title' => 'Client',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'serviceList' => $serviceList,
            'search_where' => 'username',
            'search_read' => $this->request->getGet("search") ? $this->request->getGet("search") : "",
        );
        if (!$this->request->uri->getSegment(3)):
            $action = "profit";
            $years = $conn->query("SELECT order_create FROM orders GROUP BY YEAR(order_create) ORDER BY YEAR(order_create) ASC")->fetchAll(PDO::FETCH_ASSOC);
            $yearList = [];
            $i = 0;
            foreach ($years as $key) {
                $yearList[$i] = date("Y", strtotime($key["order_create"]));
                $i += 1;
            }
        else:
            $action = $this->request->uri->getSegment(3);
            if ($action == "orders" || $action == "profit"):
                $years = $conn->query("SELECT order_create FROM orders GROUP BY YEAR(order_create) ORDER BY YEAR(order_create) ASC")->fetchAll(PDO::FETCH_ASSOC);
                $yearList = [];
                $i = 0;
                foreach ($years as $key) {
                    $yearList[$i] = date("Y", strtotime($key["order_create"]));
                    $i += 1;
                }
            elseif ($action == "payments"):
                $methods = $conn->prepare("SELECT * FROM payment_methods");
                $methods->execute(array());
                $methods = $methods->fetchAll(PDO::FETCH_ASSOC);

                $ayar['methods'] = $methods;
                $years = $conn->query("SELECT payment_create_date FROM payments GROUP BY YEAR(payment_create_date) ORDER BY YEAR(payment_create_date) ASC")->fetchAll(PDO::FETCH_ASSOC);
                $yearList = [];
                $i = 0;
                foreach ($years as $key) {
                    $yearList[$i] = date("Y", strtotime($key["payment_create_date"]));
                    $i += 1;
                }
            endif;
        endif;

        if (count($yearList) == 0): $yearList[0] = date("Y"); endif;

        if (isset($_GET["year"]) && $_GET["year"]):
            $year = $_GET["year"];
        else:
            $year = date("Y");
        endif;
        //
            //'action' => $action,
        //'year' => $year,
          //  'yearList' => $yearList,
        $ayar['action'] = $action;
        $ayar['year'] = $year;
        $ayar['yearList'] = $yearList;
        //return view('admin/reports', $ayar);
        return view('admin/yeni_admin/reports', $ayar);
    }
}