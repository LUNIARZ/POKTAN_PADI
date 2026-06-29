import { api } from './api';

const qs = (selector) => document.querySelector(selector);

async function load() {
    const profile = await api('/api/profile');
    const avatar = qs('[data-avatar-profile]');
    if (avatar && profile.photo) avatar.src = profile.photo;
    const name = qs('.teks-identitas h2');
    if (name) name.textContent = profile.name;

    const location = qs('[data-profile-lokasi]');
    if (location) {
        location.textContent = profile.locationName || profile.address || 'Lokasi belum tersedia';
        if (profile.latitude && profile.longitude) {
            location.title = `Koordinat: ${profile.latitude}, ${profile.longitude}`;
        }
    }
}

qs('[data-pilih-foto-profile]')?.addEventListener('click', () => qs('[data-input-foto-profile]').click());
qs('[data-input-foto-profile]')?.addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;
    try {
        const form = new FormData();
        form.append('foto', file);
        const result = await api('/api/profile/foto', { method: 'POST', body: form });
        qs('[data-avatar-profile]').src = result.photo;
        qs('[data-status-foto-profile]').textContent = 'Foto profil tersimpan di server.';
    } catch (error) { qs('[data-status-foto-profile]').textContent = error.message; }
});

if (window.isSecureContext && navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async ({ coords }) => {
        try {
            const query = new URLSearchParams({
                lat: coords.latitude.toFixed(4),
                lng: coords.longitude.toFixed(4),
            });
            const response = await fetch(`/api/cuaca-lokasi?${query.toString()}`, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            const weather = response.ok ? await response.json() : null;
            const locationName = weather?.lokasi?.nama || null;

            await api('/api/profile/lokasi', {
                method: 'PUT',
                body: {
                    latitude: coords.latitude,
                    longitude: coords.longitude,
                    nama_lokasi: locationName,
                },
            });

            const location = qs('[data-profile-lokasi]');
            if (locationName && location) {
                location.textContent = locationName;
                location.title = `Koordinat: ${coords.latitude.toFixed(4)}, ${coords.longitude.toFixed(4)}`;
            }
        } catch { /* lokasi tidak wajib */ }
    }, () => {}, {
        enableHighAccuracy: true,
        maximumAge: 300000,
        timeout: 10000,
    });
}
load().catch((error) => { qs('[data-status-foto-profile]').textContent = error.message; });
