<?php

namespace App\Models;

use CodeIgniter\Model;

class services extends Model
{
    protected $table = 'services';

    protected $primaryKey = 'service_id';

    protected $useTimestamps = false;
    protected $returnType = 'array';

}