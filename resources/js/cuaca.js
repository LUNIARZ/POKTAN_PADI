import { api } from './api';

const halamanCuaca = document.querySelector('[data-halaman-cuaca]');
const panelStatus = halamanCuaca?.querySelector('[data-cuaca-status]');
const judulStatus = halamanCuaca?.querySelector('[data-cuaca-status-title]');
const pesanStatus = halamanCuaca?.querySelector('[data-cuaca-status-message]');
const tombolLokasi = halamanCuaca?.querySelector('[data-cuaca-location-button]');

const fallbackIkon = {
    cerah: '/assets/cuaca/hari_yang_cerah_dengan_awan_dan_matahari-bersih.png',
    berawan: '/assets/cuaca/awan_biru_lembut_minimalis-bersih.png',
    hujan: '/assets/cuaca/awan_dengan_hujan_ringan-bersih.png',
};

let dataCuacaSudahTampil = false;

const koordinatValid = (latitude, longitude) => (
    Number.isFinite(Number(latitude))
    && Number.isFinite(Number(longitude))
);

const pilihIkonFallback = (deskripsi = '') => {
    const teks = deskripsi.toLowerCase();

    if (teks.includes('hujan')) return fallbackIkon.hujan;
    if (teks.includes('berawan')) return fallbackIkon.berawan;

    return fallbackIkon.cerah;
};

const tulisTeks = (selector, teks, fallback = '--') => {
    const elemen = halamanCuaca?.querySelector(selector);
    if (elemen) elemen.textContent = teks ?? fallback;
};

const tulisNilaiDenganSatuan = (selector, nilai, satuan) => {
    const elemen = halamanCuaca?.querySelector(selector);
    if (!elemen) return;

    elemen.replaceChildren(document.createTextNode(String(nilai)));
    const unit = document.createElement('span');
    unit.textContent = satuan;
    elemen.append(unit);
};

const aturStatus = ({
    state = 'info',
    title,
    message,
    buttonLabel = null,
    hidden = false,
}) => {
    if (!panelStatus) return;

    panelStatus.hidden = hidden;
    panelStatus.dataset.state = state;
    if (judulStatus) judulStatus.textContent = title;
    if (pesanStatus) pesanStatus.textContent = message;
    if (tombolLokasi) {
        tombolLokasi.hidden = !buttonLabel;
        tombolLokasi.textContent = buttonLabel || '';
    }
};

const aturMemuat = (memuat) => {
    tombolLokasi?.toggleAttribute('disabled', memuat);
};

const pasangIkon = (elemen, urlIkon, deskripsi) => {
    if (!elemen) return;

    const fallback = pilihIkonFallback(deskripsi);
    elemen.onerror = () => {
        elemen.onerror = null;
        elemen.src = fallback;
    };
    elemen.src = urlIkon || fallback;
    elemen.alt = deskripsi || 'Ikon cuaca';
};

const buatKartuPrakiraan = (item, index) => {
    const kartu = document.createElement('article');
    kartu.className = `kartu-prakiraan${index === 0 ? ' aktif' : ''}`;

    const hari = document.createElement('h3');
    hari.textContent = item.hari || '--';

    const tanggal = document.createElement('p');
    tanggal.className = 'tanggal';
    tanggal.textContent = item.tanggal || '--';

    const ikon = document.createElement('img');
    ikon.className = 'ikon-prakiraan';
    pasangIkon(ikon, item.ikon, item.deskripsi);

    const suhuMaks = document.createElement('p');
    suhuMaks.className = 'suhu-maks';
    suhuMaks.textContent = Number.isFinite(Number(item.suhu_maks))
        ? `${Math.round(Number(item.suhu_maks))}\u00b0C`
        : '--\u00b0C';

    const suhuMin = document.createElement('p');
    suhuMin.className = 'suhu-min';
    suhuMin.textContent = Number.isFinite(Number(item.suhu_min))
        ? `${Math.round(Number(item.suhu_min))}\u00b0C`
        : '--\u00b0C';

    const keterangan = document.createElement('p');
    keterangan.className = 'keterangan';
    keterangan.textContent = item.deskripsi || 'Data cuaca';

    kartu.append(hari, tanggal, ikon, suhuMaks, suhuMin, keterangan);

    return kartu;
};

