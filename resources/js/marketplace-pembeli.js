import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const qsa = (selector) => [...document.querySelectorAll(selector)];
const state = { products: [], settings: null, active: null };
const money = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

async function load() {
    [state.products, state.settings] = await Promise.all([
        api('/api/pembeli/marketplace'),
        api('/api/pengaturan'),
    ]);
    render();
    updatePaymentOptions();
}

function render() {
    const marketplaceOpen = state.settings?.marketplace === 'Aktif';
    const keyword = String(qs('[data-cari-produk]').value || '').toLowerCase();
    const products = state.products.filter((item) => `${item.nama} ${item.deskripsi} ${item.petani}`.toLowerCase().includes(keyword));
    qs('[data-cari-produk]').disabled = !marketplaceOpen;

    if (!marketplaceOpen) {
        qs('[data-total-produk]').textContent = `Status: ${state.settings?.marketplace || 'Nonaktif'}`;
        qs('[data-daftar-produk-pembeli]').innerHTML = `
            <article class="produk-tidak-ada marketplace-pembeli-tutup">
                <div><h2>Marketplace ${state.settings?.marketplace || 'Nonaktif'}</h2>
                <p>Marketplace pembeli sedang tidak tersedia. Silakan coba kembali nanti.</p></div>
            </article>
        `;
        qs('[data-panel-jumlah-beli]').hidden = true;
        return;
    }

    qs('[data-total-produk]').textContent = `${products.length} produk tersedia`;
    qs('[data-daftar-produk-pembeli]').innerHTML = products.length ? products.map((item) => `
        <article class="kartu-produk-pembeli">
            <img class="gambar-produk-pembeli" src="${escapeHtml(item.gambar)}" alt="${escapeHtml(item.nama)}">
            <div class="isi-produk-pembeli"><h2 class="nama-produk-pembeli">${escapeHtml(item.nama)}</h2>
            <p class="deskripsi-produk-pembeli">${escapeHtml(item.deskripsi)}</p>
            <div class="meta-produk-pembeli"><strong class="harga-produk-pembeli">${money.format(item.harga)} /Kg</strong>
            <span class="petani-produk-pembeli">${escapeHtml(item.petani)}</span><span class="alamat-produk-pembeli">${escapeHtml(item.alamat)}</span>
            <span class="stok-produk-pembeli">Stok ${number.format(item.jumlah)} ${escapeHtml(item.satuan)}</span></div>
            <button class="tombol-beli-pembeli" data-buy-product="${item.id}" ${item.jumlah <= 0 ? 'disabled' : ''}>Beli</button></div>
        </article>
    `).join('') : '<article class="produk-tidak-ada"><div><h2>Belum ada produk petani</h2><p>Produk petani akan tampil di sini.</p></div></article>';
}

function openBuy(product) {
    if (state.settings?.marketplace !== 'Aktif') {
        showToast('Marketplace pembeli sedang tidak tersedia.');
        return;
    }
    state.active = product;
    qs('[data-panel-nama-produk]').textContent = product.nama;
    qs('[data-panel-petani]').textContent = product.petani;
    qs('[data-panel-gambar-produk]').src = product.gambar;
    qs('[data-panel-harga-produk]').textContent = `${money.format(product.harga)} /Kg`;
    qs('[data-panel-stok-produk]').textContent = `Stok ${number.format(product.jumlah)} ${product.satuan}`;
    qs('[data-panel-alamat-produk]').textContent = product.alamat;
    qs('[data-panel-jumlah]').value = 1;
    qs('[data-catatan-pembeli]').value = '';
    updateTotal();
    updatePaymentOptions();
    qs('[data-panel-jumlah-beli]').hidden = false;
}

function updateTotal() {
    if (!state.active) return;
    const quantity = Math.min(Math.max(Number(qs('[data-panel-jumlah]').value) || 1, 1), Math.floor(state.active.jumlah));
    qs('[data-panel-jumlah]').value = quantity;
    qs('[data-panel-total-bayar]').textContent = money.format(quantity * state.active.harga);
}

