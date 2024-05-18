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
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->double('opening_bell_price');
            $table->double('round_one_price');
            $table->double('round_two_price');
            $table->double('round_three_price');
            $table->double('round_four_price');
            $table->double('round_five_price');
            $table->double('round_six_price');
            $table->double('round_seven_price');
            $table->double('round_eight_price');
            $table->double('round_nine_price');
            $table->double('round_ten_price');
            $table->double('closing_bell_price');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
