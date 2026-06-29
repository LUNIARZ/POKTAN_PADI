<?php

namespace App\Http\Controllers;

use App\Models\JadwalTanam;
use App\Models\ProgresTahapTanam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalTanamController extends Controller
{
    private const MINIMUM_STAGE_DAYS = 10;

    private const STAGES = [
        ['name' => 'pembibitan', 'label' => 'Pembibitan', 'range' => '0-21 Hari', 'start' => 0, 'end' => 21],
        ['name' => 'penanaman', 'label' => 'Penanaman', 'range' => '15-21 Hari Setelah Semai', 'start' => 15, 'end' => 21],
        ['name' => 'perawatan_tanaman', 'label' => 'Perawatan Tanaman', 'range' => '0-90 Hari Setelah Tanam', 'start' => 21, 'end' => 111],
        ['name' => 'panen', 'label' => 'Panen', 'range' => '100-115 Hari Setelah Tanam', 'start' => 121, 'end' => 136],
    ];

    public function show(Request $request): JsonResponse
    {
        return response()->json($this->payload($this->current($request)));
    }

    public function updateSeedDate(Request $request): JsonResponse
    {
        $data = $request->validate(['tanggal_semai' => ['required', 'date']]);
        $schedule = $this->current($request);
        DB::transaction(function () use ($schedule, $data) {
            $seed = Carbon::parse($data['tanggal_semai']);
            $schedule->update([
                'tanggal_semai' => $seed,
                'perkiraan_tanggal_tanam' => $seed->copy()->addDays(21),
                'perkiraan_tanggal_panen' => $seed->copy()->addDays(136),
            ]);
            foreach (self::STAGES as $index => $stage) {
                $updates = [
                    'tanggal_mulai_target' => $seed->copy()->addDays($stage['start']),
                    'tanggal_selesai_target' => $seed->copy()->addDays($stage['end']),
                ];

                if ($index === 0 && $schedule->status === 'aktif' && $schedule->jumlah_tahap_selesai === 0) {
                    $updates['tanggal_mulai_aktual'] = $seed;
                }

                ProgresTahapTanam::where('id_jadwal_tanam', $schedule->id)
                    ->where('urutan', $index + 1)
                    ->update($updates);
            }
        });

        return response()->json($this->payload($schedule->fresh('tahapan')));
    }

    public function start(Request $request): JsonResponse
    {
        $schedule = $this->current($request);
        abort_unless($schedule->status === 'rencana', 422, 'Jadwal tanam sudah dimulai atau telah selesai.');

        DB::transaction(function () use ($schedule) {
            $seed = today();
            $schedule->update([
                'tanggal_semai' => $seed,
                'perkiraan_tanggal_tanam' => $seed->copy()->addDays(21),
                'perkiraan_tanggal_panen' => $seed->copy()->addDays(136),
                'status' => 'aktif',
                'tahap_aktif' => 'pembibitan',
                'dimulai_pada' => now(),
                'diselesaikan_pada' => null,
            ]);

            ProgresTahapTanam::where('id_jadwal_tanam', $schedule->id)->update([
                'status' => 'menunggu',
                'tanggal_mulai_aktual' => null,
                'tanggal_selesai_aktual' => null,
            ]);

            foreach (self::STAGES as $index => $stage) {
                ProgresTahapTanam::where('id_jadwal_tanam', $schedule->id)
                    ->where('urutan', $index + 1)
                    ->update([
                        'tanggal_mulai_target' => $seed->copy()->addDays($stage['start']),
                        'tanggal_selesai_target' => $seed->copy()->addDays($stage['end']),
                        'tanggal_mulai_aktual' => $index === 0 ? $seed : null,
                        'status' => $index === 0 ? 'aktif' : 'menunggu',
                    ]);
            }
        });

        return response()->json($this->payload($schedule->fresh('tahapan')));
    }

    public function completeStage(Request $request, ProgresTahapTanam $tahap): JsonResponse
    {
        $schedule = $this->current($request);
        abort_unless($schedule->status === 'aktif', 422, 'Klik Mulai Proses Tanam sebelum menyelesaikan tahap.');
        abort_unless($tahap->id_jadwal_tanam === $schedule->id, 404, 'Tahap jadwal tanam tidak ditemukan.');
        abort_unless($tahap->status === 'aktif', 422, 'Hanya tahap aktif yang dapat diselesaikan.');

        $minimumProgress = $this->minimumProgress($schedule, $tahap);
        abort_if(
            $minimumProgress['kurangMinimum'] && ! $request->boolean('konfirmasi_peringatan'),
            422,
            sprintf(
                'Tahap %s baru berjalan %d hari. Konfirmasi diperlukan untuk menyelesaikannya sebelum minimal %d hari.',
                $this->stageLabel($tahap->nama_tahap),
                $minimumProgress['hariBerjalan'],
                self::MINIMUM_STAGE_DAYS
            )
        );

        DB::transaction(function () use ($schedule, $tahap) {
            $tahap->update(['status' => 'selesai', 'tanggal_selesai_aktual' => today()]);
            $completed = ProgresTahapTanam::where('id_jadwal_tanam', $schedule->id)->where('status', 'selesai')->count();
            $next = ProgresTahapTanam::where('id_jadwal_tanam', $schedule->id)->where('urutan', $completed + 1)->first();
            $next?->update(['status' => 'aktif', 'tanggal_mulai_aktual' => today()]);
            $schedule->update([
                'jumlah_tahap_selesai' => $completed,
                'persentase_progres' => $completed * 25,
                'tahap_aktif' => $next?->nama_tahap ?? 'selesai',
                'status' => $completed === 4 ? 'selesai' : 'aktif',
                'diselesaikan_pada' => $completed === 4 ? now() : null,
            ]);
        });

        return response()->json($this->payload($schedule->fresh('tahapan')));
    }

    public function reset(Request $request): JsonResponse
    {
        $schedule = $this->current($request);
        DB::transaction(function () use ($schedule) {
            $schedule->update([
                'tahap_aktif' => 'pembibitan',
                'jumlah_tahap_selesai' => 0,
                'persentase_progres' => 0,
                'status' => 'rencana',
                'dimulai_pada' => null,
                'diselesaikan_pada' => null,
            ]);
            foreach ($schedule->tahapan as $stage) {
                $stage->update([
                    'status' => 'menunggu',
                    'tanggal_mulai_aktual' => null,
                    'tanggal_selesai_aktual' => null,
                ]);
            }
        });

        return response()->json($this->payload($schedule->fresh('tahapan')));
    }

    private function current(Request $request): JadwalTanam
    {
        $schedule = JadwalTanam::with('tahapan')->where('id_petani', $request->user()->id)->latest()->first();
        if ($schedule) {
            return $schedule;
        }

        return DB::transaction(function () use ($request) {
            $seed = today();
            $schedule = JadwalTanam::create([
                'id_petani' => $request->user()->id,
                'tanggal_semai' => $seed,
                'perkiraan_tanggal_tanam' => $seed->copy()->addDays(21),
                'perkiraan_tanggal_panen' => $seed->copy()->addDays(136),
                'tahap_aktif' => 'pembibitan',
                'jumlah_tahap_selesai' => 0,
                'persentase_progres' => 0,
                'status' => 'rencana',
                'dimulai_pada' => null,
                'diselesaikan_pada' => null,
            ]);
            foreach (self::STAGES as $index => $stage) {
                ProgresTahapTanam::create([
                    'id_jadwal_tanam' => $schedule->id,
                    'urutan' => $index + 1,
                    'nama_tahap' => $stage['name'],
                    'rentang_hari' => $stage['range'],
                    'tanggal_mulai_target' => $seed->copy()->addDays($stage['start']),
                    'tanggal_selesai_target' => $seed->copy()->addDays($stage['end']),
                    'tanggal_mulai_aktual' => null,
                    'status' => 'menunggu',
                ]);
            }

            return $schedule->load('tahapan');
        });
    }

    private function payload(JadwalTanam $schedule): array
    {
        return [
            'id' => $schedule->id,
            'tanggalSemai' => optional($schedule->tanggal_semai)->format('Y-m-d'),
            'jumlahSelesai' => $schedule->jumlah_tahap_selesai,
            'persentase' => (int) $schedule->persentase_progres,
            'tahapAktif' => $schedule->tahap_aktif,
            'status' => $schedule->status,
            'tahapan' => $schedule->tahapan->map(function ($stage) use ($schedule) {
                $minimumProgress = $this->minimumProgress($schedule, $stage);

                return [
                    'id' => $stage->id,
                    'urutan' => $stage->urutan,
                    'nama' => $stage->nama_tahap,
                    'rentang' => $stage->rentang_hari,
                    'mulaiTarget' => optional($stage->tanggal_mulai_target)->format('Y-m-d'),
                    'selesaiTarget' => optional($stage->tanggal_selesai_target)->format('Y-m-d'),
                    'mulaiAktual' => optional($stage->tanggal_mulai_aktual)->format('Y-m-d'),
                    'selesaiAktual' => optional($stage->tanggal_selesai_aktual)->format('Y-m-d'),
                    'status' => $stage->status,
                    ...$minimumProgress,
                ];
            }),
        ];
    }

    private function minimumProgress(JadwalTanam $schedule, ProgresTahapTanam $stage): array
    {
        $requiresMinimum = in_array($stage->nama_tahap, ['pembibitan', 'penanaman'], true);
        $startedAt = $stage->tanggal_mulai_aktual;

        if (! $startedAt && $schedule->status === 'aktif' && $stage->nama_tahap === 'pembibitan') {
            $startedAt = $schedule->tanggal_semai;
        }

        $hariBerjalan = $startedAt
            ? max(0, (int) $startedAt->copy()->startOfDay()->diffInDays(today(), false))
            : 0;
        $kurangMinimum = $requiresMinimum
            && $schedule->status === 'aktif'
            && $stage->status === 'aktif'
            && $hariBerjalan < self::MINIMUM_STAGE_DAYS;

        return [
            'minimumHari' => $requiresMinimum ? self::MINIMUM_STAGE_DAYS : null,
            'hariBerjalan' => $requiresMinimum ? $hariBerjalan : null,
            'sisaHari' => $kurangMinimum ? self::MINIMUM_STAGE_DAYS - $hariBerjalan : 0,
            'kurangMinimum' => $kurangMinimum,
            'dapatDiselesaikan' => $stage->status === 'aktif' && ! $kurangMinimum,
        ];
    }

    private function stageLabel(string $stage): string
    {
        return collect(self::STAGES)->firstWhere('name', $stage)['label'] ?? ucfirst($stage);
    }
}
