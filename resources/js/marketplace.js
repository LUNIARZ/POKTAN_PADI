import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const state = { products: [], orders: [] };
const money = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));
const wholeNumber = (value) => {
    const raw = String(value ?? '').trim();
    if (!raw) return 0;

    const compact = raw.replace(/\./g, '');
    return /^[0-9]+$/.test(compact) ? Number(compact) : raw;
};
const rupiahValue = (value) => {
    const digits = String(value ?? '').replace(/\D/g, '');
    return digits ? new Intl.NumberFormat('id-ID').format(Number(digits)) : '';
};
const rupiahNumber = (value) => Number(String(value ?? '').replace(/\D/g, '')) || 0;
const supportedImageTypes = new Set(['image/jpeg', 'image/png', 'image/webp']);
const maximumImageSize = 2 * 1024 * 1024;

function setFormStatus(message = '', type = 'info') {
    const status = qs('[data-form-produk-status]');
    if (!status) return;
    status.textContent = message;
    status.dataset.status = type;
    status.hidden = !message;
}

function setSaving(saving, label = 'MENYIMPAN...') {
    const button = qs('[data-tombol-simpan-produk]');
    if (!button) return;
    button.disabled = saving;
    button.textContent = saving ? label : 'SIMPAN';
}

function validateImage(file) {
    if (!file) return;
    if (!supportedImageTypes.has(file.type)) {
        throw new Error('Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.');
    }
    if (file.size > maximumImageSize) {
        throw new Error('Ukuran foto maksimal 2 MB. Pilih foto yang lebih kecil.');
    }
}

async function load() {
    [state.products, state.orders] = await Promise.all([
        api('/api/marketplace'),
        api('/api/marketplace-pesanan'),
    ]);
    renderProducts();
    renderOrders();
}

function renderProducts() {
    const list = qs('[data-daftar-produk]');
    list.innerHTML = state.products.length ? state.products.map((item) => `
        <article class="item-produk">
            <div class="kartu-produk">
                <div class="area-gambar-produk"><div class="lingkaran-gambar"></div>
                    <img class="gambar-produk" src="${escapeHtml(item.gambar)}" alt="${escapeHtml(item.nama)}"></div>
                <div class="isi-produk"><h2>${escapeHtml(item.nama)}</h2><p>${escapeHtml(item.deskripsi)}</p>
                    <span class="alamat-produk">${escapeHtml(item.alamat || '-')}</span><div class="garis-pemisah"></div>
                    <div class="info-produk"><strong>${item.harga > 0 ? `${money.format(item.harga)} /Kg` : 'Harga nego'}</strong>
                    <span>Stok ${number.format(item.jumlah)} ${escapeHtml(item.satuan)}</span></div></div>
            </div>
            <div class="aksi-produk"><button class="tombol-edit" data-edit-product="${item.id}">EDIT</button>
            <button class="tombol-hapus" data-delete-product="${item.id}">HAPUS</button></div>
        </article>
    `).join('') : '<article class="produk-kosong"><div><h2>Belum Ada Produk</h2><p>Tekan tombol tambah untuk menambahkan produk marketplace.</p></div></article>';
}

function renderOrders() {
    const list = qs('[data-daftar-pesanan]');
    const pending = state.orders.filter((item) => item.status === 'menunggu').length;
    qs('[data-jumlah-pesanan]').textContent = pending;
    qs('[data-jumlah-pesanan]').hidden = pending === 0;
    list.innerHTML = state.orders.length ? state.orders.map((item) => {
        const buyerWarehouse = item.namaGudangPembeli
            ? `<p><strong>Gudang:</strong> ${escapeHtml(item.namaGudangPembeli)}</p>`
            : '';

        return `
        <article class="item-pesanan">
            <div class="kepala-item-pesanan"><h3>${escapeHtml(item.namaPembeli)}</h3><time>${escapeHtml(item.waktu)}</time></div>
            <div class="detail-pesanan"><p>Ingin membeli ${number.format(item.jumlah)} ${escapeHtml(item.satuan)} ${escapeHtml(item.produk)}.</p>
            <p>${escapeHtml(item.catatan || '')}</p></div>
            <div class="detail-pembeli-pesanan" data-order-detail-panel="${item.id}" hidden>
                <p><strong>No. HP:</strong> ${escapeHtml(item.nomorHpPembeli || '-')}</p>
                <p><strong>Alamat:</strong> ${escapeHtml(item.alamatPembeli || '-')}</p>
                ${buyerWarehouse}
            </div>
            <span class="status-pesanan ${item.status}">${escapeHtml(item.status)}</span>
            <div class="aksi-pesanan ${item.status !== 'menunggu' ? 'aksi-pesanan-detail-saja' : ''}">
                <button class="tombol-detail-pesanan" type="button" data-order-detail="${item.id}" aria-expanded="false">DETAIL</button>
                ${item.status === 'menunggu' ? `<button class="tombol-approve" data-order-status="disetujui" data-order-id="${item.id}">SETUJU</button>
                <button class="tombol-reject" data-order-status="ditolak" data-order-id="${item.id}">TOLAK</button>` : ''}
            </div>
        </article>
    `;
    }).join('') : '<article class="pesanan-kosong"><div><h3>Belum Ada Permintaan</h3><p>Permintaan pembelian akan tampil di sini.</p></div></article>';
}