function updatePaymentOptions() {
    const disabled = (state.settings?.buyerPaymentDisabledMethods || [])
        .map((method) => String(method).toLowerCase());

    qsa('input[name="metode_pembayaran"]').forEach((input) => {
        input.disabled = disabled.includes(input.value.toLowerCase());
        if (input.checked && input.disabled) input.checked = false;
    });

    if (!qs('input[name="metode_pembayaran"]:checked')) {
        const available = qs('input[name="metode_pembayaran"]:not(:disabled)');
        if (available) available.checked = true;
    }

    syncPaymentSelection();
}

function syncPaymentSelection() {
    const selected = qs('input[name="metode_pembayaran"]:checked');
    const status = qs('[data-status-pembayaran-pembeli]');

    qsa('[data-payment-option]').forEach((option) => {
        const input = option.querySelector('input[name="metode_pembayaran"]');
        const badge = option.querySelector('[data-payment-badge]');
        const isSelected = Boolean(input?.checked && !input.disabled);

        option.classList.toggle('terpilih', isSelected);
        option.classList.toggle('opsi-gangguan', Boolean(input?.disabled));
        option.setAttribute('aria-disabled', input?.disabled ? 'true' : 'false');

        if (badge) {
            badge.textContent = input?.disabled
                ? 'Nonaktif'
                : input?.value === 'Tunai'
                    ? 'Rekomendasi'
                    : 'Aktif';
            badge.classList.toggle('gangguan', Boolean(input?.disabled));
        }
    });

    status.textContent = selected
        ? `Metode ${selected.value} dipilih.`
        : 'Tidak ada metode pembayaran yang tersedia.';
    qs('[data-konfirmasi-beli]').disabled = !selected;
}

qs('[data-cari-produk]')?.addEventListener('input', render);
qs('[data-panel-jumlah]')?.addEventListener('input', updateTotal);
qs('[data-panel-kurang]')?.addEventListener('click', () => { qs('[data-panel-jumlah]').value = Number(qs('[data-panel-jumlah]').value) - 1; updateTotal(); });
qs('[data-panel-tambah]')?.addEventListener('click', () => { qs('[data-panel-jumlah]').value = Number(qs('[data-panel-jumlah]').value) + 1; updateTotal(); });
qs('[data-tutup-panel-beli]')?.addEventListener('click', () => qs('[data-panel-jumlah-beli]').hidden = true);
qsa('input[name="metode_pembayaran"]').forEach((input) => {
    input.addEventListener('change', syncPaymentSelection);
});
qsa('[data-payment-option]').forEach((option) => {
    option.addEventListener('click', (event) => {
        const input = option.querySelector('input[name="metode_pembayaran"]');
        if (!input) return;

        event.preventDefault();
        if (input.disabled) {
            qs('[data-status-pembayaran-pembeli]').textContent = `Metode ${input.value} sedang tidak aktif.`;
            return;
        }

        input.checked = true;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
});
qs('[data-form-pembayaran-pembeli]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
        const method = qs('input[name="metode_pembayaran"]:checked')?.value;
        if (!method) throw new Error('Pilih metode pembayaran.');
        await api('/api/pembeli/pesanan', { method: 'POST', body: {
            id_produk: state.active.id,
            jumlah: Number(qs('[data-panel-jumlah]').value),
            metode_pembayaran: method.toLowerCase(),
            catatan: qs('[data-catatan-pembeli]').value.trim() || null,
        } });
        qs('[data-panel-jumlah-beli]').hidden = true;
        showToast('Permintaan pembelian dikirim ke petani.');
    } catch (error) { showToast(error.message); }
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-buy-product]');
    if (button) openBuy(state.products.find((item) => String(item.id) === button.dataset.buyProduct));
});

function showToast(message) {
    const toast = qs('[data-toast-pembeli]');
    toast.textContent = message; toast.hidden = false;
    setTimeout(() => toast.hidden = true, 2600);
}

load().catch((error) => showToast(error.message));
