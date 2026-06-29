import { api } from './api';

const qs = (selector) => document.querySelector(selector);
const stageInfo = {
    pembibitan: {
        label: 'Pembibitan',
        note: 'Bibit lewat dari 25 hari akan menurunkan jumlah anakan produktif.',
        description: 'Benih disemai selama 15 hingga 21 hari hingga memiliki perakaran yang kuat.',
    },
    penanaman: {
        label: 'Penanaman',
        note: 'Tanam dangkal 1-2 cm agar tunas anakan mudah berkembang.',
        description: 'Bibit dipindahkan ke lahan utama, idealnya pada hari yang sama setelah dicabut.',
    },
    perawatan_tanaman: {
        label: 'Perawatan Tanaman',
        note: 'Pemupukan harus selesai sebelum padi berbunga.',
        description: 'Lakukan pemupukan, pengairan, serta pengendalian hama secara terjadwal.',
    },
    panen: {
        label: 'Panen',
        note: 'Panen ketika sekitar 90%-95% bulir padi sudah menguning.',
        description: 'Panen pada waktu ideal untuk menjaga mutu gabah dan mengurangi beras patah.',
    },
    selesai: { label: 'Jadwal Selesai', note: 'Semua proses telah selesai.', description: 'Tahapan tanam selesai sampai panen.' },
};
let schedule = null;

function formatDate(value) {
    if (!value) return '-';
    return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).format(new Date(`${value}T00:00:00`));
}

async function load() {
    schedule = await api('/api/jadwal-tanam');
    render();
}

function render() {
    const activeInfo = stageInfo[schedule.tahapAktif] || stageInfo.pembibitan;
    const isPlanned = schedule.status === 'rencana';
    qs('[data-tanggal-semai]').value = schedule.tanggalSemai;
    qs('[data-tanggal-semai]').disabled = true;
    qs('[data-ringkasan-status]').textContent = schedule.status === 'selesai'
        ? 'Semua proses selesai'
        : (isPlanned ? 'Jadwal belum dimulai' : 'Proses aktif');
    qs('[data-ringkasan-tahap]').textContent = isPlanned ? 'Siap Memulai' : activeInfo.label;
    qs('[data-ringkasan-deskripsi]').textContent = isPlanned
        ? 'Pilih tanggal semai, lalu mulai proses tanam.'
        : activeInfo.note;
    qs('[data-ringkasan-kalender]').textContent = schedule.status === 'selesai'
        ? 'Seluruh jadwal tanam telah diselesaikan.'
        : (isPlanned
            ? 'Tanggal semai akan otomatis memakai tanggal hari saat tombol Mulai Proses Tanam ditekan.'
            : `Perkiraan ${activeInfo.label} mengikuti tanggal semai ${formatDate(schedule.tanggalSemai)}.`);
    qs('[data-progress-teks]').textContent = `${schedule.jumlahSelesai} dari 4 selesai`;
    qs('[data-progress-bar]').style.width = `${schedule.persentase}%`;
    qs('[data-mulai-jadwal]').hidden = !isPlanned;
    qs('[data-reset-jadwal]').hidden = isPlanned;
    qs('[data-daftar-proses]').innerHTML = schedule.tahapan.map((stage) => {
        const info = stageInfo[stage.nama];
        const minimumWarning = stage.kurangMinimum
            ? `<div class="peringatan-proses" role="alert">
                <strong>Belum mencapai minimal ${stage.minimumHari} hari.</strong>
                <span>${info.label} baru berjalan ${stage.hariBerjalan} hari. Tunggu ${stage.sisaHari} hari lagi sebelum diselesaikan.</span>
            </div>`
            : '';
        const completeButton = stage.status === 'aktif' && !isPlanned
            ? `<button class="tombol-selesai" data-complete-stage="${stage.id}">Selesai</button>`
            : '';

        return `
            <article class="kartu-proses ${stage.status}">
                <span class="nomor-proses">${stage.status === 'selesai' ? 'OK' : String(stage.urutan).padStart(2, '0')}</span>
                <div class="isi-proses">
                    <div class="baris-proses"><h2>${info.label}</h2><span class="label-status ${stage.status}">${stage.status}</span></div>
                    <div class="meta-proses"><span>${stage.rentang}</span><span>${formatDate(stage.mulaiTarget)} - ${formatDate(stage.selesaiTarget)}</span></div>
                    <strong class="catatan-proses">${info.note}</strong><p>${info.description}</p>
                    ${minimumWarning}
                    ${completeButton}
                    ${stage.selesaiAktual ? `<span class="teks-selesai">Selesai pada ${formatDate(stage.selesaiAktual)}</span>` : ''}
                </div>
            </article>
        `;
    }).join('');
}

qs('[data-mulai-jadwal]')?.addEventListener('click', async () => {
    try {
        schedule = await api('/api/jadwal-tanam/mulai', { method: 'POST', body: {} });
        render();
    } catch (error) { alert(error.message); }
});
qs('[data-reset-jadwal]')?.addEventListener('click', async () => {
    if (!confirm('Mulai ulang seluruh progres jadwal tanam?')) return;
    schedule = await api('/api/jadwal-tanam/reset', { method: 'POST', body: {} });
    render();
});
document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-complete-stage]');
    if (!button) return;

    const stage = schedule.tahapan.find((item) => String(item.id) === button.dataset.completeStage);
    let confirmedEarly = false;

    if (stage?.kurangMinimum) {
        const info = stageInfo[stage.nama];
        confirmedEarly = confirm(
            `Peringatan: tahap ${info.label} belum mencapai waktu minimal.\n\n`
            + `Tahap baru berjalan ${stage.hariBerjalan} hari dan harus berlangsung minimal ${stage.minimumHari} hari. `
            + `Masih tersisa ${stage.sisaHari} hari.\n\n`
            + 'Klik OK untuk tetap menyelesaikan tahap ini, atau Batal untuk kembali.'
        );

        if (!confirmedEarly) return;
    }

    try {
        schedule = await api(`/api/jadwal-tanam/tahap/${button.dataset.completeStage}/selesai`, {
            method: 'POST',
            body: { konfirmasi_peringatan: confirmedEarly },
        });
        render();
    } catch (error) { alert(error.message); }
});

load().catch((error) => alert(error.message));
