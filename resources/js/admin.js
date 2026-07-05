import * as bootstrap from 'bootstrap';
import { api } from './api';

window.bootstrap = bootstrap;

const state = {
    data: null,
    role: 'Petani',
    userSearch: '',
};
const RDKK_STORAGE_KEY = 'poktan:admin:rdkk';
const rdkkFertilizerGroups = [
    { key: 'urea', matches: (name) => name.includes('urea') },
    { key: 'npk', matches: (name) => name === 'npk' || (name.includes('npk') && !name.includes('formula') && !name.includes('16-16-16')) },
    { key: 'npk_formula', matches: (name) => name.includes('npk') && (name.includes('formula') || name.includes('16-16-16')) },
    { key: 'organik', matches: (name) => name.includes('organik') },
    { key: 'za', matches: (name) => name.includes('za') },
];
const qs = (selector) => document.querySelector(selector);
const qsa = (selector) => [...document.querySelectorAll(selector)];
const money = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const dateTime = new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short',
});
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));
const wholeNumber = (value, allowThousands = true) => {
    const raw = String(value ?? '').trim();
    if (!raw) return 0;

    const compact = allowThousands ? raw.replace(/\./g, '') : raw;
    return /^[0-9]+$/.test(compact) ? Number(compact) : raw;
};
const rupiahValue = (value) => {
    const digits = String(value ?? '').replace(/\D/g, '');
    return digits ? new Intl.NumberFormat('id-ID').format(Number(digits)) : '';
};
const rupiahNumber = (value) => Number(String(value ?? '').replace(/\D/g, '')) || 0;
const safeHref = (value) => {
    const href = String(value || '').trim();
    return href.startsWith('/') || /^https?:\/\//i.test(href) ? href : '#';
};
function formatDate(value) {
    if (!value) return '-';
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? '-' : dateTime.format(date);
}

function statusClass(value) {
    return String(value || 'menunggu').toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9_-]/g, '');
}

function setBusy(element, busy, label = 'Memproses...') {
    if (!element) return;
    if (busy) {
        element.dataset.originalLabel = element.textContent;
        element.disabled = true;
        element.textContent = label;
    } else {
        element.disabled = false;
        element.textContent = element.dataset.originalLabel || element.textContent;
        delete element.dataset.originalLabel;
    }
}

function toast(message) {
    const element = qs('[data-admin-toast]');
    if (!element) return;
    qs('[data-admin-toast-body]').textContent = message;
    bootstrap.Toast.getOrCreateInstance(element).show();
}

async function load() {
    state.data = await api('/api/admin/bootstrap');
    render();
}

function render() {
    renderStats();
    renderActivities();
    renderUsers();
    renderFertilizerLimits();
    renderFertilizers();
    renderOrders();
    renderNotifications();
    renderContents();
    renderPlanting();
    renderSettings();
    renderRdkkReport();
}

function renderStats() {
    const stats = state.data.stats;
    qs('[data-stat-petani]').textContent = stats.petani;
    qs('[data-stat-pembeli]').textContent = stats.pembeli;
    qs('[data-stat-menunggu]').textContent = stats.menunggu;
    qs('[data-stat-produk]').textContent = stats.produk;
    qs('[data-stat-pupuk]').textContent = stats.pupuk;
    qs('[data-stat-pesanan]').textContent = stats.pesanan;
    const marketplaceStatus = qs('[data-status-marketplace]');
    marketplaceStatus.textContent = state.data.settings.marketplace;
    marketplaceStatus.className = state.data.settings.marketplace === 'Aktif'
        ? 'text-success'
        : state.data.settings.marketplace === 'Perawatan' ? 'text-warning' : 'text-danger';
    qs('[data-status-maintenance]').textContent = state.data.settings.maintenance;
    qs('[data-status-maintenance]').className = state.data.settings.maintenance === 'Aktif' ? 'text-danger' : 'text-success';
    qs('[data-status-notification]').textContent = `${state.data.notifications.length} tersimpan`;
    const mode = [...document.querySelectorAll('.list-group-item')].find((item) => item.textContent.includes('Mode Data'))?.querySelector('strong');
    if (mode) mode.textContent = 'Database MySQL';
}

function empty(columns, message) {
    return `<tr><td colspan="${columns}" class="text-center text-secondary py-4">${escapeHtml(message)}</td></tr>`;
}

function rdkkStoredValues() {
    try {
        return JSON.parse(localStorage.getItem(RDKK_STORAGE_KEY) || '{}') || {};
    } catch {
        return {};
    }
}

function rdkkDefaults() {
    return {
        year: String(new Date().getFullYear()),
        district: '',
        village: state.data?.settings?.location || '',
        group: state.data?.settings?.appName || 'POKTAN Lancang Kuning',
        subsector: 'Tanaman Pangan',
        commodity: 'Padi',
        kiosk: '',
    };
}

function initializeRdkkForm() {
    const firstField = qs('[data-rdkk-field]');
    if (!firstField || firstField.closest('article')?.dataset.rdkkReady) return;

    const saved = rdkkStoredValues();
    const defaults = rdkkDefaults();
    qsa('[data-rdkk-field]').forEach((input) => {
        input.value = saved[input.dataset.rdkkField] ?? input.value ?? defaults[input.dataset.rdkkField] ?? '';
        if (!input.value) input.value = defaults[input.dataset.rdkkField] ?? '';
    });
    firstField.closest('article')?.setAttribute('data-rdkk-ready', 'true');
}

