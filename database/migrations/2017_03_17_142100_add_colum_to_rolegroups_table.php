<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumToRolegroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rolegroups', function (Blueprint $table) {
            $table->string('name', 50)->unique()->comment = "Tên vai trò";
            $table->unsignedTinyInteger('status')->default(1)->comment = "Trạng thái 0 và 1";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rolegroups', function (Blueprint $table) {
            //
        });
    }
}
