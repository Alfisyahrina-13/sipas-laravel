<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $t) {
            $t->id();
            $t->string('nama_lokasi');
            $t->string('alamat')->nullable();
            $t->decimal('jarak_tpa', 8, 2);            // km
            $t->unsignedInteger('kepadatan_penduduk'); // jiwa di sekitar lokasi
            $t->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $t) {
            $t->id();
            $t->string('nama_armada');
            $t->string('plat_nomor')->unique();
            $t->unsignedInteger('kapasitas'); // kg
            $t->timestamps();
        });

        Schema::create('criterias', function (Blueprint $t) {
            $t->id();
            $t->string('kode', 10)->unique();
            $t->string('nama');
            $t->enum('tipe', ['benefit', 'cost']);
            $t->string('sumber'); // volume_kg | jarak_tpa | hari_sejak_angkut | kepadatan_penduduk
            $t->decimal('bobot', 5, 4);
            $t->timestamps();
        });

        Schema::create('waste_records', function (Blueprint $t) {
            $t->id();
            $t->foreignId('location_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->decimal('volume_kg', 10, 2);
            $t->date('terakhir_diangkut');
            $t->date('tanggal_input');
            $t->string('catatan')->nullable();
            $t->timestamps();
        });

        Schema::create('pickups', function (Blueprint $t) {
            $t->id();
            $t->foreignId('location_id')->constrained()->cascadeOnDelete();
            $t->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->date('tanggal_angkut');
            $t->enum('status', ['Dijadwalkan', 'Dalam Proses', 'Selesai'])->default('Dijadwalkan');
            $t->string('catatan')->nullable();
            $t->timestamps();
        });

        Schema::create('saw_results', function (Blueprint $t) {
            $t->id();
            $t->foreignId('location_id')->constrained()->cascadeOnDelete();
            $t->decimal('nilai_preferensi', 8, 4);
            $t->unsignedInteger('peringkat');
            $t->date('periode');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['saw_results', 'pickups', 'waste_records', 'criterias', 'vehicles', 'locations'] as $tbl) {
            Schema::dropIfExists($tbl);
        }
    }
};
