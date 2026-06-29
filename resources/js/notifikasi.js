import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const qsa = (selector) => [...document.querySelectorAll(selector)];
let notifications = [];
let category = 'semua';
let order = 'terbaru';
let loading = false;
const isBuyerNotificationPage = Boolean(qs('[data-notifikasi-pembeli-marketplace]'));

const categoryLabels = {
    transaksi: 'Transaksi',
    pupuk: 'Pupuk',
    cuaca: 'Cuaca',
    edukasi: 'Edukasi',
    hama_penyakit: 'Hama & Penyakit',
    sistem: 'Sistem',
};

const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

async function load() {
    if (loading) return;
    loading = true;
    try {
        const response = await api('/api/notifikasi');
        notifications = response.items || [];
        updateUnread(response.unread ?? notifications.filter((item) => !item.dibaca).length);
        render();
    } finally {
        loading = false;
    }
}

function updateUnread(total) {
    const count = qs('[data-jumlah-belum-dibaca]');
    const markAll = qs('[data-baca-semua]');
    if (count) count.textContent = String(total);
    if (markAll) markAll.disabled = total === 0;
}

function render() {
    const start = qs('[data-tanggal-mulai]')?.value || '';
    const end = qs('[data-tanggal-akhir]')?.value || '';
    const items = notifications
        .filter((item) => category === 'semua' || item.kategori === category || (category === 'hama' && item.kategori === 'hama_penyakit'))
        .filter((item) => (!start || item.tanggal >= start) && (!end || item.tanggal <= end))
        .sort((a, b) => order === 'terbaru'
            ? String(b.timestamp || b.tanggal).localeCompare(String(a.timestamp || a.tanggal))
            : String(a.timestamp || a.tanggal).localeCompare(String(b.timestamp || b.tanggal)));
    qs('[data-daftar-notifikasi]').innerHTML = items.length ? items.map((item) => `
        <article
            class="item-notifikasi${item.dibaca ? '' : ' belum-dibaca'}"
            data-kategori="${escapeHtml(item.kategori)}"
            data-tanggal="${escapeHtml(item.tanggal)}"
            data-notification-id="${item.notificationId}"
            tabindex="0"
        >
            <span class="label-notifikasi">${escapeHtml(categoryLabels[item.kategori] || item.kategori)}</span>
            <h2>${escapeHtml(item.judul)}</h2><p>${escapeHtml(item.pesan)}</p>
            ${item.status ? `<span class="status-notifikasi-pembelian status-${item.status}">${escapeHtml(item.status)}</span>` : ''}
            <div class="meta-notifikasi">
                <time datetime="${escapeHtml(item.timestamp || item.tanggal)}">${escapeHtml(item.waktu)}</time>
                <div class="meta-notifikasi-actions">
                    ${isBuyerNotificationPage && item.status === 'menunggu' && item.orderId
                        ? `<button class="tombol-batalkan-notifikasi" type="button" data-cancel-notification-order="${item.orderId}">Batalkan Pesanan</button>`
                        : ''}
                    ${item.dibaca ? '' : '<span class="penanda-belum-dibaca">Baru</span>'}
                </div>
            </div>
        </article>
    `).join('') : '<article class="notifikasi-kosong"><h2>Belum ada notifikasi</h2><p>Notifikasi akan tampil di sini.</p></article>';
}

function selectCategory(button) {
    category = button.dataset.filter;
    qsa('[data-filter]').forEach((item) => item.classList.toggle('tabel-aktif', item === button));
    render();
}

qsa('[data-filter]').forEach((button) => {
    button.addEventListener('click', () => selectCategory(button));
    button.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            selectCategory(button);
        }
    });
});
qsa('[data-urut]').forEach((button) => button.addEventListener('click', () => {
    order = button.dataset.urut;
    qsa('[data-urut]').forEach((item) => item.classList.toggle('aktif', item === button));
}));
function toggleFilter(open) {
    const panel = qs('[data-panel-filter]');
    const button = qs('[data-tombol-filter]');
    if (panel) panel.hidden = !open;
    button?.setAttribute('aria-expanded', String(open));
}

qs('[data-tombol-filter]')?.addEventListener('click', () => toggleFilter(true));
qs('[data-filter-tutup]')?.addEventListener('click', () => toggleFilter(false));
qs('[data-filter-terapkan]')?.addEventListener('click', () => { render(); toggleFilter(false); });
qs('[data-filter-reset]')?.addEventListener('click', () => {
    category = 'semua'; order = 'terbaru';
    if (qs('[data-tanggal-mulai]')) qs('[data-tanggal-mulai]').value = '';
    if (qs('[data-tanggal-akhir]')) qs('[data-tanggal-akhir]').value = '';
    qsa('[data-filter]').forEach((item) => item.classList.toggle('tabel-aktif', item.dataset.filter === 'semua'));
    qsa('[data-urut]').forEach((item) => item.classList.toggle('aktif', item.dataset.urut === 'terbaru'));
    render();
});

async function markAsRead(item) {
    if (!item || item.dibaca) return;
    await api(`/api/notifikasi/${item.notificationId}/baca`, { method: 'POST' });
    item.dibaca = true;
    updateUnread(notifications.filter((notification) => !notification.dibaca).length);
    render();
}

qs('[data-daftar-notifikasi]')?.addEventListener('click', (event) => {
    const cancelButton = event.target.closest('[data-cancel-notification-order]');
    if (cancelButton) {
        event.stopPropagation();
        cancelMarketplaceOrder(cancelButton);
        return;
    }

    const element = event.target.closest('[data-notification-id]');
    const item = notifications.find((notification) => String(notification.notificationId) === element?.dataset.notificationId);
    markAsRead(item).catch((error) => alert(error.message));
});

async function cancelMarketplaceOrder(button) {
    if (!confirm('Batalkan pesanan ini?')) return;

    button.disabled = true;
    button.textContent = 'Membatalkan...';
    try {
        await api(`/api/pembeli/pesanan/${button.dataset.cancelNotificationOrder}/batalkan`, { method: 'PATCH' });
        await load();
    } catch (error) {
        button.disabled = false;
        button.textContent = 'Batalkan Pesanan';
        alert(error.message);
    }
}

qs('[data-daftar-notifikasi]')?.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter' && event.key !== ' ') return;
    if (event.target.closest('[data-cancel-notification-order]')) return;
    const element = event.target.closest('[data-notification-id]');
    if (!element) return;
    event.preventDefault();
    const item = notifications.find((notification) => String(notification.notificationId) === element.dataset.notificationId);
    markAsRead(item).catch((error) => alert(error.message));
});

qs('[data-baca-semua]')?.addEventListener('click', async () => {
    const button = qs('[data-baca-semua]');
    button.disabled = true;
    try {
        await api('/api/notifikasi/baca-semua', { method: 'POST' });
        notifications.forEach((item) => { item.dibaca = true; });
        updateUnread(0);
        render();
    } catch (error) {
        updateUnread(notifications.filter((item) => !item.dibaca).length);
        alert(error.message);
    }
});

document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') load().catch(() => {});
});

load().catch((error) => {
    qs('[data-daftar-notifikasi]').innerHTML = `<article class="notifikasi-kosong"><p>${escapeHtml(error.message)}</p></article>`;
});
