@extends('layouts.auth')

@section('title', 'Login - Finrai')

@section('content')
<div>
    <!-- Page Header -->
    <div style="margin-bottom: 1.75rem;">
        <h2 class="auth-page-title">Welcome back</h2>
        <p class="auth-page-subtitle">Masuk untuk memantau pemasukan, pengeluaran & tabungan Anda.</p>
    </div>

    <!-- Session Flash Alerts -->
    @if(session('success'))
        <div class="auth-alert-box auth-alert-success">
            <i class="fa-solid fa-circle-check" style="font-size: 1.1rem; margin-top: 0.1rem; color: #059669;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('info'))
        <div class="auth-alert-box" style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF;">
            <i class="fa-solid fa-circle-info" style="font-size: 1.1rem; margin-top: 0.1rem; color: #2563EB;"></i>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="auth-alert-box auth-alert-danger">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.1rem; margin-top: 0.1rem; color: #E11D48;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 0.25rem;">Mohon periksa data Anda:</div>
                <ul style="list-style: disc inside; font-size: 0.8rem; margin: 0; padding-left: 0.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Email Field -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">
                Email <span class="req">*</span>
            </label>
            <div class="auth-input-wrap">
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    placeholder="you@example.com" 
                    class="auth-input @error('email') has-error @enderror"
                >
            </div>
            @error('email')
                <p class="auth-error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field with Eye Toggle -->
        <div class="auth-form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                <label for="password" class="auth-label" style="margin-bottom: 0;">
                    Password <span class="req">*</span>
                </label>
                <a href="#" onclick="alert('Fitur Lupa Password sedang dikembangkan. Silakan hubungi admin atau buat akun baru.'); return false;" class="auth-forgot-link">
                    Lupa Password?
                </a>
            </div>
            <div class="auth-input-wrap">
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    placeholder="••••••••" 
                    class="auth-input has-icon-right @error('password') has-error @enderror"
                >
                <button 
                    type="button" 
                    id="togglePassword" 
                    class="auth-toggle-pwd" 
                    title="Tampilkan / Sembunyikan Kata Sandi"
                    tabindex="-1"
                >
                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
                <p class="auth-error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="auth-flex-row">
            <label class="auth-checkbox-label">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember" 
                    value="1" 
                    class="auth-checkbox"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <span>Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button (Pill Blue Button) -->
        <div style="margin-top: 0.5rem;">
            <button type="submit" class="auth-btn-primary">
                <span>Masuk ke Akun</span>
                <i class="fa-solid fa-arrow-right-long text-sm"></i>
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="auth-switch-box">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="auth-switch-link">
            <span>Daftar Sekarang</span>
            <i class="fa-solid fa-arrow-right-long text-xs"></i>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection
