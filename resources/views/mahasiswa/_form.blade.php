<div class="mb-3">
    <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
    <input type="text" name="nim" id="nim" value="{{ old('nim', isset($mahasiswa) ? $mahasiswa->nim : '') }}" class="form-control @error('nim') is-invalid @enderror" placeholder="Masukkan NIM">
    @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="nama" class="form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
    <input type="text" name="nama" id="nama" value="{{ old('nama', isset($mahasiswa) ? $mahasiswa->nama : '') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama mahasiswa">
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
    <input type="text" name="prodi" id="prodi" value="{{ old('prodi', isset($mahasiswa) ? $mahasiswa->prodi : '') }}" class="form-control @error('prodi') is-invalid @enderror" placeholder="Masukkan program studi">
    @error('prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', isset($mahasiswa) ? $mahasiswa->email : '') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@example.com">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="nohp" class="form-label">Nomor HP</label>
    <input type="text" name="nohp" id="nohp" value="{{ old('nohp', isset($mahasiswa) ? $mahasiswa->nohp : '') }}" class="form-control @error('nohp') is-invalid @enderror" placeholder="Masukkan nomor HP">
    @error('nohp') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<hr>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
</div>
