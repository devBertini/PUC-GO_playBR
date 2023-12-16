<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Vídeo - playBR</title>
    <style>
        /* Estilos reutilizados da página de login */
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #181818;
            color: #fff;
        }
        .bg {
            /* Use a mesma imagem de fundo ou cor usada na página de login */
            background-color: #181818; /* Exemplo de cor de fundo */
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .upload-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1); /* Fundo levemente transparente */
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }
        .upload-form {
            display: flex;
            flex-direction: column;
        }
        .upload-form input[type="text"],
        .upload-form input[type="file"],
        .upload-form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #333;
            color: #fff;
        }
        .upload-form button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ff4b5a;
            color: white;
            cursor: pointer;
        }
        .upload-form button:hover {
            background-color: #ff2f43;
        }
        /* Estilo do modal */
        .modal {
            display: none; /* Oculto por padrão */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Estilos para o loader */
        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #3498db;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: #fff;
            font-size: 20px;
            margin-top: 10px;
        }

        /* Botão de Voltar */
        #backButton {
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <button id="backButton" onclick="goBack()">
        <span>&#8592; Voltar</span>
    </button>
    <div class="bg">
        <div class="upload-container">
            <h1>Enviar Vídeo</h1>
            <form class="upload-form" id="video-upload-form" action="{{ route('uploadVideo') }}" method="post" enctype="multipart/form-data">
                @csrf
                <P>Título</p>
                <input type="text" name="title" placeholder="Título do vídeo" required>
                <P>Descrição</p>
                <textarea name="description" placeholder="Descrição" rows="3"></textarea>
                <P>Arquivo de Vídeo</p>
                <input type="file" name="video" required> <!-- Para vídeo -->
                <P>Arquivo de Thumbnail</p>
                <input type="file" name="thumbnail"> <!-- Para thumbnail -->
                <button type="submit">Enviar</button>
            </form>
            <div class="modal" id="uploadModal">
                <div>
                    <div class="loader"></div>
                    <p class="loading-text">Enviando...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('video-upload-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var uploadModal = document.getElementById('uploadModal');
            uploadModal.style.display = 'flex'; // Exibe o modal com o loader

            fetch("/api/videos", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                }
            })
            .then(response => response.json())
            .then(data => {
                uploadModal.style.display = 'none'; // Oculta o modal com o loader
                if (data.message === 'Sucesso') {
                    alert(data.details );
                    window.location.href = '/'; // Redirecionar para uma página de sucesso
                } else {
                    alert(data.details);
                }
            })
            .catch(error => {
                uploadModal.style.display = 'none'; // Oculta o modal com o loader
                alert(error);
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
