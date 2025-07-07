<?php

namespace App\Controllers\main;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Cron_Status extends BaseController
{
    function index()
    {
        $cron_status = new \App\Models\cron_status();
        if ($this->request->isAJAX()) {
            if ($this->request->getPost('id') && $this->request->getPost('set')) {
                $cron_status
                    ->save(array(
                        'id' => $this->request->getPost('id'),
                        'set' => $this->request->getPost('set')
                    ));
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getset()
    {
        $cron_status = new \App\Models\cron_status();
        if ($this->request->isAJAX()) {
            if ($this->request->getPost('id')) {
                $status = $cron_status->where(array('id' => $this->request->getPost('id')))->get()->getResultArray()[0];
                echo json_encode(array(
                    'status' => 202,
                    'set' => $status['set'] ? 'aktif' : 'pasif'
                ));
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}

?>