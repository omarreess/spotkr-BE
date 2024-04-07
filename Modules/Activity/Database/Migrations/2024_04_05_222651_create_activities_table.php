<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Activity\Enums\ActivityStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price')->unsigned()->nullable();
            $table->unsignedTinyInteger('discount')->nullable()->comment('percentage');
            $table->longText('description');
            $table
                ->foreignId('third_party_id')
                ->constrained((new User())->getTable())
                ->cascadeOnDelete();
            $table
                ->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('type');
            $table->decimal('rating_average', 2, 1)->unsigned()->default(0);
            $table->string('status')->default(ActivityStatusEnum::PENDING);
            $table->timestamp('hold_at')->nullable();
            $table->json('address')->nullable();
            $table->json('open_times')->nullable();
            $table->json('features')->comment('array of strings');
            $table->json('social_links')->nullable();
            $table->json('course_bundles')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
