@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Please login to your account.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="flex-row">
                <label class="checkbox-container">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-primary btn-full">Login</button>

            <div class="auth-footer">
                <p>New here? <a href="{{ route('register') }}">Create an account</a></p>
            </div>
        </form>
    </div>
</div>
@endsection