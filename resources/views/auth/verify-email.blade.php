@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="verify-icon">📩</div>
            <h2>Verify <span>Email</span></h2>
            <p class="subtitle">
                Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" style="margin-bottom: 1.5rem; font-size: 0.85rem;">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="auth-actions-vertical">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary btn-full">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="btn-link-muted">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection