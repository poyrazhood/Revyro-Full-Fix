<?php

namespace App\Controllers\admin;

use App\Models\service_api;
use App\Models\ticket_reply;
use CodeIgniter\Controller;
use http\Exception;
use PDO;
use SMMApi;

class Popup extends Ana_Controller
{

    function popup()
    {
        if ($this->request->getPost('baslik') && $this->request->getPost('icerik')):
            $baslik = $this->request->getPost('baslik');
            $icerik = $this->request->getPost('icerik');
            $tur = $this->request->getPost('tur');
            $zaman = $this->request->getPost('zaman');
            $this->db->table('popup')->set(array(
                'name' => $baslik,
                'icerik' => $icerik,
                'tur' => $tur,
                'zaman' => $zaman
            ))->insert();

            header("Location:" . base_url("admin/popup"));
        endif;
        if ($this->request->getGet('delete')):
            $id = $this->request->getGet('delete');
            $this->db->table('popup')->where('id',$id)->delete();
            header("Location:" . base_url("admin/popup"));
        endif;
        $popups = $this->db->table('popup')->orderBy('id', 'DESC')->get()->getResultArray();
        $settings = new \App\Models\Settings();

        $settings = $settings->where('id', 1)->get()->getResultArray()[0];
        $ayar = array(
            'title' => 'Mail',
            'user' => $this->getuser,
            'route' => $this->request->uri->getSegment(2),
            'settings' => $settings,
            'popups' => $popups,
            'success' => 0,
            'error' => 0,
        );

        return view("admin/yeni_admin/popup-list", $ayar);
    }

}