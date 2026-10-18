<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        @yield('title') - SIAKAD
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .navbar {
            background: #1e3a8a;
            color: white;
            padding: 18px 30px;
            font-size: 20px;
            font-weight: bold;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            width: 220px;
            background: white;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            text-decoration: none;
            color: #333;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #eef2ff;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>

<body>

    @include('components.navbar')

    <div class="container">

        @include('components.sidebar')

        <main class="content">

            @yield('content')

        </main>

    </div>

</body>

</html>