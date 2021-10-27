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
            $table->string('education_institution');
            $table->foreign('education_institution')->references('name')->on('education_institutions');
            $table->string('city');
            $table->string('status')->default('candidate');
            $table->string('course');
            $table->string('academy');
            $table->foreign('academy')->references('name')->on('academies');
            $table->string('comment',1000)->default('');
            $table->string('CV')->default('');
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
