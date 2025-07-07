<?php

namespace App\Models;

use CodeIgniter\Model;

class service_report extends Model
{
    protected $table = 'service_report';

    protected $primaryKey = 'id';

    protected $allowedFields = ['service_id', 'alert', 'extra'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}