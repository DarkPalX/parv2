<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameTransferValueToBookValue extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('accountabilityDetails', 'transfer_value') && !Schema::hasColumn('accountabilityDetails', 'book_value')) {
            DB::statement('ALTER TABLE `accountabilityDetails` CHANGE `transfer_value` `book_value` DECIMAL(16,2) NULL');
        }
    }

    public function down()
    {
        if (Schema::hasColumn('accountabilityDetails', 'book_value') && !Schema::hasColumn('accountabilityDetails', 'transfer_value')) {
            DB::statement('ALTER TABLE `accountabilityDetails` CHANGE `book_value` `transfer_value` DECIMAL(16,2) NULL');
        }
    }
}
