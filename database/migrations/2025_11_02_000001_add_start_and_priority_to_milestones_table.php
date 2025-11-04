<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (! Schema::hasColumn('milestones', 'start_at')) {
                $table->timestamp('start_at')->nullable()->after('description');
            }
            if (! Schema::hasColumn('milestones', 'priority')) {
                $table->integer('priority')->default(0)->after('start_at');
            }
        });
    }

    public function down()
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (Schema::hasColumn('milestones', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('milestones', 'start_at')) {
                $table->dropColumn('start_at');
            }
        });
    }
};
