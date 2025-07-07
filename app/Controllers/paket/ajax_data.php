<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use PDO;

class ajax_data extends Ana_Controller
{
    function index()
    {
        if ($this->request->getPost('favori')) {
            helper('cookie');
            $this->response->setCookie('paket_favori', $this->request->getPost('favori'), 3600);
            echo js(array(
                'status_code' => 202,
                'status' => 'Completed',
                'message' => 'Başarılı!',
                'message_sub' => 'Başarıyla Favoriye Eklendi'
            ));
        } elseif ($this->request->getPost('search')) {
            $db = new \App\Models\services();
            if ($db->where('paket_set', 1)->where('paket_kategori', $this->request->getPost('search'))->countAllResults()) {
                $veriler = $db->where('paket_set', 1)->where('paket_kategori', $this->request->getPost('search'))->get()->getResultArray();
                return js(array(
                    'status_code' => '202',
                    'status' => 'Complete',
                    'field_count' => count($veriler),
                    'field' => $veriler
                ));
            }
        } elseif ($this->request->getPost('search_paket')) {
            $db = new \App\Models\paket_kategori();
            if ($db->where('platform', 0)->where('platform_id', $this->request->getPost('search_paket'))->countAllResults()) {
                $veriler = $db->where('platform', 0)->where('platform_id', $this->request->getPost('search_paket'))->get()->getResultArray();
                return js(array(
                    'status_code' => '202',
                    'status' => 'Complete',
                    'field_count' => count($veriler),
                    'field' => $veriler,
                ));
            }
        }
    }
}