<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Pengajuan Perubahan Judul Skripsi — Fakultas Pertanian</title>
  <script>window.__V=@json($verify);</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@verbatim
<style>
:root {
  --bg: #f8fafc;
  --card: #ffffff;
  --tx: #0f172a;
  --tx-muted: #64748b;
  --ac: #166534;
  --ac-hover: #14532d;
  --ac-light: #f0fdf4;
  --ac-border: #bbf7d0;
  --bd: #e2e8f0;
  --dg: #dc2626;
  --dg-hover: #b91c1c;
  --dg-light: #fef2f2;
  --dg-border: #fecaca;
  --warn: #d97706;
  --warn-light: #fffbeb;
  --warn-border: #fde68a;
  --succ: #16a34a;
  --succ-light: #ecfdf5;
  --succ-border: #a7f3d0;
  --radius: 12px;
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
}
* { box-sizing: border-box; }
body {
  margin: 0;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--tx);
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Header */
header {
  background: linear-gradient(135deg, #123e23 0%, #1e5e34 100%);
  color: #fff;
  padding: 14px 24px;
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.brand { display: flex; align-items: center; gap: 12px; }
.brand-icon {
  width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.15);
  display: flex; align-items: center; justify-content: center; font-size: 22px;
  border: 1px solid rgba(255,255,255,0.2);
}
.brand-text h1 { font-size: 17px; margin: 0; font-weight: 700; letter-spacing: -0.2px; }
.brand-text p { font-size: 12px; margin: 2px 0 0; opacity: 0.85; font-weight: 400; }
#who {
  font-size: 13px; font-weight: 600; background: rgba(255,255,255,0.15);
  padding: 6px 14px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.25);
  display: flex; align-items: center; gap: 6px;
}

/* Navigation */
nav {
  display: flex; flex-wrap: wrap; gap: 8px; padding: 12px 24px;
  background: #ffffff; border-bottom: 1px solid var(--bd); position: sticky; top: 0; z-index: 10;
}
button {
  background: var(--ac); color: #fff; border: 0; border-radius: 9px;
  padding: 9px 16px; cursor: pointer; font: inherit; font-size: 14px; font-weight: 600;
  display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease;
}
button:hover { background: var(--ac-hover); transform: translateY(-1px); }
button.s {
  background: #ffffff; color: var(--tx); border: 1px solid var(--bd);
}
button.s:hover { background: #f1f5f9; border-color: #cbd5e1; color: var(--ac); }
button.d {
  background: var(--dg); color: #fff; border: 0;
}
button.d:hover { background: var(--dg-hover); }
button.d-outline {
  background: transparent; color: var(--dg); border: 1px solid var(--dg-border);
}
button.d-outline:hover { background: var(--dg-light); }
button:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

/* Main Container & Cards */
main { flex: 1; width: 100%; max-width: 1180px; margin: 0 auto; padding: 20px 20px 40px; }
footer {
  margin-top: auto;
  background: #ffffff;
  border-top: 1px solid var(--bd);
  padding: 16px 24px;
  font-size: 13px;
  color: var(--tx-muted);
}
.footer-inner {
  max-width: 1180px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}
.footer-inner a {
  color: var(--tx-muted);
  text-decoration: none;
  font-size: 12px;
  transition: color .15s;
}
.footer-inner a:hover { color: var(--ac); }
.card {
  background: var(--card); border: 1px solid var(--bd); border-radius: var(--radius);
  padding: 24px; margin-bottom: 20px; box-shadow: var(--shadow);
}
.card h2, .card h3 {
  margin-top: 0; margin-bottom: 14px; font-weight: 700; color: #0f172a;
}
.card-header {
  display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 16px;
}
label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin: 12px 0 4px; }
input, select, textarea {
  width: 100%; padding: 10px 12px; border: 1px solid var(--bd); border-radius: 8px;
  font: inherit; font-size: 14px; color: var(--tx); background: #fff; transition: border-color .15s;
}
input:focus, select:focus, textarea:focus {
  outline: none; border-color: var(--ac); box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.12);
}
.g { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0 16px; }

