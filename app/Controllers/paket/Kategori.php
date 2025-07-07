<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use PDO;

class Kategori extends Ana_Controller
{
    function index()
    {
        global $conn;
        $paket_kategori = $conn->prepare("SELECT * FROM paket_kategori RIGHT JOIN categories ON categories.category_id = paket_kategori.category_id LEFT JOIN service_api ON service_api.id = paket_kategori.service_api ORDER BY categories.category_line,paket_kategori.service_line ASC ");
        $paket_kategori->execute(array());
        $paket_kategori = $paket_kategori->fetchAll(PDO::FETCH_ASSOC);
        $paket_kategori_list = array_group_by($paket_kategori, 'category_name');
        $ayar = array(
            'title' => 'Paket Kategori',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'serviceList' => $paket_kategori_list,
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
        if ($this->request->getPost('add')) {
            $db = new \App\Models\paket_kategori();
            if ($db->save(array(
                'platform' => $this->request->getPost('platform') ? 1 : 0,
                'platform_id' => $this->request->getPost('platform') ? $this->request->getPost('platform') : 0,
                'name' => $this->request->getPost('name') ? $this->request->getPost('name') : "0",
                'content' => $this->request->getPost('content') ? $this->request->getPost('name') : "0",
            ))) {
                echo js(array(
                    'status_code' => 202,
                    'status' => 'Completed',
                    'message' => 'Başarılı!',
                    'message_sub' => 'Paket Satış Kategorisi Eklendi'
                ));
            } else {
                {
                    echo js(array(
                        'status_code' => 202,
                        'status' => 'Failed',
                        'message' => 'Başarısız!',
                        'message_sub' => 'Paket Satış Kategorisi Eklenemedi'
                    ));
                }
            }

        } elseif ($this->request->getPost('edit')) {
            $db = new \App\Models\paket_kategori();
            if ($db->save(array(
                'platform' => $this->request->getPost('platform') ? 1 : 0,
                'platform_id' => $this->request->getPost('platform') ? $this->request->getPost('platform') : 0,
                'name' => $this->request->getPost('name') ? $this->request->getPost('name') : "0",
                'content' => $this->request->getPost('content') ? $this->request->getPost('name') : "0",
                'id' => $this->request->getPost('id')
            ))) {
                echo js(array(
                    'status_code' => 202,
                    'status' => 'Completed',
                    'message' => 'Başarılı!',
                    'message_sub' => 'Paket Satış Kategorisi Düzenlendi'
                ));
            } else {
                {
                    echo js(array(
                        'status_code' => 202,
                        'status' => 'Failed',
                        'message' => 'Başarısız!',
                        'message_sub' => 'Paket Satış Kategorisi Düzenlenemedi'
                    ));
                }
            }
        }
        return view('admin/paket_kategori', $ayar);
    }
}