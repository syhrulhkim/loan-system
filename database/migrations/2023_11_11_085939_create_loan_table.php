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
        Schema::create('loan', function (Blueprint $table) {
            $table->id();
            $table->string("user_id")->nullable();
            $table->string("loan_id")->nullable();
            $table->string("start_date")->nullable();
            $table->string("end_date")->nullable();
            $table->integer("months")->nullable();
            $table->integer("interest")->nullable();
            $table->integer("amount_loan")->nullable();
            $table->integer("amount_loan_interest")->nullable();
            $table->integer("amount_paid")->nullable();
            $table->string("date_paid")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan');
    }
};
