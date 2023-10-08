<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('TH', function (Blueprint $table) {
            $table->id();
            $table->string('record_type', 2);
            $table->json('scheme');
            $table->string('extracted_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobtask');
    }
};
