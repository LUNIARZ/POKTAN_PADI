const widgetCuaca = document.querySelector('[data-cuaca-widget]');
const panelLokasi = document.querySelector('[data-lokasi-permintaan]');
const pesanLokasi = document.querySelector('[data-lokasi-pesan]');
const tombolLokasi = document.querySelector('[data-lokasi-aktifkan]');
const badgePembelianDashboard = document.querySelector('[data-dashboard-badge-pembelian]');

const tulisTeks = (selector, teks) => {
    const elemen = widgetCuaca?.querySelector(selector);

    if (elemen && teks !== undefined && teks !== null) {
        elemen.textContent = teks;
    }
};

const perbaruiBadgePembelianDashboard = async () => {
    if (!badgePembelianDashboard) {
        return;
    }

    const orders = await api('/api/marketplace-pesanan');
    const jumlahMenunggu = orders.filter((item) => item.status === 'menunggu').length;

    badgePembelianDashboard.textContent = String(jumlahMenunggu);
    badgePembelianDashboard.hidden = jumlahMenunggu === 0;
};

const tampilkanPermintaanLokasi = (pesan) => {
    if (pesanLokasi && pesan) {
        pesanLokasi.textContent = pesan;
    }

    if (panelLokasi) {
        panelLokasi.hidden = false;
    }
};

const simpanLokasiDashboard = async (lokasi) => {
    if (!lokasi?.lat || !lokasi?.lng) {
        return;
    }

    try {
        await api('/api/profile/lokasi', {
            method: 'PUT',
            body: {
                latitude: lokasi.lat,
                longitude: lokasi.lng,
                nama_lokasi: lokasi.nama || null,
            },
        });
    } catch {}
};

const sembunyikanPermintaanLokasi = () => {
    if (panelLokasi) {
        panelLokasi.hidden = true;
    }
};

const ambilJson = async (url) => {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`Request gagal: ${response.status}`);
    }

    return response.json();
};

const ambilLokasiPerangkat = () => new Promise((resolve, reject) => {
    if (!window.isSecureContext) {
        reject(Object.assign(
            new Error('Akses lokasi membutuhkan HTTPS atau localhost.'),
            { code: 'INSECURE_CONTEXT' },
        ));
        return;
    }

    if (!navigator.geolocation) {
        reject(new Error('Browser tidak mendukung Geolocation API.'));
        return;
    }

    navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        maximumAge: 300000,
        timeout: 10000,
    });
});

const tampilkanCuacaLokasi = (data) => {
    const cuaca = data?.cuaca;
    const lokasi = data?.lokasi;
    const ikon = widgetCuaca?.querySelector('[data-cuaca-ikon]');

    if (!cuaca) {
        return;
    }

    if (Number.isFinite(Number(cuaca.suhu))) {
        tulisTeks('[data-cuaca-suhu]', `${Math.round(Number(cuaca.suhu))}\u00b0C`);
    }

    tulisTeks('[data-cuaca-deskripsi]', cuaca.deskripsi);

    if (Number.isFinite(Number(cuaca.kelembaban))) {
        tulisTeks('[data-cuaca-kelembaban]', `Kelembaban ${Math.round(Number(cuaca.kelembaban))}%`);
    }

    if (Number.isFinite(Number(cuaca.angin))) {
        tulisTeks('[data-cuaca-angin]', `Angin ${Math.round(Number(cuaca.angin))} km/jam`);
    }

    if (lokasi?.nama) {
        tulisTeks('[data-cuaca-lokasi]', lokasi.nama);
        widgetCuaca?.setAttribute('aria-label', `Cuaca ${lokasi.nama}`);
        simpanLokasiDashboard(lokasi);
    }

    if (ikon && cuaca.ikon) {
        const ikonDefault = ikon.dataset.cuacaIkonDefault || ikon.src;
        ikon.onerror = () => {
            ikon.onerror = null;
            ikon.src = ikonDefault;
        };
        ikon.src = cuaca.ikon;
        ikon.alt = cuaca.deskripsi || 'Ikon cuaca';
    }

    sembunyikanPermintaanLokasi();
};

