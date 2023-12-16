<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>playBR</title>
    <style>
        /* Estilos para a Página */
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

        /* Estilos para os Vídeos */
        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1400px; /* Ajustado para caber 5 vídeos de 250px + 20px de gap entre eles */
            margin: auto;
        }
        .video-card-link {
            text-decoration: none;
            color: inherit;
        }
        .video-card {
            background-color: #333;
            color: #fff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            height: 300px; /* Defina uma altura fixa para os cards */
        }
        .video-thumbnail {
            width: 100%;
            height: 200px;
            background-color: black;
            background-size: cover;
            background-position: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .video-content {
            padding: 10px;
            flex-grow: 1;
        }
        .video-title {
            font-size: 18px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .video-info {
            font-size: 14px;
            color: #aaa;
            margin-top: auto;
        }

        /* Estilos para a barra de pesquisa */
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            position: relative;
        }

         /* Estilos para os Áudios */
         .title-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding-top: 20px;
            max-width: 1400px; /* Ajustado para caber 5 vídeos de 250px + 20px de gap entre eles */
            margin: auto;
        }
         .audios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1400px; /* Ajustado para caber 5 vídeos de 250px + 20px de gap entre eles */
            margin: auto;
        }

        audio {
            width: 100%; /* Faz o player ocupar todo o espaço do cartão */
            outline: none; /* Remove o contorno ao focar */
        }

         .audio-card-link {
            text-decoration: none;
            color: inherit;
        }

        .audio-card {
            background-color: #333;
            color: #fff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            height: 160px; /* Altura menor para áudios */
            position: relative;
        }

        .audio-content {
            padding: 10px;
            flex-grow: 1;
        }

        .audio-title {
            font-size: 18px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .audio-info {
            font-size: 14px;
            color: #aaa;
            margin-top: auto;
        }


        /* Estilos para a Busca */
        .search-container input[type="search"] {
            width: 700px; /* Largura do input */
            height: 50px; /* Altura do input */
            padding: 10px 20px 10px 40px; /* Padding para texto e ícone de lupa */
            border: 2px solid transparent; /* Estilo da borda, transparente por padrão */
            border-radius: 25px; /* Bordas arredondadas */
            font-size: 18px; /* Tamanho da fonte */
            background-color: #3a3a3a; /* Cor de fundo padrão para o input */
            color: white; /* Cor da fonte */
            outline: none; /* Remover o contorno quando focado */
            transition: border-color 0.3s; /* Transição suave para a cor da borda */
        }

        .search-container input[type="search"]::placeholder {
            color: #bbb;
        }

        /* Estilos para hover */
        .search-container input[type="search"]:hover {
            background-color: #3a3a3a; /* Cor de fundo no hover */
        }

        /* Estilos para foco */
        .search-container input[type="search"]:focus {
            border-color: #007bff; /* Cor da borda ao clicar (azul) */
            background-color: #444; /* Cor de fundo ao focar */
        }

        /* Adicionando o ícone de lupa via CSS */
        .search-container input[type="search"] {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23bbb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>') no-repeat;
            background-position: 12px center;
            background-size: 20px 20px;
        }

        /* Estilos para o botão de upload */
        .upload-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-size: 16px;
            margin-top: 20px;
        }

        .upload-button:hover {
            background-color: #444;
        }

        /* Estilos para o botão de deletar */
        .delete-button {
            padding: 5px 10px;
            background-color: #ff4b5a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: absolute; /* Posicionamento absoluto */
            bottom: 10px; /* 10px acima da parte inferior do cartão */
            right: 10px; /* 10px à esquerda da parte direita do cartão */
            font-size: 12px; /* Tamanho da fonte menor para um botão pequeno */
        }

        .delete-button:hover {
            background-color: #ff2f43;
        }

        /* Estilos para o usuário logado */
        .user-menu-container {
            position: absolut;
            right: 20px;
            top: 20px;
            text-align: right;
        }

        .user-menu-background {
            color: #fff;
            background-color: #333;
            border-radius: 5px;
        }

        .user-menu {
            display: inline-block;
            cursor: pointer;
            color: #fff;
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 8px 0 rgba(16,16,16,0.2);
        }

        .user-dropdown {
            background-color: #333;
            margin-top: 5px;
            border-radius: 5px;
            display: inline-block;
            cursor: pointer;
            position: absolute;
            padding: 10px 10px;
            right: 20px;
            text-align: right;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 3;
        }

        .user-dropdown a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .user-dropdown:hover {
            background-color: #444;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Menu do Usuário -->
    <div class="user-menu-container">
        <div class="user-menu-background">
            <div class="user-menu" onclick="toggleDropdown()">
                <span id="userName" >Usuário</span><br>
                <span id="userEmail" ">email@example.com</span>
            </div>
        </div>
        <div class="user-dropdown" id="userDropdown" style="display: none;" onclick="logout()">
            <a href="#" class="">Deslogar</a>
        </div>
    </div>
    <div class="search-upload-container">
        <div class="search-container">
            <form action="{{ url('/search') }}" method="GET">
                <input type="search" name="q" placeholder="Pesquisar..." aria-label="Procurar pelo conteúdo.">
                <!-- O ícone de lupa será adicionado via CSS -->
            </form>
        </div>
        <div style="text-align: center;">
            <a href="{{ url('/uploadVideo') }}" class="upload-button">Enviar Vídeo</a>
            <a href="{{ url('/uploadSound') }}" class="upload-button">Enviar Audio</a>
        </div>
        
    </div>
    <h1 class="title-grid">Vídeos</h1>
    <div class="videos-grid">
        @foreach ($videos as $video)
            <a href="/video/{{ $video['id'] }}" class="video-card-link">
                <div class="video-card">
                <div class="video-thumbnail" style="background-image: url('{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : '' }}');"></div>
                    <div class="video-content">
                        <h2 class="video-title">{{ $video['title'] }}</h2>
                        <p class="video-info">{{ $video->user->name}} <br> {{ \Carbon\Carbon::parse($video['created_at'])->diffForHumans() }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <h1 class="title-grid">Áudios</h1>
    <div class="audios-grid">
        @foreach ($audios as $audio)
            <div class="audio-card">
                <div class="audio-content">
                    <h2 class="audio-title">{{ $audio['title'] }}</h2>
                    <audio controls>
                        <source src="{{ url('/api/sounds/' . $audio['id']) }}" type="audio/mpeg">
                        Seu navegador não suporta a tag de áudio.
                    </audio>
                    <p class="audio-info">{{ $audio->user->name}} <br> {{ \Carbon\Carbon::parse($audio['created_at'])->diffForHumans() }}</p>
                </div>
                <button onclick="deleteAudio({{ $audio['id'] }})" class="delete-button">Deletar Áudio</button>
            </div>
        @endforeach
    </div>

    <script>
        // Carregar dados do usuário do localStorage
        window.onload = function() {
            const userData = JSON.parse(localStorage.getItem('user'));
            if (userData) {
                document.getElementById('userName').textContent = userData.name;
                document.getElementById('userEmail').textContent = userData.email;
            }
        };

        // Toggle Dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Logout
        function logout() {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    localStorage.removeItem('user');
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                } else {
                    alert('Erro ao tentar deslogar.');
                }
            }).catch(error => {
                console.error('Erro ao deslogar:', error);
            });
        }

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

        function deleteAudio(id) {
            if (!confirm('Tem certeza que deseja deletar este áudio?')) return;

            fetch(`/api/sounds/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'), // Presumindo autenticação via token
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    alert('Áudio deletado com sucesso!');
                    window.location.reload(); // Recarregar a página para atualizar a lista
                } else {
                    alert('Erro ao deletar o áudio.');
                }
            })
            .catch(error => {
                alert('Erro: ' + error);
            });
        }
    </script>
</body>
</html>