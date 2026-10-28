
@php
    $user = Auth::user();
    $isAdmin = $user && $user->role === 'admin';

    $menus = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'home',
            'roles' => ['admin', 'karyawan'],
        ],
        [
            'label' => 'Kasir',
            'route' => 'sales.create',
            'icon' => 'cart',
            'roles' => ['admin', 'karyawan'],
        ],
        [
            'label' => 'Transaksi',
            'route' => 'sales.index',
            'icon' => 'receipt',
            'roles' => ['admin', 'karyawan'],
        ],
        [
            'label' => 'Laporan',
            'route' => 'sales.report',
            'icon' => 'chart',
            'roles' => ['admin', 'karyawan'],
        ],
        [
            'label' => 'Statistik',
            'route' => 'statistics.index',
            'icon' => 'chart',
            'roles' => ['admin', 'karyawan'],
        ],
    ];

    if ($isAdmin) {
        $menus = array_merge($menus, [
            [
                'label' => 'Produk',
                'route' => 'products.index',
                'icon' => 'box',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Bahan Baku',
                'route' => 'ingredients.index',
                'icon' => 'package',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Stok Masuk & Kedaluwarsa',
                'route' => 'stock-batches.index',
                'icon' => 'calendar',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Pengguna',
                'route' => 'users.index',
                'icon' => 'users',
                'roles' => ['admin'],
            ],
        ]);
    }
@endphp

<nav
    x-data="{ mobileMenuOpen: false, sidebarOpen: false }"
    class="wp-navigation"
>
    {{-- LAPTOP: SIDEBAR --}}
    <aside class="wp-sidebar">
        <a href="{{ route('dashboard') }}" class="wp-brand">
            <span class="wp-brand-logo">WP</span>
            <span class="wp-brand-text">
                <strong>WARUNG POJOK</strong>
                <small>Point of Sale</small>
            </span>
        </a>

        <div class="wp-menu-label">MENU UTAMA</div>

        <div class="wp-menu-list">
            @foreach ($menus as $menu)
                @if (Route::has($menu['route']))
                    <a
                        href="{{ route($menu['route']) }}"
                        class="wp-menu-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}"
                    >
                        <span class="wp-menu-icon">
                            @switch($menu['icon'])
                                @case('home')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-6v-7h-4v7H4a1 1 0 0 1-1-1z"/>
                                    </svg>
                                    @break
                                @case('cart')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l2.5 12h10L21 7H6M9 20h.01M17 20h.01"/>
                                    </svg>
                                    @break
                                @case('receipt')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6M9 16h3"/>
                                    </svg>
                                    @break
                                @case('chart')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10m5 10V4m5 16v-7m5 7V7M2 20h20"/>
                                    </svg>
                                    @break
                                @case('box')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 9 5-9 5-9-5zM3 8v9l9 5 9-5V8M12 13v9"/>
                                    </svg>
                                    @break
                                @case('package')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16v14H4zM8 7V4h8v3M8 12h8M8 16h5"/>
                                    </svg>
                                    @break
                                @case('calendar')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 10h18M8 14h3M8 17h6"/>
                                    </svg>
                                    @break
                                @case('users')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="9" cy="8" r="4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 21v-2a7 7 0 0 1 14 0v2m1-16a4 4 0 0 1 0 7m2 3a5 5 0 0 1 3 4v2"/>
                                    </svg>
                                    @break
                            @endswitch
                        </span>

                        <span>{{ $menu['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="wp-sidebar-footer">
            <div class="wp-user-info">
                <div class="wp-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="wp-user-text">
                    <strong>{{ $user->name ?? 'Pengguna' }}</strong>
                    <small>{{ ucfirst($user->role ?? 'user') }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="wp-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- HEADER HP DAN TABLET --}}
    <header class="wp-mobile-header">
        <a href="{{ route('dashboard') }}" class="wp-brand">
            <span class="wp-brand-logo">WP</span>
            <span class="wp-brand-text">
                <strong>WARUNG POJOK</strong>
                <small>Point of Sale</small>
            </span>
        </a>

        <button
            type="button"
            class="wp-mobile-toggle"
            @click="mobileMenuOpen = !mobileMenuOpen"
            :aria-expanded="mobileMenuOpen.toString()"
            aria-label="Buka menu navigasi"
        >
            <svg x-show="!mobileMenuOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="mobileMenuOpen" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/>
            </svg>
        </button>
    </header>

    {{-- MENU DROP-DOWN HP DAN TABLET --}}
    <div
        class="wp-mobile-menu"
        x-show="mobileMenuOpen"
        x-cloak
        @click.outside="mobileMenuOpen = false"
        @keydown.escape.window="mobileMenuOpen = false"
    >
        <div class="wp-mobile-user">
            <div class="wp-avatar">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="wp-user-text">
                <strong>{{ $user->name ?? 'Pengguna' }}</strong>
                <small>{{ ucfirst($user->role ?? 'user') }}</small>
            </div>
        </div>

        @foreach ($menus as $menu)
            @if (Route::has($menu['route']))
                <a
                    href="{{ route($menu['route']) }}"
                    class="wp-menu-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}"
                    @click="mobileMenuOpen = false"
                >
                    <span>{{ $menu['label'] }}</span>
                    <span aria-hidden="true">›</span>
                </a>
            @endif
        @endforeach

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="wp-logout">
                <span>Keluar dari akun</span>
            </button>
        </form>
    </div>
</nav>

<style>
    [x-cloak] {
        display: none !important;
    }

    .wp-navigation {
        font-family: inherit;
    }

    .wp-sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 40;
        display: flex;
        flex-direction: column;
        width: 250px;
        padding: 22px 16px;
        overflow-y: auto;
        background: #171717;
        color: #fff;
        border-right: 1px solid #303030;
    }

    .wp-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 4px 8px 24px;
        color: inherit;
        text-decoration: none;
    }

    .wp-brand-logo {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: #f97316;
        color: #fff;
        font-size: 16px;
        font-weight: 800;
    }

    .wp-brand-text,
    .wp-user-text {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 4px;
    }

    .wp-brand-text strong {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .04em;
    }

    .wp-brand-text small,
    .wp-user-text small {
        color: #a3a3a3;
        font-size: 12px;
    }

    .wp-menu-label {
        padding: 8px 10px 12px;
        color: #a3a3a3;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .13em;
    }

    .wp-menu-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .wp-menu-link {
        display: flex;
        min-height: 44px;
        align-items: center;
        gap: 12px;
        padding: 11px 12px;
        border-radius: 10px;
        color: #d4d4d4;
        font-size: 14px;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .wp-menu-link:hover,
    .wp-menu-link.active {
        background: #f97316;
        color: #fff;
    }

    .wp-menu-icon,
    .wp-menu-icon svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .wp-sidebar-footer {
        margin-top: auto;
        padding-top: 22px;
    }

    .wp-user-info,
    .wp-mobile-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 6px;
    }

    .wp-avatar {
        display: flex;
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #404040;
        color: #fff;
        font-weight: 800;
    }

    .wp-user-text strong {
        overflow: hidden;
        color: #fff;
        font-size: 13px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .wp-logout {
        display: flex;
        width: 100%;
        min-height: 42px;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #fca5a5;
        cursor: pointer;
        font: inherit;
        font-size: 14px;
        text-align: left;
    }

    .wp-logout:hover {
        background: #3f2020;
    }

    .wp-logout svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
    }

    .wp-mobile-header,
    .wp-mobile-menu {
        display: none;
    }

    @media (min-width: 1024px) {
        body > div.min-h-screen {
            padding-left: 250px;
        }
    }

    @media (max-width: 1023px) {
        .wp-sidebar {
            display: none;
        }

        .wp-mobile-header {
            position: relative;
            z-index: 50;
            display: flex;
            min-height: 70px;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
            border-bottom: 1px solid #e5e5e5;
            background: #171717;
            color: #fff;
        }

        .wp-mobile-header .wp-brand {
            padding: 0;
        }

        .wp-mobile-toggle {
            display: flex;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 1px solid #404040;
            border-radius: 10px;
            background: #262626;
            color: #fff;
            cursor: pointer;
        }

        .wp-mobile-toggle svg {
            width: 23px;
            height: 23px;
        }

        .wp-mobile-menu {
            position: absolute;
            top: 70px;
            right: 0;
            left: 0;
            z-index: 49;
            display: block;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            padding: 12px 16px 18px;
            border-bottom: 1px solid #404040;
            background: #171717;
            box-shadow: 0 12px 24px rgba(0, 0, 0, .18);
        }

        .wp-mobile-menu .wp-mobile-user {
            margin-bottom: 8px;
            border-bottom: 1px solid #404040;
        }

        .wp-mobile-menu .wp-menu-link {
            justify-content: space-between;
        }

        .wp-mobile-menu .wp-logout {
            margin-top: 8px;
        }
    }

    @media (max-width: 380px) {
        .wp-mobile-header {
            padding-right: 10px;
            padding-left: 10px;
        }

        .wp-brand-text strong {
            font-size: 11px;
        }

        .wp-brand-text small {
            font-size: 11px;
        }

        .wp-brand-logo {
            width: 38px;
            height: 38px;
        }
    }
</style>
