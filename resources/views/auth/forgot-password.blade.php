@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Reset <span>Password</span></h2>
            <p class="subtitle">
                Forgot your password? No problem. Enter your email and we'll send you a reset link.
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom: 1.5rem; font-size: 0.85rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email') 
                    <span class="text-danger small">{{ $message }}</span> 
                @enderror
            </div>

            <button type="submit" class="btn-primary btn-full">
                Email Reset Link
            </button>

            <div class="auth-footer">
                <a href="{{ route('login') }}" class="small-link">← Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection