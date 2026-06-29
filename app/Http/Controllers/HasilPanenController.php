<?php

namespace App\Http\Controllers;

use App\Models\HasilPanen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HasilPanenController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            HasilPanen::where('id_petani', $request->user()->id)
                ->latest('tanggal_panen')
                ->latest('id')
                ->get()
                ->map(fn ($item) => $this->harvestRow($item))
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateHarvest($request);
        $item = HasilPanen::create([
            'id_petani' => $request->user()->id,
            'jumlah_kg' => $data['jumlah'],
            'jenis_bibit' => $data['jenis_bibit'],
            'tanggal_panen' => $data['tanggal_panen'] ?? today(),
        ]);

        return response()->json([
            ...$this->harvestRow($item),
            'message' => 'Hasil panen disimpan.',
        ], 201);
    }

    public function update(Request $request, HasilPanen $hasilPanen): JsonResponse
    {
        $this->ensureOwner($request, $hasilPanen);
        $data = $this->validateHarvest($request);

        $hasilPanen->update([
            'jumlah_kg' => $data['jumlah'],
            'jenis_bibit' => $data['jenis_bibit'],
            'tanggal_panen' => $data['tanggal_panen'] ?? $hasilPanen->tanggal_panen,
        ]);

        return response()->json([
            ...$this->harvestRow($hasilPanen->fresh()),
            'message' => 'Hasil panen berhasil diperbarui.',
        ]);
    }

    public function destroy(Request $request, HasilPanen $hasilPanen): JsonResponse
    {
        $this->ensureOwner($request, $hasilPanen);
        $hasilPanen->delete();

        return response()->json(['message' => 'Hasil panen berhasil dihapus.']);
    }

    private function validateHarvest(Request $request): array
    {
        return $request->validate([
            'jumlah' => ['required', 'numeric', 'min:0.01', 'max:999999999999.99'],
            'jenis_bibit' => ['required', 'string', 'max:120'],
            'tanggal_panen' => ['nullable', 'date', 'before_or_equal:today'],
        ], [
            'jumlah.required' => 'Jumlah hasil panen wajib diisi.',
            'jumlah.numeric' => 'Jumlah hasil panen harus berupa angka.',
            'jumlah.min' => 'Jumlah hasil panen minimal 0,01 kg.',
            'jumlah.max' => 'Jumlah hasil panen terlalu besar.',
            'jenis_bibit.required' => 'Jenis bibit wajib diisi.',
            'jenis_bibit.max' => 'Jenis bibit maksimal 120 karakter.',
            'tanggal_panen.date' => 'Tanggal panen tidak valid.',
            'tanggal_panen.before_or_equal' => 'Tanggal panen tidak boleh melebihi hari ini.',
        ]);
    }

    private function ensureOwner(Request $request, HasilPanen $hasilPanen): void
    {
        abort_unless(
            $hasilPanen->id_petani === $request->user()->id,
            404,
            'Data hasil panen tidak ditemukan.'
        );
    }

    private function harvestRow(HasilPanen $item): array
    {
        return [
            'id' => $item->id,
            'hasil' => 'Hasil Panen Padi',
            'jumlah' => (float) $item->jumlah_kg,
            'jenisBibit' => $item->jenis_bibit,
            'tanggal' => optional($item->tanggal_panen)->format('Y-m-d'),
        ];
    }
}
