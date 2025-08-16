@extends('layouts.dashboard')

@section('content')
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div class="card" style="width: 480px;">
            <div class="card-body">
                <h5 class="card-title mb-1">Edit Category</h5>
                <p class="card-text mb-3 text-muted">Edit the category to update its details.</p>
                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="d-flex flex-column row-gap-4 mb-4"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            value="{{ $category->name }}" name="name" placeholder="Enter category name">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" placeholder="Enter category description">{{ $category->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                            <option value="1" {{ $category->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
                            placeholder="Enter category image" accept="image/*" value="{{ $category->image }}">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>

                <div class="alert alert-info d-flex row-gap-2 flex-column" role="alert">
                    <div>
                        <i class="bi bi-info-circle-fill"></i>
                        <span>
                            How to edit a category?
                        </span>
                    </div>
                    <div>
                        <ol class=" list-group-numbered list-unstyled">
                            <li class="list-group-item">
                                The category name must be unique, and the category name cannot be changed
                            </li>
                            <li class="list-group-item">
                                The category description is optional, and the category description can be changed
                            </li>
                            <li class="list-group-item">
                                The category image is optional, but it must be an image file, and the image must be less than 2MB, and the image can be changed
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection