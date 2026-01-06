<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - InternHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a0a0f;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.3) 0%, transparent 40%),
                radial-gradient(circle at 20% 80%, rgba(168, 85, 247, 0.3) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(236, 72, 153, 0.2) 0%, transparent 50%);
            animation: bgRotate 30s linear infinite reverse;
        }

        @keyframes bgRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(236, 72, 153, 0.2));
            filter: blur(40px);
            animation: float 20s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 250px;
            height: 250px;
            top: 20%;
            right: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 180px;
            height: 180px;
            bottom: 20%;
            left: 15%;
            animation-delay: -7s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            33% { transform: translateY(-30px) scale(1.1); }
            66% { transform: translateY(20px) scale(0.9); }
        }

        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .register-card {
            background: rgba(18, 18, 26, 0.9);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f43f5e 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 10px 40px rgba(168, 85, 247, 0.4);
        }

        .brand h1 {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #a855f7, #ec4899, #f43f5e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 6px;
        }

        .brand p {
            color: #71717a;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #a1a1aa;
            margin-bottom: 8px;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #71717a;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            background: rgba(26, 26, 37, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #a855f7;
            background: rgba(26, 26, 37, 1);
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.15);
        }

        .form-input:focus + i {
            color: #a855f7;
        }

        .form-input::placeholder {
            color: #52525b;
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            margin-top: 8px;
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f43f5e 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(168, 85, 247, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(168, 85, 247, 0.5);
        }

        .login-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 28px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-link p {
            color: #71717a;
            font-size: 14px;
        }

        .login-link a {
            color: #a855f7;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #c084fc;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="register-card">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Buat Akun Baru</h1>
                <p>Bergabung dengan InternHub</p>
            </div>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="form-input-wrapper">
                        <input 
                            type="text" 
                            name="name" 
                            class="form-input" 
                            placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}"
                            required
                        >
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="form-input-wrapper">
                        <input 
                            type="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Minimal 8 karakter"
                            required
                        >
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="form-input-wrapper">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="form-input" 
                            placeholder="Ulangi password"
                            required
                        >
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-rocket"></i> Daftar Sekarang
                </button>
            </form>

            <div class="login-link">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
