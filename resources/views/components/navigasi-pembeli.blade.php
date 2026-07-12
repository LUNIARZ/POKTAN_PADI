@props(['aktif' => 'marketplace'])

@php
    $menuNavigasi = [
        ['kunci' => 'marketplace', 'label' => 'Pojok Belanja', 'url' => route('pembeli.marketplace')],
        ['kunci' => 'notifikasi', 'label' => 'Notifikasi', 'url' => route('pembeli.notifikasi')],
        ['kunci' => 'pesanan', 'label' => 'Pesanan', 'url' => route('pembeli.pesanan')],
        ['kunci' => 'profile', 'label' => 'Profile', 'url' => route('pembeli.profile')],
    ];
@endphp

<nav class="navigasi-bawah navigasi-pembeli" aria-label="Navigasi pembeli">
    @foreach ($menuNavigasi as $menu)
        @php
            $sedangAktif = $aktif === $menu['kunci'];
        @endphp

        <a
            @class(['item-navigasi', 'aktif' => $sedangAktif])
            href="{{ $menu['url'] }}"
            @if ($sedangAktif) aria-current="page" @endif
        >
            @switch($menu['kunci'])
                @case('marketplace')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M6 7h12l1 14H5L6 7Z" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 7a3 3 0 0 1 6 0" stroke-linecap="round"/>
                    </svg>
                    @break

                @case('notifikasi')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @break

                @case('pesanan')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M7 4h10l2 4v12H5V8l2-4Z" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 8h14" stroke-linecap="round"/>
                        <path d="M9 12h6" stroke-linecap="round"/>
                        <path d="M9 16h4" stroke-linecap="round"/>
                    </svg>
                    @break

                @case('profile')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21a8 8 0 0 1 16 0" stroke-linecap="round"/>
                    </svg>
                    @break
            @endswitch

            <span>{{ $menu['label'] }}</span>
        </a>
    @endforeach
</nav>
