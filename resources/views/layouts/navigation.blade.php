
@php
    $user = Auth::user();

    $menuGroups = [
        [
            'label' => 'MENU UTAMA',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['dashboard'], 'icon' => 'home', 'roles' => ['admin', 'karyawan']],
                ['label' => 'Kasir', 'route' => 'sales.create', 'active' => ['sales.create'], 'icon' => 'cart', 'roles' => ['admin', 'karyawan']],
                ['label' => 'Riwayat Penjualan', 'route' => 'sales.index', 'active' => ['sales.index', 'sales.show', 'sales.receipt'], 'icon' => 'receipt', 'roles' => ['admin', 'karyawan']],
                ['label' => 'Pesanan QR', 'route' => 'orders.index', 'active' => ['orders.index', 'orders.show'], 'icon' => 'clipboard', 'roles' => ['admin', 'karyawan']],
            ],
        ],
        [
            'label' => 'LAPORAN',
            'items' => [
                ['label' => 'Laporan Penjualan', 'route' => 'sales.report', 'active' => ['sales.report'], 'icon' => 'chart', 'roles' => ['admin', 'karyawan']],
                ['label' => 'Statistik', 'route' => 'statistics.index', 'active' => ['statistics.index'], 'icon' => 'activity', 'roles' => ['admin', 'karyawan']],
            ],
        ],
        [
            'label' => 'MANAJEMEN',
            'items' => [
                ['label' => 'Produk', 'route' => 'products.index', 'active' => ['products.*'], 'icon' => 'box', 'roles' => ['admin']],
                ['label' => 'Bahan Baku', 'route' => 'ingredients.index', 'active' => ['ingredients.*'], 'icon' => 'package', 'roles' => ['admin']],
                ['label' => 'Stok Masuk & Kedaluwarsa', 'route' => 'stock-batches.index', 'active' => ['stock-batches.*'], 'icon' => 'calendar', 'roles' => ['admin']],
                ['label' => 'Manajemen Meja', 'route' => 'dining-tables.index', 'active' => ['dining-tables.*'], 'icon' => 'table', 'roles' => ['admin']],
                ['label' => 'Pengguna', 'route' => 'users.index', 'active' => ['users.*'], 'icon' => 'users', 'roles' => ['admin']],
                ['label' => 'Aktivitas Login', 'route' => 'login-activities.index', 'active' => ['login-activities.*'], 'icon' => 'clock', 'roles' => ['admin']],
            ],
        ],
    ];
@endphp

