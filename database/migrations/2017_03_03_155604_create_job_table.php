<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200)->comment = "Tên tác vụ";
            $table->text('description');
            $table->integer('project_id')->comment = "Thuộc dự án";
            $table->integer('channel_id')->comment = "Thuộc kênh";
            $table->unsignedTinyInteger('status')->default(1)->comment = "Trạng thái 0 và 1";
            $table->unsignedTinyInteger('job_type')->default(1)->comment = "0 tra truoc - 1 tra sau";
            $table->unsignedTinyInteger('is_payment')->default(1)->comment = "0 chua tra  - 1 tra roi";
            $table->date('date_finish')->comment = "Thời gian thanh toán tác vụ";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
