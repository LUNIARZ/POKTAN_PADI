<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiAplikasi;
use App\Models\PenerimaNotifikasi;
use App\Models\PesananMarketplace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NotifikasiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $this->visibleNotifications($request)
            ->where(fn ($query) => $query->whereNull('diterbitkan_pada')->orWhere('diterbitkan_pada', '<=', now()))
            ->where(fn ($query) => $query->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now()))
            ->latest('diterbitkan_pada')
            ->latest('id')
            ->get();

        PenerimaNotifikasi::upsert(
            $notifications->map(fn ($item) => [
                'id_notifikasi' => $item->id,
                'id_pengguna' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->all(),
            ['id_notifikasi', 'id_pengguna'],
            ['updated_at']
        );

        $readAt = PenerimaNotifikasi::query()
            ->where('id_pengguna', $user->id)
            ->whereIn('id_notifikasi', $notifications->pluck('id'))
            ->pluck('dibaca_pada', 'id_notifikasi');

        $orders = $this->relatedOrders($notifications);
        $items = $notifications->map(function ($item) use ($readAt, $orders) {
            $publishedAt = $item->diterbitkan_pada ?? $item->created_at;
            $orderId = data_get($item->data_tambahan, 'id_pesanan_marketplace');
            $order = $orderId ? $orders->get((int) $orderId) : null;

            return [
                'id' => 'notification-'.$item->id,
                'notificationId' => $item->id,
                'kategori' => $item->kategori,
                'judul' => $item->judul,
                'pesan' => $item->pesan,
                'status' => $order?->status_pesanan,
                'orderId' => $order?->id,
                'tautan' => $item->tautan,
                'dibaca' => filled($readAt->get($item->id)),
                'tanggal' => optional($publishedAt)->format('Y-m-d'),
                'waktu' => optional($publishedAt)->format('d M Y, H:i'),
                'timestamp' => optional($publishedAt)?->toIso8601String(),
            ];
        })->values();

        return response()->json([
            'items' => $items,
            'unread' => $items->where('dibaca', false)->count(),
        ]);
    }

    public function markAsRead(Request $request, NotifikasiAplikasi $notifikasi): JsonResponse
    {
        abort_unless(
            $this->visibleNotifications($request)->whereKey($notifikasi->id)->exists(),
            404,
            'Notifikasi tidak ditemukan.'
        );

        PenerimaNotifikasi::updateOrCreate(
            ['id_notifikasi' => $notifikasi->id, 'id_pengguna' => $request->user()->id],
            ['dibaca_pada' => now()]
        );

        return response()->json(['message' => 'Notifikasi ditandai sudah dibaca.']);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $ids = $this->visibleNotifications($request)
            ->where(fn ($query) => $query->whereNull('diterbitkan_pada')->orWhere('diterbitkan_pada', '<=', now()))
            ->where(fn ($query) => $query->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now()))
            ->pluck('id');

        PenerimaNotifikasi::upsert(
            $ids->map(fn ($id) => [
                'id_notifikasi' => $id,
                'id_pengguna' => $request->user()->id,
                'dibaca_pada' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ])->all(),
            ['id_notifikasi', 'id_pengguna'],
            ['dibaca_pada', 'updated_at']
        );

        return response()->json(['message' => 'Semua notifikasi ditandai sudah dibaca.']);
    }

    private function visibleNotifications(Request $request)
    {
        $user = $request->user();

        return NotifikasiAplikasi::query()->where(function ($query) use ($user) {
            $query->whereIn('target_peran', ['semua', $user->peran])
                ->orWhere(function ($special) use ($user) {
                    $special->where('target_peran', 'khusus')
                        ->where('data_tambahan->id_pengguna', $user->id);
                });
        });
    }

    private function relatedOrders(Collection $notifications): Collection
    {
        $ids = $notifications
            ->pluck('data_tambahan')
            ->map(fn ($data) => data_get($data, 'id_pesanan_marketplace'))
            ->filter()
            ->unique()
            ->values();

        return $ids->isEmpty()
            ? collect()
            : PesananMarketplace::whereIn('id', $ids)->get()->keyBy('id');
    }
}
