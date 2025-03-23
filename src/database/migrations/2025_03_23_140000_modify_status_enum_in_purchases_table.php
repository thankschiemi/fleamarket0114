<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // ここで 'sold' を ENUM の値に追加
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'completed', 'canceled', 'trading', 'sold') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        // 'sold' を元に戻す（削除する）
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'completed', 'canceled', 'trading') NOT NULL DEFAULT 'pending'");
    }
};
