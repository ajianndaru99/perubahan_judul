<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Backend aplikasi Pengajuan Perubahan Judul Skripsi.
 * Satu pintu: POST /rpc {fn, args:[...]} -> memanggil method a_<fn>. Nama fungsi sama dengan versi Apps Script.
 */
class ApiController extends Controller
{
    public function rpc(Request $req)
    {
        $fn = 'a_' . preg_replace('/\W/', '', (string) $req->input('fn'));
        if (!method_exists($this, $fn)) return response()->json(['error' => 'Fungsi tidak dikenal'], 404);
        try {
            return response()->json(['data' => $this->$fn(...array_values((array) $req->input('args', [])))]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /* ---------- helper ---------- */
    private function err($m) { throw new \RuntimeException($m); }
    private function now() { return gmdate('Y-m-d\TH:i:s') . '.000Z'; }
    private function str($v) { return (string) ($v ?? ''); }
    private function rows($tbl, $q = null)
    {
        $q = $q ?: DB::table($tbl);
        return $q->get()->map(fn($x) => array_map(fn($v) => (string) ($v ?? ''), (array) $x))->all();
    }
    private function p($id)
    {
        $r = DB::table('pengajuan')->where('id', (string) $id)->first();
        if (!$r) $this->err('Pengajuan tidak ditemukan.');
        return array_map(fn($v) => (string) ($v ?? ''), (array) $r);
    }
    private function hash($p) { return Hash::make((string) $p, ['rounds' => 10]); }
    private function who($t)
    {
        $s = Cache::get('t_' . $t);
        if (!$s) $this->err('Sesi habis, silakan login ulang.');
        return $s;
    }
    private function admin($t)
    {
        $u = $this->who($t);
        if ($u['role'] !== 'admin') $this->err('Khusus admin.');
        return $u;
    }

    /* ---------- tanda tangan elektronik (HMAC dengan APP_KEY) ---------- */
    private function sig($txt, $n) { return strtoupper(substr(hash_hmac('sha256', $txt, config('app.key')), 0, $n)); }
    private function signer($r, $s) { return $s === '1' ? $r['d1'] : ($s === '2' ? $r['d2'] : $r['kaprodiUser']); }
    private function kode($r, $s)
    {
        $p = [$r['nim'], $r['judulBaru'], $s, $this->signer($r, $s), $r['t' . $s]];
        if ($r['m' . $s] !== '') $p[] = $r['m' . $s];
        return $this->sig(implode('|', $p), 10);
    }
    private function notes($r)
    {
        $c = $r['catatan'] ?? '';
        if ($c === '') return [];
        $p = json_decode($c, true);
        return is_array($p) ? $p : [['u' => '', 'o' => '', 't' => '', 'x' => $c]];
    }
    private function out($r)
    {
        $rejBy = [];
        foreach (['1', '2', 'k'] as $s) {
            if ($r['s' . $s] === '2') {
                $r['v' . $s] = '2'; // 2 = tidak disetujui / ditolak
                $label = $s === '1' ? 'Pembimbing I' : ($s === '2' ? 'Pembimbing II' : 'Kaprodi');
                $rejBy[] = $label;
            } else {
                $r['v' . $s] = ($r['s' . $s] === '1' && $r['k' . $s] === $this->kode($r, $s)) ? '1' : '0';
            }
        }
        // Kaprodi pengambil keputusan: dokumen sah jika Kaprodi telah mengesahkan
        $r['kodeDok'] = $r['vk'] === '1'
            ? $this->sig(implode('|', [$r['nim'], $r['judulBaru'], $r['v1'] === '1' ? $r['k1'] : '', $r['v2'] === '1' ? $r['k2'] : '', $r['kk']]), 12) : '';
        $r['notes'] = $this->notes($r);
        $r['ke'] = $r['id'] !== $r['nim'] ? substr($r['id'], strlen($r['nim']) + 1) : '1';
        $r['isRejected'] = count($rejBy) > 0;
        $r['rejectedBy'] = $rejBy;
        $r['adminIzinkanBaru'] = false;
        foreach ($r['notes'] as $n) {
            if (!empty($n['adminIzinkanBaru'])) {
                $r['adminIzinkanBaru'] = true;
                break;
            }
        }
        return $r;
    }

    /* ===== publik ===== */
    public function a_publik()
    {
        $pengumuman = null;
        $alur = null;
        $identitas = null;

        try {
            $rowPengumuman = DB::table('pengaturan')->where('kunci', 'pengumuman')->value('nilai');
            $pengumuman = $rowPengumuman ? json_decode($rowPengumuman, true) : null;

            $rowAlur = DB::table('pengaturan')->where('kunci', 'alur_verifikasi')->value('nilai');
            $alur = $rowAlur ? json_decode($rowAlur, true) : null;

            $rowIdentitas = DB::table('pengaturan')->where('kunci', 'identitas_kampus')->value('nilai');
            $identitas = $rowIdentitas ? json_decode($rowIdentitas, true) : null;
        } catch (\Throwable $e) {
            // Fallback graceful jika tabel atau database belum terhubung
        }

        if (!$alur || empty($alur['steps'])) {
            $alur = [
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
                ]
            ];
        }

        if (!$identitas) {
            $defaultLogo = '/images/logo-kampus.png';
            $logoFile = public_path('images/logo-kampus.png');
            if (!file_exists($logoFile)) $logoFile = public_path('logo-kampus.png');
            if (!file_exists($logoFile)) $logoFile = base_path('Logo_instiper-removebg-preview.png');
            if (file_exists($logoFile)) {
                $defaultLogo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
            }

            $identitas = [
                'nama_institusi' => 'INSTITUT PERTANIAN STIPER',
                'nama_fakultas' => 'FAKULTAS PERTANIAN',
                'alamat' => 'Jl. Nangka II, Maguwoharjo, Kec. Depok, Kabupaten Sleman, D.I. Yogyakarta 55282',
                'kontak' => 'Telp. (0274) 885803 · Email: pertanian@instiperjogja.ac.id',
                'logo_url' => $defaultLogo
            ];
        }

        $prodi = [];
        $dosen = [];
        try {
            $prodi = array_map(fn($x) => $x + ['id' => $x['nama']], $this->rows('prodi', DB::table('prodi')->orderBy('nama')));
            $dosen = array_map(fn($u) => ['username' => $u['username'], 'nama' => $u['nama'], 'prodi' => $u['prodi'], 'role' => $u['role']],
                $this->rows('akun', DB::table('akun')->where('role', '!=', 'admin')->orderBy('prodi')->orderBy('nama')));
        } catch (\Throwable $e) {
            // Silently fallback if DB is offline or not yet migrated
        }

        return [
            'url' => url('/'),
            'prodi' => $prodi,
            'dosen' => $dosen,
            'pengumuman' => $pengumuman,
            'alur_verifikasi' => $alur,
            'identitas_kampus' => $identitas,
        ];
    }

    public function a_submit($f)
    {
        $f = (array) $f;
        foreach (['prodi', 'nim', 'nama', 'angkatan', 'kelas', 'hp', 'email', 'd1', 'd2', 'judulLama', 'alasan', 'judulBaru'] as $k) {
            $f[$k] = trim($this->str($f[$k] ?? ''));
            if ($f[$k] === '') $this->err('Semua kolom wajib diisi.');
        }
        if (!preg_match('/^[0-9]{5,20}$/', $f['nim'])) $this->err('Format NIM tidak valid (harus berupa 5-20 digit angka).');
        if (!preg_match('/^[0-9]{9,15}$/', $f['hp'])) $this->err('Format No HP/WA tidak valid (harus berupa 9-15 digit angka).');
        if (!preg_match('/^[0-9]{4}$/', $f['angkatan'])) $this->err('Format Angkatan harus 4 digit tahun (contoh: 2024).');
        if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $f['email'])) $this->err('Format email tidak valid.');
        if ($f['d1'] === $f['d2']) $this->err('Dosen Pembimbing 1 dan 2 harus berbeda.');
        $pr = DB::table('prodi')->where('nama', $f['prodi'])->first();
        if (!$pr) $this->err('Program studi tidak valid.');
        if (empty($pr->kaprodiUser) || empty($pr->kaprodi)) {
            $this->err('Program studi ini belum memiliki Kaprodi yang terdaftar di sistem. Silakan hubungi admin prodi.');
        }
        $d1 = DB::table('akun')->where('username', $f['d1'])->where('role', '!=', 'admin')->first();
        $d2 = DB::table('akun')->where('username', $f['d2'])->where('role', '!=', 'admin')->first();
        if (!$d1 || !$d2) $this->err('Dosen pembimbing tidak valid.');

        return DB::transaction(function () use ($f, $pr, $d1, $d2) {
            $prev = $this->rows('pengajuan', DB::table('pengajuan')->where('nim', $f['nim'])->lockForUpdate());
            foreach ($prev as $x) {
                $ox = $this->out($x);
                if ($ox['isRejected'] && empty($ox['adminIzinkanBaru'])) {
                    $this->err('Pengajuan Anda sebelumnya berstatus Tidak Disetujui. Sesuai prosedur akademik, silakan hubungi Admin Program Studi/Fakultas untuk mereset status pengajuan atau membuka izin pengajuan baru.');
                }
                if ($ox['vk'] !== '1' && empty($ox['adminIzinkanBaru']) && !$ox['isRejected']) {
                    $this->err('Pengajuan sebelumnya masih dalam proses verifikasi Dosen/Kaprodi. Pengajuan baru dapat dibuat setelah pengajuan sebelumnya disahkan Kaprodi.');
                }
            }
            $mx = 0;
            foreach ($prev as $x) {
                $k = $x['id'] === $x['nim'] ? 1 : ((int) substr($x['id'], strlen($x['nim']) + 1) ?: 1);
                $mx = max($mx, $k);
            }
            DB::table('pengajuan')->insert([
                'id' => $f['nim'] . '-' . ($mx + 1), 'nim' => $f['nim'], 'prodi' => $f['prodi'], 'nama' => $f['nama'],
                'angkatan' => $f['angkatan'], 'kelas' => $f['kelas'], 'hp' => $f['hp'], 'email' => $f['email'],
                'd1' => $f['d1'], 'd2' => $f['d2'], 'd1Nama' => $d1->nama, 'd2Nama' => $d2->nama,
                'kaprodi' => $pr->kaprodi ?? '', 'kaprodiUser' => $pr->kaprodiUser ?? '',
                'judulLama' => $f['judulLama'], 'alasan' => $f['alasan'], 'judulBaru' => $f['judulBaru'], 'tgl' => $this->now(),
            ]);
            return true;
        });
    }

