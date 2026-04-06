<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Customer - Hani Jestky Jogja</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/vendor/admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/vendor/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/admin/dist/css/adminlte.min.css">

    <style>
        body {
            background: url('{{ asset("uploads/jetski-bg.jpg") }}') no-repeat center/cover;
            background-attachment: fixed;
            font-family: 'Source Sans Pro', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .login-box {
            width: 400px;
            position: relative;
            z-index: 1;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border: none;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0;
            padding: 25px;
            text-align: center;
        }
        .card-header h2 {
            color: white;
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }
        .card-header .badge {
            background-color: rgba(255,255,255,0.3);
            color: white;
            margin-top: 10px;
            font-size: 12px;
            padding: 5px 10px;
        }
        .icon-customer {
            font-size: 32px;
            color: white;
            margin-bottom: 10px;
        }
        .login-box-msg {
            color: #666;
            font-size: 16px;
            margin: 15px 0;
            font-weight: 500;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .form-control {
            border: 1px solid #dee2e6;
            height: 45px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            height: 45px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            height: 38px;
            font-size: 12px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="card card-outline">
            <div class="card-header">
                <div class="icon-customer">
                    <i class="fas fa-user"></i>
                </div>
                <h2>CUSTOMER</h2>
                <span class="badge">Hani Jestky Jogja</span>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masukkan kredensial Anda untuk login</p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <small>{{ $error }}</small><br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('loginError'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>{{ session('loginError') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <small>{{ session('success') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="/customer/login/do" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Email" name="email" value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <a href="/customer/register" style="color: #667eea; text-decoration: none;">
                                <i class="fas fa-user-plus"></i> Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/vendor/admin/plugins/jquery/jquery.min.js"></script>
    <script src="/vendor/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