<div x-data="{ sidebarOpen: false }">

    {{-- HEADER MOBILE --}}
    <div class="wp-mobile-header">
        <button type="button" class="wp-menu-toggle"
                @click="sidebarOpen = !sidebarOpen">
            <svg x-show="!sidebarOpen" width="22" height="22"
                 viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>

            <svg x-show="sidebarOpen" x-cloak width="22" height="22"
                 viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="m6 6 12 12M18 6 6 18"/>
            </svg>
        </button>

        <a href="{{ route('dashboard') }}" class="wp-mobile-brand">
            <span class="wp-brand-mark">W</span>
            WARUNG POJOK
        </a>

        <div class="wp-mobile-avatar">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>
    </div>

    {{-- OVERLAY --}}
    <div class="wp-sidebar-overlay"
         x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false">
    </div>

    {{-- SIDEBAR --}}
    <aside class="wp-sidebar"
           :class="{ 'wp-sidebar-open': sidebarOpen }">

        {{-- BRAND --}}
        <a href="{{ route('dashboard') }}" class="wp-brand">
            <span class="wp-brand-mark">W</span>
            <span class="wp-brand-text">
                <strong>WARUNG POJOK</strong>
                <small>POINT OF SALE SYSTEM</small>
            </span>
        </a>

        {{-- USER PROFILE --}}
        <div class="wp-user-card">
            <div class="wp-user-avatar">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>

            <div class="wp-user-info">
                <strong>{{ $user->name ?? 'Pengguna' }}</strong>
                <span>{{ ucfirst($user->role ?? 'User') }}</span>
            </div>

            <span class="wp-online-dot"></span>
        </div>

        {{-- MENU --}}
        @foreach ($menuGroups as $group)

            @php
                $visibleItems = collect($group['items'])->filter(
                    fn ($item) =>
                        in_array($user?->role, $item['roles'])
                        && Route::has($item['route'])
                );
            @endphp

            @if ($visibleItems->isNotEmpty())

                <div class="wp-menu-label
                    {{ $group['label'] !== 'MENU UTAMA' ? 'wp-menu-label-spaced' : '' }}">
                    {{ $group['label'] }}
                </div>

                <div class="wp-menu">

                    @foreach ($visibleItems as $item)

                        @php
                            $isActive = request()->routeIs(...$item['active']);
                        @endphp

                        <a href="{{ route($item['route']) }}"
                           class="wp-menu-link {{ $isActive ? 'active' : '' }}"
                           @click="sidebarOpen = false">

                            <span class="wp-menu-icon">

                                @switch($item['icon'])

                                    @case('home')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m3 10 9-7 9 7"/>
                                            <path d="M5 9v11h14V9"/>
                                            <path d="M9 20v-6h6v6"/>
                                        </svg>
                                        @break

                                    @case('cart')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="20" r="1"/>
                                            <circle cx="19" cy="20" r="1"/>
                                            <path d="M2 3h2l2.5 12h12L21 7H5"/>
                                        </svg>
                                        @break

                                    @case('receipt')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 3h14v18l-3-2-4 2-4-2-3 2z"/>
                                            <path d="M9 8h6M9 12h6M9 16h3"/>
                                        </svg>
                                        @break

                                    @case('clipboard')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="5" y="4" width="14" height="17" rx="2"/>
                                            <path d="M9 4h6M9 10h6M9 14h6M9 18h4"/>
                                        </svg>
                                        @break

                                    @case('chart')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 3v18h18"/>
                                            <path d="m7 14 4-4 4 3 5-7"/>
                                        </svg>
                                        @break

                                    @case('activity')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 12h4l3-8 4 16 3-8h4"/>
                                        </svg>
                                        @break

                                    @case('box')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m12 3 9 5-9 5-9-5z"/>
                                            <path d="M3 8v9l9 5 9-5V8"/>
                                            <path d="M12 13v9"/>
                                        </svg>
                                        @break

                                    @case('package')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m12 3 9 5-9 5-9-5z"/>
                                            <path d="M3 8v9l9 5 9-5V8"/>
                                            <path d="M7.5 5.5 16.5 11"/>
                                        </svg>
                                        @break

                                    @case('calendar')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                                            <path d="M16 3v4M8 3v4M3 10h18"/>
                                        </svg>
                                        @break

                                    @case('table')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="5" width="18" height="5" rx="1"/>
                                            <path d="M5 10v10m14-10v10M3 15h18"/>
                                        </svg>
                                        @break

                                    @case('users')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="8" r="4"/>
                                            <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                                            <path d="M17 4a4 4 0 0 1 0 8M22 21v-2a7 7 0 0 0-4-6.3"/>
                                        </svg>
                                        @break

                                    @case('clock')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 7v5l3 2"/>
                                        </svg>
                                        @break

                                @endswitch

                            </span>

                            <span>{{ $item['label'] }}</span>
                        </a>

                    @endforeach

                </div>

            @endif

        @endforeach

        {{-- SIDEBAR BOTTOM --}}
        <div class="wp-sidebar-bottom">

            <div class="wp-sidebar-note">
                <span class="wp-online-dot"></span>
                <span>Sistem kasir aktif</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="wp-logout-button">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 17l5-5-5-5"/>
                        <path d="M15 12H3"/>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                    </svg>
                    <span>Keluar dari Aplikasi</span>
                </button>
            </form>

        </div>

    </aside>

</div>
