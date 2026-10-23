<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pengaturan')) {
            return;
        }

        $alurAwal = json_encode([
            'judul' => 'Alur Verifikasi Perubahan Judul Skripsi',
            'subjudul' => 'Tahapan pengajuan dan verifikasi elektronik perubahan judul skripsi',
            'steps' => [
                [
                    'nomor' => 'Langkah 1',
                    'judul' => 'Konsultasi Dospem',
                    'deskripsi' => 'Mahasiswa wajib berkonsultasi & menyepakati judul baru bersama Pembimbing I & II terlebih dahulu.'
                ],
                [
                    'nomor' => 'Langkah 2',
                    'judul' => 'Pengisian Formulir',
                    'deskripsi' => 'Mahasiswa melengkapi data diri, judul lama, alasan perubahan di lapangan, serta judul baru.'
                ],
                [
                    'nomor' => 'Langkah 3',
                    'judul' => 'Review Dosen & Kaprodi',
                    'deskripsi' => 'Dosen Pembimbing I, II dan Kaprodi meninjau pengajuan secara elektronik di sistem (menyetujui / meminta revisi).'
                ],
                [
                    'nomor' => 'Langkah 4',
                    'judul' => 'Dokumen Sah & Cetak',
                    'deskripsi' => 'Setelah disahkan Kaprodi, dokumen resmi ber-QR Code dapat diunduh dan dicetak secara mandiri.'
                ]
            ],
            'updated_at' => now()->toDateTimeString()
        ], JSON_UNESCAPED_UNICODE);

        $identitasAwal = json_encode([
            'nama_institusi' => 'INSTITUT PERTANIAN STIPER',
            'nama_fakultas' => 'FAKULTAS PERTANIAN',
            'alamat' => 'Jl. Nangka II, Maguwoharjo, Kec. Depok, Kabupaten Sleman, D.I. Yogyakarta 55282',
            'kontak' => 'Telp. (0274) 885803 · Email: pertanian@instiperjogja.ac.id',
            'logo_url' => '/images/logo-kampus.png',
            'updated_at' => now()->toDateTimeString()
        ], JSON_UNESCAPED_UNICODE);

        DB::table('pengaturan')->updateOrInsert(
            ['kunci' => 'alur_verifikasi'],
            [
                'nilai' => $alurAwal,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('pengaturan')->updateOrInsert(
            ['kunci' => 'identitas_kampus'],
            [
                'nilai' => $identitasAwal,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengaturan')) {
            DB::table('pengaturan')->whereIn('kunci', ['alur_verifikasi', 'identitas_kampus'])->delete();
        }
    }
};
