<?php

namespace App\Models;

use CodeIgniter\Model;

class clients_favorite extends Model
{
    protected $table = 'client_favorite';

    protected $primaryKey = 'id';

    protected $allowedFields = ['user_id', 'services_id'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}