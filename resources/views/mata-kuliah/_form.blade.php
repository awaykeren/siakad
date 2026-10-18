<div class="mb-3">
    <label for="kode" class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
    <input type="text" name="kode" id="kode" value="{{ old('kode', isset($mataKuliah) ? $mataKuliah->kode : '') }}" class="form-control @error('kode') is-invalid @enderror" placeholder="Contoh: IF101">
    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="nama" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
    <input type="text" name="nama" id="nama" value="{{ old('nama', isset($mataKuliah) ? $mataKuliah->nama : '') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama mata kuliah">
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="sks" class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
    <input type="number" name="sks" id="sks" min="1" max="255" value="{{ old('sks', isset($mataKuliah) ? $mataKuliah->sks : '') }}" class="form-control @error('sks') is-invalid @enderror" placeholder="Masukkan jumlah SKS">
    @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="dosen_id" class="form-label">Dosen Pengampu <span class="text-danger">*</span></label>
    <select name="dosen_id" id="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror">
        <option value="">Pilih dosen</option>
        @foreach ($dosens as $dosen)
            <option value="{{ $dosen->id }}" @selected((string) old('dosen_id', isset($mataKuliah) ? $mataKuliah->dosen_id : '') === (string) $dosen->id)>
                {{ $dosen->nama }} ({{ $dosen->nidn }})
            </option>
        @endforeach
    </select>
    @error('dosen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Kembali</a>
</div>
