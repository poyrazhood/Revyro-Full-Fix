<?php

namespace App\Models;

use CodeIgniter\Model;

class settings extends Model
{
    protected $table = 'settings';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['notlar','id'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}