const ambilCuacaLokasi = async () => {
    const posisi = await ambilLokasiPerangkat();
    const latitude = posisi.coords.latitude.toFixed(4);
    const longitude = posisi.coords.longitude.toFixed(4);
    const elemenLokasi = widgetCuaca?.querySelector('[data-cuaca-lokasi]');

    if (elemenLokasi) {
        elemenLokasi.title = `Koordinat: ${latitude}, ${longitude}`;
    }

    const query = new URLSearchParams({
        lat: latitude,
        lng: longitude,
    });

    return ambilJson(`/api/cuaca-lokasi?${query.toString()}`);
};

const tampilkanPesanCuaca = (pesan) => {
    tulisTeks('[data-cuaca-deskripsi]', pesan);
};

const pesanErrorLokasi = (error) => {
    if (error?.code === 'INSECURE_CONTEXT') {
        return 'Buka aplikasi lewat HTTPS atau localhost agar browser bisa menampilkan izin lokasi.';
    }

    if (error?.code === 1) {
        return 'Izin lokasi ditolak. Aktifkan izin lokasi dari pengaturan browser, lalu coba lagi.';
    }

    if (error?.code === 2) {
        return 'Lokasi perangkat belum tersedia. Pastikan GPS atau lokasi Android sudah aktif.';
    }

    if (error?.code === 3) {
        return 'Pencarian lokasi terlalu lama. Pastikan GPS aktif lalu tekan Aktifkan lagi.';
    }

    return 'Tekan Aktifkan, lalu pilih Izinkan pada notifikasi lokasi dari browser.';
};

const muatCuacaDashboard = async () => {
    tombolLokasi?.setAttribute('disabled', 'disabled');
    tampilkanPesanCuaca('Mengambil lokasi');

    try {
        const dataCuaca = await ambilCuacaLokasi();
        tampilkanCuacaLokasi(dataCuaca);
    } catch (error) {
        tampilkanPesanCuaca('Izinkan lokasi');
        tampilkanPermintaanLokasi(pesanErrorLokasi(error));
    } finally {
        tombolLokasi?.removeAttribute('disabled');
    }
};

const mulaiCuacaDashboard = async () => {
    if (!widgetCuaca) {
        return;
    }

    if (!window.isSecureContext) {
        tampilkanPesanCuaca('HTTPS diperlukan');
        tampilkanPermintaanLokasi('Browser hanya menampilkan izin lokasi jika aplikasi dibuka lewat HTTPS atau localhost.');
        return;
    }

    if (!navigator.geolocation) {
        tampilkanPesanCuaca('Lokasi tidak didukung');
        tampilkanPermintaanLokasi('Browser ini belum mendukung akses lokasi.');
        return;
    }

    if (!navigator.permissions?.query) {
        tampilkanPesanCuaca('Aktifkan lokasi');
        tampilkanPermintaanLokasi('Tekan Aktifkan, lalu pilih Izinkan pada notifikasi lokasi dari browser.');
        return;
    }

    try {
        const izinLokasi = await navigator.permissions.query({ name: 'geolocation' });

        if (izinLokasi.state === 'granted') {
            await muatCuacaDashboard();
            return;
        }

        if (izinLokasi.state === 'denied') {
            tampilkanPesanCuaca('Izinkan lokasi');
            tampilkanPermintaanLokasi('Izin lokasi belum aktif. Ubah izin lokasi di browser, lalu coba lagi.');
            return;
        }

        tampilkanPesanCuaca('Aktifkan lokasi');
        tampilkanPermintaanLokasi('Tekan Aktifkan, lalu pilih Izinkan pada notifikasi lokasi dari browser.');

        izinLokasi.onchange = () => {
            if (izinLokasi.state === 'granted') {
                muatCuacaDashboard();
            }
        };
    } catch (error) {
        tampilkanPesanCuaca('Aktifkan lokasi');
        tampilkanPermintaanLokasi('Tekan Aktifkan, lalu pilih Izinkan pada notifikasi lokasi dari browser.');
    }
};

tombolLokasi?.addEventListener('click', () => {
    muatCuacaDashboard();
});

perbaruiBadgePembelianDashboard().catch(() => {});
mulaiCuacaDashboard();
import { api } from './api';
