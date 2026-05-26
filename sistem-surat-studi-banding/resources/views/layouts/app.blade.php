<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Manajemen Surat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --bg-1: #f8fafc;
            --bg-2: #e2e8f0;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --brand-700: #1d4ed8;
            --brand-800: #1e40af;
            --card-radius: 20px;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.14), transparent 28%),
                radial-gradient(circle at top right, rgba(234, 179, 8, 0.12), transparent 22%),
                linear-gradient(180deg, var(--bg-1) 0%, var(--bg-2) 100%);
            min-height: 100vh;
            color: var(--ink-900);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: white;
            position: sticky;
            top: 0;
        }

        .sidebar h4 {
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .nav-link {
            color: #cfd8dc;
            margin-bottom: 5px;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(2px);
        }

        .content {
            padding: 24px;
        }

        .page-shell {
            max-width: 1440px;
        }

        .page-title {
            color: var(--ink-900);
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .content-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: var(--card-radius);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .page-intro {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.95) 0%, rgba(30, 64, 175, 0.95) 100%);
            color: #fff;
            border-radius: 24px;
            padding: 1.25rem 1.4rem;
            box-shadow: 0 16px 28px rgba(29, 78, 216, 0.22);
        }

        .page-intro .small {
            color: rgba(255, 255, 255, 0.82) !important;
        }

        .btn-dark {
            background-color: var(--ink-900);
            border-color: var(--ink-900);
        }

        .btn-dark:hover {
            background-color: #111827;
            border-color: #111827;
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="container-fluid page-shell">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar p-3">
                <h4 class="text-center mb-4">Sistem Surat</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>

                    @if (Auth::user() && Auth::user()->role === 'sekretariat')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sekretariat.surat-masuk.*') ? 'active' : '' }}"
                                href="{{ route('sekretariat.surat-masuk.index') }}">
                                <i class="fas fa-envelope me-2"></i> Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sekretariat.disposisi.tracking') ? 'active' : '' }}"
                                href="{{ route('sekretariat.disposisi.tracking') }}">
                                <i class="fas fa-file-alt me-2"></i> Disposisi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sekretariat.surat-keluar.*') ? 'active' : '' }}"
                                href="{{ route('sekretariat.surat-keluar.index') }}">
                                <i class="fas fa-paper-plane me-2"></i> Surat Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sekretariat.buku-tamu.*') ? 'active' : '' }}"
                                href="{{ route('sekretariat.buku-tamu.index') }}">
                                <i class="fas fa-qrcode me-2"></i> Buku Tamu (QR)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}"
                                href="{{ route('archives.index') }}">
                                <i class="fas fa-archive me-2"></i> Arsip Digital
                            </a>
                        </li>
                    @elseif(Auth::user() && Auth::user()->role === 'pimpinan')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pimpinan.disposisi.*') ? 'active' : '' }}"
                                href="{{ route('pimpinan.disposisi.index') }}">
                                <i class="fas fa-file-signature me-2"></i> Disposisi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}"
                                href="{{ route('archives.index') }}">
                                <i class="fas fa-archive me-2"></i> Arsip Digital
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                                href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i> Profil
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}"
                                href="{{ route('archives.index') }}">
                                <i class="fas fa-archive me-2"></i> Arsip Digital
                            </a>
                        </li>
                    @endif

                    <hr>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

            <main class="col-md-10 ms-sm-auto col-lg-10 content">
                <div
                    class="page-intro d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center gap-3 mb-4">
                    <div>
                        @isset($header)
                            {{ $header }}
                        @else
                            <h3 class="page-title text-white mb-1">@yield('title')</h3>
                        @endisset
                        <p class="mb-0 small">Kelola surat, disposisi, buku tamu, dan arsip digital dari satu tempat.
                        </p>
                    </div>
                    <div class="user-info text-white text-end">
                        <div class="small opacity-75">Pengguna aktif</div>
                        <strong>{{ Auth::user()->name }}</strong>
                    </div>
                </div>

                <div class="content-card bg-white">
                    <div class="p-3 p-md-4">
                        {{ $slot ?? '' }}
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