/* Tables & Judul Truncation */
.tw { margin-top: 12px; border: 1px solid var(--bd); border-radius: 10px; background: #fff; overflow-x: auto; }
table { border-collapse: collapse; width: 100%; background: #fff; table-layout: auto; }
th {
  background: #f8fafc; font-weight: 700; font-size: 13px; color: #475569;
  padding: 11px 14px; border-bottom: 1px solid var(--bd); text-align: left;
}
td { padding: 12px 14px; border-bottom: 1px solid var(--bd); font-size: 13.5px; vertical-align: top; }
tr:last-child td { border-bottom: 0; }
tr:hover td { background: #fbfcfe; }

.judul-box { position: relative; }
.judul-text.short {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: 600;
  line-height: 1.45;
  font-size: 13.5px;
  color: #0f172a;
  word-break: break-word;
}
.judul-text.full {
  display: block;
  font-weight: 600;
  line-height: 1.45;
  font-size: 13.5px;
  color: #0f172a;
  word-break: break-word;
}
.btn-expand {
  background: none;
  border: none;
  color: var(--ac);
  font-size: 12px;
  font-weight: 700;
  padding: 4px 0 0;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  text-decoration: underline;
}
.btn-expand:hover { color: var(--ac-hover); }
tr:last-child td { border-bottom: 0; }
tr:hover td { background: #fbfcfe; }

/* Status Badges */
.badge {
  display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600;
  padding: 3px 9px; border-radius: 999px; white-space: nowrap;
}
.badge-ok { background: var(--succ-light); color: var(--succ); border: 1px solid var(--succ-border); }
.badge-wait { background: var(--warn-light); color: var(--warn); border: 1px solid var(--warn-border); }
.badge-rej { background: var(--dg-light); color: var(--dg); border: 1px solid var(--dg-border); }
.badge-role { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 11px; }

/* Alert Messages */
.msg {
  padding: 12px 16px; border-radius: 9px; background: var(--succ-light);
  border: 1px solid var(--succ-border); color: #065f46; margin: 12px 0; font-size: 14px;
}
.err {
  color: #991b1b; background: var(--dg-light); border-color: var(--dg-border);
}

/* Hero / Announcement Board */
.hero-banner {
  background: linear-gradient(135deg, #f0fdf4 0%, #e6f7ec 100%);
  border: 1px solid #bbf7d0; border-radius: var(--radius); padding: 24px 28px;
  margin-bottom: 24px; position: relative;
}
.hero-badge {
  display: inline-block; background: #166534; color: #fff; font-size: 12px; font-weight: 700;
  padding: 3px 10px; border-radius: 999px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .5px;
}
.hero-title { font-size: 22px; font-weight: 800; color: #14532d; margin: 0 0 8px; line-height: 1.3; }
.hero-subtitle { font-size: 14px; color: #334155; margin: 0 0 16px; }
.hero-body {
  font-size: 14.5px; color: #1e293b; line-height: 1.65; background: #fff;
  padding: 18px 20px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 20px;
}
.hero-body ul, .hero-body ol { margin: 8px 0; padding-left: 22px; }
.hero-body li { margin-bottom: 6px; }
.hero-cta { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }

/* Process Steps Grid */
.steps-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 14px; margin: 20px 0 10px;
}
.step-card {
  background: #fff; border: 1px solid var(--bd); border-radius: 10px; padding: 14px;
  position: relative; transition: all .2s;
}
.step-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: var(--ac); }
.step-num {
  font-size: 12px; font-weight: 800; color: var(--ac); background: var(--ac-light);
  display: inline-block; padding: 2px 8px; border-radius: 6px; margin-bottom: 8px;
}
.step-title { font-size: 13.5px; font-weight: 700; margin: 0 0 4px; color: #0f172a; }
.step-desc { font-size: 12px; color: #64748b; margin: 0; line-height: 1.45; }

/* Stepper Tracker for Mahasiswa */
.track-step {
  display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px;
  border-radius: 10px; background: #f8fafc; border: 1px solid var(--bd); margin-bottom: 8px;
}
.track-icon {
  width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center;
  justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0;
}

/* Modal Dialog */
.modal-backdrop {
  position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(3px);
  display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 1000;
}
.modal-box {
  background: #fff; border-radius: 16px; max-width: 540px; width: 100%;
  box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);
  overflow: hidden; animation: popIn .2s ease-out;
}
@keyframes popIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-header {
  padding: 18px 22px; border-bottom: 1px solid var(--bd); display: flex;
  justify-content: space-between; align-items: center; background: #fafafa;
}
.modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
.modal-body { padding: 20px 22px; max-height: 75vh; overflow-y: auto; }
.modal-footer {
  padding: 14px 22px; border-top: 1px solid var(--bd); display: flex;
  justify-content: flex-end; gap: 10px; background: #fafafa;
}

/* Review Notes Card */
.notes-container { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
.note-item {
  border-left: 4px solid var(--ac); background: #fdfefe; border: 1px solid var(--bd);
  border-left-width: 4px; border-radius: 8px; padding: 12px 14px; font-size: 13.5px;
}
.note-item.note-tolak {
  border-left-color: var(--dg); background: var(--dg-light); border-color: var(--dg-border);
}
.note-author { font-weight: 700; font-size: 12.5px; color: var(--ac); margin-bottom: 4px; display: flex; justify-content: space-between; flex-wrap: wrap; }
.note-item.note-tolak .note-author { color: var(--dg); }
.note-text { color: #1e293b; margin: 4px 0 0; white-space: pre-line; }

/* Surat Cetak A4 */
.a4 {
  width: 210mm; min-height: 297mm; margin: 0 auto; background-color: #fff; color: #000;
  font: 12pt/1.45 'Times New Roman', serif; padding: 17mm 21mm; position: relative;
  border: 1px solid #bbb; box-shadow: 0 2px 12px #0003; overflow: hidden;
  background-image: repeating-radial-gradient(circle at 15% 25%, transparent 0 6px, rgba(20,100,55,.05) 6px 7px), repeating-radial-gradient(circle at 85% 75%, transparent 0 8px, rgba(20,60,130,.045) 8px 9px);
  -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.a4:after {
  content: ''; position: absolute; inset: 6mm; border: 1.5px solid #1f5d34;
  outline: .4mm solid rgba(31,93,52,.4); outline-offset: 1.2mm; pointer-events: none;
}
.wm {
  position: absolute; left: -20mm; right: -20mm; top: 45%; text-align: center;
  transform: rotate(-32deg); font: bold 34pt Arial, sans-serif; color: rgba(31,93,52,.07);
  letter-spacing: 3px; pointer-events: none; white-space: nowrap;
}
.a4 table { width: auto; border-collapse: collapse; }
.a4 td { border: 0; padding: 1px 8px 1px 0; font-size: 12pt; vertical-align: top; }
.a4 p { margin: 8px 0; }
.bn {
  position: absolute; right: 14mm; top: 10mm; font: bold 8pt Arial; padding: 3px 8px;
  border-radius: 3px; color: #fff; background: #b3261e;
}
.bn.ok { background: #1f5d34; }
.bn.rej { background: #b3261e; }
.sgs {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 14px;
  text-align: center; font-size: 11pt;
}
.sg .tt { font-weight: bold; margin-bottom: 4px; }
.stp {
  border: 1.5px solid #1f5d34; border-radius: 6px; padding: 6px 4px; margin: 0 auto 4px;
  max-width: 52mm; background: rgba(31,93,52,.06); font: 8.5pt Arial, sans-serif; line-height: 1.35;
}
.stp.stp-rej { border-color: #b3261e; background: rgba(179,38,30,.06); color: #b3261e; }
.stp .ok { font-weight: bold; color: #1f5d34; font-size: 10pt; letter-spacing: .5px; }
.stp .rej { font-weight: bold; color: #b3261e; font-size: 10pt; letter-spacing: .5px; }
.stp .nm { font-weight: bold; margin-top: 2px; }
.stp .mi { color: #444; font-size: 7.5pt; }
.stp .kd { font: bold 8pt 'Courier New', monospace; letter-spacing: 1.5px; color: #1f5d34; margin-top: 2px; }
.stp.wait { border: 1.5px dashed #999; background: none; color: #888; padding: 16px 4px; }
.ft {
  display: flex; gap: 12px; align-items: center; margin-top: 16px; border-top: 1px solid #1f5d34;
  padding-top: 8px; font: 8pt/1.35 Arial, sans-serif; color: #222;
}
.ft b { font-family: 'Courier New', monospace; letter-spacing: 1px; }
.fb { display: none; }
.basah .fb { display: inline; }
.basah .fn, .basah .bn { display: none; }
.basah .stp.wait { border: 0; background: none; color: transparent; height: 21mm; }
.mhs { display: flex; gap: 10px; align-items: flex-start; }
.mhs table { flex: 1; min-width: 0; }
.cat {
  flex: 0 0 55mm; font: 9.5pt/1.4 Arial, sans-serif; border: 1px solid #1f5d34;
  border-left: 4px solid #1f5d34; border-radius: 3px; padding: 6px 8px; background: rgba(31,93,52,.05); margin-top: 2px;
}
.cat .ci + .ci { margin-top: 6px; }
.cat .ct { font-weight: bold; letter-spacing: 1px; font-size: 8pt; color: #1f5d34; margin-bottom: 3px; }
.cat .cb { margin-top: 5px; font-size: 7.5pt; color: #555; border-top: 1px dashed #8aa; padding-top: 3px; }

@media print {
  @page { size: A4; margin: 0; }
  body * { visibility: hidden; }
  #surat, #surat * { visibility: visible; }
  #surat { position: absolute; left: 0; top: 0; border: 0; box-shadow: none; margin: 0; }
}
</style>
</head>
<body>
<header>
  <div class="brand">
    <div class="brand-icon">🌱</div>
    <div class="brand-text">
      <h1>Fakultas Pertanian</h1>
      <p>Sistem Informasi Pengajuan Perubahan Judul Skripsi</p>
    </div>
  </div>
  <div id="who"></div>
</header>
<nav id="nav"></nav>
<main id="app">Memuat…</main>
<footer id="footer">
  <div class="footer-inner">
    <div>© Fakultas Pertanian · Sistem Informasi Pengajuan Perubahan Judul Skripsi</div>
    <div id="footerStaffLink">
      <a href="#login" onclick="go('login')">🔒 Masuk Staf / Dosen</a>
    </div>
  </div>
</footer>
<div id="modalContainer"></div>

<script>
const VERIFY = window.__V || '';
let S = null, PUB = { prodi: [], dosen: [], pengumuman: null }, tab = 'beranda', BUSY = false;
const $ = s => document.querySelector(s);
const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const call = (fn, ...a) => fetch('/rpc', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
  },
  body: JSON.stringify({ fn, args: a })
}).then(async r => {
  const j = await r.json().catch(() => ({}));
  if (!r.ok || j.error) throw new Error(j.error || (r.status === 419 ? 'Halaman kedaluwarsa, muat ulang (F5).' : 'Terjadi kesalahan server.'));
  return j.data;
});

const tk = () => S && S.token;
const msg = (t, err) => { const m = $('#m'); if (m) m.innerHTML = `<div class="msg ${err ? 'err' : ''}">${esc(t)}</div>`; };
const errm = e => String((e && e.message) || e).replace(/^Error:\s*/, '');
async function run(p, okMsg) {
  try {
    const r = await p;
    if (okMsg) msg(okMsg);
    return r;
  } catch (e) {
    msg(errm(e), 1);
    if (/Sesi habis/.test(errm(e))) { S = null; tab = 'login'; render(); }
    throw e;
  }
}
const opt = (a, v, f) => a.map(x => `<option value="${esc(v(x))}">${esc(f(x))}</option>`).join('');

/* ===== Modal Helper ===== */
function openModal({ title, contentHtml, okText = 'Simpan', okClass = '', onOk = null, cancelText = 'Batal' }) {
  const el = $('#modalContainer');
  el.innerHTML = `
    <div class="modal-backdrop" id="modalBackdrop">
      <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
          <h3>${esc(title)}</h3>
          <button class="s" style="padding:4px 8px;font-size:16px;border-radius:50%" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body">${contentHtml}</div>
        <div class="modal-footer">
          ${cancelText ? `<button class="s" onclick="closeModal()">${esc(cancelText)}</button>` : ''}
          ${okText ? `<button class="${okClass}" id="modalOkBtn">${esc(okText)}</button>` : ''}
        </div>
      </div>
    </div>`;
  if (onOk) {
    const btn = $('#modalOkBtn');
    if (btn) btn.onclick = async () => {
      btn.disabled = true;
      try {
        const ok = await onOk();
        if (ok !== false) closeModal();
      } catch (e) {
        let errDiv = $('#modalInlineErr');
        if (!errDiv) {
          errDiv = document.createElement('div');
          errDiv.id = 'modalInlineErr';
          errDiv.className = 'msg err';
          errDiv.style.marginBottom = '12px';
          $('.modal-body').prepend(errDiv);
        }
        errDiv.textContent = errm(e);
      } finally {
        btn.disabled = false;
      }
    };
  }
}
function closeModal() {
  $('#modalContainer').innerHTML = '';
}
function showAlert(title, message) {
  openModal({
    title,
    contentHtml: `<p style="font-size:14px;margin:0;color:var(--tx)">${esc(message)}</p>`,
    okText: 'Mengerti',
    cancelText: ''
  });
}

/* ===== Init & Navigation ===== */
async function init() {
  if (VERIFY) return vVerif();
  try { PUB = await call('publik'); } catch (e) {
    $('#app').innerHTML = '<div class="card err">Gagal memuat sistem: ' + esc(errm(e)) + '</div>';
    return;
  }
  const h = location.hash.replace('#', '');
  if (h && ['beranda', 'ajukan', 'cek', 'login'].includes(h)) tab = h;
  render();
}
window.addEventListener('hashchange', () => {
  const h = location.hash.replace('#', '');
  if (h && h !== tab) go(h);
});

function render() {
  const r = S && S.role;
  let t = [];

  if (!r) {
    // Pengunjung / Mahasiswa (belum login): Tombol Login Staf disembunyikan dari navbar utama
    t = [
      ['beranda', '📢 Papan Pengumuman'],
      ['ajukan', '📝 Ajukan Judul'],
      ['cek', '🔍 Cek Pengajuan']
    ];
  } else if (r === 'admin') {
    // Admin: Memiliki akses kelola pengumuman & manajemen sistem
    t = [
      ['beranda', '📢 Papan Pengumuman'],
      ['data', '📋 Semua Pengajuan'],
      ['prodi', '🏢 Program Studi'],
      ['user', '👥 Kelola Akun Dosen'],
      ['import', '📥 Import Dosen'],
      ['pw', '🔑 Ganti Password'],
      ['out', '🚪 Keluar']
    ];
  } else {
    // Dosen & Kaprodi: Menu "Ajukan Judul" dan "Cek Pengajuan" DIHILANGKAN sesuai permintaan!
    t = [
      ['inbox', '📥 Kotak Masuk Pengajuan'],
      ['pw', '🔑 Ganti Password'],
      ['out', '🚪 Keluar']
    ];
  }

  $('#nav').innerHTML = t.map(([k, l]) => `<button class="${tab === k ? '' : 's'}" onclick="go('${k}')">${l}</button>`).join('');
  
  const footerStaff = $('#footerStaffLink');
  if (S) {
    const roleLabel = S.role === 'kaprodi' ? '⭐ Kaprodi' : (S.role === 'admin' ? '🛡️ Admin' : '👨‍🏫 Dosen');
    $('#who').innerHTML = `<span>${esc(S.nama)}</span> <span class="badge badge-role">${roleLabel}</span>`;
    if (footerStaff) footerStaff.style.display = 'none';
  } else {
    $('#who').innerHTML = `<span style="opacity:0.85;font-weight:400;font-size:12px">Portal Mahasiswa</span>`;
    if (footerStaff) footerStaff.style.display = 'block';
  }

  ({
    beranda: vBeranda,
    ajukan: vAjukan,
    cek: vCek,
    login: vLogin,
    inbox: vInbox,
    pw: vPw,
    prodi: vProdi,
    user: vUser,
    import: vImport,
    data: vData
  })[tab]();
}

async function go(k) {
  if (k === 'out') {
    S = null;
    tab = 'beranda';
  } else {
    tab = k;
  }
  render();
  if (k === 'out' || !PUB || !PUB.prodi || PUB.prodi.length === 0) {
    try {
      PUB = await call('publik');
      render();
    } catch (e) {}
  }
}

/* ===== 1. Halaman Awal / Papan Pengumuman ===== */
function vBeranda() {
  const p = PUB.pengumuman || {
    judul: 'Pemberitahuan & Alur Pengajuan Perubahan Judul Skripsi',
    subjudul: 'Fakultas Pertanian — Harap membaca ketentuan dan alur sebelum melakukan pengajuan formulir',
    isi: 'Bagi mahasiswa Fakultas Pertanian yang mengajukan perubahan judul skripsi karena kendala di lapangan, pastikan judul baru telah dikonsultasikan dengan Dosen Pembimbing I dan II.',
    kontak: 'Gedung A Fakultas Pertanian / Layanan Akademik',
    aktif: '1'
  };

  const isAdmin = S && S.role === 'admin';
  const formattedIsi = esc(p.isi || '')
    .replace(/^### (.*$)/gim, '<h4 style="margin:12px 0 6px;color:#14532d;font-size:15px;font-weight:700">$1</h4>')
    .replace(/^## (.*$)/gim, '<h3 style="margin:14px 0 6px;color:#14532d;font-size:16px;font-weight:700">$1</h3>')
    .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
    .replace(/\*(.*?)\*/g, '<i>$1</i>')
    .replace(/^> (.*$)/gim, '<blockquote style="border-left:4px solid #166534;margin:10px 0;padding:6px 12px;background:#f0fdf4;border-radius:4px">$1</blockquote>')
    .replace(/\n\n/g, '<p style="margin:8px 0"></p>')
    .replace(/\n/g, '<br>');

  $('#app').innerHTML = `
    <div class="hero-banner">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px">
        <div>
          <span class="hero-badge">Papan Pengumuman & Panduan</span>
          <h2 class="hero-title">${esc(p.judul)}</h2>
          <p class="hero-subtitle">${esc(p.subjudul || '')}</p>
        </div>
        ${isAdmin ? `<button class="s" onclick="editPengumuman()"><span style="font-size:15px">✏️</span> Edit Pengumuman (Admin)</button>` : ''}
      </div>

      <div class="hero-body" style="margin-bottom:0">
        ${formattedIsi}
      </div>
    </div>

    <div class="card">
      <h3>Alur Verifikasi Perubahan Judul Skripsi</h3>
      <div class="steps-grid">
        <div class="step-card">
          <span class="step-num">Langkah 1</span>
          <h4 class="step-title">Konsultasi Dospem</h4>
          <p class="step-desc">Mahasiswa wajib berkonsultasi & menyepakati judul baru bersama Pembimbing I & II terlebih dahulu.</p>
        </div>
        <div class="step-card">
          <span class="step-num">Langkah 2</span>
          <h4 class="step-title">Pengisian Formulir</h4>
          <p class="step-desc">Mahasiswa melengkapi data diri, judul lama, alasan perubahan di lapangan, serta judul baru.</p>
        </div>
        <div class="step-card">
          <span class="step-num">Langkah 3</span>
          <h4 class="step-title">Review Dosen & Kaprodi</h4>
          <p class="step-desc">Dosen Pembimbing I, II dan Kaprodi meninjau pengajuan secara elektronik di sistem (menyetujui / meminta revisi).</p>
        </div>
        <div class="step-card">
          <span class="step-num">Langkah 4</span>
          <h4 class="step-title">Dokumen Sah & Cetak</h4>
          <p class="step-desc">Setelah disahkan Kaprodi, dokumen resmi ber-QR Code dapat diunduh dan dicetak secara mandiri.</p>
        </div>
      </div>
    </div>

    ${p.kontak ? `
    <div style="font-size:13px;color:var(--tx-muted);text-align:center;padding:10px">
      ℹ️ <b>Bantuan Layanan Akademik:</b> ${esc(p.kontak)}
    </div>` : ''}
  `;
}

function editPengumuman() {
  const p = PUB.pengumuman || {};
  openModal({
    title: 'Edit Papan Pengumuman (Admin)',
    contentHtml: `
      <div id="modalErr" style="display:none;color:var(--dg);font-weight:600;font-size:13px;margin-bottom:8px"></div>
      <div><label>Judul Pengumuman</label><input id="epJudul" value="${esc(p.judul || '')}" placeholder="Judul pengumuman..."></div>
      <div><label>Subjudul / Keterangan Singkat</label><input id="epSub" value="${esc(p.subjudul || '')}" placeholder="Keterangan singkat..."></div>
      <div><label>Isi Lengkap Pengumuman (Mendukung Markdown sederhana: **, *, ###)</label><textarea id="epIsi" rows="8" placeholder="Tuliskan isi pengumuman atau alur...">${esc(p.isi || '')}</textarea></div>
      <div><label>Kontak / Informasi Bantuan</label><input id="epKontak" value="${esc(p.kontak || '')}" placeholder="Contoh: Gedung A Lt. 2 / WhatsApp Layanan..."></div>
      <div style="margin-top:12px"><label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer"><input type="checkbox" id="epAktif" style="width:auto" ${p.aktif !== '0' ? 'checked' : ''}> Aktifkan pengumuman di halaman awal</label></div>
    `,
    okText: 'Simpan Perubahan',
    okClass: '',
    onOk: async () => {
      const data = {
        judul: $('#epJudul').value.trim(),
        subjudul: $('#epSub').value.trim(),
        isi: $('#epIsi').value.trim(),
        kontak: $('#epKontak').value.trim(),
        aktif: $('#epAktif').checked ? '1' : '0'
      };
      if (!data.judul || !data.isi) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'Judul dan isi pengumuman wajib diisi.'; errEl.style.display = 'block'; }
        return false;
      }
      await run(call('simpanPengumuman', tk(), data), 'Papan pengumuman berhasil diperbarui.');
      PUB = await call('publik');
      vBeranda();
    }
  });
}

/* ===== 2. Mahasiswa: Formulir Pengajuan Judul ===== */
const dosenOpt = () => {
  const d = PUB.dosen || [];
  const prodis = {};
  d.forEach(x => {
    const p = x.prodi || 'Lainnya';
    if (!prodis[p]) prodis[p] = [];
    prodis[p].push(x);
  });
  let html = '<option value="">-- Pilih Dosen Pembimbing --</option>';
  Object.keys(prodis).sort().forEach(p => {
    html += `<optgroup label="Prodi ${esc(p)}">`;
    prodis[p].sort((a, b) => a.nama.localeCompare(b.nama, 'id')).forEach(x => {
      const isK = x.role === 'kaprodi' ? ' ⭐ [Kaprodi]' : '';
      html += `<option value="${esc(x.username)}">${esc(x.nama)}${isK}</option>`;
    });
    html += '</optgroup>';
  });
  return html;
};

function vAjukan() {
  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Formulir Pengajuan Perubahan Judul Skripsi</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Pastikan judul baru telah disepakati bersama Dosen Pembimbing I & II.</p>
        </div>
        <button class="s" onclick="go('beranda')">← Kembali ke Pengumuman</button>
      </div>
      <div id="m"></div>
      <div class="g">
        <div><label>Program Studi</label><select id="prodi"><option value="">-- Pilih Program Studi --</option>${opt(PUB.prodi, x => x.nama, x => x.nama)}</select></div>
        <div><label>NIM Mahasiswa (5-20 angka)</label><input id="nim" type="text" inputmode="numeric" maxlength="20" placeholder="Contoh: 2021012345" oninput="this.value=this.value.replace(/\\D/g,'')"></div>
        <div><label>Nama Lengkap</label><input id="nama" placeholder="Nama lengkap sesuai KTM"></div>
        <div><label>Angkatan (tahun 4 digit)</label><input id="ang" type="text" inputmode="numeric" maxlength="4" placeholder="Contoh: 2024" oninput="this.value=this.value.replace(/\\D/g,'')"></div>
        <div><label>Kelas</label><input id="kelas" placeholder="Contoh: A / B / Reguler"></div>
        <div><label>No. HP / WhatsApp Aktif</label><input id="hp" type="tel" inputmode="numeric" maxlength="15" placeholder="Contoh: 081234567890" oninput="this.value=this.value.replace(/\\D/g,'')"></div>
        <div><label>Email Aktif</label><input id="email" type="email" placeholder="contoh: nama@email.com"></div>
        <div><label>Dosen Pembimbing I</label><select id="d1">${dosenOpt()}</select></div>
        <div><label>Dosen Pembimbing II</label><select id="d2">${dosenOpt()}</select></div>
      </div>
      <label>Judul Lama</label><textarea id="jl" rows="2" placeholder="Tuliskan judul skripsi yang terdaftar sebelumnya"></textarea>
      <label>Tidak bisa terlaksana di lapangan karena</label><textarea id="alasan" rows="2" placeholder="Uraikan secara jelas kendala atau alasan perubahan di lapangan"></textarea>
      <label>Judul Baru (yang sudah dikonsultasikan dengan Dosen Pembimbing)</label><textarea id="jb" rows="2" placeholder="Tuliskan rumusan judul baru hasil konsultasi"></textarea>
      <p style="margin-top:20px"><button id="bk" onclick="kirim()" style="padding:11px 22px;font-size:15px">🚀 Kirim Pengajuan Perubahan Judul</button></p>
    </div>`;
}

async function kirim() {
  const v = i => $('#' + i).value.trim();
  if (!v('prodi')) return msg('Pilih Program Studi.', 1);
  if (!/^\d{5,20}$/.test(v('nim'))) return msg('NIM harus berupa angka (5-20 digit).', 1);
  if (!v('nama')) return msg('Nama lengkap wajib diisi.', 1);
  if (!/^\d{4}$/.test(v('ang'))) return msg('Angkatan harus berupa 4 digit tahun (contoh: 2024).', 1);
  if (!v('kelas')) return msg('Kelas wajib diisi.', 1);
  if (!/^\d{9,15}$/.test(v('hp'))) return msg('Nomor HP/WA harus berupa angka (9-15 digit).', 1);
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))) return msg('Format email tidak valid (contoh: nama@email.com).', 1);
  if (!v('d1') || !v('d2')) return msg('Pilih Dosen Pembimbing I dan Dosen Pembimbing II.', 1);
  if (v('d1') === v('d2')) return msg('Dosen Pembimbing I dan II harus berbeda.', 1);
  if (!v('jl')) return msg('Judul lama wajib diisi.', 1);
  if (!v('alasan')) return msg('Alasan tidak bisa terlaksana wajib diisi.', 1);
  if (!v('jb')) return msg('Judul baru wajib diisi.', 1);

  $('#bk').disabled = true;
  try {
    await run(call('submit', {
      prodi: v('prodi'), nim: v('nim'), nama: v('nama'), angkatan: v('ang'),
      kelas: v('kelas'), hp: v('hp'), email: v('email'), d1: v('d1'), d2: v('d2'),
      judulLama: v('jl'), alasan: v('alasan'), judulBaru: v('jb')
    }), '✅ Pengajuan berhasil dikirim! Pengajuan telah diteruskan ke Pembimbing I, II, dan Kaprodi. Anda dapat memantau status di menu Cek Pengajuan.');
    $('#app').scrollIntoView();
  } catch (e) {}
  $('#bk').disabled = false;
}

/* ===== 3. Mahasiswa: Cek Pengajuan & Catatan Evaluasi ===== */
const fmt = t => new Date(t).toLocaleString('id-ID', {
  day: '2-digit', month: 'long', year: 'numeric',
  hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta'
}) + ' WIB';

function vCek() {
  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Cek Status & Riwayat Pengajuan</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Masukkan NIM untuk melihat status persetujuan, catatan dosen, dan cetak lembar sah.</p>
        </div>
      </div>
      <div style="display:flex;gap:10px;align-items:center;max-width:440px">
        <input id="cn" placeholder="Masukkan NIM Mahasiswa..." onkeydown="if(event.key==='Enter')cek()">
        <button onclick="cek()" style="flex-shrink:0">🔍 Cari</button>
      </div>
    </div>
    <div id="hasil"></div>`;
}

async function cek() {
  const nim = $('#cn').value.trim();
  if (!nim) {
    $('#hasil').innerHTML = '<div class="card msg err">Silakan masukkan NIM terlebih dahulu.</div>';
    return;
  }
  $('#hasil').innerHTML = '<div class="card">Mencari pengajuan…</div>';
  try {
    const a = await call('cek', nim);
    if (!a.length) {
      $('#hasil').innerHTML = '<div class="card msg err">Pengajuan dengan NIM ' + esc(nim) + ' tidak ditemukan di sistem.</div>';
      return;
    }
    LIST = a;
    const latest = a[a.length - 1];
    
    // Status badges helper
    const statusPill = r => {
      if (r.vk === '1') return '<span class="badge badge-ok">✔ Disahkan Kaprodi (Sah)</span>';
      if (r.isRejected) return `<span class="badge badge-rej">❌ Tidak Disetujui (${esc(r.rejectedBy.join(', '))})</span>`;
      return '<span class="badge badge-wait">⏳ Dalam Proses Review</span>';
    };

    $('#hasil').innerHTML = `
      <div class="card">
        <h3>Riwayat Pengajuan Mahasiswa (${a.length})</h3>
        <div class="tw">
          <table>
            <thead><tr><th>Ke</th><th>Tanggal Pengajuan</th><th>Judul Baru</th><th>Status Keseluruhan</th><th>Aksi</th></tr></thead>
            <tbody>
              ${a.map((r, i) => `
                <tr>
                  <td><b>ke-${esc(r.ke)}</b></td>
                  <td>${new Date(r.tgl).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric', timeZone:'Asia/Jakarta'})}</td>
                  <td><b>${esc(r.judulBaru)}</b></td>
                  <td>${statusPill(r)}</td>
                  <td><button class="s" onclick="tampilDetailCek(${i})">Lihat Detail</button></td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
        <p style="font-size:12.5px;color:var(--tx-muted);margin-top:10px">
          💡 Catatan: Jika pengajuan berstatus <b>Tidak Disetujui / Perlu Revisi</b>, periksa catatan dari Dosen Pembimbing/Kaprodi di bawah, lalu Anda dapat mengajukan judul baru.
        </p>
      </div>
      <div id="sx"></div>`;

    tampilDetailCek(a.length - 1);
  } catch (e) {
    $('#hasil').innerHTML = '<div class="card msg err">' + esc(errm(e)) + '</div>';
  }
}

function tampilDetailCek(i) {
  const r = LIST[i];
  const stepIcon = v => v === '1' ? '✔' : (v === '2' ? '✕' : '⏳');
  const stepClass = v => v === '1' ? 'badge-ok' : (v === '2' ? 'badge-rej' : 'badge-wait');
  const stepText = (v, nama, tgl) => {
    if (v === '1') return `Disetujui oleh <b>${esc(nama)}</b> pada ${fmt(tgl)}`;
    if (v === '2') return `<b>Tidak Disetujui / Perlu Revisi</b> oleh <b>${esc(nama)}</b>`;
    return `Menunggu review dari <b>${esc(nama)}</b>`;
  };

  // Dedicated Review Notes block
  const notesHtml = (r.notes && r.notes.length) ? `
    <div class="card" style="border-left:5px solid ${r.isRejected ? 'var(--dg)' : 'var(--ac)'}">
      <h3>📝 Catatan Evaluasi dari Pembimbing & Kaprodi</h3>
      <div class="notes-container">
        ${r.notes.map(n => `
          <div class="note-item ${n.status === 'tolak' || (n.x && n.x.includes('TIDAK DISETUJUI')) ? 'note-tolak' : ''}">
            <div class="note-author">
              <span>👤 ${esc(n.o || 'Reviewer')}</span>
              <span style="font-size:11.5px;font-weight:normal;color:var(--tx-muted)">${fmt(n.t)}</span>
            </div>
            <div class="note-text">${esc(n.x)}</div>
          </div>
        `).join('')}
      </div>
    </div>
  ` : '';

  $('#sx').innerHTML = `
    <div class="card">
      <h3>Detail Status Verifikasi (Pengajuan ke-${esc(r.ke)})</h3>
      <div style="margin-bottom:16px">
        <div class="track-step">
          <div class="track-icon ${stepClass(r.v1)}">${stepIcon(r.v1)}</div>
          <div style="flex:1">
            <div style="font-weight:700;font-size:14px">Dosen Pembimbing I</div>
            <div style="font-size:13px;color:var(--tx-muted)">${stepText(r.v1, r.d1Nama, r.t1)}</div>
          </div>
        </div>

        <div class="track-step">
          <div class="track-icon ${stepClass(r.v2)}">${stepIcon(r.v2)}</div>
          <div style="flex:1">
            <div style="font-weight:700;font-size:14px">Dosen Pembimbing II</div>
            <div style="font-size:13px;color:var(--tx-muted)">${stepText(r.v2, r.d2Nama, r.t2)}</div>
          </div>
        </div>

        <div class="track-step">
          <div class="track-icon ${stepClass(r.vk)}">${stepIcon(r.vk)}</div>
          <div style="flex:1">
            <div style="font-weight:700;font-size:14px">Ketua Program Studi (${esc(r.prodi)})</div>
            <div style="font-size:13px;color:var(--tx-muted)">${stepText(r.vk, r.kaprodi, r.tk)}</div>
          </div>
        </div>
      </div>
    </div>
    ${notesHtml}
    <div id="lembarSurat"></div>
  `;

  show(r, '#lembarSurat');
}

/* ===== Lembar Cetak Surat A4 ===== */
const stamp = (r, s, title, nama) => {
  const isOk = r['v' + s] === '1';
  const isRej = r['v' + s] === '2';
  let body = `<div class="stp wait">Menunggu<br>persetujuan</div>`;
  if (isOk) {
    body = `<div class="stp"><div class="ok">✔ DISETUJUI</div><div class="mi">${r['m' + s] ? 'tanda tangan basah · disahkan admin' : 'secara elektronik'}</div><div class="nm">${esc(nama)}</div><div class="mi">${fmt(r['t' + s])}</div><div class="kd">${esc(r['k' + s])}</div></div>`;
  } else if (isRej) {
    body = `<div class="stp stp-rej"><div class="rej">✕ TIDAK DISETUJUI</div><div class="nm">${esc(nama)}</div><div class="mi">${fmt(r['t' + s])}</div></div>`;
  }
  return `<div class="sg"><div class="tt">${title}</div>${body}<b><u>${esc(nama)}</u></b></div>`;
};

function surat(r) {
  const row = (a, b) => `<tr><td><b>${a}</b></td><td>:</td><td><b>${esc(b)}</b></td></tr>`;
  const ok = !!r.kodeDok;
  const isRej = r.isRejected;
  const u = (PUB.url || '') + '?v=' + encodeURIComponent(r.id);
  const cat = (r.notes && r.notes.length) ? `
    <div class="cat">
      <div class="ct">CATATAN EVALUASI</div>
      ${r.notes.map(n => `<div class="ci">${esc(n.x).replace(/\n/g, '<br>')}<div class="cb">${esc(n.o)} · ${fmt(n.t)}</div></div>`).join('')}
    </div>` : '';

  let statusBanner = `<div class="bn">BELUM DISAHKAN KAPRODI</div>`;
  if (ok) {
    statusBanner = `<div class="bn ok">DOKUMEN SAH</div>`;
  } else if (isRej) {
    statusBanner = `<div class="bn rej">TIDAK DISETUJUI / REVISI</div>`;
  }

  return `
    <p class="pb" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <button onclick="window.print()">🖨️ Cetak A4 / Simpan PDF</button>
      <button class="s" onclick="cetakBasah()">✍️ Cetak Lembar Tanda Tangan Basah</button>
    </p>
    <div id="surat" class="a4">
      <div class="wm">FAKULTAS PERTANIAN</div>
      ${statusBanner}
      <b>Hal : Perubahan Judul<br>( Perubahan Judul yang sudah dikonsultasikan dengan Dosen Pembimbing )</b>
      <p style="margin-top:14px">Kepada<br>Yth. Ketua Jurusan ${esc(r.prodi)}<br>Fakultas Pertanian<br>Yogyakarta</p>
      <p>Bersama ini mahasiswa :</p>
      <div class="mhs">
        <table>${row('Nama', r.nama)}${row('Angkatan', r.angkatan)}${row('NIM', r.nim)}${row('Kelas', r.kelas)}${row('No. HP / WA', r.hp)}${row('Email', r.email)}</table>
        ${cat}
      </div>
      <p>Mengajukan permohonan perubahan judul karena judul kami yang lama yaitu :</p>
      <p>Judul Lama:<br><b>${esc(r.judulLama)}</b></p>
      <table>${row('Dosen Pembimbing I', r.d1Nama)}${row('Dosen Pembimbing II', r.d2Nama)}</table>
      <p>Tidak bisa terlaksana di lapangan karena :<br><b>${esc(r.alasan)}</b></p>
      <p>Judul baru yang sudah dikonsultasikan dengan Dosen Pembimbing yaitu :<br><b>${esc(r.judulBaru)}</b></p>
      <p>Demikian harap menjadikan periksa.</p>
      <p style="margin-bottom:0">Mengetahui,</p>
      <div class="sgs">
        ${stamp(r, '1', 'Dosen Pembimbing I', r.d1Nama)}
        ${stamp(r, '2', 'Dosen Pembimbing II', r.d2Nama)}
        ${stamp(r, 'k', 'Kaprodi ' + esc(r.prodi), r.kaprodi)}
      </div>
      <div style="font:8pt Arial,sans-serif;text-align:right;color:#555;margin-top:8px">
        No. Pengajuan: <b>${esc(r.id)}</b> · Pengajuan ke-${esc(r.ke)}
      </div>
      <div class="ft">
        <div class="qr" data-u="${esc(u)}"></div>
        <div>
          ${ok ? `Dokumen ini disetujui secara elektronik dan <b>dapat diverifikasi keasliannya</b>:<br>Pindai QR atau kunjungi ${esc(u)}<br>Kode Dokumen: <b>${esc(r.kodeDok)}</b>`
               : (isRej ? `<span style="color:#b3261e;font-weight:bold">Dokumen ini Tidak Disetujui / Memerlukan Perbaikan. Periksa catatan pembimbing.</span>`
                        : `<span class="fn">Dokumen ini <b>belum sah</b> sebelum disahkan oleh Kaprodi.</span><span class="fb">Lembar ini ditandatangani langsung oleh Dosen Pembimbing & Kaprodi.<br>NIM: <b>${esc(r.nim)}</b></span>`)}
        </div>
      </div>
    </div>`;
}

function cetakBasah() {
  const el = $('#surat');
  el.classList.add('basah');
  window.addEventListener('afterprint', () => el.classList.remove('basah'), { once: true });
  setTimeout(() => window.print(), 50);
}

function show(r, tg) {
  tg = tg || '#hasil';
  $(tg).innerHTML = surat(r);
  document.querySelectorAll('.qr').forEach(e => {
    e.innerHTML = '';
    try { new QRCode(e, { text: e.dataset.u, width: 78, height: 78 }); } catch (x) {}
  });
}

/* ===== 4. Dosen & Kaprodi: Kotak Masuk & Aksi Persetujuan / Penolakan ===== */
let LIST = [];

function vLogin() {
  $('#app').innerHTML = `
    <div class="card" style="max-width:420px;margin:20px auto">
      <div class="card-header">
        <h3>Login Dosen / Staf</h3>
        <button class="s" onclick="go('beranda')">← Kembali ke Beranda</button>
      </div>
      <div id="m"></div>
      <label>Username / NIDN</label>
      <input id="u" placeholder="Dosen: NIDN | Admin: admin" onkeydown="if(event.key==='Enter'){$('#p').focus()}">
      <label>Password</label>
      <input id="p" type="password" placeholder="Password..." onkeydown="if(event.key==='Enter')login()">
      <p style="margin-top:18px"><button onclick="login()" style="width:100%;justify-content:center;padding:11px">Masuk ke Sistem</button></p>
      <p style="font-size:12px;color:var(--tx-muted);text-align:center">Password awal dosen = NIDN. Harap segera ganti password setelah berhasil login.</p>
    </div>`;
}

async function login() {
  try {
    S = await run(call('login', $('#u').value, $('#p').value));
    tab = (S.role === 'admin') ? 'data' : 'inbox';
    render();
  } catch (e) {}
}

const statusBadgeRow = r => {
  const sText = (slot, label) => {
    const v = r['v' + slot];
    if (v === '1') return `<span class="badge badge-ok">${label} ✔</span>`;
    if (v === '2') return `<span class="badge badge-rej">${label} ✕ Ditolak</span>`;
    return `<span class="badge badge-wait">${label} ⏳</span>`;
  };
  return `<div style="display:flex;flex-wrap:wrap;gap:4px">${sText('1', 'D1')}${sText('2', 'D2')}${sText('k', 'Kaprodi')}</div>`;
};

const stAdm = r => [['1', 'Dosen 1'], ['2', 'Dosen 2'], ['k', 'Kaprodi']].map(([s, l]) => `
  ${l}: ${r['v' + s] === '1' ? `<span class="badge badge-ok">✔ Sah</span> <button class="s" style="padding:1px 6px;font-size:11px" onclick="adm('adminBatal','${esc(r.id)}','${s}')">Batal</button>` : `<span class="badge badge-wait">⏳ Menunggu</span> <button class="s" style="padding:1px 6px;font-size:11px" onclick="adm('adminSetuju','${esc(r.id)}','${s}')">Sahkan</button>`}
`).join('<br>');

async function adm(fn, nim, slot) {
  const L = { '1': 'Dosen Pembimbing I', '2': 'Dosen Pembimbing II', k: 'Kaprodi' }[slot];
  if (fn === 'adminSetuju') {
    openModal({
      title: 'Sahkan Tanda Tangan Basah (Admin)',
      contentHtml: `
        <p style="margin:0 0 10px;font-size:14px">Sahkan persetujuan manual <b>${L}</b> untuk pengajuan <b>${esc(nim)}</b>?</p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:12px;border-radius:8px;font-size:13px;color:#14532d">
          Lakukan hanya jika fisik lembar pengesahan tanda tangan basah telah diterima dan diverifikasi.
        </div>
      `,
      okText: '✔ Sahkan Sekarang',
      onOk: async () => {
        await call(fn, tk(), nim, slot);
        vData();
      }
    });
    return;
  }
  openModal({
    title: 'Batalkan Pengesahan Manual',
    contentHtml: `<p style="margin:0;font-size:14px">Batalkan pengesahan manual <b>${L}</b> untuk pengajuan <b>${esc(nim)}</b>?</p>`,
    okText: 'Batalkan',
    okClass: 's',
    onOk: async () => {
      await call(fn, tk(), nim, slot);
      vData();
    }
  });
}

const tbl = (l, adm, inb) => `
  <div class="tw">
    <table>
      <thead>
        <tr>
          <th style="width:24%">Mahasiswa & NIM</th>
          <th style="width:38%">Judul Baru</th>
          <th style="width:16%">Status Verifikasi</th>
          <th style="width:22%">Aksi Keputusan</th>
        </tr>
      </thead>
      <tbody>
        ${l.map((r, i) => {
          const me = S && S.username;
          const isK = S && S.role === 'kaprodi' && me === r.kaprodiUser;
          const isD1 = me === r.d1;
          const isD2 = me === r.d2;
          
          const canD = (isD1 && r.v1 === '0') || (isD2 && r.v2 === '0');
          const unD = (isD1 && r.v1 === '1') || (isD2 && r.v2 === '1');
          const rejD = (isD1 && r.v1 === '2') || (isD2 && r.v2 === '2');

          const canK = isK && r.vk === '0';
          const unK = isK && r.vk === '1';
          const rejK = isK && r.vk === '2';

          const peran = [isD1 ? 'Pembimbing I' : '', isD2 ? 'Pembimbing II' : '', isK ? 'Kaprodi' : ''].filter(Boolean).join(' + ');
          const isLongTitle = (r.judulBaru || '').length > 55;

          return `
            <tr>
              <td>
                <div style="font-weight:700;font-size:14px;color:var(--tx)">${esc(r.nama)}</div>
                <div style="font-size:12px;color:var(--tx-muted);margin-top:2px">NIM: <b>${esc(r.nim)}</b> (${esc(r.prodi)})</div>
                <div style="font-size:11.5px;color:var(--tx-muted)">ke-${esc(r.ke)} · Angkatan ${esc(r.angkatan)}</div>
              </td>
              <td>
                <div class="judul-box" id="jb_${i}">
                  <div class="judul-text short" id="jbt_${i}" title="${esc(r.judulBaru)}">${esc(r.judulBaru)}</div>
                  ${isLongTitle ? `<button type="button" class="btn-expand" id="jbtn_${i}" onclick="toggleJudul(${i})">🔍 Detail Penuh</button>` : ''}
                </div>
              </td>
              <td>
                ${adm ? stAdm(r) : statusBadgeRow(r)}
                ${inb && peran ? `<div style="margin-top:4px"><span class="badge badge-role">Peran: ${peran}</span></div>` : ''}
              </td>
              <td>
                <div style="display:flex;flex-wrap:wrap;gap:4px;align-items:center">
                  <button class="s" style="padding:5px 9px;font-size:12px" onclick="lihat(${i})">👁️ Lihat</button>
                  ${adm || (inb && (isK || isD1 || isD2 || (S && S.role === 'admin'))) ? ` <button class="s" style="padding:5px 9px;font-size:12px" onclick="editCat(${i})">📝 Catatan</button>` : ''}
                  
                  ${inb && canD ? `
                    <button style="padding:5px 10px;font-size:12px" onclick="aksi('setuju','${esc(r.id)}','dosen')">✔ Setujui</button>
                    <button class="d-outline" style="padding:5px 9px;font-size:12px" onclick="bukaModalTolak('${esc(r.id)}','dosen')">✕ Tidak Disetujui</button>
                  ` : ''}

                  ${inb && canK ? `
                    <button style="padding:5px 10px;font-size:12px" onclick="aksi('setuju','${esc(r.id)}','kaprodi')">✔ Sahkan</button>
                    <button class="d-outline" style="padding:5px 9px;font-size:12px" onclick="bukaModalTolak('${esc(r.id)}','kaprodi')">✕ Tidak Disetujui</button>
                  ` : ''}

                  ${inb && unD ? `<button class="s" style="padding:5px 9px;font-size:12px" onclick="aksi('batal','${esc(r.id)}','dosen')">Batalkan Persetujuan</button>` : ''}
                  ${inb && unK ? `<button class="s" style="padding:5px 9px;font-size:12px" onclick="aksi('batal','${esc(r.id)}','kaprodi')">Batalkan Pengesahan</button>` : ''}
                  ${inb && rejD ? `<button class="s" style="padding:5px 9px;font-size:12px" onclick="aksi('batalTolak','${esc(r.id)}','dosen')">Batalkan Penolakan</button>` : ''}
                  ${inb && rejK ? `<button class="s" style="padding:5px 9px;font-size:12px" onclick="aksi('batalTolak','${esc(r.id)}','kaprodi')">Batalkan Penolakan</button>` : ''}
                  ${adm ? ` <button class="s" style="padding:5px 9px;font-size:12px;color:var(--ac);font-weight:700" onclick="modalAdminReset('${esc(r.id)}')">🔄 Kelola / Reset</button>` : ''}
                </div>
              </td>
            </tr>
          `;
        }).join('') || '<tr><td colspan=4 style="text-align:center;color:var(--tx-muted);padding:24px">Tidak ada data pengajuan.</td></tr>'}
      </tbody>
    </table>
  </div>`;

function toggleJudul(i) {
  const el = $('#jbt_' + i);
  const btn = $('#jbtn_' + i);
  if (!el || !btn) return;
  if (el.classList.contains('short')) {
    el.classList.remove('short');
    el.classList.add('full');
    btn.innerHTML = '🔼 Perpendek';
  } else {
    el.classList.remove('full');
    el.classList.add('short');
    btn.innerHTML = '🔍 Detail Penuh';
  }
}

function aksi(fn, nim, peran) {
  const r = LIST.find(x => x.id === nim);
  const peranNama = peran === 'kaprodi' ? 'Ketua Program Studi' : 'Dosen Pembimbing';

  if (fn === 'setuju') {
    openModal({
      title: 'Konfirmasi Persetujuan Elektronik',
      contentHtml: `
        <div style="font-size:14px;color:var(--tx);margin-bottom:12px">
          <div>Mahasiswa: <b>${esc(r ? r.nama : '')}</b> (${esc(r ? r.nim : nim)})</div>
          <div>Judul Baru: <b>${esc(r ? r.judulBaru : '')}</b></div>
        </div>
        <p style="margin:0 0 10px;font-size:13.5px">
          Setujui pengajuan perubahan judul skripsi ini sebagai <b>${esc(peranNama)}</b>?
        </p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:12px;border-radius:8px;font-size:13px;color:#14532d">
          ✍️ Persetujuan Anda akan dibubuhkan sebagai <b>tanda tangan digital resmi</b> ber-QR Code pada lembar pengesahan.
        </div>
      `,
      okText: '✔ Ya, Setujui Pengajuan',
      okClass: '',
      onOk: async () => {
        await call('setuju', tk(), nim, peran);
        vInbox();
      }
    });
    return;
  }

  if (fn === 'batal') {
    openModal({
      title: 'Konfirmasi Pembatalan Persetujuan',
      contentHtml: `
        <p style="margin:0 0 10px;font-size:14px">
          Batalkan persetujuan pengajuan untuk NIM <b>${esc(r ? r.nim : nim)}</b> (${esc(r ? r.nama : '')})?
        </p>
        <p style="font-size:13px;color:var(--tx-muted)">
          Tanda tangan elektronik Anda akan ditarik dan status pengajuan akan kembali menjadi <b>Menunggu Persetujuan</b>.
        </p>
      `,
      okText: 'Batalkan Persetujuan',
      okClass: 's',
      onOk: async () => {
        await call('batal', tk(), nim, peran);
        vInbox();
      }
    });
    return;
  }

  if (fn === 'batalTolak') {
    openModal({
      title: 'Konfirmasi Pembatalan Penolakan',
      contentHtml: `
        <p style="margin:0 0 10px;font-size:14px">
          Batalkan status penolakan untuk NIM <b>${esc(r ? r.nim : nim)}</b> (${esc(r ? r.nama : '')})?
        </p>
        <p style="font-size:13px;color:var(--tx-muted)">
          Status pengajuan akan dikembalikan ke <b>Menunggu Review</b>, dan catatan penolakan sebelumnya akan dibersihkan.
        </p>
      `,
      okText: 'Batalkan Penolakan',
      okClass: 's',
      onOk: async () => {
        await call('batalTolak', tk(), nim, peran);
        vInbox();
      }
    });
    return;
  }
}

function modalAdminReset(id) {
  const r = LIST.find(x => x.id === id);
  if (!r) return;
  openModal({
    title: 'Kelola / Reset Status Pengajuan (Admin)',
    contentHtml: `
      <div style="font-size:13.5px;color:var(--tx);margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid var(--bd)">
        <div>NIM / Nama: <b>${esc(r.nim)}</b> - ${esc(r.nama)}</div>
        <div>Prodi: <b>${esc(r.prodi)}</b> | Pengajuan ke-${esc(r.ke)}</div>
        <div style="margin-top:4px">Judul: <i>${esc(r.judulBaru)}</i></div>
        <div style="margin-top:6px;font-weight:700">Status: ${r.vk === '1' ? '<span class="badge badge-ok">Sah</span>' : (r.isRejected ? '<span class="badge badge-rej">Tidak Disetujui (' + esc(r.rejectedBy.join(', ')) + ')</span>' : '<span class="badge badge-wait">Dalam Proses</span>')}</div>
      </div>
      <label style="font-weight:700;margin-bottom:8px">Pilih Tindakan Reset Admin:</label>
      <div style="display:flex;flex-direction:column;gap:10px">
        <label style="display:flex;align-items:flex-start;gap:10px;padding:10px;border:1px solid var(--bd);border-radius:8px;cursor:pointer;background:#fff">
          <input type="radio" name="adminResetMode" value="review_ulang" checked style="width:auto;margin-top:3px">
          <div>
            <b>1. Reset ke Menunggu (Review Ulang)</b>
            <div style="font-size:12px;color:var(--tx-muted)">Membersihkan status penolakan dosen/kaprodi agar dapat ditinjau dan disetujui kembali tanpa mengisi form dari awal.</div>
          </div>
        </label>
        <label style="display:flex;align-items:flex-start;gap:10px;padding:10px;border:1px solid var(--bd);border-radius:8px;cursor:pointer;background:#fff">
          <input type="radio" name="adminResetMode" value="izin_baru" style="width:auto;margin-top:3px">
          <div>
            <b>2. Buka Izin Pengajuan Baru (Buka Kunci NIM)</b>
            <div style="font-size:12px;color:var(--tx-muted)">Mengarsipkan pengajuan ini dan membuka izin agar mahasiswa dapat mengisi formulir pengajuan judul baru di sistem.</div>
          </div>
        </label>
        <label style="display:flex;align-items:flex-start;gap:10px;padding:10px;border:1px solid var(--dg-border);border-radius:8px;cursor:pointer;background:var(--dg-light)">
          <input type="radio" name="adminResetMode" value="hapus" style="width:auto;margin-top:3px">
          <div>
            <b style="color:var(--dg)">3. Hapus Pengajuan Permanen</b>
            <div style="font-size:12px;color:#991b1b">Menghapus data pengajuan ini secara menyeluruh dari database.</div>
          </div>
        </label>
      </div>
    `,
    okText: 'Jalankan Tindakan',
    okClass: '',
    onOk: async () => {
      const mode = document.querySelector('input[name="adminResetMode"]:checked').value;
      const res = await call('adminResetPengajuan', tk(), id, mode);
      vData();
      showAlert('Hasil Reset Pengajuan', res);
    }
  });
}

function bukaModalTolak(id, peran) {
  const r = LIST.find(x => x.id === id);
  openModal({
    title: 'Konfirmasi Penolakan / Permintaan Revisi',
    contentHtml: `
      <div style="font-size:13.5px;color:var(--tx);margin-bottom:12px">
        <div>Mahasiswa: <b>${esc(r ? r.nama : '')}</b> (${esc(r ? r.nim : '')})</div>
        <div>Judul Baru: <b>${esc(r ? r.judulBaru : '')}</b></div>
      </div>
      <div id="modalErr" style="display:none;color:var(--dg);font-weight:600;font-size:13px;margin-bottom:8px"></div>
      <label>Alasan Tidak Disetujui / Arahan Revisi (Wajib Diisi):</label>
      <textarea id="alasanTolak" rows="4" placeholder="Jelaskan alasan mengapa judul ini belum disetujui atau hal-hal yang perlu diperbaiki mahasiswa..."></textarea>
      <p style="font-size:12px;color:var(--tx-muted);margin-top:6px">Alasan ini akan otomatis masuk ke Catatan Evaluasi dan dapat dibaca oleh mahasiswa di halaman Cek Pengajuan.</p>
    `,
    okText: '✕ Kirim Tidak Disetujui',
    okClass: 'd',
    onOk: async () => {
      const alasan = $('#alasanTolak').value.trim();
      if (!alasan) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'Mohon isi alasan penolakan / catatan revisi.'; errEl.style.display = 'block'; }
        return false;
      }
      await run(call('tolak', tk(), id, peran, alasan), 'Pengajuan telah ditandai Tidak Disetujui.');
      vInbox();
    }
  });
}

function lihat(i) {
  show(LIST[i]);
  $('#surat').scrollIntoView({ behavior: 'smooth' });
}

function editCat(i) {
  const r = LIST[i];
  const mine = (r.notes || []).find(n => n.u === S.username);

  const otherNotes = (r.notes || []).filter(n => n.u !== S.username);
  const otherNotesHtml = otherNotes.length ? `
    <div style="margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid var(--bd)">
      <div style="font-weight:700;font-size:12.5px;color:var(--tx-muted);margin-bottom:6px">Catatan dari Reviewer Lain:</div>
      ${otherNotes.map(n => `
        <div style="font-size:13px;margin-bottom:6px">
          <b>${esc(n.o)}:</b> ${esc(n.x)} <small style="color:var(--tx-muted)">(${fmt(n.t)})</small>
        </div>
      `).join('')}
    </div>
  ` : '';

  openModal({
    title: `Catatan Anda untuk: ${r.nama} (${r.nim})`,
    contentHtml: `
      ${otherNotesHtml}
      <label>Catatan Anda (Tampil di lembar cetak dan halaman Cek Pengajuan Mahasiswa):</label>
      <textarea id="ct" rows="4" maxlength="300" placeholder="Contoh: Dosen Pembimbing II diganti menjadi ..., atau revisi variabel...">${esc(mine ? mine.x : '')}</textarea>
      <small style="color:var(--tx-muted);display:block;margin-top:4px">Maksimal 300 karakter. Kosongkan lalu simpan jika ingin menghapus catatan Anda.</small>
    `,
    okText: 'Simpan Catatan',
    onOk: async () => {
      const teks = $('#ct').value.trim();
      await run(call('simpanCatatan', tk(), r.id, teks), 'Catatan berhasil disimpan.');
      LIST = await call(tab === 'data' ? 'semuaPengajuan' : 'inbox', tk());
      const updated = LIST.find(x => x.id === r.id);
      if (updated) show(updated);
    }
  });
}

let INBOX_TAB = 'pending';

async function vInbox() {
  $('#app').innerHTML = '<div class="card">Memuat kotak masuk pengajuan…</div>';
  try {
    LIST = await call('inbox', tk());
  } catch (e) {
    $('#app').innerHTML = '<div class="card msg err">' + esc(errm(e)) + '</div>';
    return;
  }

  const me = S && S.username;
  const isPendingForMe = r => {
    const isK = S && S.role === 'kaprodi' && me === r.kaprodiUser;
    const isD1 = me === r.d1;
    const isD2 = me === r.d2;
    const needD = (isD1 && r.v1 === '0') || (isD2 && r.v2 === '0');
    const needK = isK && r.vk === '0';
    return needD || needK;
  };
  const isApprovedByMe = r => {
    const isK = S && S.role === 'kaprodi' && me === r.kaprodiUser;
    const isD1 = me === r.d1;
    const isD2 = me === r.d2;
    const okD = (isD1 && r.v1 === '1') || (isD2 && r.v2 === '1');
    const okK = isK && r.vk === '1';
    return okD || okK;
  };
  const isRejectedByMe = r => {
    const isK = S && S.role === 'kaprodi' && me === r.kaprodiUser;
    const isD1 = me === r.d1;
    const isD2 = me === r.d2;
    const rejD = (isD1 && r.v1 === '2') || (isD2 && r.v2 === '2');
    const rejK = isK && r.vk === '2';
    return rejD || rejK;
  };

  const pendingList = LIST.filter(isPendingForMe);
  const approvedList = LIST.filter(isApprovedByMe);
  const rejList = LIST.filter(isRejectedByMe);
  const displayList = INBOX_TAB === 'pending'
    ? pendingList
    : (INBOX_TAB === 'approved'
      ? approvedList
      : (INBOX_TAB === 'rejected' ? rejList : LIST));

  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Kotak Masuk Pengajuan Judul (${LIST.length})</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Daftar pengajuan perubahan judul skripsi mahasiswa bimbingan Anda.</p>
        </div>
      </div>
      <div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap">
        <button class="${INBOX_TAB === 'pending' ? '' : 's'}" onclick="INBOX_TAB='pending';vInbox()">
          ⏳ Perlu Tindakan Saya (${pendingList.length})
        </button>
        <button class="${INBOX_TAB === 'approved' ? '' : 's'}" onclick="INBOX_TAB='approved';vInbox()">
          ✔ Disetujui (${approvedList.length})
        </button>
        <button class="${INBOX_TAB === 'rejected' ? '' : 's'}" onclick="INBOX_TAB='rejected';vInbox()">
          ❌ Ditolak (${rejList.length})
        </button>
        <button class="${INBOX_TAB === 'all' ? '' : 's'}" onclick="INBOX_TAB='all';vInbox()">
          📋 Semua Pengajuan (${LIST.length})
        </button>
      </div>
      <div id="m"></div>
      ${tbl(displayList, 0, 1)}
    </div>
    <div id="hasil"></div>`;
}

function vPw() {
  $('#app').innerHTML = `
    <div class="card" style="max-width:400px;margin:20px auto">
      <div class="card-header"><h3>Ganti Password</h3></div>
      <div id="m"></div>
      <label>Password Lama</label><input id="a" type="password">
      <label>Password Baru (minimal 6 karakter)</label><input id="b" type="password">
      <p style="margin-top:18px"><button onclick="run(call('gantiPasswordSaya',tk(),$('#a').value,$('#b').value),'Password Anda berhasil diganti.')">Simpan Password</button></p>
    </div>`;
}

/* ===== 5. Admin Panel ===== */
async function vProdi() {
  PUB = await call('publik');
  $('#app').innerHTML = `
    <div class="card">
      <h3>Daftar Program Studi</h3>
      <div class="tw">
        <table>
          <thead><tr><th>Program Studi</th><th>Kaprodi Aktif</th><th>Aksi</th></tr></thead>
          <tbody>
            ${PUB.prodi.map(p => `
              <tr>
                <td><b>${esc(p.nama)}</b></td>
                <td>${p.kaprodi ? `<b>${esc(p.kaprodi)}</b><br><small style="color:var(--tx-muted)">NIDN: ${esc(p.kaprodiUser)}</small>` : `<span style="color:var(--tx-muted);font-style:italic">Belum ditentukan (atur di Kelola Dosen)</span>`}</td>
                <td>
                  <button class="s" style="padding:4px 8px;font-size:12px" onclick="modalTambahDosen('${esc(p.nama)}')">➕ Tambah Dosen</button>
                  <button class="d" style="padding:4px 8px;font-size:12px" onclick="hp('hapusProdi','${esc(p.id)}')">Hapus</button>
                </td>
              </tr>
            `).join('') || '<tr><td colspan=3>Belum ada program studi.</td></tr>'}
          </tbody>
        </table>
      </div>
    </div>
    <div class="card">
      <h3>Tambah Program Studi Baru</h3>
      <div id="m"></div>
      <div class="g"><div><label>Nama Program Studi</label><input id="pn" placeholder="Contoh: Agroteknologi"></div></div>
      <p><button onclick="simpanProdi()">Tambah Prodi</button></p>
      <small style="color:var(--tx-muted)">💡 Penetapan Kaprodi dilakukan melalui menu <b>Manajemen User</b> dengan mengubah role dosen terkait menjadi <b>kaprodi</b>.</small>
    </div>`;
}

async function simpanProdi() {
  const v = $('#pn').value.trim();
  if (!v) return msg('Nama program studi wajib diisi.', 1);
  try {
    await run(call('simpanProdi', tk(), { nama: v }), 'Program studi ditambahkan.');
    vProdi();
  } catch (e) {}
}

async function hp(fn, id) {
  openModal({
    title: 'Konfirmasi Hapus Data',
    contentHtml: `<p style="margin:0;font-size:14px">Apakah Anda yakin ingin menghapus data ini secara permanen?</p>`,
    okText: 'Hapus Data',
    okClass: 'd',
    onOk: async () => {
      await run(call(fn, tk(), id));
      render();
    }
  });
}
const hpPeng = nim => hp('hapusPengajuan', nim);

let USERS = [], UF = { q: '', role: '', prodi: '', sort: 'nama_asc' };
async function vUser() {
  try { USERS = await call('users', tk()); } catch (e) { return; }
  renderUserView();
}

function renderUserView() {
  const allU = USERS, tot = allU.length;
  const dCnt = allU.filter(u => u.role === 'dosen').length;
  const kCnt = allU.filter(u => u.role === 'kaprodi').length;
  const aCnt = allU.filter(u => u.role === 'admin').length;

  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Kelola Data & Akun Dosen</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Tambah data dosen baru, atur Program Studi, hak akses Kaprodi, dan password akun.</p>
        </div>
        <button onclick="modalTambahDosen()" style="padding:9px 18px">➕ Tambah Akun Dosen Baru</button>
      </div>
      <div id="m"></div>
      <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:14px">
        <input id="qUser" placeholder="🔍 Cari nama dosen, NIDN, atau prodi..." value="${esc(UF.q)}" oninput="UF.q=this.value;renderUserRows()" style="max-width:260px;flex:1">
        <select id="filterProdiUser" onchange="UF.prodi=this.value;renderUserRows()" style="max-width:220px">
          <option value="">-- Semua Program Studi --</option>
          ${(PUB.prodi || []).map(p => `<option value="${esc(p.nama)}" ${UF.prodi === p.nama ? 'selected' : ''}>Prodi ${esc(p.nama)}</option>`).join('')}
        </select>
        <select id="sortUser" onchange="UF.sort=this.value;renderUserRows()" style="max-width:180px">
          <option value="nama_asc" ${UF.sort === 'nama_asc' ? 'selected' : ''}>Nama (A - Z)</option>
          <option value="nama_desc" ${UF.sort === 'nama_desc' ? 'selected' : ''}>Nama (Z - A)</option>
          <option value="nidn_asc" ${UF.sort === 'nidn_asc' ? 'selected' : ''}>NIDN / Username</option>
          <option value="prodi_asc" ${UF.sort === 'prodi_asc' ? 'selected' : ''}>Program Studi</option>
          <option value="role_kaprodi" ${UF.sort === 'role_kaprodi' ? 'selected' : ''}>⭐ Kaprodi Pertama</option>
        </select>
        <div style="display:flex;flex-wrap:wrap;gap:4px">
          <button class="${UF.role === '' ? '' : 's'}" style="padding:6px 12px;font-size:13px" onclick="UF.role='';renderUserView()">Semua (${tot})</button>
          <button class="${UF.role === 'dosen' ? '' : 's'}" style="padding:6px 12px;font-size:13px" onclick="UF.role='dosen';renderUserView()">Dosen (${dCnt})</button>
          <button class="${UF.role === 'kaprodi' ? '' : 's'}" style="padding:6px 12px;font-size:13px" onclick="UF.role='kaprodi';renderUserView()">⭐ Kaprodi (${kCnt})</button>
          <button class="${UF.role === 'admin' ? '' : 's'}" style="padding:6px 12px;font-size:13px" onclick="UF.role='admin';renderUserView()">Admin (${aCnt})</button>
        </div>
      </div>
      <div class="tw">
        <table>
          <thead><tr><th>Username (NIDN)</th><th>Nama Lengkap & Gelar</th><th>Program Studi</th><th>Role</th><th>Aksi</th></tr></thead>
          <tbody id="userRows"></tbody>
        </table>
      </div>
    </div>
    <div class="card" style="border-color:var(--dg-border)">
      <h3 style="color:var(--dg)">Hapus Massal Akun</h3>
      <p style="font-size:13px;color:var(--tx-muted)">Hapus seluruh akun berdasarkan role. Akun admin tidak ikut terhapus.</p>
      <div class="g"><div><label>Role yang Dihapus</label><select id="hr">${['dosen', 'kaprodi'].map(r => `<option value="${r}">${r} (${USERS.filter(u => u.role === r).length} akun)</option>`).join('')}</select></div></div>
      <p style="margin-top:14px"><button class="d" onclick="hapusMassal()">Hapus Semua Akun Role Ini</button></p>
    </div>`;
  renderUserRows();
}

function renderUserRows() {
  const q = UF.q.toLowerCase().trim();
  let list = USERS.filter(u => {
    if (UF.role && u.role !== UF.role) return false;
    if (UF.prodi && u.prodi !== UF.prodi) return false;
    if (!q) return true;
    return u.username.toLowerCase().includes(q) || u.nama.toLowerCase().includes(q) || (u.prodi || '').toLowerCase().includes(q);
  });
  list.sort((a, b) => {
    if (UF.sort === 'nama_asc') return a.nama.localeCompare(b.nama, 'id');
    if (UF.sort === 'nama_desc') return b.nama.localeCompare(a.nama, 'id');
    if (UF.sort === 'nidn_asc') return a.username.localeCompare(b.username);
    if (UF.sort === 'prodi_asc') return (a.prodi || '').localeCompare(b.prodi || '');
    if (UF.sort === 'role_kaprodi') {
      const rk = { 'kaprodi': 1, 'admin': 2, 'dosen': 3 };
      return (rk[a.role] || 9) - (rk[b.role] || 9) || a.nama.localeCompare(b.nama, 'id');
    }
    return 0;
  });
  const tbody = $('#userRows');
  if (!tbody) return;
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan=5 style="text-align:center;color:var(--tx-muted);padding:24px">Tidak ada dosen yang cocok dengan filter.</td></tr>';
    return;
  }
  tbody.innerHTML = list.map(u => {
    const origIdx = USERS.indexOf(u), isK = u.role === 'kaprodi';
    return `
      <tr>
        <td><b>${esc(u.username)}</b></td>
        <td><b>${esc(u.nama)}</b>${isK ? `<span class="badge badge-ok" style="margin-left:6px">⭐ Kaprodi</span>` : ''}</td>
        <td>${esc(u.prodi) ? `<b>Prodi ${esc(u.prodi)}</b>` : '<span style="color:var(--tx-muted);font-style:italic">-</span>'}</td>
        <td>
          <select onchange="pilihRole(${origIdx},this.value,this)" style="${isK ? 'font-weight:700;border-color:var(--ac);background:var(--ac-light)' : ''}">
            ${['dosen', 'kaprodi', 'admin'].map(r => `<option value="${r}" ${u.role === r ? 'selected' : ''}>${r === 'kaprodi' ? '⭐ kaprodi' : r}</option>`).join('')}
          </select>
        </td>
        <td style="white-space:nowrap">
          <button class="s" style="padding:4px 8px;font-size:12px" onclick="modalEditDosen(${origIdx})">✏️ Edit</button>
          <button class="s" style="padding:4px 8px;font-size:12px" onclick="resetPw(${origIdx})">🔑 Password</button>
          <button class="d" style="padding:4px 8px;font-size:12px" onclick="hpUser(${origIdx})">Hapus</button>
        </td>
      </tr>`;
  }).join('');
}

function modalTambahDosen(defaultProdi = '') {
  openModal({
    title: 'Tambah Akun Dosen Baru',
    contentHtml: `
      <div id="modalErr" style="display:none;color:var(--dg);font-weight:600;font-size:13px;margin-bottom:8px"></div>
      <div><label>NIDN / Username Dosen (Wajib)</label><input id="tdNidn" placeholder="Contoh: 0516118901" oninput="this.value=this.value.replace(/\\D/g,'')"></div>
      <div><label>Nama Lengkap & Gelar (Wajib)</label><input id="tdNama" placeholder="Contoh: Dr. Ir. Budi Santoso, M.P."></div>
      <div>
        <label>Program Studi (Wajib)</label>
        <select id="tdProdi">
          <option value="">-- Pilih Program Studi --</option>
          ${opt(PUB.prodi || [], x => x.nama, x => x.nama)}
        </select>
      </div>
      <div>
        <label>Role Akun</label>
        <select id="tdRole">
          <option value="dosen">Dosen Pembimbing</option>
          <option value="kaprodi">⭐ Ketua Program Studi (Kaprodi)</option>
          <option value="admin">Administrator</option>
        </select>
      </div>
      <div><label>Password Akun (Kosongkan jika disamakan dengan NIDN)</label><input id="tdPw" placeholder="Default = sama dengan NIDN..."></div>
      <small style="color:var(--tx-muted);display:block;margin-top:8px">💡 Dosen yang ditambahkan akan otomatis muncul di form mahasiswa sesuai Program Studi masing-masing.</small>
    `,
    okText: 'Simpan Akun Dosen',
    okClass: '',
    onOk: async () => {
      const nidn = $('#tdNidn').value.trim();
      const nama = $('#tdNama').value.trim();
      const prodi = $('#tdProdi').value.trim();
      const role = $('#tdRole').value;
      const pw = $('#tdPw').value.trim() || nidn;

      if (!nidn || !nama) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'NIDN dan Nama Lengkap wajib diisi.'; errEl.style.display = 'block'; }
        return false;
      }
      if (role !== 'admin' && !prodi) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'Harap pilih Program Studi untuk dosen ini.'; errEl.style.display = 'block'; }
        return false;
      }

      await simpanU(nidn, nama, prodi, role, pw);
      showAlert('Berhasil', `Akun dosen ${nama} (NIDN: ${nidn}) berhasil ditambahkan untuk Prodi ${prodi}.`);
      await vUser();
    }
  });
  if (defaultProdi) {
    const sel = $('#tdProdi');
    if (sel) sel.value = defaultProdi;
  }
}

function modalEditDosen(idx) {
  const u = USERS[idx];
  if (!u) return;
  openModal({
    title: `Edit Data Dosen: ${u.nama}`,
    contentHtml: `
      <div id="modalErr" style="display:none;color:var(--dg);font-weight:600;font-size:13px;margin-bottom:8px"></div>
      <div><label>NIDN / Username</label><input id="edNidn" value="${esc(u.username)}" disabled style="background:#f1f5f9;cursor:not-allowed"></div>
      <div><label>Nama Lengkap & Gelar</label><input id="edNama" value="${esc(u.nama)}"></div>
      <div>
        <label>Program Studi</label>
        <select id="edProdi">
          <option value="">-- Pilih Program Studi --</option>
          ${(PUB.prodi || []).map(p => `<option value="${esc(p.nama)}" ${u.prodi === p.nama ? 'selected' : ''}>${esc(p.nama)}</option>`).join('')}
        </select>
      </div>
      <div>
        <label>Role Akun</label>
        <select id="edRole">
          <option value="dosen" ${u.role === 'dosen' ? 'selected' : ''}>Dosen Pembimbing</option>
          <option value="kaprodi" ${u.role === 'kaprodi' ? 'selected' : ''}>⭐ Ketua Program Studi (Kaprodi)</option>
          <option value="admin" ${u.role === 'admin' ? 'selected' : ''}>Administrator</option>
        </select>
      </div>
      <div><label>Ganti Password Baru (Kosongkan jika tidak diubah)</label><input id="edPw" type="password" placeholder="Biarkan kosong jika tidak diubah..."></div>
    `,
    okText: 'Simpan Perubahan',
    okClass: '',
    onOk: async () => {
      const nama = $('#edNama').value.trim();
      const prodi = $('#edProdi').value.trim();
      const role = $('#edRole').value;
      const pw = $('#edPw').value.trim();

      if (!nama) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'Nama lengkap wajib diisi.'; errEl.style.display = 'block'; }
        return false;
      }
      if (role === 'kaprodi' && !prodi) {
        const errEl = $('#modalErr');
        if (errEl) { errEl.textContent = 'Program Studi wajib diisi untuk role Kaprodi.'; errEl.style.display = 'block'; }
        return false;
      }

      await simpanU(u.username, nama, prodi, role, pw || undefined);
      showAlert('Sukses', `Data dosen ${nama} berhasil diperbarui.`);
      await vUser();
    }
  });
}

async function pilihRole(i, role, selEl) {
  const u = USERS[i]; if (!u) return;
  if (role === 'kaprodi') {
    if (!u.prodi) {
      showAlert('Pemberitahuan', 'Dosen ini belum memiliki Program Studi. Harap tentukan Prodi terlebih dahulu.');
      if (selEl) selEl.value = u.role;
      return;
    }
    const prevK = USERS.find(x => x.role === 'kaprodi' && x.prodi === u.prodi && x.username !== u.username);
    const msgConfirm = `Tetapkan <b>${esc(u.nama)}</b> sebagai Kaprodi <b>${esc(u.prodi)}</b>?` + 
      (prevK ? `<br><br><span style="font-size:13px;color:var(--tx-muted)">Catatan: ${esc(prevK.nama)} (Kaprodi sebelumnya) akan dialihkan menjadi Dosen biasa.</span>` : '');
    
    openModal({
      title: 'Tetapkan Kaprodi',
      contentHtml: `<p style="margin:0;font-size:14px">${msgConfirm}</p>`,
      okText: 'Tetapkan Kaprodi',
      onOk: async () => {
        await simpanU(u.username, u.nama, u.prodi, role);
        vUser();
      },
      cancelText: 'Batal'
    });
    return;
  }
  await simpanU(u.username, u.nama, u.prodi, role);
  vUser();
}
function resetPw(i) {
  const u = USERS[i]; if (!u) return;
  openModal({
    title: `Ganti Password: ${u.nama}`,
    contentHtml: `
      <p style="margin:0 0 10px;font-size:13px;color:var(--tx-muted)">Masukkan password baru untuk akun NIDN <b>${esc(u.username)}</b>:</p>
      <input type="password" id="adminNewPw" placeholder="Password baru (minimal 6 karakter)...">
    `,
    okText: 'Simpan Password',
    onOk: async () => {
      const x = $('#adminNewPw').value.trim();
      if (!x) return false;
      await simpanU(u.username, u.nama, u.prodi, u.role, x);
      showAlert('Sukses', `Password untuk ${u.nama} berhasil diperbarui.`);
    }
  });
}
async function hpUser(i) {
  const u = USERS[i]; if (u) hp('hapusUser', u.username);
}
async function simpanU(username, nama, prodi, role, password) {
  try {
    await run(call('simpanUser', tk(), { username, nama, prodi, role, password }), 'Tersimpan.');
    PUB = await call('publik');
  } catch (e) {}
}
function hapusMassal() {
  const r = $('#hr').value;
  openModal({
    title: `Hapus Massal Akun (${r})`,
    contentHtml: `
      <p style="margin:0 0 10px;color:var(--dg);font-weight:600;font-size:14px">Semua akun dengan role "${esc(r)}" akan dihapus permanen!</p>
      <p style="font-size:13px;color:var(--tx-muted);margin:0 0 8px">Ketik <b>HAPUS</b> untuk mengonfirmasi tindakan ini:</p>
      <input id="confirmHapusMassal" placeholder="Ketik HAPUS di sini...">
    `,
    okText: 'Hapus Semua',
    okClass: 'd',
    onOk: async () => {
      if ($('#confirmHapusMassal').value.trim() !== 'HAPUS') {
        showAlert('Batal', 'Konfirmasi kata HAPUS tidak sesuai. Penghapusan dibatalkan.');
        return;
      }
      const n = await run(call('hapusUserRole', tk(), r));
      await vUser();
      showAlert('Selesai', `${n} akun role "${r}" berhasil dihapus.`);
    }
  });
}
async function tambahU() {
  const v = i => $('#' + i).value.trim();
  await simpanU(v('nu'), v('nn'), v('npr'), v('nr'), v('np'));
  vUser();
}

function vImport() {
  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Import Data Dosen dari Excel (XLS / XLSX)</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Kolom wajib: <b>Nama Dosen, Prodi, NIDN</b>. Akun dibuat otomatis dengan username & password awal = NIDN.</p>
        </div>
      </div>
      <input type="file" id="fx" accept=".xls,.xlsx" style="margin-bottom:14px">
      <div style="display:flex;gap:10px">
        <button onclick="doImport()">📤 Mulai Import</button>
        <button class="s" onclick="tmpl()">📥 Unduh Template Excel</button>
      </div>
      <div id="m"></div>
    </div>`;
}

function tmpl() {
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([['Nama Dosen', 'Prodi', 'NIDN'], ['Contoh Dosen, S.P., M.P.', 'Agroteknologi', '0512345678']]), 'Dosen');
  XLSX.writeFile(wb, 'template-dosen.xlsx');
}

const padId = v => {
  v = String(v == null ? '' : v).trim();
  return /^\d{8,9}$/.test(v) ? ('0000000000' + v).slice(-10) : v;
};

async function doImport() {
  const f = $('#fx').files[0];
  if (!f) return msg('Silakan pilih file Excel terlebih dahulu.', 1);
  const wb = XLSX.read(await f.arrayBuffer());
  const js = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { raw: false });
  const rows = js.map(r => {
    const k = {};
    Object.keys(r).forEach(a => k[a.toLowerCase().replace(/\s/g, '')] = r[a]);
    return { nama: k.namadosen || k.nama, prodi: k.prodi, nidn: padId(k.nidn) };
  });
  msg('Sedang mengimpor ' + rows.length + ' baris data… mohon tunggu.');
  try {
    const n = await call('importDosen', tk(), rows);
    msg('✅ Sebanyak ' + n + ' dosen berhasil diimpor ke sistem.');
  } catch (e) {
    msg(errm(e), 1);
  }
}

async function vData() {
  $('#app').innerHTML = '<div class="card">Memuat semua pengajuan…</div>';
  try { LIST = await call('semuaPengajuan', tk()); } catch (e) { return; }
  $('#app').innerHTML = `
    <div class="card">
      <div class="card-header">
        <div>
          <h3>Semua Pengajuan Perubahan Judul (${LIST.length})</h3>
          <p style="margin:0;font-size:13px;color:var(--tx-muted)">Data seluruh pengajuan mahasiswa dari seluruh program studi.</p>
        </div>
      </div>
      <div id="m"></div>
      ${tbl(LIST, 1)}
    </div>
    <div id="hasil"></div>`;
}

/* ===== Verifikasi Publik via QR / Parameter ?v= ===== */
async function vVerif() {
  $('#nav').style.display = 'none';
  let r = null;
  try { r = await call('verifikasi', VERIFY); } catch (e) {}
  if (!r) {
    $('#app').innerHTML = '<div class="card err"><h3>⚠ Dokumen Tidak Ditemukan</h3>Data pengajuan ini tidak terdaftar di sistem kami. Dokumen patut dicurigai keasliannya.</div>';
    return;
  }
  const ok = !!r.kodeDok;
  const isRej = r.isRejected;
  const sg = (t, s, n) => {
    const v = r['v' + s];
    let st = '<span class="badge badge-wait">⏳ Menunggu</span>';
    if (v === '1') st = `<span class="badge badge-ok">✔ Sah · ${fmt(r['t' + s])}</span>`;
    else if (v === '2') st = `<span class="badge badge-rej">✕ Tidak Disetujui</span>`;
    return `<tr><td>${t}</td><td><b>${esc(n)}</b></td><td>${st}</td></tr>`;
  };

  $('#app').innerHTML = `
    <div class="card" style="border:2px solid ${ok ? 'var(--succ)' : (isRej ? 'var(--dg)' : 'var(--warn)')}">
      <h2>${ok ? '✔ DOKUMEN ASLI & SAH' : (isRej ? '✕ DOKUMEN TIDAK DISETUJUI / REVISI' : '⚠ BELUM DISAHKAN KAPRODI')}</h2>
      <p>Hasil verifikasi resmi sistem pengajuan perubahan judul skripsi Fakultas Pertanian.</p>
      <div class="tw">
        <table>
          <tr><td>NIM Mahasiswa</td><td colspan=2><b>${esc(r.nim)}</b> (Pengajuan ke-${esc(r.ke)})</td></tr>
          <tr><td>Nama Mahasiswa</td><td colspan=2><b>${esc(r.nama)}</b></td></tr>
          <tr><td>Program Studi</td><td colspan=2>${esc(r.prodi)}</td></tr>
          <tr><td>Judul Baru</td><td colspan=2><b>${esc(r.judulBaru)}</b></td></tr>
          ${sg('Dosen Pembimbing I', '1', r.d1Nama)}
          ${sg('Dosen Pembimbing II', '2', r.d2Nama)}
          ${sg('Ketua Program Studi', 'k', r.kaprodi)}
          ${(r.notes || []).map(n => `
            <tr>
              <td>Catatan</td>
              <td colspan=2><b>${esc(n.o)}:</b> ${esc(n.x)}<br><small style="color:var(--tx-muted)">${fmt(n.t)}</small></td>
            </tr>
          `).join('')}
          <tr><td>Kode Dokumen</td><td colspan=2><b>${esc(r.kodeDok) || '-'}</b></td></tr>
        </table>
      </div>
      <p style="margin-top:20px"><a href="/" style="color:var(--ac);text-decoration:none;font-weight:700">← Kembali ke Halaman Utama</a></p>
    </div>`;
}

init();
</script>
</body>
</html>
@endverbatim