const tampilkanPrakiraan = (prakiraan = []) => {
    const daftar = halamanCuaca?.querySelector('[data-prakiraan-list]');
    if (!daftar) return;

    daftar.replaceChildren();

    if (!prakiraan.length) {
        const kosong = document.createElement('p');
        kosong.className = 'pesan-prakiraan';
        kosong.textContent = 'Prakiraan cuaca belum tersedia.';
        daftar.append(kosong);
        return;
    }

    prakiraan.slice(0, 5).forEach((item, index) => {
        daftar.append(buatKartuPrakiraan(item, index));
    });
};

const tampilkanCuaca = (data) => {
    const cuaca = data?.cuaca;
    const lokasi = data?.lokasi;
    if (!cuaca) throw new Error('Data cuaca tidak tersedia.');

    if (lokasi?.nama) {
        tulisTeks('[data-cuaca-lokasi]', lokasi.nama);
        const elemenLokasi = halamanCuaca?.querySelector('[data-cuaca-lokasi]');
        if (elemenLokasi && koordinatValid(lokasi.lat, lokasi.lng)) {
            elemenLokasi.title = `Koordinat: ${Number(lokasi.lat).toFixed(4)}, ${Number(lokasi.lng).toFixed(4)}`;
        }
    }

    if (Number.isFinite(Number(cuaca.suhu))) {
        tulisNilaiDenganSatuan('[data-cuaca-suhu]', Math.round(Number(cuaca.suhu)), '\u00b0C');
    }

    tulisTeks('[data-cuaca-deskripsi]', cuaca.deskripsi, 'Cuaca lokasi Anda');
    tulisTeks('[data-cuaca-tanggal]', cuaca.tanggal, 'Waktu pengamatan tidak tersedia');

    if (Number.isFinite(Number(cuaca.kelembaban))) {
        tulisTeks('[data-cuaca-kelembaban]', `${Math.round(Number(cuaca.kelembaban))}%`);
    }

    if (Number.isFinite(Number(cuaca.angin))) {
        tulisNilaiDenganSatuan('[data-cuaca-angin]', Math.round(Number(cuaca.angin)), ' km/jam');
    }

    if (Number.isFinite(Number(cuaca.peluang_hujan))) {
        tulisTeks('[data-cuaca-peluang-hujan]', `${Math.round(Number(cuaca.peluang_hujan))}%`);
    } else {
        tulisTeks('[data-cuaca-peluang-hujan]', 'N/A');
    }

    pasangIkon(halamanCuaca?.querySelector('[data-cuaca-ikon]'), cuaca.ikon, cuaca.deskripsi);
    tampilkanPrakiraan(data?.prakiraan || []);
    dataCuacaSudahTampil = true;
};

const simpanLokasi = async (lokasi) => {
    if (!koordinatValid(lokasi?.lat, lokasi?.lng)) return;

    await api('/api/profile/lokasi', {
        method: 'PUT',
        body: {
            latitude: lokasi.lat,
            longitude: lokasi.lng,
            nama_lokasi: lokasi.nama || null,
        },
    });
};

const muatCuaca = async ({ latitude, longitude, simpan = false }) => {
    if (!koordinatValid(latitude, longitude)) {
        throw new Error('Koordinat lokasi tidak valid.');
    }

    const query = new URLSearchParams({
        lat: Number(latitude).toFixed(4),
        lng: Number(longitude).toFixed(4),
    });
    const data = await api(`/api/cuaca-lokasi?${query.toString()}`);

    tampilkanCuaca(data);
    if (simpan) await simpanLokasi(data.lokasi);

    return data;
};

