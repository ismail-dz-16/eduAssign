@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="verify-icon">🔒</div>
            <h2>Confirm <span>Access</span></h2>
            <p class="subtitle">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••"
                       autofocus>
                
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth-actions-vertical">
                <button type="submit" class="btn-primary btn-full">
                    Confirm Password
                </button>
                
                <a href="{{ url()->previous() }}" class="btn-link-muted" style="text-decoration: none; margin-top: 1rem; display: block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection