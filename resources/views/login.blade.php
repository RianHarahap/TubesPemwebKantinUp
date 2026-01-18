<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kantin - Universitas Pertamina</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; font-family: 'Roboto', sans-serif; }
        body { 
            margin: 0; display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; background-color: #e0e0e0; 
            background-image: radial-gradient(#d1d1d1 1px, transparent 1px); background-size: 20px 20px;
        }
        .login-card { background-color: #ffffff; width: 400px; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #ccc; overflow: hidden; }
        .login-header { padding: 40px 20px 20px 20px; text-align: center; }
        .login-header img { max-width: 250px; margin-bottom: 10px; }
        .login-body { padding: 0 40px 30px 40px; }
        
        /* Gaya untuk Pesan Error */
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
        }

        .input-group { position: relative; margin-bottom: 15px; display: flex; align-items: center; }
        .input-group i { position: absolute; left: 15px; color: #888; border-right: 1px solid #ddd; padding-right: 10px; }
        .input-group input { width: 100%; padding: 12px 12px 12px 50px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        .btn-login { width: 100%; padding: 12px; background-color: #007bff; border: none; color: white; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .login-footer { background-color: #f5f5f5; padding: 15px; text-align: center; border-top: 1px solid #eee; }
        .login-footer a { color: #777; text-decoration: none; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Universitas Pertamina">
        </div>

        <div class="login-body">
            
            <!-- PESAN ERROR DI SINI -->
                @if($errors->any())
                    <div class="alert-error">
                        {{-- Ini akan menampilkan pesan error pertama yang dikirim dari Controller --}}
                        {{ $errors->first() }}
                    </div>
                @endif

            <!-- Form mengarah ke route 'login.post' -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf 
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">LOGIN</button>
            </form>
        </div>

        <div class="login-footer">
            <!-- Sekarang link ini akan mengarah ke halaman Forgot Password yang baru kita buat -->
            <a href="{{ route('password.request') }}">FORGOT PASSWORD?</a>
            <br><br>
            <small style="color:#666; font-size:11px;">
                <strong>Testing Accounts:</strong><br>
                Admin: admin_up / password123<br>
                Penjual: penjual_up / password123<br>
                Mahasiswa: mahasiswa_up / password123
            </small>
        </div>
    </div>

</body>
</html>