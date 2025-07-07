<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Settings extends Migration
{
    public function up()
    {
        $fields = ['mail_sablon' => ['type' => 'TEXT','null' => true]];
        $this->forge->addColumn('settings', $fields);
    }

    public function down()
    {
    }
}
