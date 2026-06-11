<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city', function (Blueprint $table) {
            $table->increments('city_id');
            $table->unsignedInteger('province_id');
            $table->string('city_code', 10);
            $table->string('city_name', 100);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('province_id')->references('province_id')->on('province')->onDelete('cascade');
        });

        DB::table('city')->insert([
            // NTB (province_id = 1)
            ['province_id' => 1, 'city_code' => 'MTR', 'city_name' => 'Kota Mataram',          'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'city_code' => 'Lobar', 'city_name' => 'Kabupaten Lombok Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'city_code' => 'Lotim', 'city_name' => 'Kabupaten Lombok Timur', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'city_code' => 'KLU', 'city_name' => 'Kabupaten Lombok Utara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 1, 'city_code' => 'Loteng', 'city_name' => 'Kabupaten Lombok Tengah', 'created_at' => now(), 'updated_at' => now()],
            // NTT (province_id = 2)
            ['province_id' => 2, 'city_code' => 'KPG', 'city_name' => 'Kota Kupang',            'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'city_code' => 'TTS', 'city_name' => 'Kabupaten TTS',          'created_at' => now(), 'updated_at' => now()],
            ['province_id' => 2, 'city_code' => 'TTU', 'city_name' => 'Kabupaten TTU',          'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};
