<?php

namespace App\Controllers\main;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Popup extends BaseController
{
    function ajax()
    {
        helper('cookie','date');
        if ($this->request->isAJAX()) {
            $session = \Config\Services::session();
            $session->start();
            $result = array('field' => array());
            $count = 0;

            if ($session->get('neira_userlogin')) {
                $request = $this->db->table('popup')->where('tur', 1)->get()->getResultArray();
                $count += count($request);
                foreach ($request as $r) {
                if($r['zaman'] == 1){
                    $zaman = strtotime('+1 hours');
                }elseif($r['zaman'] == 2){
                    
                    $zaman = strtotime('+3 hours');
                }elseif($r['zaman'] == 3){
                    
                    $zaman = strtotime('+6 hours');
                }elseif($r['zaman'] == 4){
                    
                    $zaman = strtotime('+12 hours');
                }elseif($r['zaman'] == 5){
                    
                    $zaman = strtotime('+1 days');
                }
                    if(!isset($_COOKIE['popup'.$r['id']])){
                    if (isset($r['tur'])) {
                        array_push($result['field'], array(
                            'baslik' => $r['name'],
                            'icerik' => $r['icerik'],
                            'tur' => $r['tur'],
                            'zaman' => $r['zaman']
                        ));
                    }
                    setcookie("popup".$r['id'], '1', $zaman, '/', null, null, true);
                    break;
                }
                }
            } else {
                $request = $this->db->table('popup')->where('tur', 2)->get()->getResultArray();
                $count += count($request);
                foreach ($request as $r) {
                if($r['zaman'] == 1){
                    $zaman = strtotime('+1 hours');
                }elseif($r['zaman'] == 2){
                    
                    $zaman = strtotime('+3 hours');
                }elseif($r['zaman'] == 3){
                    
                    $zaman = strtotime('+6 hours');
                }elseif($r['zaman'] == 4){
                    
                    $zaman = strtotime('+12 hours');
                }elseif($r['zaman'] == 5){
                    
                    $zaman = strtotime('+1 days');
                }
                    if(!isset($_COOKIE['popup'.$r['id']])){
                    if (isset($r['tur'])) {
                        array_push($result['field'], array(
                            'baslik' => $r['name'],
                            'icerik' => $r['icerik'],
                            'tur' => $r['tur'],
                            'zaman' => $r['zaman']
                        ));
                    }
                    setcookie("popup".$r['id'], '1', $zaman, '/', null, null, true);break;
                    }
                }

            }
            
            $request = $this->db->table('popup')->where('tur', 3)->get()->getResultArray();
            $count += count($request);

            
            foreach ($request as $r) {
                if($r['zaman'] == 1){
                    $zaman = strtotime('+1 hours');
                }elseif($r['zaman'] == 2){
                    
                    $zaman = strtotime('+3 hours');
                }elseif($r['zaman'] == 3){
                    
                    $zaman = strtotime('+6 hours');
                }elseif($r['zaman'] == 4){
                    
                    $zaman = strtotime('+12 hours');
                }elseif($r['zaman'] == 5){
                    
                    $zaman = strtotime('+1 days');
                }
                if(!isset($_COOKIE['popup'.$r['id']])){
                if (isset($r['tur'])) {
                    array_push($result['field'], array(
                        'baslik' => $r['name'],
                        'icerik' => $r['icerik'],
                        'tur' => $r['tur'],
                        'zaman' => $r['zaman']
                    ));
                }
                
                setcookie("popup".$r['id'], '1', $zaman, '/', null, null, true);break;
                
                }
            }

            $result['field_count'] = $count;
            $this->response->setHeader('Content-Type', 'application/json');
            return $this->response
                ->setStatusCode(200)
                ->setBody(json_encode($result));

        }
        return 0;
    }
}