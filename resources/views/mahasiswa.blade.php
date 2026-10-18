<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
</head>
<body>

    <h1>Data Mahasiswa</h1>

    <p>Nama: {{ $nama }}</p>

    @if($status == 'aktif')
        <p>Status: Mahasiswa Aktif</p>
    @elseif($status == 'cuti')
        <p>Status: Mahasiswa Cuti</p>
    @else
        <p>Status: Tidak Diketahui</p>
    @endif

</body>
</html>