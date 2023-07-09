<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger("company_id")->unsigned();
            $table->foreign('company_id')->references('id')->on('users');
            $table->bigInteger("job_id")->unsigned();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->enum("status", ["pending", "accepted", "rejected"])->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
