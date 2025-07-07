<?php

namespace App\Models;

use CodeIgniter\Model;

class ticket_reply extends Model
{
    protected $table = 'ticket_reply';

    protected $primaryKey = 'id';

    protected $allowedFields = ['subject','status','time','lastupdate_time','client_new','support_new','canmessage'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}