function rdkkValues() {
    const defaults = rdkkDefaults();
    return Object.fromEntries(qsa('[data-rdkk-field]').map((input) => [
        input.dataset.rdkkField,
        input.value.trim() || defaults[input.dataset.rdkkField] || '-',
    ]));
}

function saveRdkkValues() {
    try {
        localStorage.setItem(RDKK_STORAGE_KEY, JSON.stringify(rdkkValues()));
    } catch {
        // Local storage is optional; the current report remains usable without it.
    }
}

function formatHectare(value) {
    return (Math.max(0, Number(value) || 0)).toFixed(3);
}

function formatKarung(value) {
    return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.round(Number(value) || 0));
}

function rdkkFertilizerPurchases(farmer, year) {
    const purchases = farmer.reportFertilizerPurchases?.[String(year)] || {};

    return rdkkFertilizerGroups.map((group) => Math.max(0, Number(purchases[group.key]) || 0));
}

function renderRdkkReport() {
    const body = qs('[data-rdkk-rows]');
    const totals = qs('[data-rdkk-totals]');
    if (!body || !totals || !state.data) return;

    initializeRdkkForm();
    const values = rdkkValues();
    qs('[data-rdkk-year]').textContent = values.year;
    qsa('[data-rdkk-meta]').forEach((element) => {
        element.textContent = values[element.dataset.rdkkMeta] || '-';
    });
    qs('[data-rdkk-print-date]').textContent = new Intl.DateTimeFormat('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric',
    }).format(new Date());

    const farmers = state.data.users
        .filter((user) => user.role === 'Petani' && user.status === 'Aktif')
        .sort((first, second) => String(first.name).localeCompare(String(second.name), 'id-ID'));
    const allTotals = Array(6).fill(0);

    const rows = farmers.map((farmer, index) => {
        const plantingAreaHa = Math.max(0, Number(farmer.rencana_tanam_ha) || 0);
        const fertilizer = rdkkFertilizerPurchases(farmer, values.year);
        const reportValues = [plantingAreaHa, ...fertilizer];
        reportValues.forEach((value, valueIndex) => {
            allTotals[valueIndex] += value;
        });

        return `<tr>
            <td>${index + 1}</td>
            <td>${escapeHtml(farmer.nik || '-')}</td>
            <td>${escapeHtml(farmer.name || '-')}</td>
            <td>${formatHectare(plantingAreaHa)}</td>
            ${fertilizer.map((value) => `<td>${formatKarung(value)}</td>`).join('')}
        </tr>`;
    });

    body.innerHTML = rows.length ? rows.join('') : empty(9, 'Belum ada petani aktif untuk dicetak.');
    totals.innerHTML = `<tr>
        <th colspan="3">Jumlah</th>
        <td>${formatHectare(allTotals[0])}</td>
        ${allTotals.slice(1).map((value) => `<td>${formatKarung(value)}</td>`).join('')}
    </tr>`;
}

function renderActivities() {
    const body = qs('[data-admin-aktivitas]');
    body.innerHTML = state.data.activities.length ? state.data.activities.map((item) => `
        <tr>
            <td>${escapeHtml(item.description)}</td>
            <td>${escapeHtml(item.category)}</td>
            <td><span class="status-pill status-${statusClass(item.status)}">${escapeHtml(item.status)}</span></td>
            <td>${escapeHtml(formatDate(item.occurredAt))}</td>
        </tr>
    `).join('') : empty(4, 'Belum ada aktivitas terbaru.');
}

