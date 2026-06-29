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
    disetujui: 'Disetujui',
    ditolak: 'Ditolak',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan',
};
let orders = [];
let filter = 'semua';

async function load() {
    orders = await api('/api/pembeli/pesanan');
    render();
}

function render() {
    const rows = orders.filter((item) => filter === 'semua' || item.status === filter);
    qs('[data-total-belanja]').textContent = `${rows.length} pesanan`;
    qs('[data-total-nilai-belanja]').textContent = `Total ${money.format(rows.reduce((sum, item) => sum + item.totalBayar, 0))}`;
    qs('[data-daftar-riwayat-belanja]').innerHTML = rows.length
        ? rows.map((item) => `
            <article class="kartu-transaksi kartu-belanja status-kartu-${escapeHtml(item.status)}" data-tipe-transaksi="marketplace">
                <div class="kepala-transaksi">
                    <span class="label-transaksi">Marketplace</span>
                    <time class="tanggal-transaksi" datetime="${escapeHtml(item.tanggal)}">${escapeHtml(item.waktu)}</time>
                </div>

                <div class="isi-transaksi-belanja">
                    <h2 class="judul-transaksi">${escapeHtml(item.produk)}</h2>
                    <p class="detail-transaksi">
                        ${number.format(item.jumlah)} ${escapeHtml(item.satuan)}
                    </p>
                    ${item.catatan ? `<p class="catatan-transaksi">Catatan: ${escapeHtml(item.catatan)}</p>` : ''}
                </div>

                <div class="kaki-transaksi">
                    <div class="rincian-pembayaran">
                        <span class="metode-transaksi">Pembayaran ${escapeHtml(item.metodePembayaran)}</span>
                        <span class="status-transaksi status-${escapeHtml(item.status)}">
                            <i aria-hidden="true"></i>
                            ${escapeHtml(statusLabels[item.status] || item.status)}
                        </span>
                    </div>
                    <div class="nilai-transaksi">
                        <span>Total Belanja</span>
                        <strong class="total-transaksi">${money.format(item.totalBayar)}</strong>
                    </div>
                </div>
                ${item.status === 'menunggu' ? `
                    <div class="aksi-belanja">
                        <button class="tombol-batalkan-pesanan" type="button" data-cancel-order="${item.id}">Batalkan Pesanan</button>
                    </div>
                ` : ''}
            </article>
        `).join('')
        : `
            <article class="riwayat-kosong">
                <div>
                    <h2>Belum ada transaksi</h2>
                    <p>Tidak ada riwayat belanja dengan status ini.</p>
                </div>
            </article>
        `;
}

qsa('[data-filter-belanja]').forEach((button) => button.addEventListener('click', () => {
    filter = button.dataset.filterBelanja;
    qsa('[data-filter-belanja]').forEach((item) => {
        const active = item === button;
        item.classList.toggle('aktif', active);
        item.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    render();
}));

function showStatus(message, isError = false) {
    const status = qs('[data-status-riwayat-belanja]');
    status.textContent = message;
    status.classList.toggle('error', isError);
    status.hidden = false;
}

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-cancel-order]');
    if (!button || !confirm('Batalkan pesanan ini?')) return;

    button.disabled = true;
    button.textContent = 'Membatalkan...';
    try {
        const response = await api(`/api/pembeli/pesanan/${button.dataset.cancelOrder}/batalkan`, { method: 'PATCH' });
        await load();
        showStatus(response.message);
    } catch (error) {
        button.disabled = false;
        button.textContent = 'Batalkan Pesanan';
        showStatus(error.message, true);
    }
});

load().catch((error) => alert(error.message));
