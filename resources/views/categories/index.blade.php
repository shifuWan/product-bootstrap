@extends('layouts.dashboard')

@section('title')
    Categories
@endsection

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Categories</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Create Category
    </a>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:60px">#</th>
              <th style="min-width:260px">Name</th>
              <th style="min-width:260px">Description</th>
              <th style="width:120px">Status</th>
              <th style="width:140px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($categories as $category)
              <tr>
                <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    @if ($category->image)
                      <img src="{{ $category->image }}" alt="{{ $category->name }}"
                           class="rounded border" style="width:48px;height:48px;object-fit:cover;">
                    @else
                      <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                           style="width:48px;height:48px;">
                        <i class="bi bi-image" style="opacity:.5;font-size:1.25rem;"></i>
                      </div>
                    @endif

                    <div class="fw-semibold">{{ $category->name }}</div>
                  </div>
                </td>
                <td class="text-truncate" style="max-width: 360px;">
                  {{ $category->description }}
                </td>
                <td>
                  <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                      <i class="bi bi-pencil-square"></i>
                    </a>

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal-{{ $category->id }}" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>

                  <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1" aria-hidden="true"
                       aria-labelledby="deleteModalLabel-{{ $category->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $category->id }}">Delete Category</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to delete <strong>{{ $category->name }}</strong>?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger text-white">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">No categories found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {{ $categories->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
