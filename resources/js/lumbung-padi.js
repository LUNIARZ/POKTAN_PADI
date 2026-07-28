import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const number = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
let harvests = [];

const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

const today = () => {
    const date = new Date();
    const offset = date.getTimezoneOffset();
    return new Date(date.getTime() - offset * 60000).toISOString().slice(0, 10);
};

function openPanel(item = null) {
    const panel = qs('[data-panel-panen]');
    const form = qs('[data-form-panen]');
    form.reset();
    qs('[data-input-id]').value = item?.id || '';
    qs('[data-input-jumlah]').value = item ? String(item.jumlah).replace('.', ',') : '';
    qs('[data-input-bibit]').value = item?.jenisBibit || '';
    qs('[data-input-tanggal]').value = item?.tanggal || today();
    qs('[data-judul-form-panen]').textContent = item ? 'Edit Hasil Panen' : 'Tambah Hasil Panen';
    qs('[data-deskripsi-form-panen]').textContent = item
        ? 'Perbarui jumlah, jenis bibit, atau tanggal hasil panen.'
        : 'Masukkan jumlah, jenis bibit, dan tanggal panen.';
    qs('[data-tombol-simpan]').textContent = item ? 'PERBARUI' : 'SIMPAN';
    qs('[data-buka-form-panen]').setAttribute('aria-expanded', 'true');
    panel.hidden = false;
    qs('[data-input-jumlah]').focus();
}

function closePanel() {
    qs('[data-panel-panen]').hidden = true;
    qs('[data-buka-form-panen]').setAttribute('aria-expanded', 'false');
    qs('[data-form-panen]').reset();
    qs('[data-input-id]').value = '';
}

async function load() {
    harvests = await api('/api/hasil-panen');
    const body = qs('[data-tabel-panen]');
    body.innerHTML = harvests.length ? harvests.map((item) => `
        <tr>
            <td><strong>${escapeHtml(item.hasil)}</strong><span>(per Kg)</span></td>
            <td>${number.format(item.jumlah)}</td>
            <td>${escapeHtml(item.jenisBibit)}</td>
            <td>${item.tanggal.split('-').reverse().join('/')}</td>
            <td>
                <div class="aksi-baris">
                    <button class="tombol-aksi tombol-edit" type="button" data-edit-panen="${item.id}" aria-label="Edit hasil panen ${escapeHtml(item.jenisBibit)}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4z"></path>
                        </svg>
                        Edit
                    </button>
                    <button class="tombol-aksi tombol-hapus" type="button" data-hapus-panen="${item.id}" aria-label="Hapus hasil panen ${escapeHtml(item.jenisBibit)}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18"></path>
                            <path d="M8 6V4h8v2"></path>
                            <path d="M19 6l-1 14H6L5 6"></path>
                            <path d="M10 11v5"></path>
                            <path d="M14 11v5"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('') : `
        <tr>
            <td><strong>Hasil Panen Padi</strong><span>(per Kg)</span></td>
            <td>0</td>
            <td>Jenis Bibit Padi</td>
            <td>-</td>
            <td>-</td>
        </tr>
    `;
}

qs('[data-buka-form-panen]')?.addEventListener('click', () => openPanel());
qs('[data-tutup-form-panen]')?.addEventListener('click', closePanel);
qs('[data-form-panen]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submitButton = qs('[data-tombol-simpan]');
    const itemId = qs('[data-input-id]').value;

    try {
        const value = Number(qs('[data-input-jumlah]').value.replace(/\./g, '').replace(',', '.'));
        submitButton.disabled = true;
        submitButton.textContent = itemId ? 'MEMPERBARUI...' : 'MENYIMPAN...';
        await api(itemId ? `/api/hasil-panen/${itemId}` : '/api/hasil-panen', {
            method: itemId ? 'PUT' : 'POST',
            body: {
                jumlah: value,
                jenis_bibit: qs('[data-input-bibit]').value.trim(),
                tanggal_panen: qs('[data-input-tanggal]').value,
            },
        });
        closePanel();
        await load();
    } catch (error) {
        alert(error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = itemId ? 'PERBARUI' : 'SIMPAN';
    }
});

document.addEventListener('click', async (event) => {
    const editButton = event.target.closest('[data-edit-panen]');
    if (editButton) {
        const item = harvests.find((harvest) => String(harvest.id) === editButton.dataset.editPanen);
        if (item) openPanel(item);
        return;
    }

    const deleteButton = event.target.closest('[data-hapus-panen]');
    if (!deleteButton) return;

    const item = harvests.find((harvest) => String(harvest.id) === deleteButton.dataset.hapusPanen);
    if (!item || !confirm(`Hapus hasil panen ${item.jenisBibit} sebanyak ${number.format(item.jumlah)} kg?`)) return;

    try {
        deleteButton.disabled = true;
        await api(`/api/hasil-panen/${item.id}`, { method: 'DELETE' });
        await load();
    } catch (error) {
        deleteButton.disabled = false;
        alert(error.message);
    }
});

load().catch((error) => alert(error.message));
