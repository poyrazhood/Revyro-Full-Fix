<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Popup extends Migration
{
    public function up()
    {
      $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'icerik' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tur' => [
                'type' => 'INT',
                'null' => true,
            ],
            'zaman' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
         $this->forge->addKey('id', true);
        $this->forge->createTable('popup');
    }

    public function down()
    {
        //
    }
}
