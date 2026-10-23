<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->string('kunci', 100)->primary();
            $table->longText('nilai')->nullable();
            $table->timestamps();
        });

        $pengumumanAwal = json_encode([
            'judul' => 'Pemberitahuan & Alur Pengajuan Perubahan Judul Skripsi',
            'subjudul' => 'Fakultas Pertanian — Harap membaca ketentuan dan alur sebelum melakukan pengajuan formulir',
            'isi' => "### 📌 Ketentuan & Prosedur Perubahan Judul Skripsi\n\nBagi mahasiswa Fakultas Pertanian yang bermaksud mengajukan perubahan judul skripsi karena adanya kendala atau penyesuaian di lapangan, wajib memperhatikan ketentuan berikut:\n\n1. **Konsultasi Awal Wajib:** Judul baru **harus sudah dikonsultasikan dan disepakati** terlebih dahulu bersama Dosen Pembimbing I dan Dosen Pembimbing II sebelum mengisi formulir online ini.\n2. **Kelengkapan Data:** Siapkan data identitas mahasiswa (NIM, Program Studi, Angkatan, Kelas, No. WhatsApp aktif, dan Email aktif).\n3. **Uraian Alasan yang Jelas:** Jelaskan secara spesifik alasan ketidakterlaksanaan judul lama di lapangan pada kolom yang disediakan.\n4. **Alur Persetujuan Online:**\n   - **Langkah 1:** Mahasiswa mengisi formulir pengajuan di menu **Ajukan Judul**.\n   - **Langkah 2:** Pengajuan otomatis masuk ke Kotak Masuk Dosen Pembimbing I & II untuk diperiksa dan disetujui secara elektronik.\n   - **Langkah 3:** Ketua Program Studi (Kaprodi) memeriksa dan mengesahkan dokumen.\n   - **Langkah 4:** Mahasiswa dapat memantau status secara berkala melalui menu **Cek Pengajuan** menggunakan NIM, serta mengunduh/mencetak Lembar Pengesahan ber-QR Code resmi.\n\n> ⚠️ **Catatan Penting:** Jika pengajuan Anda dinyatakan **Tidak Disetujui / Perlu Revisi** oleh Dosen Pembimbing atau Kaprodi, perhatikan catatan yang diberikan dan Anda dapat mengajukan judul baru setelah berkonsultasi kembali.",
            'aktif' => '1',
            'kontak' => 'Admin Akademik Faperta: Gedung A Lt. 2 / WhatsApp Layanan Akademik',
            'updated_at' => now()->toDateTimeString()
        ], JSON_UNESCAPED_UNICODE);

        DB::table('pengaturan')->insert([
            'kunci' => 'pengumuman',
            'nilai' => $pengumumanAwal,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