    public function a_cek($nim)
    {
        return array_map(fn($r) => $this->out($r),
            $this->rows('pengajuan', DB::table('pengajuan')->where('nim', trim($this->str($nim)))->orderBy('tgl')));
    }

    public function a_verifikasi($id)
    {
        $r = DB::table('pengajuan')->where('id', trim($this->str($id)))->first();
        if (!$r) return null;
        $r = $this->out(array_map(fn($v) => (string) ($v ?? ''), (array) $r));
        foreach (['hp', 'email', 'alasan', 'kaprodiUser', 'd1', 'd2'] as $k) unset($r[$k]);
        return $r;
    }

    /* ===== login & inbox ===== */
    public function a_login($username, $password)
    {
        $k = 'login:' . request()->ip();
        if (RateLimiter::tooManyAttempts($k, 8)) $this->err('Terlalu banyak percobaan. Coba lagi beberapa menit lagi.');
        $a = DB::table('akun')->where('username', trim($this->str($username)))->first();
        if (!$a || !Hash::check($this->str($password), $a->hash)) {
            RateLimiter::hit($k, 300);
            $this->err('Username atau password salah.');
        }
        RateLimiter::clear($k);
        $s = ['username' => $a->username, 'nama' => $a->nama, 'role' => $a->role];
        $token = (string) Str::uuid() . Str::random(16);
        Cache::put('t_' . $token, $s, now()->addHours(6));
        return $s + ['token' => $token];
    }

