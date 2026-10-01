<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssuanceDateToAccountabilityHeadersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('accountabilityHeaders', 'issuance_date')) {
            Schema::table('accountabilityHeaders', function (Blueprint $table) {
                $table->date('issuance_date')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('accountabilityHeaders', 'issuance_date')) {
            Schema::table('accountabilityHeaders', function (Blueprint $table) {
                $table->dropColumn('issuance_date');
            });
        }
    }
}
