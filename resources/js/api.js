export function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

export async function api(url, options = {}) {
    const headers = new Headers(options.headers || {});
    headers.set('Accept', 'application/json');
    if (!(options.body instanceof FormData) && options.body !== undefined) {
        headers.set('Content-Type', 'application/json');
    }
    if (!['GET', 'HEAD'].includes(String(options.method || 'GET').toUpperCase())) {
        headers.set('X-CSRF-TOKEN', csrfToken());
    }

    let response;
    try {
        response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers,
            body: options.body instanceof FormData
                ? options.body
                : options.body === undefined
                    ? undefined
                    : JSON.stringify(options.body),
        });
    } catch {
        throw new Error('Tidak dapat terhubung ke server. Periksa koneksi lalu coba lagi.');
    }

    if (response.redirected && new URL(response.url).pathname === '/login') {
        window.location.assign(response.url);
        throw new Error('Sesi Anda telah berakhir. Silakan masuk kembali.');
    }

    const rawResponse = response.status === 204 ? '' : await response.text();
    let data = null;
    if (rawResponse) {
        try {
            data = JSON.parse(rawResponse);
        } catch {
            const jsonStart = rawResponse.indexOf('{');
            if (jsonStart >= 0) {
                try {
                    data = JSON.parse(rawResponse.slice(jsonStart));
                } catch {
                    data = null;
                }
            }
        }
    }

    if (!response.ok) {
        const validation = data?.errors ? Object.values(data.errors).flat()[0] : null;
        const statusMessage = {
            413: 'Ukuran data terlalu besar untuk dikirim. Gunakan foto yang lebih kecil.',
            419: 'Sesi halaman telah berakhir. Muat ulang halaman lalu coba lagi.',
            429: 'Terlalu banyak permintaan. Tunggu sebentar lalu coba lagi.',
            500: 'Server mengalami kesalahan saat menyimpan produk.',
        }[response.status];
        throw new Error(validation || data?.message || statusMessage || `Permintaan gagal diproses (HTTP ${response.status}).`);
    }

    if (response.status !== 204 && data === null) {
        const temporaryStorageFailure = /unable to create (?:a )?temporary file|post data can't be buffered/i.test(rawResponse);
        throw new Error(temporaryStorageFailure
            ? 'Server tidak dapat membuat file sementara untuk upload. Periksa folder storage/app/tmp/uploads lalu coba lagi.'
            : 'Respons server tidak valid. Muat ulang halaman lalu coba lagi.');
    }

    return data;
}
