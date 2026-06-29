import { api } from './api';

const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[char]));

const contentType = (value) => {
    const type = String(value || 'Artikel').toLowerCase();
    const icons = {
        artikel: `
            <path d="M7 3h8l4 4v14H7z"></path>
            <path d="M15 3v5h5"></path>
            <path d="M10 13h7"></path>
            <path d="M10 17h7"></path>
        `,
        video: `
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M10 8l6 4-6 4z"></path>
        `,
        panduan: `
            <path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"></path>
            <path d="M8 4v16"></path>
            <path d="M11 9h5"></path>
            <path d="M11 13h5"></path>
        `,
        solusi: `
            <path d="M9 18h6"></path>
            <path d="M10 22h4"></path>
            <path d="M8.5 14.5A6 6 0 1 1 15.5 14.5c-1 .7-1.5 1.5-1.5 2.5h-4c0-1-.5-1.8-1.5-2.5z"></path>
        `,
    };
    const safeType = Object.hasOwn(icons, type) ? type : 'artikel';

    return {
        className: `label-${safeType}`,
        icon: icons[safeType],
        label: safeType.charAt(0).toUpperCase() + safeType.slice(1),
    };
};

async function renderContents() {
    const page = document.querySelector('[data-admin-content-page]')?.dataset.adminContentPage;
    const list = document.querySelector('[data-admin-content-list]');
    if (!page || !list) return;
    const category = page === 'Hama & Penyakit' ? 'hama_penyakit' : 'edukasi';
    const contents = await api(`/api/konten?kategori=${category}`);
    const html = contents.map((item) => {
        if (page === 'Hama & Penyakit') {
            return `
                <a class="kartu-masalah kartu-masalah-tautan" href="${escapeHtml(item.link)}">
                    <img src="${escapeHtml(item.image || '/assets/hama-penyakit/hama.png')}" alt="${escapeHtml(item.title)}" class="gambar-masalah">
                    <div class="isi-masalah"><h3>${escapeHtml(item.title)}</h3><p>${escapeHtml(item.description)}</p></div>
                    <span class="tautan-solusi">${escapeHtml(item.type)}</span>
                </a>
            `;
        }

        const type = contentType(item.type);

        return `
            <a class="kartu-artikel kartu-artikel-tautan" href="${escapeHtml(item.link)}">
                <img src="${escapeHtml(item.image || '/assets/edukasi/orang-edukasi.png')}" alt="${escapeHtml(item.title)}" class="gambar-artikel">
                <div class="isi-artikel">
                    <div class="baris-judul-artikel">
                        <h3 class="judul-artikel">${escapeHtml(item.title)}</h3>
                        <span class="label-jenis ${type.className}">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                ${type.icon}
                            </svg>
                            ${type.label}
                        </span>
                    </div>
                    <p class="deskripsi-artikel">${escapeHtml(item.description)}</p>
                </div>
                <span class="panah-kartu" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"></path>
                    </svg>
                </span>
            </a>
        `;
    }).join('');
    list.insertAdjacentHTML('afterbegin', html);
}
renderContents().catch(() => {});
