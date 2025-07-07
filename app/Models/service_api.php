<?php

namespace App\Models;

use CodeIgniter\Model;

class service_api extends Model
{
    protected $table = 'service_api';

    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['api_name', 'api_url', 'api_key','api_type','api_limit', 'api_alert', 'api_json'];
    protected $useTimestamps = false;
    protected $updatedField = "update_date";
    protected $returnType = 'array';

}