<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyStatusEnumInProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default('available')->after('condition');
        });

        DB::statement("UPDATE products SET status = 'sold' WHERE is_sold = 1");
        DB::statement("UPDATE products SET status = 'available' WHERE is_sold = 0");

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_sold');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_sold')->default(false)->after('condition');
        });

        DB::statement("UPDATE products SET is_sold = 1 WHERE status = 'sold'");
        DB::statement("UPDATE products SET is_sold = 0 WHERE status = 'available'");

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
