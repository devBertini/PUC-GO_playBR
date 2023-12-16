<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #181818;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 10% auto;
            
        }
        h1 {
            font-size: 24px;
        }
        .bg {
            background: rgba(16, 16, 16, 1);
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background: rgba(58, 58, 58, 0.8);
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }
        .login-form {
            display: flex;
            flex-direction: column;
        }
        .login-form input[type="email"], .login-form input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ff4b5a;
            color: white;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #ff2f43;
        }
        .login-form .create-account {
            background-color: transparent;
            color: #f8f8f8;
            text-align: center;
            text-decoration: underline;
            margin-top: 15px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="bg">
        <div class="login-container">
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <p>Email</p>
                <input type="email" name="email" placeholder="Email" required autofocus>
                <p>Senha</p>
                <input type="password" name="password" placeholder="Senha" required>
                <button type="submit">Entrar</button>
                <a href="{{ route('register') }}" class="create-account">Criar nova conta</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.querySelector('.login-form');

            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const payload = {
                    email: formData.get('email'),
                    password: formData.get('password')
                };

                fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        // Armazena o token no localStorage
                        localStorage.setItem('token', data.access_token);
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Redireciona para a página home
                        window.location.href = '/';
                    } else {
                        // Trata erros de login
                        alert(data.details);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>
