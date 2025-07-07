<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use App\Models\ticket_reply;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class UpdateNote extends Ana_Controller
{

    function not()
    {
        $settings = new \App\Models\Settings();
        $settings_cek = $settings->where('id',1)->get()->getResultArray()[0];
        $ayar = array(
            'title' => 'Mail',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $settings_cek,
            'success' => 0,
            'error' => 0,
        );

        return view("admin/yeni_admin/update-note",$ayar);
    }

}