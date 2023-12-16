<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
        .register-container {
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
        .register-form {
            display: flex;
            flex-direction: column;
        }
        .register-form input[type="text"], .register-form input[type="email"], .register-form input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-form button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ff4b5a;
            color: white;
            cursor: pointer;
        }
        .register-form button:hover {
            background-color: #ff2f43;
        }
        .register-form .create-account {
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
        <div class="register-container">
            <form class="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <p>Nome</p>
                <input type="text" name="name" placeholder="Nome completo" required autofocus>
                <p>Email</p>
                <input type="email" name="email" placeholder="Email" required>
                <p>Senha</p>
                <input type="password" name="password" placeholder="Senha" required>
                <p>Confirmação da Senha</p>
                <input type="password" name="password_confirmation" placeholder="Confirmar senha" required>
                <br>
                <button type="submit">Criar Conta</button>
                <a href="{{ route('login') }}" class="create-account">Já tem uma conta? Entre aqui</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const registerForm = document.querySelector('.register-form');

            registerForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const payload = {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    password: formData.get('password')
                };

                fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message == "Sucesso") {
                        // Redireciona para a página home
                        alert(data.details);
                        window.location.href = '/login';
                    } else {
                        // Trata erros de login
                        alert(data.details);
                    }
                })
                .catch((error) => {
                    alert(error);
                });
            });
        });
    </script>
</body>
</html>
