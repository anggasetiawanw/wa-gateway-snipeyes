<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldToCollectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collect', function (Blueprint $table) {
            $table->string('data')->nullable(); // Add new_field as a string
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collect', function (Blueprint $table) {
            $table->dropColumn('data'); // Drop the column if the migration is rolled back
        });
    }
}
