import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const qsa = (selector) => [...document.querySelectorAll(selector)];
const money = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));
const statusLabels = {
    menunggu: 'Menunggu',
    diterima: 'Diterima',
    disetujui: 'Disetujui',
    ditolak: 'Ditolak',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan',
};
let marketplace = [];
let fertilizer = [];
let filter = 'semua';

async function load() {
    const [marketplaceOrders, fertilizerData] = await Promise.all([
        api('/api/marketplace-pesanan'),
        api('/api/pupuk'),
    ]);
    marketplace = marketplaceOrders.map((item) => ({
        tipe: 'marketplace',
        label: 'Marketplace',
        nama: item.produk,
        detail: `${number.format(item.jumlah)} ${escapeHtml(item.satuan)}`,
        tanggal: item.waktu,
        timestamp: item.timestamp || 0,
        metode: item.metodePembayaran,
        catatan: item.catatan,
        total: item.totalBayar,
        status: item.status,
    }));
    fertilizer = fertilizerData.orders.map((item) => ({
        tipe: 'pupuk',
        label: 'Pupuk',
        nama: item.items.map((row) => row.nama).join(', '),
        detail: item.items.map((row) => `${number.format(row.jumlah)} ${escapeHtml(row.satuan || 'paket')}`).join(', '),
        tanggal: item.tanggal,
        timestamp: item.timestamp || 0,
        metode: item.metode,
        total: item.total,
        status: item.status,
    }));
    render();
}

function render() {
    const rows = [...marketplace, ...fertilizer]
        .filter((item) => filter === 'semua' || item.tipe === filter)
        .sort((left, right) => right.timestamp - left.timestamp);
    qs('[data-total-transaksi]').textContent = `${rows.length} transaksi`;
    qs('[data-total-nilai]').textContent = `Total ${money.format(rows.reduce((sum, item) => sum + item.total, 0))}`;
    qs('[data-daftar-riwayat-transaksi]').innerHTML = rows.length
        ? rows.map((item) => `
            <article class="kartu-transaksi kartu-riwayat-petani status-kartu-${escapeHtml(item.status)}" data-tipe-transaksi="${escapeHtml(item.tipe)}">
                <div class="kepala-transaksi">
                    <span class="label-transaksi label-${escapeHtml(item.tipe)}">${escapeHtml(item.label)}</span>
                    <time class="tanggal-transaksi">${escapeHtml(item.tanggal)}</time>
                </div>

                <div>
                    <h2 class="judul-transaksi">${escapeHtml(item.nama)}</h2>
                    <p class="detail-transaksi">${item.detail}</p>
                    ${item.catatan ? `<p class="catatan-transaksi">Catatan pembeli: ${escapeHtml(item.catatan)}</p>` : ''}
                </div>

                <div class="kaki-transaksi">
                    <div class="rincian-pembayaran">
                        <span class="metode-transaksi">Pembayaran ${escapeHtml(item.metode)}</span>
                        <span class="status-transaksi status-${escapeHtml(item.status)}">
                            <i aria-hidden="true"></i>
                            ${escapeHtml(statusLabels[item.status] || item.status)}
                        </span>
                    </div>
                    <div class="nilai-transaksi">
                        <span>Total Transaksi</span>
                        <strong class="total-transaksi">${money.format(item.total)}</strong>
                    </div>
                </div>
            </article>
        `).join('')
        : `
            <article class="riwayat-kosong">
                <div>
                    <h2>Belum ada transaksi</h2>
                    <p>Belum ada transaksi ${filter === 'semua' ? 'marketplace atau pupuk' : filter}.</p>
                </div>
            </article>
        `;
}

qsa('[data-filter-riwayat]').forEach((button) => button.addEventListener('click', () => {
    filter = button.dataset.filterRiwayat;
    qsa('[data-filter-riwayat]').forEach((item) => {
        const active = item === button;
        item.classList.toggle('aktif', active);
        item.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    render();
}));
load().catch((error) => alert(error.message));
