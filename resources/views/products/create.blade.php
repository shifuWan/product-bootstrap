@extends('layouts.dashboard')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Create Product</h1>
            <a href="{{ route('products.index') }}" class="link-secondary">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('products.store') }}" id="productForm" class="needs-validation" novalidate>
            @csrf

            {{-- Basic Info --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Contoh: Laptop Pro 14" required>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror"
                            placeholder="0.00" required>
                    </div>
                    @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                        placeholder="Deskripsi singkat...">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1"
                            @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>


            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection