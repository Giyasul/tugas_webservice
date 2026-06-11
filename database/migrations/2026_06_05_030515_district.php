<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district', function (Blueprint $table) {
            $table->increments('district_id');
            $table->unsignedInteger('city_id');
            $table->string('district_code', 10);
            $table->string('district_name', 100);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')->references('city_id')->on('city')->onDelete('cascade');
        });

        DB::table('district')->insert([
            // Kota Mataram (city_id = 1)
            ['city_id' => 1, 'district_code' => 'AMR', 'district_name' => 'Ampenan',    'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 1, 'district_code' => 'MTM', 'district_name' => 'Mataram',    'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 1, 'district_code' => 'CPK', 'district_name' => 'Cakranegara', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 1, 'district_code' => 'SKR', 'district_name' => 'Sekarbela',  'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 1, 'district_code' => 'SLT', 'district_name' => 'Selaparang', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 1, 'district_code' => 'SND', 'district_name' => 'Sandubaya',  'created_at' => now(), 'updated_at' => now()],
            // Kabupaten Lombok Barat (city_id = 2)
            ['city_id' => 2, 'district_code' => 'GRP', 'district_name' => 'Gerung',     'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 2, 'district_code' => 'LBR', 'district_name' => 'Labuapi',    'created_at' => now(), 'updated_at' => now()],
            ['city_id' => 2, 'district_code' => 'KDR', 'district_name' => 'Kediri',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};
