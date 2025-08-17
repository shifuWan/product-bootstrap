@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Category</h1>
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

  <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data"
        class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name" name="name"
               value="{{ old('name', $category->name) }}"
               placeholder="Enter category name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="4"
                  placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label for="is_active" class="form-label">Status</label>
        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
          <option value="1" @selected(old('is_active', (int)$category->is_active) == 1)>Active</option>
          <option value="0" @selected(old('is_active', (int)$category->is_active) == 0)>Inactive</option>
        </select>
        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
          <label class="form-check-label" for="remove_image">Hapus gambar saat update</label>
        </div>
      </div>

      <div class="col-md-8">
        <label for="image" class="form-label">Image</label>
        <input type="file"
               class="form-control @error('image') is-invalid @enderror"
               id="image" name="image" accept="image/*">
        <div class="form-text">Opsional. Maks 2MB, format gambar.</div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Preview</label>
        <img id="imagePreview"
             src="{{ $category->image }}"
             class="img-fluid img-thumbnail w-100 {{ $category->image ? '' : 'd-none' }}"
             alt="Preview">
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-primary px-4">Update</button>
      <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>

  <div class="alert alert-info mt-4" role="alert">
    <div class="d-flex gap-2 align-items-start">
      <i class="bi bi-info-circle-fill"></i>
      <div>
        <div class="fw-semibold mb-1">Tips mengedit kategori</div>
        <ol class="mb-0 ps-3">
          <li>Nama kategori harus unik.</li>
          <li>Deskripsi opsional.</li>
          <li>Gambar opsional; pastikan tipe gambar dan &lt; 2MB.</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<script>
  const imgInput = document.getElementById('image');
  const preview  = document.getElementById('imagePreview');
  const removeCb = document.getElementById('remove_image');

  imgInput?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) { if (!preview.src) preview.classList.add('d-none'); return; }
    const reader = new FileReader();
    reader.onload = ev => { preview.src = ev.target.result; preview.classList.remove('d-none'); };
    reader.readAsDataURL(file);
    if (removeCb) removeCb.checked = false;
  });

  removeCb?.addEventListener('change', (e) => {
    if (e.target.checked) {
      if (preview) { preview.classList.add('d-none'); preview.src = ''; }
      if (imgInput) imgInput.value = '';
    }
  });
</script>
@endsection
