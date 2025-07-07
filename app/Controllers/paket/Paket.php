<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use PDO;

class Paket extends Ana_Controller
{
    function index()
    {
        global $conn;
        $paket_kategori = $conn->prepare("SELECT * FROM paket_kategori RIGHT JOIN categories ON categories.category_id = paket_kategori.category_id LEFT JOIN service_api ON service_api.id = paket_kategori.service_api ORDER BY categories.category_line,paket_kategori.service_line ASC ");
        $paket_kategori->execute(array());
        $paket_kategori = $paket_kategori->fetchAll(PDO::FETCH_ASSOC);
        $paket_list = array_group_by($paket_kategori, 'category_name');
        $ayar = array(
            'title' => 'Paket',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $this->settings,
            'success' => 0,
            'error' => 0,
            'search_word' => '',
            'serviceList' => $paket_list,
            'search_read' => $this->request->getGet("search") ? $this->request->getGet("search") : "",
        );


        //
            //'action' => $action,
        //'year' => $year,
          //  'yearList' => $yearList,
        return view('admin/paket', $ayar);
    }
}