const ambilLokasiPerangkat = () => new Promise((resolve, reject) => {
    if (!window.isSecureContext) {
        reject(Object.assign(new Error('Akses lokasi membutuhkan HTTPS atau localhost.'), {
            code: 'INSECURE_CONTEXT',
        }));
        return;
    }

    if (!navigator.geolocation) {
        reject(Object.assign(new Error('Browser tidak mendukung akses lokasi.'), {
            code: 'UNSUPPORTED',
        }));
        return;
    }

    navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        maximumAge: 300000,
        timeout: 12000,
    });
});

const pesanLokasi = (error) => {
    if (error?.code === 'INSECURE_CONTEXT') {
        return 'Buka aplikasi melalui HTTPS atau localhost untuk memperbarui lokasi.';
    }
    if (error?.code === 'UNSUPPORTED') {
        return 'Browser ini tidak mendukung akses lokasi perangkat.';
    }
    if (error?.code === 1) {
        return 'Izin lokasi ditolak. Aktifkan izin lokasi pada pengaturan browser.';
    }
    if (error?.code === 2) {
        return 'Lokasi belum ditemukan. Pastikan GPS perangkat aktif.';
    }
    if (error?.code === 3) {
        return 'Pencarian lokasi terlalu lama. Pastikan GPS aktif lalu coba lagi.';
    }

    return error?.message || 'Lokasi atau data cuaca belum dapat dimuat.';
};

const perbaruiDariPerangkat = async () => {
    aturMemuat(true);
    aturStatus({
        title: 'Mencari lokasi',
        message: 'Pastikan GPS aktif dan izinkan akses lokasi pada browser.',
    });

    try {
        const posisi = await ambilLokasiPerangkat();
        await muatCuaca({
            latitude: posisi.coords.latitude,
            longitude: posisi.coords.longitude,
            simpan: true,
        });
        aturStatus({
            title: 'Cuaca diperbarui',
            message: 'Data mengikuti lokasi perangkat Anda saat ini.',
            hidden: true,
        });
    } catch (error) {
        aturStatus({
            state: dataCuacaSudahTampil ? 'warning' : 'error',
            title: dataCuacaSudahTampil ? 'Menggunakan lokasi tersimpan' : 'Lokasi belum tersedia',
            message: pesanLokasi(error),
            buttonLabel: 'Coba lagi',
        });
    } finally {
        aturMemuat(false);
    }
};

const siapkanHalamanCuaca = async () => {
    if (!halamanCuaca) return;

    aturMemuat(true);

    try {
        const profile = await api('/api/profile');
        if (koordinatValid(profile.latitude, profile.longitude)) {
            if (profile.locationName) tulisTeks('[data-cuaca-lokasi]', profile.locationName);

            try {
                await muatCuaca({
                    latitude: profile.latitude,
                    longitude: profile.longitude,
                });
                aturStatus({
                    title: 'Lokasi tersimpan',
                    message: 'Cuaca ditampilkan dari lokasi terakhir. Tekan perbarui untuk memakai lokasi saat ini.',
                    buttonLabel: 'Perbarui',
                });
            } catch (error) {
                aturStatus({
                    state: 'error',
                    title: 'Data cuaca belum tersedia',
                    message: error.message,
                    buttonLabel: 'Coba lagi',
                });
            }
        } else {
            aturStatus({
                title: 'Aktifkan lokasi',
                message: 'Izinkan akses lokasi agar cuaca di sekitar lahan dapat ditampilkan.',
                buttonLabel: 'Aktifkan',
            });
        }

        if (navigator.permissions?.query && window.isSecureContext) {
            const permission = await navigator.permissions.query({ name: 'geolocation' });
            if (permission.state === 'granted') await perbaruiDariPerangkat();
        }
    } catch (error) {
        aturStatus({
            state: 'error',
            title: 'Halaman cuaca belum siap',
            message: error.message,
            buttonLabel: 'Coba lagi',
        });
    } finally {
        aturMemuat(false);
    }
};

tombolLokasi?.addEventListener('click', perbaruiDariPerangkat);

siapkanHalamanCuaca();
