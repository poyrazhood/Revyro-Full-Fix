<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use App\Models\ticket_reply;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Mail extends Ana_Controller
{

    function sablon()
    {
        $settings = new \App\Models\Settings();
        if ($this->request->getPost('sablon')):

            $settings->protect(false)->set('mail_sablon',$this->request->getPost('sablon'))->where('id',1)->update();
        endif;
        $settings_cek = $settings->where('id',1)->get()->getResultArray()[0];
        $ayar = array(
            'title' => 'Mail',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $settings_cek,
            'success' => 0,
            'error' => 0,
        );

        return view("admin/yeni_admin/mail-sablon",$ayar);
    }

}