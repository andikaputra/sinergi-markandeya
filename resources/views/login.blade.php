<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sinergi Markandeya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #d4a574;
            font-size: 14px;
        }

        .login-card {
            background-color: rgba(245, 230, 211, 0.95);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #0f2d26;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background-color: #ffffff;
            border: 2px solid #d4a574;
            border-radius: 10px;
            color: #1a5d4d;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #c9905c;
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.1);
            background-color: #ffffff;
        }

        .form-group input::placeholder {
            color: #998877;
        }

        .password-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .forgot-link {
            color: #1a5d4d;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #d4a574;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4f1d4;
            color: #1a5d4d;
            border: 1px solid #b3d9b3;
        }

        .alert-error {
            background-color: #ffd4d4;
            color: #8b0000;
            border: 1px solid #ffb3b3;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #d4a574;
            color: #0f2d26;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background-color: #c9905c;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 165, 116, 0.3);
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(26, 93, 77, 0.2);
            color: #1a5d4d;
            font-size: 13px;
        }

        .footer-link {
            color: #d4a574;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            display: block;
            margin-top: 12px;
        }

        .footer-link:hover {
            color: #c9905c;
        }

        .copyright {
            text-align: center;
            margin-top: 40px;
            color: rgba(245, 230, 211, 0.7);
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="logo">
                <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo Universitas Markandeya">
            </div>
            <h1>Sinergi Markandeya</h1>
            <p>Sistem Manajemen KKN, PPL, PKL</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">NIM / Email / NIDN</label>
                    <input
                        type="text"
                        name="email"
                        id="email"
                        required
                        placeholder="Masukkan NIM, Email, atau NIDN"
                        value="{{ old('email') }}"
                    >
                </div>

                <div class="form-group">
                    <div class="password-group">
                        <label for="password">Kata Sandi</label>
                        <a href="{{ route('lupa-password') }}" class="forgot-link">Lupa Password?</a>
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        placeholder="••••••••"
                    >
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? Hubungi admin untuk pendaftaran.</p>
                <a href="{{ route('loginadmin') }}" class="footer-link">
                    <i class="fas fa-lock"></i> Login sebagai Admin
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            &copy; {{ date('Y') }} Universitas Markandeya | Sinergi Sistem
        </div>
    </div>
</body>
</html>

