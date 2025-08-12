@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<!-- Left Section with Form -->
<div class="left-section">
    <div class="login-card">
        <h2>Daftar Akun Baru</h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email address" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-field">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="toggleIcon1"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <div class="password-field">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password" required>
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="toggleIcon2"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Verifikasi Captcha</label>
                <div class="captcha-container">
                    <input type="checkbox" class="captcha-checkbox" required>
                    <span class="captcha-text">Saya bukan robot</span>
                    <div class="captcha-logo">
                        reCAPTCHA
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 14px; color: #6c757d; line-height: 1.4;">
                    <input type="checkbox" name="terms" style="margin-top: 2px;" required>
                    Saya setuju dengan <a href="#" style="color: #ff6b35;">syarat dan ketentuan</a> yang berlaku
                </label>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i> Daftar Akun
            </button>
        </form>

        <button class="btn-google" onclick="signUpWithGoogle()">
            <i class="fab fa-google"></i>
            Sign up with Google
        </button>

        <div class="auth-links">
            <p>Sudah memiliki akun?</p>
            <a href="{{ route('login') }}">Klik disini untuk masuk</a>
        </div>
    </div>
</div>

<!-- Right Section with Illustration -->
<div class="right-section">
    <div style="text-align: center;">
        <div class="illustration"></div>
        <div class="info-card">
            <p>Bergabunglah bersama Lotte Mart dan jadilah bagian dari tim yang dinamis! Kelola karier Anda melalui sistem HRM kami yang terintegrasi, dirancang untuk mendukung perkembangan profesional dan kemudahan akses informasi karyawan.</p>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldName) {
    const passwordField = document.querySelector(`input[name="${fieldName}"]`);
    const toggleIcon = fieldName === 'password' ? 
        document.getElementById('toggleIcon1') : 
        document.getElementById('toggleIcon2');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function signUpWithGoogle() {
    // Implementasi Google Sign-Up
    alert('Google Sign-Up functionality would be implemented here');
}
</script>
@endsection