function openProduct(item = null) {
    const panel = qs('[data-panel-produk]');
    qs('[data-form-produk]').reset();
    qs('[data-input-id-produk]').value = item?.id || '';
    qs('[data-input-nama-produk]').value = item?.nama || '';
    qs('[data-input-deskripsi-produk]').value = item?.deskripsi || '';
    qs('[data-input-alamat-produk]').value = item?.alamat || '';
    qs('[data-input-harga-produk]').value = rupiahValue(item?.harga);
    qs('[data-input-stok-produk]').value = item?.jumlah || '';
    qs('[data-input-satuan-produk]').value = item?.satuan || 'kg';
    qs('[data-judul-panel-produk]').textContent = item ? 'Edit Produk' : 'Tambah Produk';
    setFormStatus();
    setSaving(false);
    panel.hidden = false;
}

qs('[data-form-produk]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    setFormStatus();
    setSaving(true);
    try {
        const id = qs('[data-input-id-produk]').value;
        const form = new FormData();
        form.append('nama_produk', qs('[data-input-nama-produk]').value);
        form.append('deskripsi', qs('[data-input-deskripsi-produk]').value);
        form.append('alamat_produk', qs('[data-input-alamat-produk]').value);
        form.append('harga', rupiahNumber(qs('[data-input-harga-produk]').value));
        form.append('jumlah_stok', wholeNumber(qs('[data-input-stok-produk]').value));
        form.append('satuan', qs('[data-input-satuan-produk]').value);
        const image = qs('[data-input-gambar-produk]').files[0];
        if (image) {
            validateImage(image);
            form.append('gambar', image);
        }
        setSaving(true);
        setFormStatus('Produk sedang disimpan.');
        await api(id ? `/api/marketplace/${id}` : '/api/marketplace', { method: 'POST', body: form });
        qs('[data-panel-produk]').hidden = true;
        await load();
    } catch (error) {
        setFormStatus(error.message, 'error');
        qs('[data-form-produk-status]')?.focus();
    } finally {
        setSaving(false);
    }
});

qs('[data-input-gambar-produk]')?.addEventListener('change', (event) => {
    const file = event.target.files[0];
    setFormStatus();
    if (!file) return;

    try {
        validateImage(file);
        setFormStatus(`Foto siap diunggah (${(file.size / 1024 / 1024).toFixed(2)} MB).`);
    } catch (error) {
        event.target.value = '';
        setFormStatus(error.message, 'error');
        qs('[data-form-produk-status]')?.focus();
    }
});

qs('[data-input-harga-produk]')?.addEventListener('input', (event) => {
    event.target.value = rupiahValue(event.target.value);
});

document.addEventListener('click', async (event) => {
    const button = event.target.closest('button');
    if (!button) return;
    try {
        if (button.matches('[data-buka-panel-produk]')) openProduct();
        if (button.matches('[data-tutup-panel-produk]')) qs('[data-panel-produk]').hidden = true;
        if (button.matches('[data-buka-notifikasi-pesanan]')) { qs('[data-panel-notifikasi-pesanan]').hidden = false; renderOrders(); }
        if (button.matches('[data-tutup-notifikasi-pesanan]')) qs('[data-panel-notifikasi-pesanan]').hidden = true;
        if (button.matches('[data-order-detail]')) {
            const panel = qs(`[data-order-detail-panel="${button.dataset.orderDetail}"]`);
            if (!panel) return;
            const willOpen = panel.hidden;
            panel.hidden = !willOpen;
            button.setAttribute('aria-expanded', String(willOpen));
            button.textContent = willOpen ? 'TUTUP DETAIL' : 'DETAIL';
        }
        if (button.matches('[data-edit-product]')) openProduct(state.products.find((item) => String(item.id) === button.dataset.editProduct));
        if (button.matches('[data-delete-product]')) {
            if (!confirm('Hapus produk ini?')) return;
            await api(`/api/marketplace/${button.dataset.deleteProduct}`, { method: 'DELETE' }); await load();
        }
        if (button.matches('[data-order-status]')) {
            await api(`/api/marketplace-pesanan/${button.dataset.orderId}`, { method: 'PATCH', body: { status: button.dataset.orderStatus } });
            await load();
        }
    } catch (error) { alert(error.message); }
});

load().catch((error) => alert(error.message));
