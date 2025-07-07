<?php

namespace App\Models;

use CodeIgniter\Model;

class ticket_ready extends Model
{
    protected $table = 'ticket_ready';

    protected $primaryKey = 'id';

    protected $allowedFields = ['content','title'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}