function renderUsers() {
    const body = qs('[data-admin-users]');
    const roleUsers = state.data.users.filter((user) => user.role === state.role);
    const query = state.userSearch.trim().toLocaleLowerCase('id-ID');
    const numericQuery = query.replace(/\D/g, '');
    const users = roleUsers.filter((user) => {
        if (!query) return true;

        const nameMatches = String(user.name || '').toLocaleLowerCase('id-ID').includes(query);
        if (state.role === 'Petani') {
            const nik = String(user.nik || '').replace(/\D/g, '');
            return nameMatches || (numericQuery !== '' && nik.includes(numericQuery));
        }

        const phone = String(user.phone || '').replace(/\D/g, '');
        return nameMatches || (numericQuery !== '' && phone.includes(numericQuery));
    });
    const searchInput = qs('[data-admin-user-search]');
    const resultCount = qs('[data-admin-user-result-count]');
    qs('[data-admin-user-list-title]').textContent = `Daftar ${state.role}`;
    qs('[data-admin-user-role]').value = state.role;
    if (searchInput) {
        searchInput.value = state.userSearch;
        searchInput.placeholder = state.role === 'Petani'
            ? searchInput.dataset.farmerPlaceholder
            : searchInput.dataset.buyerPlaceholder;
        searchInput.setAttribute('aria-label', searchInput.placeholder);
    }
    if (resultCount) {
        resultCount.textContent = query
            ? `${users.length} dari ${roleUsers.length} ${state.role.toLowerCase()} ditemukan`
            : `${roleUsers.length} ${state.role.toLowerCase()}`;
    }
    qsa('[data-admin-user-filter]').forEach((button) => button.classList.toggle('active', button.dataset.adminUserFilter === state.role));
    //qsa('[data-admin-user-farmer-field], [data-admin-user-farmer-limit], [data-admin-user-farmer-col]').forEach((item) => item.hidden = state.role !== 'Petani');
    qsa('[data-admin-user-farmer-field], [data-admin-user-farmer-limit]').forEach((item) => item.hidden = state.role !== 'Petani');
    qsa('[data-admin-user-buyer-field]').forEach((item) => item.hidden = state.role !== 'Pembeli');
    qsa('[data-admin-user-status-field]').forEach((item) => item.hidden = state.role === 'Pembeli');
    if (state.role === 'Pembeli') qs('[data-admin-user-status]').value = 'Aktif';
    syncUserFieldRequirements();

    body.innerHTML = users.length ? users.map((user) => `
        <tr>
            <td>${escapeHtml(user.name)}</td>
            <td>${escapeHtml(user.phone)}</td>
            <td>
                <strong>${user.role === 'Petani' ? `NIK: ${escapeHtml(user.nik || '-')}` : `Gudang: ${escapeHtml(user.warehouseName || '-')}`}</strong>
                <div class="small text-secondary">${escapeHtml(user.address || '-')}</div>
            </td>
            <td>${user.role === 'Petani' ? escapeHtml(user.kelompokTaniName || '-') : '-'}</td>
            <td>${user.role === 'Petani' ? `${number.format(user.landAreaMeter)} meter` : '-'}</td>
            <td><span class="status-pill status-${statusClass(user.status)}">${escapeHtml(user.status)}</span></td>
            <td><span class="badge text-bg-success">${user.hasPassword ? 'Diatur' : 'Belum'}</span></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-success" data-edit-user="${user.id}">Edit</button>
                <button class="btn btn-sm btn-outline-danger" data-delete-user="${user.id}">Hapus</button>
            </td>
        </tr>
    `).join('') : empty(8, query
        ? `${state.role} dengan nama atau ${state.role === 'Petani' ? 'NIK' : 'No. HP'} tersebut tidak ditemukan.`
        : `Belum ada ${state.role.toLowerCase()}.`);
}

function syncUserFieldRequirements() {
    const editing = Boolean(qs('[data-admin-user-id]').value);
    qs('[data-admin-user-nik]').required = state.role === 'Petani';
    qs('[data-admin-user-warehouse]').required = state.role === 'Pembeli';
    qs('[data-admin-user-status]').required = state.role === 'Petani';
    qs('[data-admin-user-password]').required = !editing;
    qs('[data-admin-user-password-confirmation]').required = !editing;
}

function renderFertilizerLimits(values = null) {
    const container = qs('[data-admin-user-fertilizer-limits]');
    if (!container || state.role !== 'Petani') return;
    const current = values ?? readFertilizerLimits();
    container.innerHTML = state.data.fertilizers.map((item) => `
        <label class="input-group input-group-sm">
            <span class="input-group-text">${escapeHtml(item.nama)}</span>
            <input class="form-control" type="number" min="0" step="1" inputmode="numeric"
                value="${escapeHtml(current[item.id] || '')}" data-admin-user-fertilizer-limit data-product-id="${item.id}">
            <span class="input-group-text">Karung</span>
        </label>
    `).join('') || '<small class="text-secondary">Belum ada produk pupuk.</small>';
}

function readFertilizerLimits() {
    return Object.fromEntries(qsa('[data-admin-user-fertilizer-limit]')
        .map((input) => [input.dataset.productId, wholeNumber(input.value, false)])
        .filter(([, value]) => (typeof value === 'number' ? value > 0 : String(value).trim() !== '')));
}

function resetUserForm() {
    const form = qs('[data-admin-user-form]');
    if (!form) return;
    form.reset();
    if (qs('[data-admin-user-id]')) qs('[data-admin-user-id]').value = '';
    if (qs('[data-admin-user-role]')) qs('[data-admin-user-role]').value = state.role;
    if (qs('[data-admin-user-form-title]')) qs('[data-admin-user-form-title]').textContent = `Tambah ${state.role}`;
    renderFertilizerLimits({});
    syncUserFieldRequirements();
}

function renderFertilizers() {
    const body = qs('[data-admin-fertilizers]');
    qs('[data-admin-fertilizer-count]').textContent = `${state.data.fertilizers.length} produk`;
    body.innerHTML = state.data.fertilizers.length ? state.data.fertilizers.map((item) => `
        <tr>
            <td><div class="d-flex align-items-center gap-2"><img class="product-thumb" src="${escapeHtml(item.gambar)}" alt="">
                <div><strong>${escapeHtml(item.nama)}</strong><div class="small text-secondary">${escapeHtml(item.deskripsi)}</div></div></div></td>
            <td>${money.format(item.harga)}</td><td>${escapeHtml(item.satuan)}</td><td>${number.format(item.stok)}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-success" data-edit-fertilizer="${item.id}">Edit</button>
                <button class="btn btn-sm btn-outline-danger" data-delete-fertilizer="${item.id}">Hapus</button>
            </td>
        </tr>
    `).join('') : empty(5, 'Belum ada produk pupuk.');
}

