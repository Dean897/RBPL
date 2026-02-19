<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #0d1b2a;
            color: white;
        }

        .nav-link {
            color: #cfd8dc;
            margin-bottom: 5px;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #1b263b;
            color: white;
            border-radius: 5px;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar p-3">
                <h4 class="text-center mb-4">Sistem Surat</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('sekretariat.dashboard') }}">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
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
                        <a class="nav-link" href="#">
                            <i class="fas fa-paper-plane me-2"></i> Surat Keluar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-qrcode me-2"></i> Buku Tamu (QR)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-archive me-2"></i> Arsip Digital
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </nav>

            <main class="col-md-10 ms-sm-auto col-lg-10 content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3>@yield('title')</h3>
                    <div class="user-info">
                        <span>Halo, Sekretariat</span>
                    </div>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
