<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Categories extends Migration
{
    public function up()
    {
        $fields = [
            'price_line' => [
                'type' => 'ENUM',
                'constraint' => ['1', '0'],
                'default' => '0',
            ],

        ];
        $this->forge->addColumn('categories', $fields);
    }

    public function down()
    {
        //
    }
}
