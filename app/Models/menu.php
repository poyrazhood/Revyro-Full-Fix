<?php

namespace App\Models;

use CodeIgniter\Model;

class menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['coin_id', 'user_id', 'content','status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}