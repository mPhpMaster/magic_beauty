<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("prescription_id");
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("pharmacist_id");
            $table->unsignedBigInteger("patient_id");
            $table->text("notes")->nullable();
            $table->string("status")->nullable()->default('pending');
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
        Schema::dropIfExists('prescription_histories');
    }
}