    public function a_inbox($t)
    {
        $u = $this->who($t);
        $q = DB::table('pengajuan')->orderBy('tgl', 'desc');
        if ($u['role'] !== 'admin') {
            $q->where(function ($w) use ($u) {
                $w->where('d1', $u['username'])->orWhere('d2', $u['username']);
                if ($u['role'] === 'kaprodi') $w->orWhere('kaprodiUser', $u['username']);
            });
        }
        return array_map(fn($r) => $this->out($r), $this->rows('pengajuan', $q));
    }

    public function a_gantiPasswordSaya($t, $lama, $baru)
    {
        $s = $this->who($t);
        $a = DB::table('akun')->where('username', $s['username'])->first();
        if (!$a) $this->err('Akun tidak ditemukan.');
        if (!Hash::check($this->str($lama), $a->hash)) $this->err('Password lama salah.');
        if (strlen($this->str($baru)) < 6) $this->err('Password baru minimal 6 karakter.');
        DB::table('akun')->where('username', $s['username'])->update(['hash' => $this->hash($baru)]);
        return true;
    }

    /* ===== persetujuan ===== */
    // peran: 'dosen' | 'kaprodi' | kosong (semua peran yang dimiliki pada pengajuan ini). Kaprodi dapat mengesahkan kapan saja.
    public function a_setuju($t, $id, $peran = null)
    {
        $u = $this->who($t);
        return DB::transaction(function () use ($u, $id, $peran) {
            $r = $this->p($id);
            $doD = !$peran || $peran === 'dosen';
            $doK = !$peran || $peran === 'kaprodi';
            $o = []; $now = $this->now(); $n = 0;
            if ($doD) foreach (['1', '2'] as $s) {
                if ($r['d' . $s] === $u['username'] && $r['s' . $s] !== '1') {
                    $r['t' . $s] = $now; $r['s' . $s] = '1';
                    $o['s' . $s] = '1'; $o['t' . $s] = $now; $o['k' . $s] = $this->kode($r, $s); $n++;
                }
            }
            if ($doK && $u['role'] === 'kaprodi' && $r['kaprodiUser'] === $u['username'] && $r['sk'] !== '1') {
                $r['tk'] = $now; $o['sk'] = '1'; $o['tk'] = $now; $o['kk'] = $this->kode($r, 'k'); $n++;
            }
            if (!$n) $this->err('Tidak ada persetujuan yang dapat Anda berikan.');
            DB::table('pengajuan')->where('id', $id)->update($o);
            return true;
        });
    }

