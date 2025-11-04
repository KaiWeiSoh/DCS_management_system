<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('email');
            $table->string('contact_number')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('contact_number');
            $table->string('subject_course')->nullable()->after('bio');
            $table->string('profile_picture')->nullable()->after('subject_course');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender','contact_number','bio','subject_course','profile_picture']);
        });
    }
};
