<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * id
         * user_details
         * status
         * activity_id
         * cost
         * discount
         * created_at
         * */
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table
                ->foreignId('activity_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('cost')->unsigned();
            $table->decimal('discount')->unsigned()->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('adults_count')->nullable();
            $table->unsignedInteger('children_count')->nullable();
            $table->date('calendar_date');
            $table->json('user_details');
            $table->unsignedInteger('sessions_count')->nullable();
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
};
