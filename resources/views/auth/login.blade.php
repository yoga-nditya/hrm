@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<!-- Left Section with Form -->
<div class="left-section">
    <div class="login-card">
        <h2>Masuk ke Akun Anda</h2>
        
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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email address" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-field">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
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
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #6c757d;">
                    <input type="checkbox" name="remember" style="margin: 0;">
                    Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn-primary">
                Masuk
            </button>
        </form>

        <button class="btn-google" onclick="signInWithGoogle()">
            <i class="fab fa-google"></i>
            Sign in with Google
        </button>

        <div class="auth-links">
            <p>Belum memiliki akun?</p>
            <a href="{{ route('register') }}">Klik disini untuk melakukan pendaftaran akun</a>
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
function togglePassword() {
    const passwordField = document.querySelector('input[name="password"]');
    const toggleIcon = document.getElementById('toggleIcon');
    
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

function signInWithGoogle() {
    // Implementasi Google Sign-In
    alert('Google Sign-In functionality would be implemented here');
}
</script>
@endsection