<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ZestShop</title>
    
    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css"/>
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet"/>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        :root {
            --primary: #FF6B1A;
            --primary-hover: #E05510;
            --primary-light: #FFF0E8;
            --primary-pale: #FFFDFB;
            --primary-border: #FFD6BB;
            --dark: #0F172A;
            --light-bg: #FFF8F4;
            --border-color: #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background blobs for a premium look */
        .bg-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 107, 26, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: -1;
        }
        .blob-1 {
            top: -150px;
            right: -150px;
        }
        .blob-2 {
            bottom: -150px;
            left: -150px;
        }

        .auth-card {
            background-color: #ffffff;
            border: 1px solid rgba(255, 107, 26, 0.15);
            border-radius: 28px;
            box-shadow: 0 20px 50px -12px rgba(255, 107, 26, 0.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary), #FF853C);
            padding: 48px 32px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .auth-brand {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background-color: #ffffff;
            color: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 900;
        }

        .auth-subtitle {
            font-size: 14.5px;
            opacity: 0.9;
            margin-bottom: 0;
            font-weight: 500;
        }

        .auth-body {
            padding: 40px 36px;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 19px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            background-color: #F8FAFC;
            outline: none;
            transition: all 0.2s ease;
            color: var(--dark);
        }

        .form-control-custom::placeholder {
            color: #94A3B8;
        }

        .form-control-custom:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 107, 26, 0.12);
        }

        .form-control-custom:focus + i {
            color: var(--primary);
        }

        .btn-auth {
            background: linear-gradient(135deg, var(--primary), #FF7D32);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s ease;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(255, 107, 26, 0.2);
        }

        .btn-auth:hover {
            background: linear-gradient(135deg, #FF5B03, var(--primary));
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 107, 26, 0.3);
        }

        .btn-auth:active {
            transform: translateY(1px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 13.5px;
            color: #64748B;
            font-weight: 500;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Error Styling */
        .invalid-feedback-custom {
            font-size: 12px;
            color: #EF4444;
            margin-top: 6px;
            display: block;
            padding-left: 4px;
            font-weight: 600;
        }

        .form-control-custom.is-invalid {
            border-color: #FCA5A5;
            background-color: #FEF2F2;
        }

        .form-control-custom.is-invalid + i {
            color: #EF4444;
        }
    </style>
</head>
<body>

    <!-- Decorative blobs -->
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-brand">
                <div class="brand-icon">Z</div>
                <span>ZestShop</span>
            </div>
            <p class="auth-subtitle">Create your account to start shopping</p>
        </div>

        <div class="auth-body">
            <form action="{{ url('/register') }}" method="POST">
                @csrf

                <!-- Name Input -->
                <div class="input-group-custom">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" 
                               class="form-control-custom @error('name') is-invalid @enderror" 
                               placeholder="John Doe" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <i class="ti ti-user"></i>
                    </div>
                    @error('name')
                        <span class="invalid-feedback-custom">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="input-group-custom">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" 
                               class="form-control-custom @error('email') is-invalid @enderror" 
                               placeholder="john@example.com" value="{{ old('email') }}" required autocomplete="email">
                        <i class="ti ti-mail"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback-custom">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="input-group-custom">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" 
                               class="form-control-custom @error('password') is-invalid @enderror" 
                               placeholder="••••••••" required autocomplete="new-password">
                        <i class="ti ti-lock"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback-custom">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="input-group-custom">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control-custom" 
                               placeholder="••••••••" required autocomplete="new-password">
                        <i class="ti ti-lock-check"></i>
                    </div>
                </div>

                <button type="submit" class="btn-auth btn">
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ url('/login') }}">Login here</a>
            </div>
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