    public function a_batal($t, $id, $peran = null)
    {
        $u = $this->who($t);
        $r = $this->p($id);
        $doD = !$peran || $peran === 'dosen';
        $doK = !$peran || $peran === 'kaprodi';

        if ($doD && $r['sk'] === '1' && ($r['d1'] === $u['username'] || $r['d2'] === $u['username'])) {
            $this->err('Pengajuan ini sudah disahkan oleh Kaprodi. Pembatalan persetujuan pembimbing tidak dapat dilakukan kecuali pengesahan Kaprodi dibatalkan terlebih dahulu.');
        }

        $o = []; $n = 0;
        $clr = function ($s) use (&$o) { foreach (['s', 't', 'k', 'm'] as $p) $o[$p . $s] = ''; };
        if ($doD) foreach (['1', '2'] as $s) {
            if ($r['d' . $s] === $u['username'] && $r['s' . $s] === '1' && $r['m' . $s] === '') { $clr($s); $n++; }
        }
        if ($doK && $u['role'] === 'kaprodi' && $r['kaprodiUser'] === $u['username'] && $r['sk'] === '1' && $r['mk'] === '') { $clr('k'); $n++; }
        if (!$n) $this->err('Tidak ada persetujuan Anda yang dapat dibatalkan.');
        DB::table('pengajuan')->where('id', $id)->update($o);
        return true;
    }

    public function a_tolak($t, $id, $peran, $alasan)
    {
        $u = $this->who($t);
        $alasan = trim($this->str($alasan));
        if ($alasan === '') $this->err('Alasan tidak disetujui / catatan revisi wajib diisi.');
        return DB::transaction(function () use ($u, $id, $peran, $alasan) {
            $r = $this->p($id);
            $doD = !$peran || $peran === 'dosen';
            $doK = !$peran || $peran === 'kaprodi';
            $o = []; $now = $this->now(); $n = 0; $peranList = [];

            if ($doD) foreach (['1', '2'] as $s) {
                if ($r['d' . $s] === $u['username']) {
                    $o['s' . $s] = '2'; // 2 = tidak disetujui
                    $o['t' . $s] = $now;
                    $o['k' . $s] = '';
                    $n++;
                    $peranList[] = $s === '1' ? 'Pembimbing I' : 'Pembimbing II';
                }
            }
            if ($doK && $u['role'] === 'kaprodi' && $r['kaprodiUser'] === $u['username']) {
                $o['sk'] = '2'; // 2 = tidak disetujui kaprodi
                $o['tk'] = $now;
                $o['kk'] = '';
                $n++;
                $peranList[] = 'Kaprodi';
            }
            if (!$n) $this->err('Tidak ada peran Anda yang dapat memberikan penolakan pada pengajuan ini.');

            $notes = array_values(array_filter($this->notes($r), fn($item) => ($item['u'] ?? '') !== $u['username']));
            $notes[] = [
                'u' => $u['username'],
                'o' => $u['nama'] . ' (' . implode(' + ', $peranList) . ')',
                't' => $now,
                'x' => $alasan,
                'status' => 'tolak'
            ];
            $o['catatan'] = json_encode($notes, JSON_UNESCAPED_UNICODE);

            DB::table('pengajuan')->where('id', $id)->update($o);
            return true;
        });
    }

