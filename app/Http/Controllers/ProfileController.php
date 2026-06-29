<?php

namespace App\Http\Controllers;

use App\Models\LahanPetani;
use App\Models\ProfilPembeli;
use App\Models\ProfilPetani;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profilPetani', 'profilPembeli']);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->nomor_hp,
            'nik' => $user->nik,
            'address' => $user->alamat,
            'locationName' => $user->nama_lokasi,
            'photo' => $user->foto_profil,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
            'warehouseName' => $user->profilPembeli?->nama_gudang,
            'landAreaMeter' => $user->profilPetani?->luas_lahan_meter,
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'no_handphone' => ['required', 'regex:/^08[0-9]{8,13}$/', 'unique:users,nomor_hp,'.$user->id],
            'nik' => ['nullable', 'digits:16', 'unique:users,nik,'.$user->id],
            'alamat' => ['required', 'string'],
            'nama_gudang' => ['nullable', 'string', 'max:150'],
        ]);

        $user->update([
            'name' => $data['nama_lengkap'],
            'nomor_hp' => $data['no_handphone'],
            'nik' => $user->peran === 'petani' ? ($data['nik'] ?? $user->nik) : null,
            'alamat' => $data['alamat'],
        ]);
        if ($user->peran === 'pembeli') {
            ProfilPembeli::updateOrCreate(
                ['id_pengguna' => $user->id],
                ['nama_gudang' => $data['nama_gudang'] ?? 'Gudang '.$user->name, 'alamat_gudang' => $data['alamat']]
            );
        } else {
            ProfilPetani::firstOrCreate(['id_pengguna' => $user->id]);
            LahanPetani::updateOrCreate(
                ['id_petani' => $user->id, 'nama_lahan' => 'Lahan Padi'],
                [
                    'nama_pemilik' => $user->name,
                    'alamat' => $data['alamat'],
                    'status' => 'aktif',
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data diri diperbarui.']);
        }

        return back()->with('status', 'Data diri berhasil diperbarui.');
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ]);

        $oldPhoto = $request->user()->foto_profil;
        $path = '/storage/'.$request->file('foto')->store('poktan/profile', 'public');
        $request->user()->update(['foto_profil' => $path]);

        if ($oldPhoto && Str::startsWith($oldPhoto, '/storage/')) {
            Storage::disk('public')->delete(Str::after($oldPhoto, '/storage/'));
        }

        return response()->json(['photo' => $path]);
    }

    public function updateLocation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'nama_lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('nama_lokasi', $data)) {
            $data['nama_lokasi'] = filled($data['nama_lokasi'])
                ? trim($data['nama_lokasi'])
                : null;
        }

        $request->user()->update($data);

        return response()->json([
            'message' => 'Lokasi diperbarui.',
            'locationName' => $request->user()->fresh()->nama_lokasi,
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        abort_unless($request->user()->isRole('petani', 'pembeli'), 403, 'Akses perubahan password tidak tersedia.');

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.letters' => 'Password baru harus mengandung huruf.',
            'password.numbers' => 'Password baru harus mengandung angka.',
        ]);

        $request->user()->forceFill([
            'password' => $data['password'],
            'password_updated_at' => now(),
            'remember_token' => Str::random(60),
        ])->save();
        $request->session()->regenerate();

        return response()->json(['message' => 'Password akun berhasil diperbarui.']);
    }
}
