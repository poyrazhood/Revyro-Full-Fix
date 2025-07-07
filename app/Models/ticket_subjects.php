<?php

namespace App\Models;

use CodeIgniter\Model;

class comments extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $allowedFields = ['coin_id', 'user_id', 'content','status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}