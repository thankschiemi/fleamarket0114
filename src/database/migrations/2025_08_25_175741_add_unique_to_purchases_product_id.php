<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {

        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'purchases')
            ->where('index_name', 'uniq_product_id')
            ->exists();

        if (! $exists) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->unique('product_id', 'uniq_product_id');
            });
        }
    }

    public function down(): void
    {
        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'purchases')
            ->where('index_name', 'uniq_product_id')
            ->exists();

        if ($exists) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropUnique('uniq_product_id');
            });
        }
    }
};
