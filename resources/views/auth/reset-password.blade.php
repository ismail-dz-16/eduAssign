@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>New <span>Password</span></h2>
            <p class="subtitle">Enter your email and choose a strong new password to regain access.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus placeholder="email@example.com">
                @error('email') 
                    <span class="text-danger small">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required placeholder="••••••••">
                @error('password') 
                    <span class="text-danger small">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary btn-full">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection