<?php

namespace App\Models;

use CodeIgniter\Model;

class cift_servis extends Model
{
    protected $table = 'cift_servis';

    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['order_id', 'status'];
    protected $useTimestamps = false;
    protected $updatedField = "update_date";
    protected $returnType = 'array';

}