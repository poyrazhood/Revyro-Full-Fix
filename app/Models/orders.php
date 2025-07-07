<?php

namespace App\Models;

use CodeIgniter\Model;

class orders extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    //protected $allowedFields = ['coin_id', 'user_id', 'content','status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}