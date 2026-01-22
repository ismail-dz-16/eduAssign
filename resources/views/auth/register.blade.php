@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Join Edu<span>Assign</span></h2>
            <p>Create your account to start managing assignments.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com">
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="••••••••">
                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label class="role-selector">
                    <input type="checkbox" name="is_teacher" value="1" {{ old('is_teacher') ? 'checked' : '' }}>
                    <div class="role-box">
                        <span class="role-icon">🎓</span>
                        <div class="role-text">
                            <strong>Sign up as a Teacher</strong>
                            <p>Check this if you want to create and grade assignments.</p>
                        </div>
                    </div>
                </label>
            </div>

            <button type="submit" class="btn-primary btn-full">Create Account</button>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection