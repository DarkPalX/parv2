<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferValueToAccountabilityDetailsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('accountabilityDetails', 'transfer_value')) {
            Schema::table('accountabilityDetails', function (Blueprint $table) {
                $table->decimal('transfer_value', 16, 2)->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('accountabilityDetails', 'transfer_value')) {
            Schema::table('accountabilityDetails', function (Blueprint $table) {
                $table->dropColumn('transfer_value');
            });
        }
    }
}
