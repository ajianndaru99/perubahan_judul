<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $t) {
            $t->string('nama')->primary();
            $t->string('kaprodi')->default('');
            $t->string('kaprodiUser')->default('');
        });
        Schema::create('akun', function (Blueprint $t) {
            $t->string('username')->primary();
            $t->string('hash');
            $t->string('nama');
            $t->string('role')->index();          // admin | kaprodi | dosen
            $t->string('prodi')->default('');
            $t->string('nidn')->default('');
        });
        Schema::create('pengajuan', function (Blueprint $t) {
            $t->string('id')->primary();          // NIM-urutan (satu NIM boleh punya banyak pengajuan = riwayat)
            foreach (['nim', 'prodi', 'nama', 'angkatan', 'kelas', 'hp', 'email', 'd1', 'd2', 'd1Nama', 'd2Nama', 'kaprodiUser', 'kaprodi', 'tgl',
                      's1', 't1', 'k1', 's2', 't2', 'k2', 'sk', 'tk', 'kk', 'm1', 'm2', 'mk'] as $c) {
                $t->string($c)->default('');
            }
            foreach (['judulLama', 'alasan', 'judulBaru', 'catatan'] as $c) $t->text($c)->nullable();
            $t->index('nim');
            $t->index('d1');
            $t->index('d2');
            $t->index('kaprodiUser');
        });

        DB::table('akun')->insert(['username' => 'admin', 'hash' => Hash::make('admin', ['rounds' => 10]), 'nama' => 'Administrator', 'role' => 'admin']);
        DB::table('prodi')->insert([['nama' => 'Agroteknologi'], ['nama' => 'Agribisnis']]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
        Schema::dropIfExists('akun');
        Schema::dropIfExists('prodi');
    }
};
