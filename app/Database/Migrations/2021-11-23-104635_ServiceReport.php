<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ServiceReport extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'alert' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'extra' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at timestamp default current_timestamp'
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('service_report');

    }

    public function down()
    {

        $this->forge->dropTable('service_report');
    }
}
