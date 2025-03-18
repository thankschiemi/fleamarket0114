<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status VARCHAR(20) NOT NULL");

        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'completed', 'canceled', 'trading') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::table('purchases')->where('status', 'trading')->update(['status' => 'pending']);

        DB::statement("ALTER TABLE purchases MODIFY COLUMN status VARCHAR(20) NOT NULL");

        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'completed', 'canceled') NOT NULL DEFAULT 'pending'");
    }
};
