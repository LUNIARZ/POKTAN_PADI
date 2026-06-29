document.querySelectorAll('[data-toggle-password]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = button.closest('.wadah-password')?.querySelector('[data-password-field]');
        if (!input) return;

        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        button.classList.toggle('aktif', !visible);
        button.setAttribute('aria-pressed', visible ? 'false' : 'true');
        button.setAttribute(
            'aria-label',
            visible
                ? `Tampilkan ${input.name === 'password_confirmation' ? 'konfirmasi ' : ''}password`
                : `Sembunyikan ${input.name === 'password_confirmation' ? 'konfirmasi ' : ''}password`
        );
        input.focus({ preventScroll: true });
        input.setSelectionRange(input.value.length, input.value.length);
    });
});

document.querySelectorAll('.form-login').forEach((form) => {
    const registrationAlert = form.querySelector('[data-registration-alert]');
    const requiredMessages = {
        nik: 'NIK wajib diisi.',
        nama: 'Nama lengkap wajib diisi.',
        no_hp: 'Nomor handphone wajib diisi.',
        nama_lengkap: 'Nama lengkap wajib diisi.',
        no_handphone: 'Nomor handphone wajib diisi.',
        password: 'Password wajib diisi.',
        password_confirmation: 'Konfirmasi password wajib diisi.',
    };

    const showAlert = (message) => {
        if (!registrationAlert) return;

        registrationAlert.hidden = false;
        registrationAlert.textContent = message;
    };

    const showFieldError = (input, message) => {
        const field = input.closest('.kolom-formulir');
        if (!field) return;

        const errorId = `${input.name}-error`;
        let error = field.querySelector('[data-field-error]');
        if (!error) {
            error = document.createElement('p');
            error.id = errorId;
            error.className = 'pesan-field';
            error.setAttribute('role', 'alert');
            error.dataset.fieldError = '';
            field.append(error);
        }

        error.textContent = message;
        field.classList.add('memiliki-error');
        input.setAttribute('aria-invalid', 'true');
        input.setAttribute('aria-describedby', errorId);
    };

    const clearFieldError = (input) => {
        const field = input.closest('.kolom-formulir');
        if (!field) return;

        field.classList.remove('memiliki-error');
        field.querySelector('[data-field-error]')?.remove();
        input.removeAttribute('aria-invalid');
        input.removeAttribute('aria-describedby');
    };

    const passwordConfirmationMessage = () => {
        const password = form.querySelector('[name="password"]');
        const confirmation = form.querySelector('[name="password_confirmation"]');
        if (!password || !confirmation || !confirmation.value || password.value === confirmation.value) {
            return '';
        }

        return 'Konfirmasi password tidak sesuai.';
    };

    form.querySelectorAll('input').forEach((input) => {
        input.addEventListener('invalid', () => {
            input.setCustomValidity('');

            let message = '';

            if (input.validity.valueMissing) {
                message = requiredMessages[input.name] || 'Kolom ini wajib diisi.';
            } else if (input.name === 'nik' && input.validity.patternMismatch) {
                message = 'NIK harus terdiri dari 16 digit angka.';
            } else if (input.validity.tooShort) {
                message = `Isian minimal ${input.minLength} karakter.`;
            } else if (input.name === 'password_confirmation') {
                message = passwordConfirmationMessage();
            }

            if (!message) return;

            input.setCustomValidity(message);
            showFieldError(input, message);
            showAlert('Pendaftaran belum dapat dikirim. Periksa isian yang ditandai merah.');
        });

        input.addEventListener('input', () => {
            input.setCustomValidity('');
            clearFieldError(input);

            if (input.name === 'password' || input.name === 'password_confirmation') {
                const confirmation = form.querySelector('[name="password_confirmation"]');
                const message = passwordConfirmationMessage();
                if (confirmation && message) {
                    confirmation.setCustomValidity(message);
                    showFieldError(confirmation, message);
                } else if (confirmation) {
                    confirmation.setCustomValidity('');
                    clearFieldError(confirmation);
                }
            }
        });
    });

    form.addEventListener('submit', (event) => {
        const confirmation = form.querySelector('[name="password_confirmation"]');
        const message = passwordConfirmationMessage();
        if (confirmation && message) {
            event.preventDefault();
            confirmation.setCustomValidity(message);
            showFieldError(confirmation, message);
            showAlert('Pendaftaran belum dapat dikirim. Periksa isian yang ditandai merah.');
            confirmation.focus();
        }
    });
});
