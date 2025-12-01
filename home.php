<?php
session_start();

if (!isset($_SESSION['nome_usuario'])) {
    header("Location: index.php");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
$user_id = $_SESSION['user_id'] ?? null;

require_once __DIR__ . '/config.php';

// Buscar últimas postagens para o feed
$posts_per_page = 5;
$posts_stmt = $mysqli->prepare("
    SELECT 
        PO.ID AS id,
        PO.TEXTO AS texto,
        P.NOME AS nome
    FROM POSTAGEM PO
    JOIN PERFIL P ON P.ID = PO.PERFIL_ID
    ORDER BY PO.ID DESC
    LIMIT ?
");

if ($posts_stmt) {
    $posts_stmt->bind_param("i", $posts_per_page);
    $posts_stmt->execute();
    $posts = $posts_stmt->get_result();
    $initial_posts_count = $posts ? $posts->num_rows : 0;
    $posts_stmt->close();
} else {
    $posts = false;
    $initial_posts_count = 0;
}
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
                    <form action="postar.php" method="POST">
                        <div class="form-group">
                            <textarea name="texto" rows="3" required placeholder="O que você está pensando?"></textarea>
                        </div>
                        <button type="submit" class="btn">Publicar</button>
                    </form>
                </div>
                
                <?php if ($posts && $posts->num_rows > 0): ?>
                    <div id="posts-container" data-limit="<?php echo $posts_per_page; ?>">
                        <?php while ($row = $posts->fetch_assoc()): ?>
                            <div class="post">
                                <div class="post-header">
                                    <strong><?php echo htmlspecialchars($row['nome']); ?></strong>
                                </div>
                                <div><?php echo nl2br(htmlspecialchars($row['texto'])); ?></div>
                                <div class="post-actions">
                                    <a href="#">Curtir</a>
                                    <a href="#">Comentar</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php if ($initial_posts_count >= $posts_per_page): ?>
                        <div class="load-more-wrapper">
                            <button type="button" id="load-more" class="btn btn-secondary" data-offset="<?php echo $initial_posts_count; ?>">Carregar mais</button>
                            <div id="load-more-status" class="load-status"></div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Sem postagens ainda.</p>
                <?php endif; ?>
                
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

    <script>
        (function() {
            const postsContainer = document.getElementById('posts-container');
            const loadMoreBtn = document.getElementById('load-more');
            const statusEl = document.getElementById('load-more-status');

            if (!postsContainer || !loadMoreBtn) {
                return;
            }

            const limit = parseInt(postsContainer.dataset.limit, 10) || 5;

            const setStatus = (text, isError = false) => {
                if (!statusEl) return;
                statusEl.textContent = text || '';
                if (isError) {
                    statusEl.classList.add('error');
                } else {
                    statusEl.classList.remove('error');
                }
            };

            const formatText = (texto) => {
                const temp = document.createElement('div');
                temp.textContent = texto || '';
                return temp.innerHTML.replace(/\n/g, '<br>');
            };

            const renderPost = (post) => {
                const postEl = document.createElement('div');
                postEl.className = 'post';

                const header = document.createElement('div');
                header.className = 'post-header';
                header.textContent = post.nome || '';

                const body = document.createElement('div');
                body.innerHTML = formatText(post.texto);

                const actions = document.createElement('div');
                actions.className = 'post-actions';

                const like = document.createElement('a');
                like.href = '#';
                like.textContent = 'Curtir';

                const comment = document.createElement('a');
                comment.href = '#';
                comment.textContent = 'Comentar';

                actions.appendChild(like);
                actions.appendChild(comment);

                postEl.appendChild(header);
                postEl.appendChild(body);
                postEl.appendChild(actions);

                return postEl;
            };

            loadMoreBtn.addEventListener('click', () => {
                const offset = parseInt(loadMoreBtn.dataset.offset, 10) || 0;

                loadMoreBtn.disabled = true;
                loadMoreBtn.textContent = 'Carregando...';
                setStatus('');

                fetch(`carregar_posts.php?offset=${offset}&limit=${limit}`, { credentials: 'same-origin' })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Erro ao buscar posts');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        if (!data || !Array.isArray(data.posts)) {
                            throw new Error('Resposta invalida');
                        }

                        if (data.posts.length === 0) {
                            loadMoreBtn.remove();
                            setStatus('Sem mais posts');
                            return;
                        }

                        data.posts.forEach((post) => {
                            postsContainer.appendChild(renderPost(post));
                        });

                        const newOffset = offset + data.posts.length;
                        loadMoreBtn.dataset.offset = newOffset;

                        if (data.hasMore) {
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.textContent = 'Carregar mais';
                        } else {
                            loadMoreBtn.remove();
                            setStatus('Sem mais posts');
                        }
                    })
                    .catch(() => {
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Carregar mais';
                        setStatus('Nao foi possivel carregar. Tente novamente.', true);
                    });
            });
        })();
    </script>
</body>
</html>
