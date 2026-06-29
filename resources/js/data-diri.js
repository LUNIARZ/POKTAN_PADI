import { api } from './api';

const form = document.querySelector('[data-form-password]');
const status = document.querySelector('[data-status-password]');
const money = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
});
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

function statusLabel(value) {
    return String(value || 'menunggu')
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

async function loadBuyerTransactions() {
    const body = document.querySelector('[data-tabel-riwayat-pembeli]');
    if (!body) return;

    try {
        const orders = await api('/api/pembeli/pesanan');
        const latestOrders = orders.slice(0, 5);
        body.innerHTML = latestOrders.length
            ? latestOrders.map((item) => `
                <tr>
                    <td>
                        <strong>${escapeHtml(item.produk)}</strong>
                        <span>${escapeHtml(item.waktu)}</span>
                    </td>
                    <td>${number.format(item.jumlah)} ${escapeHtml(item.satuan)}</td>
                    <td>${money.format(item.totalBayar)}</td>
                    <td>
                        <span class="status-riwayat status-${escapeHtml(item.status)}">
                            ${escapeHtml(statusLabel(item.status))}
                        </span>
                    </td>
                </tr>
            `).join('')
            : `
                <tr>
                    <td colspan="4" class="baris-riwayat-kosong">Belum ada transaksi marketplace.</td>
                </tr>
            `;
        document.querySelector('[data-status-riwayat-pembeli]').textContent = orders.length > 5
            ? `Menampilkan 5 dari ${orders.length} transaksi.`
            : `${orders.length} transaksi ditemukan.`;
    } catch (error) {
        body.innerHTML = `
            <tr>
                <td colspan="4" class="baris-riwayat-kosong">Riwayat transaksi belum dapat dimuat.</td>
            </tr>
        `;
        document.querySelector('[data-status-riwayat-pembeli]').textContent = error.message;
    }
}

document.querySelectorAll('[data-toggle-password]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = button.closest('.wadah-input-password')?.querySelector('[data-password-field]');
        if (!input) return;

        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        button.classList.toggle('aktif', !visible);
        button.setAttribute('aria-pressed', visible ? 'false' : 'true');
        button.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
    });
});

form?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const button = form.querySelector('[data-simpan-password]');
    const data = new FormData(form);
    const password = String(data.get('password') || '');
    const confirmation = String(data.get('password_confirmation') || '');

    status.className = 'status-password';
    if (password !== confirmation) {
        status.textContent = 'Konfirmasi password baru tidak sesuai.';
        status.classList.add('gagal');
        return;
    }

    try {
        button.disabled = true;
        button.textContent = 'MENYIMPAN...';
        status.textContent = '';
        const result = await api('/api/profile/password', {
            method: 'PUT',
            body: {
                current_password: data.get('current_password'),
                password,
                password_confirmation: confirmation,
            },
        });
        form.reset();
        form.querySelectorAll('[data-password-field]').forEach((input) => {
            input.type = 'password';
        });
        form.querySelectorAll('[data-toggle-password]').forEach((toggle) => {
            toggle.classList.remove('aktif');
            toggle.setAttribute('aria-pressed', 'false');
            toggle.setAttribute('aria-label', 'Tampilkan password');
        });
        status.textContent = result.message;
        status.classList.add('berhasil');
    } catch (error) {
        status.textContent = error.message;
        status.classList.add('gagal');
    } finally {
        button.disabled = false;
        button.textContent = 'SIMPAN PASSWORD';
    }
});

loadBuyerTransactions();
