<?php

namespace App\Http\Controllers;

use App\Models\LahanPetani;
use App\Models\ProfilPembeli;
use App\Models\ProfilPetani;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIK atau nomor handphone wajib diisi.',
            'username.string' => 'Identitas login tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password tidak valid.',
        ]);

        $identifier = $this->normalizeLoginIdentifier($credentials['username']);
        $user = User::query()
            ->where(function ($query) use ($identifier) {
                $query
                    ->where(function ($admin) use ($identifier) {
                        $admin
                            ->where('peran', 'admin')
                            ->where('username', $identifier);
                    })
                    ->orWhere(function ($petani) use ($identifier) {
                        $petani
                            ->where('peran', 'petani')
                            ->where(function ($identitas) use ($identifier) {
                                $identitas
                                    ->where('nik', $identifier)
                                    ->orWhere('nomor_hp', $identifier);
                            });
                    })
                    ->orWhere(function ($pembeli) use ($identifier) {
                        $pembeli
                            ->where('peran', 'pembeli')
                            ->where('nomor_hp', $identifier);
                    });
            })
            ->first();

        if (! $user || $user->status !== 'aktif' || ! Auth::attempt([
            'id' => $user->id,
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'username' => 'NIK, nomor handphone, atau password tidak sesuai; atau akun belum aktif.',
                ])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        $request->session()->forget('url.intended');

        return redirect()->to($this->homeFor($user));
    }

    public function registerPetani(Request $request): RedirectResponse
    {
        $request->merge([
            'no_hp' => $this->normalizePhoneNumber($request->input('no_hp')),
        ]);

        $data = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:users,nik'],
            'nama' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'regex:/^08[0-9]{8,13}$/', 'unique:users,nomor_hp'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar. Gunakan NIK lain.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.string' => 'Nama lengkap harus berupa teks.',
            'nama.max' => 'Nama lengkap maksimal 150 karakter.',
            'no_hp.required' => 'Nomor handphone wajib diisi.',
            'no_hp.regex' => 'Nomor handphone harus diawali 08 dan terdiri dari 10-15 digit angka.',
            'no_hp.unique' => 'Nomor handphone sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['nama'],
                'username' => $data['nik'],
                'nomor_hp' => $data['no_hp'],
                'nik' => $data['nik'],
                'peran' => 'petani',
                'status' => 'menunggu',
                'password' => $data['password'],
                'password_updated_at' => now(),
            ]);

            ProfilPetani::create(['id_pengguna' => $user->id]);
            LahanPetani::create([
                'id_petani' => $user->id,
                'nama_lahan' => 'Lahan Padi',
                'nama_pemilik' => $user->name,
                'luas_meter' => 0,
            ]);

            return $user;
        });

        return redirect()->route('login')
            ->with('status', "Pendaftaran {$user->name} berhasil. Tunggu admin mengaktifkan akun.");
    }

    public function registerPembeli(Request $request): RedirectResponse
    {
        $request->merge([
            'no_handphone' => $this->normalizePhoneNumber($request->input('no_handphone')),
        ]);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'no_handphone' => ['required', 'regex:/^08[0-9]{8,13}$/', 'unique:users,nomor_hp'],
            'nama_gudang' => ['nullable', 'string', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'no_handphone.required' => 'Nomor handphone wajib diisi.',
            'no_handphone.regex' => 'Nomor handphone harus diawali 08 dan terdiri dari 10-15 digit angka.',
            'no_handphone.unique' => 'Nomor handphone sudah terdaftar.',
            'nama_gudang.string' => 'Nama gudang harus berupa teks.',
            'nama_gudang.max' => 'Nama gudang maksimal 150 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'username' => $data['no_handphone'],
                'nomor_hp' => $data['no_handphone'],
                'peran' => 'pembeli',
                'status' => 'aktif',
                'password' => $data['password'],
                'password_updated_at' => now(),
            ]);

            ProfilPembeli::create([
                'id_pengguna' => $user->id,
                'nama_gudang' => ($data['nama_gudang'] ?? null) ?: 'Gudang '.$data['nama_lengkap'],
            ]);
        });

        return redirect()->route('login')
            ->with('status', 'Pendaftaran pembeli berhasil. Akun sudah aktif dan dapat digunakan untuk login.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function homeFor(User $user): string
    {
        return match ($user->peran) {
            'admin' => route('admin'),
            'pembeli' => route('pembeli.marketplace'),
            default => route('dashboard'),
        };
    }

    private function normalizeLoginIdentifier(string $identifier): string
    {
        $identifier = trim($identifier);
        $phone = $this->normalizePhoneNumber($identifier);

        return preg_match('/^08[0-9]{8,13}$/', $phone) ? $phone : $identifier;
    }

    private function normalizePhoneNumber(mixed $value): string
    {
        $phone = preg_replace('/[\s().-]+/', '', trim((string) $value)) ?? '';

        if (str_starts_with($phone, '+62')) {
            return '0'.substr($phone, 3);
        }

        if (str_starts_with($phone, '62')) {
            return '0'.substr($phone, 2);
        }

        if (preg_match('/^8[0-9]{8,13}$/', $phone)) {
            return '0'.$phone;
        }

        return $phone;
    }
}
