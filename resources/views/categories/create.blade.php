@extends('layouts.dashboard')

@section('title')
    Create Category
@endsection

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Create Category</h1>
    <a href="{{ route('categories.index') }}" class="link-secondary">← Kembali</a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Periksa kembali input berikut:</div>
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data"
        class="needs-validation" novalidate>
    @csrf

    <div class="row g-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name') }}" placeholder="Enter category name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

     

      <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="4"
                  placeholder="Enter category description">{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-8">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror"
               id="image" name="image" accept="image/*">
        <div class="form-text">Opsional. Maks 2MB, format gambar.</div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <img id="imagePreview" class="img-fluid object-fit-cover img-thumbnail w-100 d-none" style="max-height: 200px;" alt="Preview">
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-primary px-4">Create</button>
      <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>

  <div class="alert alert-info mt-4" role="alert">
    <div class="d-flex gap-2 align-items-start">
      <i class="bi bi-info-circle-fill"></i>
      <div>
        <div class="fw-semibold mb-1">Tips membuat kategori</div>
        <ol class="mb-0 ps-3">
          <li>Nama kategori harus unik.</li>
          <li>Deskripsi opsional.</li>
          <li>Gambar opsional; pastikan tipe gambar dan &lt; 2MB.</li>
        </ol>
      </div>
    </div>
  </div>
</div>

{{-- Scripts: auto-slug + image preview (konsisten dengan form Product) --}}
<script>


  // Preview gambar
  const imgInput = document.getElementById('image');
  const preview  = document.getElementById('imagePreview');
  imgInput?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) { preview.classList.add('d-none'); preview.src = ''; return; }
    const reader = new FileReader();
    reader.onload = ev => { preview.src = ev.target.result; preview.classList.remove('d-none'); };
    reader.readAsDataURL(file);
  });
</script>
@endsection
