@extends('layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold mb-4">Kelola Produk</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Tambah Produk
            </a>
        </div>

        {{-- Filter Bar --}}
        <form id="filterForm" class="grid md:grid-cols-6 gap-3 items-end mb-4">
            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Cari Nama</label>
                <input type="text" name="search" class="w-full border rounded px-3 py-2"
                    placeholder="laptop, tshirt, ..." />
            </div>

            <div>
                <label class="block text-sm mb-1">Kategori</label>
                <select name="category" class="w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->slug }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Min Harga</label>
                <input type="number" step="0.01" name="min_price" class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block text-sm mb-1">Max Harga</label>
                <input type="number" step="0.01" name="max_price" class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block text-sm mb-1">Sort</label>
                <select name="sort" class="w-full border rounded px-3 py-2">
                    <option value="name">Nama</option>
                    <option value="price">Harga</option>
                </select>
            </div>

            <div class="md:col-span-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded">Terapkan</button>
                <button id="resetBtn" type="button" class="px-4 py-2 border rounded">Reset</button>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto border rounded">
            <table class="min-w-full" id="resultTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3">Produk</th>
                        <th class="text-left p-3">Kategori</th>
                        <th class="text-left p-3">Harga</th>
                        <th class="text-left p-3">Rating</th>
                        <th class="text-left p-3">Review</th>
                        <th class="text-center p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="resultBody"></tbody>
            </table>
        </div>

        <div id="pagination" class="flex gap-2 mt-4"></div>
    </div>

    <script>
        const form = document.getElementById('filterForm');
        const tbody = document.getElementById('resultBody');
        const pagination = document.getElementById('pagination');

        let state = { page: 1, limit: 10 };

        function qs(params) {
            const sp = new URLSearchParams(params);
            return sp.toString();
        }

        async function fetchProducts() {
            const fd = new FormData(form);
            const params = Object.fromEntries(fd.entries());
            const query = { ...params, ...state };
            const url = `/api/products?${qs(query)}`;

            const res = await fetch(url);
            const data = await res.json();

            // Render rows
            tbody.innerHTML = '';
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-3 text-center">Tidak ada data</td></tr>';
            } else {
                data.data.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                          <td class="p-3">
                            <div class="font-medium">${p.name}</div>
                            <div class="text-xs text-gray-500">${p.slug}</div>
                          </td>
                          <td class="p-3">${p.category ? p.category.name : '-'}</td>
                          <td class="p-3">Rp ${Number(p.price).toLocaleString('id-ID')}</td>
                          <td class="p-3">${p.rating ?? 0}</td>
                          <td class="p-3">${p.reviews_count ?? 0}</td>
                          <td class="p-3 text-center">
                            <a href="/products/${p.id}/edit" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-${p.id}">
                                                <i class="bi bi-trash text-white"></i>
                                            </button>
                            <div class="modal fade" id="deleteModal-${p.id}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="deleteModalLabel-${p.id}">Delete Category</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this category?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <form action="/products/${p.id}" method="POST"
                                                                    class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger text-white">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                          </td>
                        `;
                    tbody.appendChild(tr);
                });
            }

            // Render pagination
            pagination.innerHTML = '';
            const meta = data.meta;
            const links = data.links;
            // Prev
            const prev = document.createElement('button');
            prev.textContent = 'Prev';
            prev.disabled = !links.prev;
            prev.className = 'px-3 py-1 border rounded disabled:opacity-50';
            prev.onclick = () => { state.page = meta.current_page - 1; fetchProducts(); };
            pagination.appendChild(prev);
            // Page info
            const info = document.createElement('span');
            info.className = 'px-2 py-1';
            info.textContent = `Page ${meta.current_page} / ${meta.last_page}`;
            pagination.appendChild(info);
            // Next
            const next = document.createElement('button');
            next.textContent = 'Next';
            next.disabled = !links.next;
            next.className = 'px-3 py-1 border rounded disabled:opacity-50';
            next.onclick = () => { state.page = meta.current_page + 1; fetchProducts(); };
            pagination.appendChild(next);

            // Update URL (history)
            const displayUrl = `${location.pathname}?${qs({ ...params, page: state.page, limit: state.limit })}`;
            history.replaceState(null, '', displayUrl);
        }

        form.addEventListener('submit', (e) => { e.preventDefault(); state.page = 1; fetchProducts(); });
        document.getElementById('resetBtn').onclick = () => { form.reset(); state = { page: 1, limit: 10 }; fetchProducts(); };

        // Initial load
        fetchProducts();
    </script>
@endsection