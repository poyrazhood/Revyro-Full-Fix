<?php

namespace App\Models;

use CodeIgniter\Model;

class proxy extends Model
{
    protected $table = 'proxy';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['notlar','id'];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}

/*class comments extends Model
{
    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $allowedFields = ['coin_id', 'user_id', 'content','status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}*/