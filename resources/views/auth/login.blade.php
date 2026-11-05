
<x-guest-layout>

    <style>
        body {
            background: #ffffff !important;
            font-family: 'Figtree', sans-serif !important;
        }

        body > div,
        .min-h-screen {
            background: #ffffff !important;
        }

        .wp-login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 18px;
            background: #ffffff;
        }

        .wp-login-container {
            width: 100%;
            max-width: 430px;
        }

        .wp-login-brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .wp-login-logo {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border-radius: 18px;
            color: white;
            background: #f97316;
            font-size: 25px;
            font-weight: 900;
            box-shadow: 0 7px 20px rgba(249,115,22,.20);
        }

        .wp-login-brand h1 {
            font-size: 25px;
            font-weight: 900;
            color: #171717;
            letter-spacing: -.5px;
        }

        .wp-login-brand p {
            margin-top: 5px;
            font-size: 13px;
            color: #737373;
        }

        .wp-login-card {
            padding: 32px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,.06);
        }

        .wp-login-title {
            margin-bottom: 25px;
        }

        .wp-login-title h2 {
            font-size: 22px;
            font-weight: 800;
            color: #171717;
        }

        .wp-login-title p {
            margin-top: 6px;
            font-size: 13px;
            color: #737373;
        }

        .wp-login-group {
            margin-bottom: 20px;
        }

        .wp-login-group label {
            display: block;
            margin-bottom: 8px;
            color: #404040;
            font-size: 13px;
            font-weight: 700;
        }

        .wp-login-input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid #d4d4d4;
            border-radius: 10px;
            background: #ffffff;
            color: #171717;
            font-size: 14px;
            outline: none;
            transition: .2s;
        }

        .wp-login-input:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,.12);
        }

        .wp-login-remember {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 24px;
            color: #525252;
            font-size: 13px;
        }

        .wp-login-remember input {
            width: 16px;
            height: 16px;
            accent-color: #f97316;
        }

        .wp-login-button {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 10px;
            color: white;
            background: #f97316;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .wp-login-button:hover {
            background: #ea580c;
            transform: translateY(-1px);
        }

        .wp-login-footer {
            margin-top: 25px;
            text-align: center;
            color: #a3a3a3;
            font-size: 11px;
        }

        .wp-login-error {
            margin-top: 7px;
            color: #dc2626;
            font-size: 12px;
        }

        .wp-login-status {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 9px;
            background: #f0fdf4;
            color: #15803d;
            font-size: 13px;
        }

        @media(max-width: 480px) {
            .wp-login-card {
                padding: 24px 20px;
            }
        }
    </style>

    <div class="wp-login-wrapper">
        <div class="wp-login-container">

            {{-- BRAND --}}
            <div class="wp-login-brand">
                <div class="wp-login-logo">
                    WP
                </div>

                <h1>WARUNG POJOK</h1>

                <p>Sistem Informasi Kasir</p>
            </div>

            {{-- LOGIN CARD --}}
            <div class="wp-login-card">

                <div class="wp-login-title">
                    <h2>Selamat Datang!</h2>
                    <p>Silakan login untuk mengakses sistem kasir.</p>
                </div>

                {{-- STATUS --}}
                <x-auth-session-status
                    class="wp-login-status"
                    :status="session('status')"
                />

                {{-- FORM LOGIN --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="wp-login-group">
                        <label for="email">Alamat Email</label>

                        <input
                            id="email"
                            class="wp-login-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan alamat email"
                            required
                            autofocus
                            autocomplete="username"
                        >

                        @error('email')
                            <p class="wp-login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="wp-login-group">
                        <label for="password">Password</label>

                        <input
                            id="password"
                            class="wp-login-input"
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >

                        @error('password')
                            <p class="wp-login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="wp-login-remember">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                        >

                        <label for="remember_me">
                            Ingat saya
                        </label>
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit" class="wp-login-button">
                        MASUK KE SISTEM
                    </button>

                </form>

            </div>

            {{-- FOOTER --}}
            <div class="wp-login-footer">
                &copy; {{ date('Y') }} WARUNG POJOK
                <br>
                Sistem Informasi Kasir
            </div>

        </div>
    </div>

</x-guest-layout>