function resetFertilizerForm() {
    const form = qs('[data-admin-fertilizer-form]');
    if (!form) return;
    form.reset();
    if (qs('[data-admin-fertilizer-id]')) qs('[data-admin-fertilizer-id]').value = '';
    if (qs('[data-admin-fertilizer-package]')) qs('[data-admin-fertilizer-package]').value = '50 kg';
    if (qs('[data-admin-fertilizer-form-title]')) qs('[data-admin-fertilizer-form-title]').textContent = 'Tambah Produk Pupuk';
    updateImagePreview('fertilizer', '');
}

function renderOrders() {
    const body = qs('[data-admin-orders]');
    body.innerHTML = state.data.orders.length ? state.data.orders.map((order) => `
        <tr>
            <td><strong>${escapeHtml(order.petani)}</strong><div class="small text-secondary">${escapeHtml(order.tanggal)}</div></td>
            <td>${escapeHtml(order.items.map((item) => item.nama).join(', '))}</td>
            <td>${number.format(order.items.reduce((sum, item) => sum + Number(item.jumlah), 0))} paket</td>
            <td><strong>${money.format(order.total)}</strong><div class="small text-secondary">${escapeHtml(order.metode)}</div></td>
            <td>
                <span class="status-pill status-${statusClass(order.status)}">${escapeHtml(order.statusLabel)}</span>
                <div class="small text-secondary mt-1">Pembayaran: ${escapeHtml(order.paymentStatusLabel)}</div>
            </td>
            <td class="text-end">
                <div class="admin-actions">
                    ${order.status === 'menunggu' ? `
                        <button class="btn btn-sm btn-outline-success" data-order-status="diterima" data-order-id="${order.id}">Terima</button>
                        <button class="btn btn-sm btn-outline-danger" data-order-status="ditolak" data-order-id="${order.id}">Tolak</button>
                    ` : ''}
                    ${order.status === 'diterima' ? `
                        <button class="btn btn-sm btn-success" data-order-status="selesai" data-order-id="${order.id}">Selesaikan</button>
                        <button class="btn btn-sm btn-outline-danger" data-order-status="dibatalkan" data-order-id="${order.id}">Batalkan</button>
                    ` : ''}
                <button class="btn btn-sm btn-danger" data-delete-order="${order.id}">Hapus</button>
                </div>
            </td>
        </tr>
    `).join('') : empty(6, 'Belum ada pesanan pupuk.');
}

function renderNotifications() {
    const body = qs('[data-admin-notifications]');
    body.innerHTML = state.data.notifications.length ? state.data.notifications.map((item) => `
        <tr><td><strong>${escapeHtml(item.title)}</strong><div class="small text-secondary">${escapeHtml(formatDate(item.createdAt))}</div></td>
        <td>${escapeHtml(item.category)}</td><td>${escapeHtml(item.target)}</td><td>${escapeHtml(item.message)}</td>
        <td class="text-end"><button class="btn btn-sm btn-outline-danger" data-delete-notification="${item.id}">Hapus</button></td></tr>
    `).join('') : empty(5, 'Belum ada notifikasi admin.');
    qs('[data-admin-notification-count]').textContent = `${state.data.notifications.length} notifikasi`;
}

function renderContents() {
    const body = qs('[data-admin-contents]');
    qs('[data-admin-content-count]').textContent = `${state.data.contents.length} konten`;
    body.innerHTML = state.data.contents.length ? state.data.contents.map((item) => `
        <tr><td><strong>${escapeHtml(item.title)}</strong><div class="small text-secondary">${escapeHtml(item.type)}</div></td>
        <td>${escapeHtml(item.category)}</td><td><a href="${escapeHtml(safeHref(item.link))}" target="_blank" rel="noopener noreferrer">Buka</a></td>
        <td class="text-end"><button class="btn btn-sm btn-outline-success" data-edit-content="${item.id}">Edit</button>
        <button class="btn btn-sm btn-outline-danger" data-delete-content="${item.id}">Hapus</button></td></tr>
    `).join('') : empty(4, 'Belum ada konten tambahan.');
}

function resetContentForm() {
    const form = qs('[data-admin-content-form]');
    if (!form) return;
    form.reset();
    if (qs('[data-admin-content-id]')) qs('[data-admin-content-id]').value = '';
    if (qs('[data-admin-content-form-title]')) qs('[data-admin-content-form-title]').textContent = 'Tambah Konten';
    updateImagePreview('content', '');
    const success = qs('[data-admin-content-success]');
    if (success) {
        success.textContent = '';
        success.hidden = true;
    }
}

function showContentSuccess(message) {
    const success = qs('[data-admin-content-success]');
    if (!success) return;
    success.textContent = message;
    success.hidden = false;
}

function showNotificationSuccess(message = '') {
    const success = qs('[data-admin-notification-success]');
    if (!success) return;
    success.textContent = message;
    success.hidden = !message;
}

function renderPlanting() {
    const body = qs('[data-admin-planting-progress]');
    qs('[data-admin-planting-count]').textContent = `${state.data.planting.length} petani`;
    body.innerHTML = state.data.planting.length ? state.data.planting.map((item) => `
        <tr><td><strong>${escapeHtml(item.name)}</strong><div class="small text-secondary">${escapeHtml(item.phone)}</div></td>
        <td>${formatHectare(item.landAreaHa)} Ha</td><td>${escapeHtml(item.activeStage)}</td>
        <td><div class="progress"><div class="progress-bar bg-success" style="width:${Math.min(100, Math.max(0, Number(item.percent) || 0))}%"></div></div><small>${item.completed}/4 (${number.format(item.percent)}%)</small></td>
        <td>${escapeHtml(item.seedDate || '-')}</td><td>${escapeHtml(item.completedAt || '-')}</td>
        <td><span class="status-pill status-${statusClass(item.status)}">${escapeHtml(item.status)}</span></td></tr>
    `).join('') : empty(7, 'Belum ada data petani.');
}

function renderSettings() {
    const settings = state.data.settings;
    document.title = 'Admin Panel - POKTAN';
    const appName = qs('[data-admin-setting-app-name]');
    const location = qs('[data-admin-setting-location]');
    const marketplace = qs('[data-admin-setting-marketplace]');
    const maintenance = qs('[data-admin-setting-maintenance]');
    const maintenanceMessage = qs('[data-admin-setting-maintenance-message]');

    if (appName) appName.value = settings.appName;
    if (location) location.value = settings.location || '';
    if (marketplace) marketplace.value = settings.marketplace;
    if (maintenance) maintenance.checked = settings.maintenance === 'Aktif';
    if (maintenanceMessage) maintenanceMessage.value = settings.maintenanceMessage;
    setPaymentSwitches('buyer', settings.buyerPaymentDisabledMethods);
    setPaymentSwitches('farmer', settings.farmerPaymentDisabledMethods);
    qsa('.setting-switch input[type="checkbox"]').forEach(updateSwitch);
    updateMarketplaceDescription(settings.marketplace);
}

function setPaymentSwitches(group, disabled) {
    const disabledMethods = Array.isArray(disabled) ? disabled : [];
    qsa(`[data-admin-payment-enabled="${group}"]`).forEach((input) => input.checked = !disabledMethods.includes(input.value));
}

function updateSwitch(input) {
    const wrapper = input.closest('.setting-switch');
    wrapper?.classList.toggle('is-active', input.checked);
    const label = wrapper?.querySelector('[data-setting-switch-state]');
    if (label) label.textContent = input.checked ? 'Aktif' : 'Nonaktif';
}

function updateMarketplaceDescription(status) {
    const description = qs('[data-admin-marketplace-description]');
    if (!description) return;

    const messages = {
        Aktif: 'Marketplace dapat digunakan oleh pembeli.',
        Perawatan: 'Marketplace ditampilkan dalam status perawatan dan transaksi pembeli dinonaktifkan.',
        Nonaktif: 'Marketplace dan transaksi pembeli dinonaktifkan.',
    };
    description.textContent = messages[status] || messages.Aktif;
}

function showSettingsFeedback(message, type = 'success') {
    const feedback = qs('[data-admin-settings-feedback]');
    if (!feedback) return;
    feedback.classList.remove('alert-success', 'alert-danger');
    feedback.classList.add(type === 'error' ? 'alert-danger' : 'alert-success');
    feedback.textContent = message;
    feedback.hidden = false;
}

function clearSettingsFeedback() {
    const feedback = qs('[data-admin-settings-feedback]');
    if (!feedback) return;
    feedback.textContent = '';
    feedback.hidden = true;
}

async function saveSettings() {
    const marketplace = qs('[data-admin-setting-marketplace]').value;
    const appName = qs('[data-admin-setting-app-name]')?.value ?? state.data.settings.appName;
    const location = qs('[data-admin-setting-location]')?.value ?? state.data.settings.location;
    state.data.settings = await api('/api/admin/pengaturan', {
        method: 'PUT',
        body: {
            appName,
            location,
            marketplace,
            maintenance: qs('[data-admin-setting-maintenance]').checked ? 'Aktif' : 'Nonaktif',
            maintenanceMessage: qs('[data-admin-setting-maintenance-message]').value,
            buyerPaymentDisabledMethods: qsa('[data-admin-payment-enabled="buyer"]').filter((i) => !i.checked).map((i) => i.value),
            farmerPaymentDisabledMethods: qsa('[data-admin-payment-enabled="farmer"]').filter((i) => !i.checked).map((i) => i.value),
        },
    });
    renderSettings();
    renderStats();
    showSettingsFeedback('Pengaturan aplikasi berhasil disimpan.');
    toast('Pengaturan aplikasi berhasil disimpan.');
}

function updateImagePreview(type, src, label = '', marker = src) {
    const prefix = type === 'content' ? 'content' : 'fertilizer';
    const image = qs(`[data-admin-${prefix}-image-preview]`);
    const name = qs(`[data-admin-${prefix}-image-name]`);
    const hidden = qs(`[data-admin-${prefix}-image]`);
    if (hidden) hidden.value = marker || '';
    if (image) {
        image.hidden = !src;
        if (src) image.src = src;
    }
    if (name) name.textContent = label || (src ? 'Gambar siap digunakan.' : 'Belum ada gambar dipilih.');
}

