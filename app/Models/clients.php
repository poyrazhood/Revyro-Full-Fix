<?php

namespace App\Models;

use CodeIgniter\Model;

class clients extends Model
{
    protected $table = 'clients';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['coin_id', 'user_id', 'content','status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}