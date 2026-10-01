<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapexCodeToItemsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('items', 'capex_code')) {
            Schema::table('items', function (Blueprint $table) {
                $table->string('capex_code', 50)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('items', 'capex_code')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('capex_code');
            });
        }
    }
}
