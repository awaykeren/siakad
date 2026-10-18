
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - SIAKAD</title>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .siakad-navbar {
            background: #1e3a8a;
            color: white;
            padding: 18px 30px;
            font-size: 20px;
            font-weight: bold;
        }

        .siakad-layout {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .siakad-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: white;
            padding: 20px;
        }

        .siakad-sidebar a {
            display: block;
            padding: 12px;
            text-decoration: none;
            color: #333;
            margin-bottom: 5px;
            border-radius: 6px;
        }

        .siakad-sidebar a:hover {
            background: #eef2ff;
            color: #1e3a8a;
        }

        .siakad-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .siakad-layout {
                flex-direction: column;
            }

            .siakad-sidebar {
                width: 100%;
            }

            .siakad-content {
                padding: 20px 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="siakad-layout">

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Konten halaman --}}
        <main class="siakad-content">
            @yield('content')
        </main>

    </div>

    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>

</html>
