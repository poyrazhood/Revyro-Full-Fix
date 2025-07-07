<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Services extends Migration
{
    public function up()
    {
                $fields = [
            'sync_kar_oran' => [
                'type' => 'ENUM',
                'constraint' => ['1', '0'],
                'default' => '1',
            ],
        ];
        $this->forge->addColumn('services', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('services', 'sync_kar_oran');
    }
}
