<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surnname');
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->date('application_date');
            $table->foreignId('education_institution_id')->references('id')->on('education_institutions');
            $table->string('city');
            $table->string('status')->default('candidate');
            $table->string('course');
            $table->string('can_manage_data')->default('1');
            $table->foreignId('academy_id')->references('id')->on('academies');
            $table->string('CV')->nullable();
        
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
        Schema::dropIfExists('candidates');
    }
}
