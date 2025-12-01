<?php
session_start();

if (!isset($_SESSION['nome_usuario'])) {
    header("Location: index.php");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
$user_id = $_SESSION['user_id'] ?? null;

require_once __DIR__ . '/config.php';

// carrear mais posts
$baseLimit = 10; // quantos posts por clique
$p = max(1, $_GET['p'] ?? 1); // p vai de 1 até p, se não é p
$limit = $baseLimit * $p; 

// query das postagens no banco de dados
$posts = $mysqli->query("
    SELECT PO.ID, PO.TEXTO, PO.IMAGEM, P.NOME
    FROM POSTAGEM PO
    JOIN PERFIL P ON P.ID = PO.PERFIL_ID
    ORDER BY PO.ID DESC
    LIMIT $limit
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conect24 - Inicio</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!--NAVBAR-->
    <div class="cima">
        <h1>Conect24</h1>
        <div class="nav-links">
            <span style="font-weight: bolder; margin-right: 18px;">Ola, <?php echo htmlspecialchars($nome_usuario); ?>!</span>
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
                        <li><a href="home.php">Inicio</a></li>
                        <li><a href="perfil.php">Meu perfil</a></li>
                        <li><a href="#">Editar perfil</a></li>
                        <li><a href="#">Minhas comunidades</a></li>
                        <li><a href="cadastrar-comunidades.html">Criar comunidade</a></li>
                    </ul>
                </div>
                <!-- CARTOES -->
                <div class="card">
                    <h4>Amigos online</h4>
                    <ul class="lista-amigos">
                        <li>carlos</li>
                        <li>fernando pessoa</li>
                        <li>joao machado</li>
                        <li>mickey mouse</li>
                    </ul>
                </div>
            </div>
            <!-- conteudo principal -->
            <div class="main-content">
                <div class="card">
                    <h3>Ultimas atividades</h3>
                    <div class="activity-item">
                        <strong>joao machado</strong> postou uma nova foto 
                        <span style="float: right; color: #666;">ha 2 horas</span>
                        <p>"machado novo"</p>
                    </div>
                </div>
                
                <div class="card">
                    <h4>Escreva algo:</h4>
                    <form action="postar.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <textarea name="texto" rows="3" required placeholder="O que voce esta pensando?"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="imagem" accept="image/*">
                        </div>
                        <button type="submit" class="btn">Publicar</button>
                    </form>
                </div>
                
                <?php if ($posts && $posts->num_rows > 0):
                    while ($row = $posts->fetch_assoc()): ?>
                        <div class="post">
                            <div class="post-header">
                                <strong><?php echo htmlspecialchars($row['NOME']); ?></strong>
                            </div>
                            <div><?php echo nl2br(htmlspecialchars($row['TEXTO'])); ?></div>
                            <?php if (!empty($row['IMAGEM'])): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($row['IMAGEM']); ?>" alt="Imagem da postagem" style="max-width: 100%; height: auto;">
                                </div>
                            <?php endif; ?>
                            <div class="post-actions">
                                <a href="#">Curtir</a>
                                <a href="#">Comentar</a>
                            </div>
                        </div>
                    <?php endwhile;
                        else: ?>
                    <p>Sem postagens ainda.</p>
                <?php endif; ?>

                <?php if ($posts && $posts->num_rows >= $limit): ?>
                    <form method="get" class="load-more-form">
                        <input type="hidden" name="p" value="<?php echo $p + 1; ?>">
                        <button type="submit" class="btn">Carregar mais</button>
                    </form>
                <?php endif; ?>
                
            </div>
            
            <div class="barra-direita">
                
                <div class="card">
                    <h4>Comunidades sugeridas</h4>
                    <ul class="lista-menu">
                        <li><a href="#">estudantes da AIS</a></li>
                        <li><a href="#">matrizes</a></li>
                        <li><a href="#">nao era pra ter lei dos senos</a></li>
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