    public function a_batalTolak($t, $id, $peran = null)
    {
        $u = $this->who($t);
        $r = $this->p($id);
        $doD = !$peran || $peran === 'dosen';
        $doK = !$peran || $peran === 'kaprodi';
        $o = []; $n = 0;
        if ($doD) foreach (['1', '2'] as $s) {
            if ($r['d' . $s] === $u['username'] && $r['s' . $s] === '2') {
                $o['s' . $s] = ''; $o['t' . $s] = ''; $o['k' . $s] = ''; $n++;
            }
        }
        if ($doK && $u['role'] === 'kaprodi' && $r['kaprodiUser'] === $u['username'] && $r['sk'] === '2') {
            $o['sk'] = ''; $o['tk'] = ''; $o['kk'] = ''; $n++;
        }
        if (!$n) $this->err('Tidak ada status penolakan Anda yang dapat dibatalkan.');
        // Bersihkan catatan penolakan terkait agar data tetap konsisten
        $notes = array_values(array_filter($this->notes($r), fn($item) => ($item['u'] ?? '') !== $u['username']));
        $o['catatan'] = $notes ? json_encode($notes, JSON_UNESCAPED_UNICODE) : '';
        DB::table('pengajuan')->where('id', $id)->update($o);
        return true;
    }

    public function a_adminResetPengajuan($t, $id, $mode)
    {
        $a = $this->admin($t);
        $r = $this->p($id);
        if ($mode === 'hapus') {
            DB::table('pengajuan')->where('id', $id)->delete();
            return 'Pengajuan berhasil dihapus permanen.';
        }
        if ($mode === 'review_ulang') {
            $notes = array_values(array_filter($this->notes($r), fn($item) => empty($item['status']) || $item['status'] !== 'tolak'));
            $notes[] = [
                'u' => $a['username'],
                'o' => 'Administrator',
                't' => $this->now(),
                'x' => 'Admin telah mereset status penolakan agar pengajuan dapat ditinjau ulang oleh Dosen Pembimbing/Kaprodi.'
            ];
            DB::table('pengajuan')->where('id', $id)->update([
                's1' => $r['s1'] === '2' ? '' : $r['s1'],
                't1' => $r['s1'] === '2' ? '' : $r['t1'],
                's2' => $r['s2'] === '2' ? '' : $r['s2'],
                't2' => $r['s2'] === '2' ? '' : $r['t2'],
                'sk' => $r['sk'] === '2' ? '' : $r['sk'],
                'tk' => $r['sk'] === '2' ? '' : $r['tk'],
                'catatan' => json_encode($notes, JSON_UNESCAPED_UNICODE)
            ]);
            return 'Status pengajuan berhasil direset ke status Menunggu (Review Ulang).';
        }
        if ($mode === 'izin_baru') {
            $notes = $this->notes($r);
            $notes[] = [
                'u' => $a['username'],
                'o' => 'Administrator',
                't' => $this->now(),
                'x' => 'Admin telah memberikan izin kepada mahasiswa untuk mengajukan judul baru.',
                'adminIzinkanBaru' => true
            ];
            DB::table('pengajuan')->where('id', $id)->update([
                'catatan' => json_encode($notes, JSON_UNESCAPED_UNICODE)
            ]);
            return 'Izin berhasil diberikan. Mahasiswa sekarang dapat mengisi formulir pengajuan judul baru.';
        }
        $this->err('Mode reset tidak valid.');
    }

    // Admin mengesahkan persetujuan manual (tanda tangan basah sudah diterima): slot '1' | '2' | 'k'
    public function a_adminSetuju($t, $id, $slot)
    {
        $a = $this->admin($t);
        if (!in_array($slot, ['1', '2', 'k'], true)) $this->err('Penandatangan tidak valid.');
        return DB::transaction(function () use ($a, $id, $slot) {
            $r = $this->p($id);
            if ($r['s' . $slot] === '1') $this->err('Sudah disetujui.');
            $now = $this->now(); $r['t' . $slot] = $now; $r['m' . $slot] = $a['username'];
            DB::table('pengajuan')->where('id', $id)->update([
                's' . $slot => '1', 't' . $slot => $now, 'm' . $slot => $a['username'], 'k' . $slot => $this->kode($r, $slot),
            ]);
            return true;
        });
    }

    public function a_adminBatal($t, $id, $slot)
    {
        $this->admin($t);
        if (!in_array($slot, ['1', '2', 'k'], true)) $this->err('Penandatangan tidak valid.');
        $this->p($id);
        DB::table('pengajuan')->where('id', $id)->update(['s' . $slot => '', 't' . $slot => '', 'k' . $slot => '', 'm' . $slot => '']);
        return true;
    }

    // Catatan: Dosen Pembimbing 1/2 terkait, Kaprodi prodi terkait, atau admin. Tiap orang mengelola catatannya sendiri.
    public function a_simpanCatatan($t, $id, $teks)
    {
        $u = $this->who($t);
        return DB::transaction(function () use ($u, $id, $teks) {
            $r = $this->p($id);
            $peran = [];
            if ($r['d1'] === $u['username']) $peran[] = 'Pembimbing I';
            if ($r['d2'] === $u['username']) $peran[] = 'Pembimbing II';
            if ($u['role'] === 'kaprodi' && $r['kaprodiUser'] === $u['username']) $peran[] = 'Kaprodi';
            if ($u['role'] === 'admin') $peran[] = 'Admin';
            if (!$peran) $this->err('Hanya Dosen Pembimbing, Kaprodi prodi terkait, atau admin yang dapat menambah catatan.');
            $teks = mb_substr(trim($this->str($teks)), 0, 300);
            $list = array_values(array_filter($this->notes($r), fn($n) => ($n['u'] ?? '') !== $u['username']));
            if ($teks !== '') $list[] = ['u' => $u['username'], 'o' => $u['nama'] . ' (' . implode(' + ', $peran) . ')', 't' => $this->now(), 'x' => $teks];
            DB::table('pengajuan')->where('id', $id)->update(['catatan' => $list ? json_encode($list, JSON_UNESCAPED_UNICODE) : '']);
            return true;
        });
    }

    /* ===== admin ===== */
    public function a_semuaPengajuan($t)
    {
        $this->admin($t);
        return array_map(fn($r) => $this->out($r), $this->rows('pengajuan', DB::table('pengajuan')->orderBy('tgl', 'desc')));
    }

    public function a_hapusPengajuan($t, $id)
    {
        $this->admin($t);
        DB::table('pengajuan')->where('id', (string) $id)->delete();
        return true;
    }

    public function a_users($t)
    {
        $this->admin($t);
        return $this->rows('akun', DB::table('akun')->select('username', 'nama', 'role', 'prodi', 'nidn')->orderBy('role')->orderBy('nama'));
    }