qs('[data-admin-user-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submit = event.currentTarget.querySelector('[type="submit"]');
    setBusy(submit, true, 'Menyimpan...');
    try {
        const id = qs('[data-admin-user-id]').value;
        const password = qs('[data-admin-user-password]').value;
        const body = {
            name: qs('[data-admin-user-name]').value,
            phone: qs('[data-admin-user-phone]').value,
            role: state.role,
            nik: qs('[data-admin-user-nik]').value || null,
            kelompokTaniId: state.role === 'Petani' ? (qs('[data-admin-user-kelompok-tani]').value || null) : null,
            warehouseName: qs('[data-admin-user-warehouse]').value || null,
            address: qs('[data-admin-user-address]').value,
            landAreaMeter: wholeNumber(qs('[data-admin-user-land-area]').value),
            fertilizerLimits: readFertilizerLimits(),
            status: state.role === 'Petani' ? qs('[data-admin-user-status]').value : null,
            password: password || null,
            password_confirmation: qs('[data-admin-user-password-confirmation]').value || null,
        };
        await api(id ? `/api/admin/pengguna/${id}` : '/api/admin/pengguna', { method: id ? 'PUT' : 'POST', body });
        resetUserForm();
        await load();
        toast('Data pengguna disimpan.');
    } catch (error) {
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-fertilizer-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submit = event.currentTarget.querySelector('[type="submit"]');
    setBusy(submit, true, 'Menyimpan...');
    try {
        const id = qs('[data-admin-fertilizer-id]').value;
        const form = new FormData();
        form.append('nama_produk', qs('[data-admin-fertilizer-name]').value);
        form.append('harga', rupiahNumber(qs('[data-admin-fertilizer-price]').value));
        form.append('jumlah_stok', wholeNumber(qs('[data-admin-fertilizer-stock]').value));
        form.append('ukuran_kemasan', qs('[data-admin-fertilizer-package]').value.replace(/^\/\s*/, ''));
        form.append('deskripsi', qs('[data-admin-fertilizer-description]').value);
        const file = qs('[data-admin-fertilizer-image-file]').files[0];
        if (file) form.append('gambar', file);
        if (qs('[data-admin-fertilizer-image]').value === '__remove__') form.append('remove_image', '1');
        await api(id ? `/api/admin/pupuk/${id}` : '/api/admin/pupuk', { method: 'POST', body: form });
        resetFertilizerForm();
        await load();
        toast('Produk pupuk disimpan.');
    } catch (error) {
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-notification-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formElement = event.currentTarget;
    const submit = formElement.querySelector('[type="submit"]');
    showNotificationSuccess();
    setBusy(submit, true, 'Menerbitkan...');
    try {
        const labels = { 'Hama & Penyakit': 'hama_penyakit' };
        await api('/api/admin/notifikasi', { method: 'POST', body: {
            judul: qs('[data-admin-notification-title]').value,
            kategori: labels[qs('[data-admin-notification-category]').value] || qs('[data-admin-notification-category]').value.toLowerCase(),
            pesan: qs('[data-admin-notification-message]').value,
            target_peran: qs('[data-admin-notification-target]').value,
        } });
        formElement.reset();
        await load();
        showNotificationSuccess('Notifikasi berhasil disimpan.');
        toast('Notifikasi berhasil disimpan.');
    } catch (error) {
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-content-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submit = event.currentTarget.querySelector('[type="submit"]');
    const success = qs('[data-admin-content-success]');
    if (success) {
        success.textContent = '';
        success.hidden = true;
    }
    setBusy(submit, true, 'Menyimpan...');
    try {
        const id = qs('[data-admin-content-id]').value;
        const isEditing = Boolean(id);
        const form = new FormData();
        form.append('kategori', qs('[data-admin-content-category]').value === 'Hama & Penyakit' ? 'hama_penyakit' : 'edukasi');
        form.append('judul', qs('[data-admin-content-title]').value);
        form.append('jenis_konten', qs('[data-admin-content-type]').value.toLowerCase());
        form.append('deskripsi', qs('[data-admin-content-description]').value);
        form.append('tautan', qs('[data-admin-content-link]').value);
        const file = qs('[data-admin-content-image-file]').files[0];
        if (file) form.append('gambar', file);
        if (qs('[data-admin-content-image]').value === '__remove__') form.append('remove_image', '1');
        await api(id ? `/api/admin/konten/${id}` : '/api/admin/konten', { method: 'POST', body: form });
        resetContentForm();
        await load();
        const message = isEditing
            ? 'Konten berhasil diperbarui.'
            : 'Konten berhasil ditambahkan.';
        showContentSuccess(message);
        toast(message);
    } catch (error) {
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-password-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formElement = event.currentTarget;
    const submit = formElement.querySelector('[type="submit"]');
    setBusy(submit, true, 'Memperbarui...');
    try {
        await api('/api/admin/password', { method: 'PUT', body: {
            current_password: qs('[data-admin-current-password]').value,
            password: qs('[data-admin-new-password]').value,
            password_confirmation: qs('[data-admin-confirm-password]').value,
        } });
        formElement.reset();
        toast('Password admin diperbarui.');
    } catch (error) {
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-settings-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submit = event.currentTarget.querySelector('[type="submit"]');
    clearSettingsFeedback();
    setBusy(submit, true, 'Menyimpan...');
    try {
        await saveSettings();
    } catch (error) {
        showSettingsFeedback(error.message, 'error');
        toast(error.message);
    } finally {
        setBusy(submit, false);
    }
});

qs('[data-admin-user-search-form]')?.addEventListener('submit', (event) => {
    event.preventDefault();
    state.userSearch = qs('[data-admin-user-search]')?.value || '';
    renderUsers();
});

qs('[data-admin-user-search]')?.addEventListener('input', (event) => {
    if (event.target.value !== '') return;
    state.userSearch = '';
    renderUsers();
});

document.addEventListener('click', async (event) => {
    const button = event.target.closest('button');
    if (!button) return;
    try {
        if (button.matches('[data-admin-user-filter]')) {
            state.role = button.dataset.adminUserFilter;
            state.userSearch = '';
            resetUserForm();
            renderUsers();
            return;
        }
        if (button.matches('[data-edit-user]')) {
            const user = state.data.users.find((item) => String(item.id) === button.dataset.editUser);
            state.role = user.role; renderUsers();
            qs('[data-admin-user-id]').value = user.id; qs('[data-admin-user-name]').value = user.name;
            qs('[data-admin-user-phone]').value = user.phone; qs('[data-admin-user-nik]').value = user.nik;
            qs('[data-admin-user-kelompok-tani]').value = user.kelompokTaniId || '';
            qs('[data-admin-user-warehouse]').value = user.warehouseName; qs('[data-admin-user-address]').value = user.address;
            qs('[data-admin-user-land-area]').value = user.landAreaMeter; qs('[data-admin-user-status]').value = user.status;
            qs('[data-admin-user-form-title]').textContent = `Edit ${user.role}`;
            renderFertilizerLimits(user.fertilizerLimits);
            syncUserFieldRequirements();
            qs('[data-admin-user-form]').scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }
        if (button.matches('[data-delete-user]')) {
            if (!confirm('Hapus pengguna ini?')) return;
            await api(`/api/admin/pengguna/${button.dataset.deleteUser}`, { method: 'DELETE' });
            await load(); toast('Pengguna dihapus.'); return;
        }
        if (button.matches('[data-edit-fertilizer]')) {
            const item = state.data.fertilizers.find((row) => String(row.id) === button.dataset.editFertilizer);
            qs('[data-admin-fertilizer-id]').value = item.id; qs('[data-admin-fertilizer-name]').value = item.nama;
            qs('[data-admin-fertilizer-price]').value = rupiahValue(item.harga); qs('[data-admin-fertilizer-stock]').value = item.stok;
            qs('[data-admin-fertilizer-package]').value = item.package; qs('[data-admin-fertilizer-description]').value = item.deskripsi;
            qs('[data-admin-fertilizer-form-title]').textContent = 'Edit Produk Pupuk';
            updateImagePreview('fertilizer', item.gambar);
            qs('[data-admin-fertilizer-form]').scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }
        if (button.matches('[data-delete-fertilizer]')) {
            if (!confirm('Hapus produk pupuk ini?')) return;
            await api(`/api/admin/pupuk/${button.dataset.deleteFertilizer}`, { method: 'DELETE' });
            await load(); toast('Produk pupuk dihapus.'); return;
        }
        if (button.matches('[data-order-status]')) {
            const labels = { diterima: 'menerima', ditolak: 'menolak', selesai: 'menyelesaikan', dibatalkan: 'membatalkan' };
            if (!confirm(`Yakin ingin ${labels[button.dataset.orderStatus] || 'mengubah'} pesanan ini?`)) return;
            await api(`/api/admin/pesanan-pupuk/${button.dataset.orderId}`, { method: 'PATCH', body: { status: button.dataset.orderStatus } });
            await load(); toast('Status pesanan diperbarui.'); return;
        }
        if (button.matches('[data-delete-order]')) {
            if (!confirm('Hapus pesanan pupuk ini?')) return;
            await api(`/api/admin/pesanan-pupuk/${button.dataset.deleteOrder}`, { method: 'DELETE' });
            await load(); toast('Pesanan pupuk dihapus.'); return;
        }
        if (button.matches('[data-delete-notification]')) {
            if (!confirm('Hapus notifikasi ini?')) return;
            await api(`/api/admin/notifikasi/${button.dataset.deleteNotification}`, { method: 'DELETE' });
            await load(); toast('Notifikasi dihapus.'); return;
        }
        if (button.matches('[data-edit-content]')) {
            const item = state.data.contents.find((row) => String(row.id) === button.dataset.editContent);
            qs('[data-admin-content-id]').value = item.id; qs('[data-admin-content-category]').value = item.category;
            qs('[data-admin-content-title]').value = item.title; qs('[data-admin-content-type]').value = item.type;
            qs('[data-admin-content-description]').value = item.description; qs('[data-admin-content-link]').value = item.link;
            qs('[data-admin-content-form-title]').textContent = 'Edit Konten';
            updateImagePreview('content', item.image);
            qs('[data-admin-content-form]').scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }
        if (button.matches('[data-delete-content]')) {
            if (!confirm('Hapus konten ini?')) return;
            await api(`/api/admin/konten/${button.dataset.deleteContent}`, { method: 'DELETE' });
            await load(); toast('Konten dihapus.'); return;
        }
        if (button.matches('[data-admin-refresh]')) { await load(); toast('Data diperbarui.'); return; }
        if (button.matches('[data-admin-user-reset]')) { resetUserForm(); return; }
        if (button.matches('[data-admin-fertilizer-reset]')) { resetFertilizerForm(); return; }
        if (button.matches('[data-admin-content-reset]')) { resetContentForm(); return; }
        if (button.matches('[data-admin-fertilizer-image-button]')) qs('[data-admin-fertilizer-image-file]').click();
        if (button.matches('[data-admin-content-image-button]')) qs('[data-admin-content-image-file]').click();
        if (button.matches('[data-admin-fertilizer-image-clear]')) {
            qs('[data-admin-fertilizer-image-file]').value = '';
            updateImagePreview('fertilizer', '', 'Gambar akan dikembalikan ke gambar bawaan.', '__remove__');
            return;
        }
        if (button.matches('[data-admin-content-image-clear]')) {
            qs('[data-admin-content-image-file]').value = '';
            updateImagePreview('content', '', 'Gambar akan dihapus.', '__remove__');
            return;
        }
    } catch (error) { toast(error.message); }
});

qsa('[data-admin-payment-enabled], [data-admin-setting-maintenance]').forEach((input) => input.addEventListener('change', () => {
    updateSwitch(input);
}));
qs('[data-admin-setting-marketplace]')?.addEventListener('change', (event) => {
    updateMarketplaceDescription(event.target.value);
});
qs('[data-admin-fertilizer-image-file]')?.addEventListener('change', (event) => {
    const file = event.target.files[0]; if (file) updateImagePreview('fertilizer', URL.createObjectURL(file), file.name, '__new__');
});
qs('[data-admin-fertilizer-price]')?.addEventListener('input', (event) => {
    event.target.value = rupiahValue(event.target.value);
});
qs('[data-admin-content-image-file]')?.addEventListener('change', (event) => {
    const file = event.target.files[0]; if (file) updateImagePreview('content', URL.createObjectURL(file), file.name, '__new__');
});
qsa('[data-rdkk-field]').forEach((input) => input.addEventListener('input', () => {
    saveRdkkValues();
    renderRdkkReport();
}));
qs('[data-admin-print-report]')?.addEventListener('click', () => {
    if (!state.data) {
        toast('Data laporan belum siap. Silakan coba lagi.');
        return;
    }

    renderRdkkReport();
    window.print();
});

function setMenuGroupOpen(group, open) {
    if (!group) return;

    group.open = open;
    group.classList.toggle('is-open', open);
}

function syncActiveMenu(activeButton) {
    qsa('[data-admin-menu-group]').forEach((group) => {
        const containsActiveTab = group.contains(activeButton);
        group.classList.toggle('has-active', containsActiveTab);

        if (containsActiveTab) {
            setMenuGroupOpen(group, true);
        }
    });
}

qsa('[data-admin-tab]').forEach((button) => {
    button.addEventListener('click', () => {
        bootstrap.Tab.getOrCreateInstance(button).show();
        syncActiveMenu(button);
        history.replaceState(null, '', button.dataset.bsTarget);

        const offcanvasElement = qs('#sidebarMenu');
        if (window.innerWidth < 768 && offcanvasElement) {
            bootstrap.Offcanvas.getInstance(offcanvasElement)?.hide();
        }
    });

    button.addEventListener('shown.bs.tab', () => syncActiveMenu(button));
});

qsa('[data-admin-menu-group]').forEach((group) => {
    group.addEventListener('toggle', () => {
        group.classList.toggle('is-open', group.open);

        if (group.open) {
            qsa('[data-admin-menu-group]').forEach((otherGroup) => {
                if (otherGroup !== group) setMenuGroupOpen(otherGroup, false);
            });
        }
    });
});

qs('[data-admin-sidebar-toggle]')?.addEventListener('click', (event) => {
    const collapsed = document.body.classList.toggle('sidebar-collapsed');
    event.currentTarget.setAttribute('aria-expanded', String(!collapsed));
    event.currentTarget.setAttribute('aria-label', collapsed ? 'Buka sidebar admin' : 'Tutup sidebar admin');
    event.currentTarget.setAttribute('title', collapsed ? 'Buka sidebar' : 'Tutup sidebar');
});

syncActiveMenu(qs('[data-admin-tab].active'));

const requestedTab = qsa('[data-admin-tab]').find((button) => button.dataset.bsTarget === window.location.hash);
if (requestedTab) {
    bootstrap.Tab.getOrCreateInstance(requestedTab).show();
    syncActiveMenu(requestedTab);
}

load().catch((error) => toast(error.message));