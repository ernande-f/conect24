<?php
session_start(); // inicia a sessao pra saber quem esta logado

if (!isset($_SESSION['nome_usuario'])) {
    // se nao tem usuario logado, manda pra index e encerra
    header("Location: index.php");
    exit();
}

// guarda dados do usuario logado pra usar na pagina
$nome_usuario = $_SESSION['nome_usuario'];
$user_id = $_SESSION['user_id'] ?? null;

require_once __DIR__ . '/config.php'; // puxa config/ conexao com o banco

// carregar mais posts
$baseLimit = 10; // quantos posts cada clique carrega
$p = max(1, $_GET['p'] ?? 1); // pega a paginacao p da querystring, garantindo minimo 1
$limit = $baseLimit * $p; // total de posts que vamos pedir agora

// query das postagens no banco de dados (texto, imagem e nome do autor)
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
            <span style="font-weight: bolder; margin-right: 18px;">Ola, <?php echo htmlspecialchars($nome_usuario); // mostra nome do logado ?>!</span>
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
                        <li><a href="editar_perfil.php">Editar perfil</a></li>
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
                
                <?php if ($posts && $posts->num_rows > 0): // se veio lista de posts, percorre e mostra um a um
                    while ($row = $posts->fetch_assoc()): ?>
                        <div class="post">
                            <div class="post-header">
                                <strong><?php echo htmlspecialchars($row['NOME']); // nome de quem postou ?></strong>
                            </div>
                            <div><?php echo nl2br(htmlspecialchars($row['TEXTO'])); // texto do post com quebras ?></div>
                            <?php if (!empty($row['IMAGEM'])): // se tiver imagem salva, exibe ?>
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
                        else: // se nao tiver nenhum post, mostra recado ?>
                    <p>Sem postagens ainda.</p>
                <?php endif; ?>

                <?php if ($posts && $posts->num_rows >= $limit): // se ainda tem mais posts alem do limite, mostra botao pra carregar ?>
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
