@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm" style="width: 480px;padding: 36px 40px;">
        <div class="card-body p-0">
            <h1 class="h3 text-center">Welcome to dashboard</h1>
            <p class="text-muted text-center">Please login to continue</p>
            @error('error')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="********">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
        
    </div>
@endsection