<?php

namespace App\Models;

use CodeIgniter\Model;

class payments extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    protected $useTimestamps = false;
    protected $returnType = 'array';

}