<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pimpinan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --ink-900: #0f172a;
            --ink-700: #334155;
            --sky-50: #f8fafc;
            --sky-100: #e2e8f0;
            --brand-700: #1d4ed8;
            --ok-700: #166534;
            --danger-700: #b91c1c;
            --card-radius: 18px;
        }

        body {
            background: radial-gradient(circle at 0% 0%, #dbeafe 0%, transparent 40%),
                radial-gradient(circle at 100% 0%, #fef9c3 0%, transparent 36%),
                linear-gradient(180deg, var(--sky-50) 0%, var(--sky-100) 100%);
            min-height: 100vh;
            color: var(--ink-900);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .top-shell {
            position: sticky;
            top: 0;
            z-index: 1040;
            backdrop-filter: blur(8px);
            background: rgba(248, 250, 252, 0.85);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .top-shell .brand {
            color: var(--ink-900);
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.2px;
        }

        .content-wrap {
            max-width: 860px;
            padding-top: 1rem;
            padding-bottom: 6.25rem;
        }

        .card-pimpinan,
        .flash-card {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1050;
            background: rgba(255, 255, 255, 0.96);
            border-top: 1px solid rgba(15, 23, 42, 0.1);
            backdrop-filter: blur(8px);
        }

        .bottom-nav .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            font-size: 0.78rem;
            color: var(--ink-700);
            padding: 0.55rem 0.25rem;
        }

        .bottom-nav .nav-link i {
            font-size: 1rem;
        }

        .bottom-nav .nav-link.active,
        .bottom-nav .nav-link:hover {
            color: var(--brand-700);
        }

        @media (min-width: 992px) {
            .content-wrap {
                max-width: 1040px;
                padding-bottom: 2.5rem;
            }

            .bottom-nav {
                left: auto;
                right: 1rem;
                bottom: 1rem;
                width: 240px;
                border: 1px solid rgba(15, 23, 42, 0.12);
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.15);
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <header class="top-shell">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a class="brand" href="{{ route('pimpinan.disposisi.index') }}">
                <i class="fas fa-user-tie me-2"></i>Dashboard Pimpinan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="container content-wrap">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-card" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show flash-card" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <nav class="bottom-nav">
        <div class="container">
            <div class="row g-0 text-center">
                <div class="col-4">
                    <a href="{{ route('pimpinan.disposisi.index') }}"
                        class="nav-link {{ request()->routeIs('pimpinan.disposisi.*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="#pencarian" class="nav-link">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
