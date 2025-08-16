@extends('layouts.dashboard')

@section('title')
    Categories
@endsection

@section('content')
    <div class="d-flex flex-column mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Categories</h1>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Create Category
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($categories->count() > 0)
                        @foreach ($categories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <div class="d-flex align-items-center column-gap-2">
                                        @if ($category->image)
                                            <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                                class="img-thumbnail ratio-1x1" style="width: 50px;object-fit: cover;">
                                        @else
                                            <i class="bi bi-image-fill" style="font-size: 32px;opacity: 0.5;"></i>
                                        @endif
                                        {{ $category->name }}
                                    </div>
                                </td>
                                <td class="text-nowrap overflow-hidden text-ellipsis" style="max-width: 200px;">
                                    {{ $category->description }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td class="d-flex column-gap-2">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $category->id }}">
                                        <i class="bi bi-trash text-white"></i>
                                    </button>
                                    <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $category->id }}">Delete Category</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this category?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
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
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No categories found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $categories->links('pagination::bootstrap-5') }}

        </div>
@endsection