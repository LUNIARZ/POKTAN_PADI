import { api } from './api';

async function applyMaintenance() {
    try {
        const settings = await api('/api/pengaturan');
        if (settings.maintenance !== 'Aktif' || document.querySelector('[data-maintenance-overlay]')) return;
        const overlay = document.createElement('div');
        overlay.dataset.maintenanceOverlay = 'true';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:99999;background:#f4fbf3;display:grid;place-items:center;padding:24px;text-align:center;';
        const content = document.createElement('div');
        const title = document.createElement('h1');
        const message = document.createElement('p');
        content.style.maxWidth = '520px';
        title.style.color = '#176b32';
        title.textContent = 'Aplikasi Sedang Maintenance';
        message.textContent = settings.maintenanceMessage || 'Aplikasi sedang dalam perawatan.';
        content.append(title, message);
        overlay.append(content);
        document.body.appendChild(overlay);
    } catch { /* halaman tetap dapat dibuka jika status gagal dimuat */ }
}
applyMaintenance();
