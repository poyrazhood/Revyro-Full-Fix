<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Categories extends Migration
{
    public function up()
    {
        $fields = [
            'icon' => ['type' => 'TEXT', 'null' => true],
            'color' => ['type' => 'TEXT', 'null' => true],
            'fiyat_siralama' => [
                'type' => 'ENUM',
                'constraint' => ['1', '0'],
                'default' => '1',
            ],

        ];
        $this->forge->addColumn('categories', $fields);
    }

    public function down()
    {
        //
    }
}
