<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | SistemaTienda</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.10);
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .login-title {
            font-size: 25px;
            font-weight: bold;
            color: #333;
        }

        .login-subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
        }

        .btn-login {
            height: 48px;
            border-radius: 8px;
            background: #0dcaf0;
            border: none;
            color: white;
            font-weight: bold;
        }

        .btn-login:hover {
            background: #0aa8c7;
            color: white;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="text-center">

            <img src="{{ asset('image/logo.jpeg') }}"
                 alt="Logo"
                 class="logo">

            <div class="login-title">
                SistemaTienda
            </div>

            <div class="login-subtitle">
                Inicia sesión para continuar
            </div>

        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Correo electrónico
                </label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email') }}"
                       placeholder="Ingrese su correo"
                       required
                       autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Contraseña
                </label>

                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Ingrese su contraseña"
                       required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input"
                       type="checkbox"
                       name="remember"
                       id="remember">

                <label class="form-check-label" for="remember">
                    Recordarme
                </label>
            </div>

            <button type="submit"
                    class="btn btn-login w-100">
                Iniciar sesión
            </button>

        </form>

    </div>

</div>

</body>
</html>

