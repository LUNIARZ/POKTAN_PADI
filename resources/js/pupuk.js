import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const qsa = (selector) => [...document.querySelectorAll(selector)];
const money = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const state = { products: [], orders: [], cart: [], paymentMethods: {} };
const orderStatusLabels = {
    menunggu: 'Menunggu',
    diterima: 'Diterima',
    ditolak: 'Ditolak',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan',
};
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

async function load() {
    const data = await api('/api/pupuk');
    state.products = data.products;
    state.orders = data.orders;
    state.paymentMethods = data.paymentMethods;
    renderProducts();
    renderHistory();
    renderCart();
    renderPayment();
}

function renderProducts() {
    qs('[data-daftar-produk-pupuk]').innerHTML = state.products.map((item) => `
        <article class="kartu-produk">
            <img src="${escapeHtml(item.gambar)}" alt="${escapeHtml(item.nama)}" class="gambar-produk">
            <h3>${escapeHtml(item.nama)}</h3><p class="deskripsi-produk">${escapeHtml(item.deskripsi)}</p>
            ${item.dibatasi ? `<p class="stok-produk-pupuk ${item.batas <= 0 ? 'batas-habis' : ''}">Batas ${number.format(item.batas)} karung</p>` : ''}
            <div class="harga-produk"><strong>${money.format(item.harga)}</strong><span>${escapeHtml(item.satuan)}</span></div>
            <button class="tombol-beli" data-add-fertilizer="${item.id}" ${item.dibatasi && item.batas <= 0 ? 'disabled' : ''}>
                ${item.dibatasi && item.batas <= 0 ? 'BATAS HABIS' : 'BELI'}
            </button>
        </article>
    `).join('');
}

function renderCart() {
    const quantity = state.cart.reduce((sum, item) => sum + item.jumlah, 0);
    const total = state.cart.reduce((sum, item) => sum + item.jumlah * item.harga, 0);
    qs('[data-jumlah-keranjang]').textContent = quantity;
    qs('[data-jumlah-keranjang]').hidden = quantity === 0;
    qs('[data-checkout-produk]').textContent = state.cart.length ? `${state.cart.length} produk dipilih` : 'Belum memilih produk';
    qs('[data-checkout-harga]').textContent = state.cart.length ? `${quantity} item dalam pesanan` : 'Tekan BELI pada satu atau beberapa produk';
    qs('[data-daftar-checkout]').hidden = !state.cart.length;
    qs('[data-total-checkout]').hidden = !state.cart.length;
    qs('[data-total-bayar]').textContent = money.format(total);
    qs('[data-daftar-checkout]').innerHTML = state.cart.map((item) => `
        <article class="item-checkout"><div class="info-checkout"><strong>${escapeHtml(item.nama)}</strong>
        <span>${money.format(item.harga)} ${escapeHtml(item.satuan)}</span></div>
        <div class="kontrol-jumlah"><button data-cart-action="less" data-cart-id="${item.id}">−</button>
        <strong>${item.jumlah}</strong><button data-cart-action="more" data-cart-id="${item.id}"
            ${item.dibatasi && item.jumlah >= item.batas ? 'disabled' : ''}>+</button></div></article>
    `).join('');
    syncPaymentSelection();
}

function renderHistory() {
    qs('[data-jumlah-riwayat]').textContent = state.orders.length;
    qs('[data-jumlah-riwayat]').hidden = !state.orders.length;
    qs('[data-daftar-riwayat]').innerHTML = state.orders.length ? state.orders.map((order) => `
        <article class="item-riwayat-pupuk status-${escapeHtml(order.status)}">
            <div class="kepala-riwayat-pupuk">
                <h3>Pesanan Pupuk</h3>
                <time>${escapeHtml(order.tanggal)}</time>
            </div>
            <div class="detail-riwayat-pupuk">
                ${order.items.map((item) => `
                    <p>
                        <strong>${escapeHtml(item.nama)}</strong>
                        <span>${number.format(item.jumlah)} ${escapeHtml(item.satuan || 'paket')}</span>
                    </p>
                `).join('')}
            </div>
            <div class="ringkasan-riwayat-pupuk">
                <div>
                    <span>Pembayaran ${escapeHtml(order.metode)}</span>
                    <strong>${money.format(order.total)}</strong>
                </div>
                <span class="status-riwayat-pupuk status-${escapeHtml(order.status)}">
                    ${escapeHtml(orderStatusLabels[order.status] || order.status)}
                </span>
            </div>
            ${order.bisaDibatalkan ? `
                <div class="aksi-riwayat-pupuk">
                    <button class="tombol-batalkan-pupuk" type="button" data-cancel-fertilizer-order="${order.id}">Batalkan Pesanan</button>
                </div>
            ` : ''}
        </article>
    `).join('') : `
        <article class="riwayat-kosong">
            <div>
                <h3>Belum Ada Pesanan</h3>
                <p>Pesanan pupuk yang Anda buat akan tampil di sini.</p>
            </div>
        </article>
    `;
}

function renderPayment() {
    qsa('input[name="metode_pembayaran"]').forEach((input) => {
        input.disabled = state.paymentMethods[input.value] === false;
        if (input.checked && input.disabled) input.checked = false;
    });

    if (!qs('input[name="metode_pembayaran"]:checked:not(:disabled)')) {
        const available = qs('input[name="metode_pembayaran"]:not(:disabled)');
        if (available) available.checked = true;
    }

    syncPaymentSelection();
}

function syncPaymentSelection() {
    const selected = qs('input[name="metode_pembayaran"]:checked:not(:disabled)');
    const status = qs('[data-status-checkout]');

    qsa('[data-payment-option]').forEach((option) => {
        const input = option.querySelector('input[name="metode_pembayaran"]');
        const badge = option.querySelector('[data-payment-badge]');
        const isDisabled = Boolean(input?.disabled);
        const isSelected = Boolean(input?.checked && !isDisabled);

        option.classList.toggle('terpilih', isSelected);
        option.classList.toggle('opsi-gangguan', isDisabled);
        option.setAttribute('aria-disabled', isDisabled ? 'true' : 'false');

        if (badge) {
            badge.textContent = isDisabled
                ? 'Nonaktif'
                : input?.value === 'Tunai'
                    ? 'Rekomendasi'
                    : 'Aktif';
            badge.classList.toggle('gangguan', isDisabled);
        }
    });

    if (status && state.cart.length) {
        status.textContent = selected
            ? `Metode ${selected.value} dipilih.`
            : 'Tidak ada metode pembayaran yang tersedia.';
    }

    const submit = qs('[data-submit-checkout]');
    if (submit) submit.disabled = !state.cart.length || !selected;
}

function addProduct(product) {
    if (!product || (product.dibatasi && product.batas <= 0)) {
        qs('[data-status-checkout]').textContent = product
            ? `Batas pembelian ${product.nama} sudah habis.`
            : 'Produk pupuk tidak ditemukan.';
        return;
    }
    const row = state.cart.find((item) => item.id === product.id);
    const next = (row?.jumlah || 0) + 1;
    if (product.dibatasi && next > product.batas) {
        qs('[data-status-checkout]').textContent = `${product.nama} maksimal ${number.format(product.batas)} karung.`;
        return;
    }
    if (row) row.jumlah = next;
    else state.cart.push({
        id: product.id,
        nama: product.nama,
        harga: product.harga,
        satuan: product.satuan,
        jumlah: 1,
        dibatasi: product.dibatasi,
        batas: product.batas,
    });
    renderCart();
    qs('[data-panel-checkout]').hidden = false;
}

function showHistoryStatus(message, isError = false) {
    const status = qs('[data-status-riwayat-pupuk]');
    if (!status) return;
    status.textContent = message;
    status.classList.toggle('error', isError);
    status.hidden = false;
}

async function cancelFertilizerOrder(button) {
    if (!confirm('Batalkan pesanan pupuk ini?')) return;

    button.disabled = true;
    button.textContent = 'Membatalkan...';
    try {
        const response = await api(`/api/pupuk/pesanan/${button.dataset.cancelFertilizerOrder}/batalkan`, { method: 'PATCH' });
        await load();
        showHistoryStatus(response.message);
    } catch (error) {
        button.disabled = false;
        button.textContent = 'Batalkan Pesanan';
        showHistoryStatus(error.message, true);
    }
}

document.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    if (!button) return;
    if (button.matches('[data-add-fertilizer]')) addProduct(state.products.find((item) => String(item.id) === button.dataset.addFertilizer));
    if (button.matches('[data-cancel-fertilizer-order]')) cancelFertilizerOrder(button);
    if (button.matches('[data-cart-action]')) {
        const row = state.cart.find((item) => String(item.id) === button.dataset.cartId);
        if (!row) return;
        if (button.dataset.cartAction === 'more' && (!row.dibatasi || row.jumlah < row.batas)) row.jumlah++;
        if (button.dataset.cartAction === 'less') row.jumlah--;
        state.cart = state.cart.filter((item) => item.jumlah > 0);
        renderCart();
    }
});

qs('[data-buka-checkout]')?.addEventListener('click', () => qs('[data-panel-checkout]').hidden = false);
qs('[data-tutup-checkout]')?.addEventListener('click', () => qs('[data-panel-checkout]').hidden = true);
qs('[data-buka-riwayat]')?.addEventListener('click', () => qs('[data-panel-riwayat]').hidden = false);
qs('[data-tutup-riwayat]')?.addEventListener('click', () => qs('[data-panel-riwayat]').hidden = true);
qsa('input[name="metode_pembayaran"]').forEach((input) => {
    input.addEventListener('change', syncPaymentSelection);
});
qsa('[data-payment-option]').forEach((option) => {
    option.addEventListener('click', (event) => {
        const input = option.querySelector('input[name="metode_pembayaran"]');
        if (!input) return;

        event.preventDefault();
        if (input.disabled) {
            qs('[data-status-checkout]').textContent = `Metode ${input.value} sedang tidak aktif.`;
            return;
        }

        input.checked = true;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
});
qs('[data-form-checkout]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
        const method = qs('input[name="metode_pembayaran"]:checked:not(:disabled)')?.value;
        if (!method) {
            qs('[data-status-checkout]').textContent = 'Pilih metode pembayaran terlebih dahulu.';
            return;
        }

        await api('/api/pupuk/pesanan', { method: 'POST', body: {
            metode_pembayaran: method.toLowerCase(),
            items: state.cart.map((item) => ({ id: item.id, jumlah: item.jumlah })),
        } });
        state.cart = [];
        qs('[data-status-checkout]').textContent = 'Pesanan pupuk dikirim ke admin.';
        await load();
    } catch (error) { qs('[data-status-checkout]').textContent = error.message; }
});

load().catch((error) => { qs('[data-status-checkout]').textContent = error.message; });
