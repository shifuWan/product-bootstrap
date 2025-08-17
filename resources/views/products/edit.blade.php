@extends('layouts.dashboard')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Product</h1>
            <a href="{{ route('products.index') }}" class="link-secondary">← Kembali</a>
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

        <form method="POST" action="{{ route('products.update', $product->id) }}" id="productForm" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ $product->name }}" class="form-control"
                        placeholder="Contoh: Laptop Pro 14" required>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected($product->category_id == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control"
                            placeholder="0.00" required>
                    </div>
                    @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control"
                        placeholder="Deskripsi singkat...">{{ $product->description }}</textarea>
                    @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            @checked($product->is_active)>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>

            {{-- Variants --}}
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Variants</span>
                    <button type="button" id="addVariant" class="btn btn-outline-secondary btn-sm">+ Tambah Variant</button>
                </div>
                <div class="card-body">
                    <div id="variants" class="vstack gap-3">
                        
                        <template id="variantRowTpl">
                            <div class="row g-2 align-items-end variant-row">
                                <div class="col-md-3">
                                    <label class="form-label small">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="variants[IDX][sku]" class="form-control"
                                        placeholder="SKU-XXXXXX">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Nama Variant <span class="text-danger">*</span></label>
                                    <input type="text" name="variants[IDX][variant]" class="form-control"
                                        placeholder="Color: Red / Size: L">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="variants[IDX][price]" class="form-control"
                                        placeholder="0.00">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Stock <span class="text-danger">*</span></label>
                                    <input type="number" name="variants[IDX][stock]" class="form-control" value="0" min="0">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="button" class="btn btn-outline-danger remove-variant">Hapus</button>
                                </div>
                            </div>
                        </template>

                        @if($product->variants->count() > 0)
                            @foreach($product->variants as $i => $v)
                                <div class="row g-2 align-items-end variant-row">
                                    <div class="col-md-3">
                                        <label class="form-label small">SKU <span class="text-danger">*</span></label>
                                        <input type="text" name="variants[{{ $i }}][sku]" class="form-control"
                                            value="{{ $v->sku ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">Nama Variant <span class="text-danger">*</span></label>
                                        <input type="text" name="variants[{{ $i }}][variant]" class="form-control"
                                            value="{{ $v->variant ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Harga (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="variants[{{ $i }}][price]" class="form-control"
                                            value="{{ $v->price ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small">Stock <span class="text-danger">*</span></label>
                                        <input type="number" name="variants[{{ $i }}][stock]" class="form-control"
                                            value="{{ $v->stock ?? 0 }}">
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        <button type="button" class="btn btn-outline-danger remove-variant">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>



            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
    
    <script>
        const variantsWrap = document.getElementById('variants');
        const tpl = document.getElementById('variantRowTpl').content;
        const addBtn = document.getElementById('addVariant');
        let idx = document.querySelectorAll('.variant-row').length || 0;

        function addVariantRow(prefill = {}) {
            const node = document.importNode(tpl, true);
            node.querySelectorAll('input').forEach(inp => {
                inp.name = inp.name.replace('IDX', idx);
                const key = inp.name.match(/\[(\w+)\]$/)?.[1];
                if (key && prefill[key] !== undefined) inp.value = prefill[key];
            });
            variantsWrap.appendChild(node);
            idx++;
        }

        if (idx === 0) addVariantRow();

        addBtn.addEventListener('click', () => addVariantRow());

        variantsWrap.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-variant')) {
                const row = e.target.closest('.variant-row');
                row?.remove();
            }
        });
    </script>

@endsection