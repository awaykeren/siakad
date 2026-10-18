
<aside class="siakad-sidebar">

    <div class="mb-3">
        <h6 class="text-muted text-uppercase small fw-bold">
            Menu Utama
        </h6>
    </div>

    <nav class="nav flex-column gap-1">

        <a href="{{ url('/dashboard') }}"
           class="nav-link text-dark rounded">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
        </a>

        <a href="{{ route('mahasiswa.index') }}"
           class="nav-link text-dark rounded">
            <i class="bi bi-people me-2"></i>
            Mahasiswa
        </a>

        <a href="{{ route('dosen.index') }}"
           class="nav-link text-dark rounded">
            <i class="bi bi-person-badge me-2"></i>
            Data Dosen
        </a>

        <a href="{{ route('mata-kuliah.index') }}"
           class="nav-link text-dark rounded">
            <i class="bi bi-book me-2"></i>
            Mata Kuliah
        </a>

    </nav>

</aside>
