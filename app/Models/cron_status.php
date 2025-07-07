<?php

namespace App\Models;

use CodeIgniter\Model;

class cron_status extends Model
{
    protected $table = 'cron_status';

    protected $primaryKey = 'id';

    protected $allowedFields = ['cron_name', 'status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}