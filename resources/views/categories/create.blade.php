@extends('layouts.dashboard')

@section('title')
    Create Category
@endsection

@section('content')
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div class="card" style="width: 480px;">
            <div class="card-body">
                <h5 class="card-title mb-1">Create Category</h5>
                <p class="card-text mb-3 text-muted">Create a new category to categorize your products.</p>
                <form action="{{ route('categories.store') }}" method="POST" class="d-flex flex-column row-gap-4 mb-4"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            value="{{ old('name') }}" name="name" placeholder="Enter category name">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
                            placeholder="Enter category image" accept="image/*" value="{{ old('image') }}">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

                <div class="alert alert-info d-flex row-gap-2 flex-column" role="alert">
                    <div>
                        <i class="bi bi-info-circle-fill"></i>
                        <span>
                            How to create a category?
                        </span>
                    </div>
                    <div>
                        <ol class=" list-group-numbered list-unstyled">
                            <li class="list-group-item">
                                The category name must be unique
                            </li>
                            <li class="list-group-item">
                                The category description is optional
                            </li>
                            <li class="list-group-item">
                                The category image is optional, but it must be an image file, and the image must be less than 2MB
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection