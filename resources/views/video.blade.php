<!DOCTYPE html>
<html>
<head>
    <title>{{ $videoData['title'] }}</title>
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
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
        }
        .video-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 20px;
        }
        .video-info {
            margin-top: 10px;
        }
        .username {
            color: #aaa;
            font-size: 14px;
        }
        
        p {
            color: #bbb;
        }

        .description {
            background-color: #202020;
            border-radius: 4px;
            padding: 8px 15px;
            margin-top: 10px;
            border-radius: 20px;
            max-height: 4em;
            overflow: hidden;
            position: relative;
            padding-bottom: 18px;
            transition: max-height 0.5s;
        }

        .description p {
            color: #aaa;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            overflow: hidden;
        }

        .posted-date {
            color: #909090;
            font-size: 12px;
            margin-top: 5px;
        }

        .description::after {
            content: '... mais';
            color: #fff;
            position: absolute;
            bottom: 0;
            right: 0;
            padding: 0 20px 5px 20px;
            background: linear-gradient(to left, #181818, rgba(32, 32, 32, 0));
            cursor: pointer;
            color: #aaa;
            width: 100%;
            text-align: right;
            box-sizing: border-box;
        }

        .description.expanded {
            max-height: none;
            padding-bottom: 20px; 
        }

        .description.expanded::after {
            content: 'Mostrar menos';
            background: none; 
            padding: 0 10px; 
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

        /* Estilo do botão de remoção do Vídeo */
        #deleteButton {
            position: absolute;
            right: 10px;
            bottom: 10px;
            background-color: #ff4b5a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #deleteButton:hover {
            background-color: #ff2f43;
        }
    </style>
</head>

<body>
    <button id="backButton" onclick="goBack()">
        <span>&#8592; Voltar</span>
    </button>
    <div class="container">
        <button id="deleteButton" onclick="deleteVideo()">
            Deletar Vídeo
        </button>
        <div class="video-wrapper">
            <video width="640" height="360" controls>
                <source src="{{ $videoData['video_url'] }}" type="video/mp4">
                Seu navegador não suporta a tag de vídeo.
            </video>
        </div>
        <div class="video-info">
            <h1>{{ $videoData['title'] }}</h1>
            <h2 class="username">{{ $videoData['author'] }}</h2>
            <div class="description">
                <div class="posted-date">Publicado em: {{ $videoData['posted_date'] }}</div>
                <p>{{ $videoData['description'] }}</p>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const description = document.querySelector('.description');
            const moreText = '... mais';
            const lessText = 'Mostrar menos';

            description.addEventListener('click', () => {
                if (description.classList.contains('expanded')) {
                    description.classList.remove('expanded');
                    description.style.maxHeight = '4em'; // Define o maxHeight inicial
                    description.querySelector('::after').content = moreText;
                } else {
                    description.classList.add('expanded');
                    description.style.maxHeight = null; // Remove o maxHeight para expandir completamente
                    description.querySelector('::after').content = lessText;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');

            // Se não há token, redireciona para a página de login
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Verifica se o token é válido
            fetch('/api/auth/validade', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                // Se o token não for válido, remove do localStorage e redireciona
                if (response.status !== 200) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                }
            })
            .catch(error => {
                alert(error);
            });
        });

        function goBack() {
            window.history.back();
        }

        function deleteVideo() {
            if (confirm('Tem certeza que deseja deletar este vídeo?')) {
                fetch('/api/videos/{{ $videoData['id'] }}', {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        alert(data.details);
                    } else {
                        alert(data.details);
                    }
                    window.location.href = '/';
                })
                .catch(error => {
                    alert(error);
                });
            }
        }
    </script>
</body>

</html>
