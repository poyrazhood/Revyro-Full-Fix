<?php

namespace App\Models;

use CodeIgniter\Model;

class tickets extends Model
{
    protected $table = 'tickets';

    protected $primaryKey = 'ticket_id';
    protected $allowedFields = ['subject','status','time','lastupdate_time','client_new','support_new','canmessage'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

}