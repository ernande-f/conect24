<?php
session_start();

if (!isset($_SESSION['nome_usuario'])) {
    header("Location: index.php");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conect24 - Início</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!--NAVBAR-->
    <div class="cima">
        <h1>Conect24</h1>
        <div class="nav-links">
            <span style="font-weight: bolder; margin-right: 18px;">Olá, <?php echo htmlspecialchars($nome_usuario); ?>!</span>
            <a href="perfil.php">Meu perfil</a>
            <a href="#">Editar perfil</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
    <!--CONTAINER DE TUDO-->
    <div class="container">
        <div class="colunas3">
            <div class="barra-esquerda">
                <div class="card">
                    <h3>Menu</h3>
                    <ul class="lista-menu">
                        <li><a href="home.php">Início</a></li>
                        <li><a href="perfil.php">Meu perfil</a></li>
                        <li><a href="#">Editar perfil</a></li>
                        <li><a href="#">Minhas comunidades</a></li>
                        <li><a href="cadastrar-comunidades.html">Criar comunidade</a></li>
                    </ul>
                </div>
                <!-- CARTÕES -->
                <div class="card">
                    <h4>Amigos online</h4>
                    <ul class="lista-amigos">
                        <li>carlos</li>
                        <li>fernando pessoa</li>
                        <li>joão machado</li>
                        <li>mickey mouse</li>
                    </ul>
                </div>
            </div>
            <!-- conteudo principal -->
            <div class="main-content">
                <div class="card">
                    <h3>Últimas atividades</h3>
                    <div class="activity-item">
                        <strong>joão machado</strong> postou uma nova foto 
                        <span style="float: right; color: #666;">há 2 horas</span>
                        <p>"machado novo"</p>
                    </div>
                </div>
                
                <div class="card">
                    <h4>Escreva algo:</h4>
                    <form>
                        <div class="form-group">
                            <textarea name="post" rows="3" placeholder="O que você está pensando?"></textarea>
                        </div>
                        <button type="submit" class="btn">Publicar</button>
                    </form>
                </div>
                
                <div class="post">
                    <div class="post-header">
                        <strong><?php echo htmlspecialchars($nome_usuario); ?></strong> - há 1 hora
                    </div>
                    <div>ótimo dia para estudar para a AIS</div>
                    <div class="post-actions">
                        <a href="#">Curtir</a>
                        <a href="#">Comentar</a>
                    </div>
                </div>
                
            </div>
            
            <div class="barra-direita">
                
                <div class="card">
                    <h4>Comunidades sugeridas</h4>
                    <ul class="lista-menu">
                        <li><a href="#">estudantes da AIS</a></li>
                        <li><a href="#">matrizes</a></li>
                        <li><a href="#">não era pra ter lei dos senos</a></li>
                        <li><a href="#">43</a></li>
                    </ul>
                </div>
                
                <div class="card">
                    <h4>Buscar pessoas</h4>
                    <form action="#" method="GET">
                        <div class="form-group">
                            <input type="text" name="busca" placeholder="Nome ou email">
                        </div>
                        <button type="submit" class="btn">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
