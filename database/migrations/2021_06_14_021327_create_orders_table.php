<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_type_id')->nullable()->default(0);
            $table->unsignedBigInteger('branch_id')->nullable()->default(0);
//            $table->double('vat')->nullable()->default(0);
//            $table->double('vat_percentage')->nullable()->default(0);
            $table->double('sub_total')->nullable()->default(0);
            $table->double('total')->nullable()->default(0);
            $table->text('note')->nullable();
            $table->string('status')->nullable()->default('pending');
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
        Schema::dropIfExists('orders');
    }
}
