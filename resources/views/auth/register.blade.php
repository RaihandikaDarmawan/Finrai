@extends('layouts.auth')

@section('title', 'Buat Akun - Finrai')

@section('content')
<div>
    <!-- Page Header -->
    <div style="margin-bottom: 1.75rem;">
        <h2 class="auth-page-title">Buat Akun Baru</h2>
        <p class="auth-page-subtitle">Daftar sekarang untuk mulai mengontrol masa depan finansial Anda.</p>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="auth-alert-box auth-alert-danger">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.1rem; margin-top: 0.1rem; color: #E11D48;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 0.25rem;">Mohon periksa input Anda:</div>
                <ul style="list-style: disc inside; font-size: 0.8rem; margin: 0; padding-left: 0.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Registration Form -->
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <!-- Full Name Field -->
        <div class="auth-form-group">
            <label for="name" class="auth-label">
                Nama Lengkap <span class="req">*</span>
            </label>
            <div class="auth-input-wrap">
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    placeholder="Contoh: Ahmad Raihan" 
                    class="auth-input @error('name') has-error @enderror"
                >
            </div>
            @error('name')
                <p class="auth-error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">
                Alamat Email <span class="req">*</span>
            </label>
            <div class="auth-input-wrap">
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="you@example.com" 
                    class="auth-input @error('email') has-error @enderror"
                >
            </div>
            @error('email')
                <p class="auth-error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password & Confirm Password (2 Columns Layout for Inputs) -->
        <div class="auth-grid-2">
            <!-- Password Field -->
            <div class="auth-form-group">
                <label for="password" class="auth-label">
                    Password <span class="req">*</span>
                </label>
                <div class="auth-input-wrap">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        placeholder="Min. 8 karakter" 
                        class="auth-input has-icon-right @error('password') has-error @enderror"
                    >
                    <button 
                        type="button" 
                        id="togglePassword" 
                        class="auth-toggle-pwd" 
                        title="Tampilkan / Sembunyikan Password"
                        tabindex="-1"
                    >
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="auth-error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="auth-form-group">
                <label for="password_confirmation" class="auth-label">
                    Konfirmasi <span class="req">*</span>
                </label>
                <div class="auth-input-wrap">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        placeholder="Ulangi password" 
                        class="auth-input has-icon-right"
                    >
                    <button 
                        type="button" 
                        id="toggleConfirmPassword" 
                        class="auth-toggle-pwd" 
                        title="Tampilkan / Sembunyikan Konfirmasi Password"
                        tabindex="-1"
                    >
                        <i class="fa-regular fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div style="margin-bottom: 1.25rem;">
            <p style="font-size: 0.775rem; color: #64748B; line-height: 1.5;">
                Dengan mendaftar, Anda menyetujui 
                <a href="#" onclick="alert('Ketentuan Layanan Finrai melindungi privasi dan keamanan data finansial Anda.'); return false;" style="color: #2563EB; font-weight: 600; text-decoration: none;">Ketentuan Layanan</a> 
                dan 
                <a href="#" onclick="alert('Kebijakan Privasi Finrai: Seluruh data keuangan disimpan secara aman dan terenkripsi.'); return false;" style="color: #2563EB; font-weight: 600; text-decoration: none;">Kebijakan Privasi</a> 
                Finrai.
            </p>
        </div>

        <!-- Submit Button -->
        <div style="margin-top: 0.5rem;">
            <button type="submit" class="auth-btn-primary">
                <span>Daftar Akun Baru</span>
                <i class="fa-solid fa-arrow-right-long text-sm"></i>
            </button>
        </div>
    </form>

    <!-- Back to Login Link -->
    <div class="auth-switch-box">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="auth-switch-link">
            <span>Masuk ke Akun</span>
            <i class="fa-solid fa-arrow-right-long text-xs"></i>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle main password
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }

        // Toggle confirm password
        const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
        const confirmInput = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        if (toggleConfirmBtn && confirmInput && eyeIconConfirm) {
            toggleConfirmBtn.addEventListener('click', function() {
                const isPassword = confirmInput.getAttribute('type') === 'password';
                confirmInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    eyeIconConfirm.classList.remove('fa-eye');
                    eyeIconConfirm.classList.add('fa-eye-slash');
                } else {
                    eyeIconConfirm.classList.remove('fa-eye-slash');
                    eyeIconConfirm.classList.add('fa-eye');
                }
            });
        }
    });
</script>
@endsection
