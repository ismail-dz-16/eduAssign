@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <div>
            <h1>Account Settings</h1>
            <p class="subtitle">Manage your personal information, security, and account status.</p>
        </div>
    </div>

    <div class="profile-grid">
        
        {{-- SECTION 1: PROFILE INFO --}}
        <section class="card profile-section" id="info">
            <div class="card-step-title">
                <span class="step-badge">👤</span>
                <div>
                    <h3>Profile Information</h3>
                    <p class="text-dim small">Update your account's profile name and email address.</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="mt-4">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="verification-notice">
                            <p>Your email is unverified. <button form="send-verification" class="btn-link">Resend link</button></p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="text-success small">A new link has been sent!</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex-row">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    @if (session('status') === 'profile-updated')
                        <span class="status-msg success">✓ Saved</span>
                    @endif
                </div>
            </form>
            {{-- Hidden form for email verification --}}
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
        </section>

        {{-- SECTION 2: PASSWORD UPDATE --}}
        <section class="card profile-section" id="password">
            <div class="card-step-title">
                <span class="step-badge">🔒</span>
                <div>
                    <h3>Update Password</h3>
                    <p class="text-dim small">Ensure your account is using a long, random password to stay secure.</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="mt-4">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input id="current_password" name="current_password" type="password">
                    @error('current_password', 'updatePassword') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password">
                    @error('password', 'updatePassword') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                </div>

                <div class="flex-row">
                    <button type="submit" class="btn-primary">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <span class="status-msg success">✓ Password Updated</span>
                    @endif
                </div>
            </form>
        </section>

        {{-- SECTION 3: DELETE ACCOUNT --}}
        <section class="card profile-section border-danger" id="danger-zone">
            <div class="card-step-title">
                <span class="step-badge bg-danger">⚠️</span>
                <div>
                    <h3 class="text-danger">Danger Zone</h3>
                    <p class="text-dim small">Permanently delete your account and all associated data.</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="small text-dim mb-4">Once your account is deleted, all resources and data will be permanently removed. Please download any information you wish to retain first.</p>
                
                <button class="btn-danger" onclick="toggleModal('delete-modal')">Delete Account</button>
            </div>
        </section>

    </div>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div id="delete-modal" class="custom-modal">
    <div class="modal-content card">
        <h3>Are you sure?</h3>
        <p class="text-dim small">Please enter your password to confirm you would like to permanently delete your account.</p>
        
        <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
            @csrf
            @method('delete')

            <div class="form-group">
                <input name="password" type="password" placeholder="Confirm Password" required>
                @error('password', 'userDeletion') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="toggleModal('delete-modal')">Cancel</button>
                <button type="submit" class="btn-danger">Permanently Delete</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('active');
    }
</script>
@endpush