    public function a_simpanUser($t, $u)
    {
        $this->admin($t);
        $u = (array) $u;
        $un = trim($this->str($u['username'] ?? ''));
        if ($un === '') $this->err('Username wajib.');
        if (!in_array($u['role'] ?? '', ['admin', 'kaprodi', 'dosen'], true)) $this->err('Role tidak valid.');
        $role = $u['role'];
        $prodi = trim($this->str($u['prodi'] ?? ''));
        $nama = trim($this->str($u['nama'] ?? '')) ?: $un;

        if ($role === 'kaprodi' && $prodi === '') {
            $this->err('Dosen harus memiliki Program Studi untuk ditetapkan sebagai Kaprodi.');
        }

        return DB::transaction(function () use ($un, $nama, $role, $prodi, $u) {
            $ada = DB::table('akun')->where('username', $un)->first();
            if (!$ada && empty($u['password'])) $this->err('Username & password wajib.');

            if ($role === 'kaprodi') {
                // Turunkan kaprodi sebelumnya di prodi ini menjadi dosen biasa
                DB::table('akun')->where('prodi', $prodi)->where('role', 'kaprodi')->where('username', '!=', $un)->update(['role' => 'dosen']);
                // Sinkronkan ke tabel prodi
                DB::table('prodi')->where('nama', $prodi)->update([
                    'kaprodi' => $nama,
                    'kaprodiUser' => $un,
                ]);
            } else {
                // Jika sebelumnya user ini adalah kaprodi, bersihkan dari prodi
                if ($ada && $ada->role === 'kaprodi') {
                    DB::table('prodi')->where('kaprodiUser', $un)->update(['kaprodi' => '', 'kaprodiUser' => '']);
                }
            }

            $o = ['nama' => $nama, 'role' => $role, 'prodi' => $prodi];
            if (!empty($u['password'])) $o['hash'] = $this->hash($u['password']);
            DB::table('akun')->updateOrInsert(['username' => $un], $o);
            return true;
        });
    }

    public function a_hapusUser($t, $username)
    {
        $a = $this->admin($t);
        if ($a['username'] === $username) $this->err('Tidak bisa menghapus akun sendiri.');
        return DB::transaction(function () use ($username) {
            DB::table('prodi')->where('kaprodiUser', (string) $username)->update(['kaprodi' => '', 'kaprodiUser' => '']);
            DB::table('akun')->where('username', (string) $username)->delete();
            return true;
        });
    }

    // Hapus massal berdasarkan role ('dosen' atau 'kaprodi'). Akun admin tidak bisa dihapus massal.
    public function a_hapusUserRole($t, $role)
    {
        $this->admin($t);
        if (!in_array($role, ['dosen', 'kaprodi'], true)) $this->err('Role tidak valid. Akun admin tidak dapat dihapus massal.');
        return DB::transaction(function () use ($role) {
            if ($role === 'kaprodi') {
                DB::table('prodi')->update(['kaprodi' => '', 'kaprodiUser' => '']);
            }
            return DB::table('akun')->where('role', $role)->delete();
        });
    }

    public function a_simpanProdi($t, $p)
    {
        $this->admin($t);
        $p = (array) $p;
        $nama = trim($this->str($p['nama'] ?? ''));
        if ($nama === '') $this->err('Nama prodi wajib.');
        $ada = DB::table('prodi')->where('nama', $nama)->exists();
        if (!$ada) {
            DB::table('prodi')->insert(['nama' => $nama, 'kaprodi' => '', 'kaprodiUser' => '']);
        }
        return true;
    }

    public function a_hapusProdi($t, $id)
    {
        $this->admin($t);
        return DB::transaction(function () use ($id) {
            DB::table('prodi')->where('nama', (string) $id)->delete();
            DB::table('akun')->where('prodi', (string) $id)->update(['prodi' => '']);
            return true;
        });
    }

    public function a_importDosen($t, $rows)
    {
        $this->admin($t);
        set_time_limit(0);
        $n = 0;
        foreach ((array) $rows as $r) {
            $nidn = trim($this->str($r['nidn'] ?? ''));
            $nama = trim($this->str($r['nama'] ?? ''));
            if (preg_match('/^\d{8,9}$/', $nidn)) $nidn = substr('0000000000' . $nidn, -10); // nol di depan yang hilang dari Excel
            if ($nidn === '' || $nama === '') continue;
            $ada = DB::table('akun')->where('username', $nidn)->exists();
            $data = [
                'nama' => $nama,
                'role' => 'dosen',
                'prodi' => trim($this->str($r['prodi'] ?? '')),
                'nidn' => $nidn,
            ];
            if (!$ada) {
                $data['hash'] = $this->hash($nidn);
            }
            DB::table('akun')->updateOrInsert(['username' => $nidn], $data);
            $n++;
        }
        return $n;
    }

    public function a_simpanPengumuman($t, $p)
    {
        $this->admin($t);
        $p = (array) $p;
        $judul = trim($this->str($p['judul'] ?? ''));
        $subjudul = trim($this->str($p['subjudul'] ?? ''));
        $isi = trim($this->str($p['isi'] ?? ''));
        $kontak = trim($this->str($p['kontak'] ?? ''));
        $aktif = !empty($p['aktif']) ? '1' : '0';

        if ($judul === '' || $isi === '') $this->err('Judul dan isi pengumuman wajib diisi.');

        $data = json_encode([
            'judul' => $judul,
            'subjudul' => $subjudul,
            'isi' => $isi,
            'kontak' => $kontak,
            'aktif' => $aktif,
            'updated_at' => now()->toDateTimeString(),
        ], JSON_UNESCAPED_UNICODE);

        DB::table('pengaturan')->updateOrInsert(['kunci' => 'pengumuman'], [
            'nilai' => $data,
            'updated_at' => now(),
        ]);
        return true;
    }

    public function a_simpanAlurVerifikasi($t, $p)
    {
        $this->admin($t);
        $p = (array) $p;
        $judul = trim($this->str($p['judul'] ?? 'Alur Verifikasi Perubahan Judul Skripsi'));
        $subjudul = trim($this->str($p['subjudul'] ?? ''));
        $rawSteps = (array) ($p['steps'] ?? []);
        $steps = [];

        foreach ($rawSteps as $idx => $s) {
            $s = (array) $s;
            $nomor = trim($this->str($s['nomor'] ?? ('Langkah ' . ($idx + 1))));
            $j = trim($this->str($s['judul'] ?? ''));
            $d = trim($this->str($s['deskripsi'] ?? ''));
            if ($j === '' && $d === '') continue;
            $steps[] = [
                'nomor' => $nomor !== '' ? $nomor : ('Langkah ' . ($idx + 1)),
                'judul' => $j,
                'deskripsi' => $d,
            ];
        }

        if (empty($steps)) {
            $this->err('Alur verifikasi minimal harus memiliki 1 langkah.');
        }

        $data = json_encode([
            'judul' => $judul !== '' ? $judul : 'Alur Verifikasi Perubahan Judul Skripsi',
            'subjudul' => $subjudul,
            'steps' => $steps,
            'updated_at' => now()->toDateTimeString(),
        ], JSON_UNESCAPED_UNICODE);

        DB::table('pengaturan')->updateOrInsert(['kunci' => 'alur_verifikasi'], [
            'nilai' => $data,
            'updated_at' => now(),
        ]);
        return true;
    }

    public function a_simpanIdentitasKampus($t, $p)
    {
        $this->admin($t);
        $p = (array) $p;
        $namaInstitusi = trim($this->str($p['nama_institusi'] ?? 'INSTITUT PERTANIAN STIPER'));
        $namaFakultas = trim($this->str($p['nama_fakultas'] ?? 'FAKULTAS PERTANIAN'));
        $alamat = trim($this->str($p['alamat'] ?? ''));
        $kontak = trim($this->str($p['kontak'] ?? ''));
        $logoUrl = trim($this->str($p['logo_url'] ?? '/images/logo-kampus.png'));

        if (!empty($p['logo_base64']) && preg_match('/^data:image\/(png|jpeg|jpg|webp);base64,/', (string) $p['logo_base64'])) {
            $b64 = (string) $p['logo_base64'];
            $parts = explode(',', $b64, 2);
            $ext = str_contains($parts[0], 'png') ? 'png' : (str_contains($parts[0], 'webp') ? 'webp' : 'jpg');
            $imgData = base64_decode($parts[1]);
            if ($imgData !== false && strlen($imgData) <= 2 * 1024 * 1024) {
                $filename = 'logo-kampus-custom.' . $ext;
                $target = public_path('images/' . $filename);
                if (!is_dir(public_path('images'))) {
                    mkdir(public_path('images'), 0755, true);
                }
                file_put_contents($target, $imgData);
                $logoUrl = '/images/' . $filename . '?t=' . time();
            }
        }

        $data = json_encode([
            'nama_institusi' => $namaInstitusi,
            'nama_fakultas' => $namaFakultas,
            'alamat' => $alamat,
            'kontak' => $kontak,
            'logo_url' => $logoUrl,
            'updated_at' => now()->toDateTimeString(),
        ], JSON_UNESCAPED_UNICODE);

        DB::table('pengaturan')->updateOrInsert(['kunci' => 'identitas_kampus'], [
            'nilai' => $data,
            'updated_at' => now(),
        ]);
        return true;
    }
}
