<?php
session_start();

require_once __DIR__ . '/config.php';

if (!isset($_SESSION['nome_usuario'])) {
    header("Location: index.php");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
$user_id = intval($_SESSION['user_id']);

// consultar data de nascimento e calcular idade
$consulta_data_nasc = $mysqli->query("SELECT DATA_NASC FROM PERFIL WHERE NOME='" . $nome_usuario . "' LIMIT 1");
$row_data = $consulta_data_nasc->fetch_assoc();
$data_nasc = new DateTime($row_data['DATA_NASC']);
$idade = $data_nasc->diff(new DateTime())->y;
// echo "Idade: " . $idade . " anos";

// consultar foto
$consultar_foto = $mysqli->query("SELECT FOTO FROM PERFIL WHERE ID='" . $_SESSION['user_id'] . "' LIMIT 1");

// Postagens do usuario logado
$user_posts = $mysqli->query("
    SELECT ID, TEXTO, IMAGEM
    FROM POSTAGEM
    WHERE PERFIL_ID = $user_id
    ORDER BY ID DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conect24 - Meu Perfil</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!--NAVBAR-->
    <div class="cima">
        <h1>Conect24</h1>
        <div class="nav-links">
            <span style="font-weight: bolder; margin-right: 18px;">Olá, <?php echo htmlspecialchars($nome_usuario); ?>!</span>
            <a href="home.php">Início</a>
            <a href="perfil.php">Meu perfil</a>
            <a href="editar_perfil.php">Editar perfil</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
    <!--CONTAINER DE TUDO-->
    <div class="container">
        <!--AS 3 COLUNAS-->
        <div class="colunas3">
            <!--COLUNA DA ESQUERDA-->
            <div class="sidebar-left">
                <!-- CARTÕES-->
                <div class="card">
                    <h3>Foto do perfil</h3>
                    <div class="profile-photo">
                        <!-- fazer query pra foto de perfil também e não sei como exibir -->
                        <div style="margin: 15px 0;">
                            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #ddd; margin-bottom: 10px; margin-left: auto; margin-right: auto;">
                                <img id="preview-foto" src="<?php 
                                    // Buscar foto atual do banco
                                    $query_foto = $mysqli->query("SELECT FOTO FROM PERFIL WHERE ID=$user_id");
                                    $foto_atual = $query_foto->fetch_assoc()['FOTO'];
                                    echo $foto_atual ? htmlspecialchars($foto_atual) : 'default.jpg'; 
                                ?>" alt="Foto de perfil" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- TABELA COM AS INFORMAÇÕES -->
                    <h4>Informações básicas</h4>
                    <table class="info-table">
                        <tr>
                            <td>Nome:</td>
                            <td style="font-weight: bolder;"><?php echo htmlspecialchars($nome_usuario); ?></td>
                        </tr>
                        <tr>
                            <td>Idade:</td>
                            <td style="font-weight: bolder;"><?php echo htmlspecialchars($idade); ?></td>
                        </tr>
                    </table>
                    <br>
                    <!-- BOTÃO DE EDITAR PERFIL-->
                    <a href="editar_perfil.php" style="text-decoration: none;">
                        <button class="btn" style="cursor: pointer;"><span style="font-weight: bold; color: white;">Editar perfil</span></button>
                    </a>
                </div>
            </div>
            
            <div class="main-content">
                <div class="card">
                    <h2 style="margin-bottom: 10px;"><?php echo htmlspecialchars($nome_usuario); ?></h2>
                    <br>
                    <h3>Sobre mim</h3>
                    <p>
<!--  fazer consulta aqui da biografia-->
                        <?php 

                        require_once __DIR__ . '/config.php';
                        $consulta_bio = $mysqli->query("SELECT BIO FROM PERFIL WHERE NOME='" . $nome_usuario . "' LIMIT 1");
                         
                        if ($consulta_bio) {

                            $row_bio = $consulta_bio->fetch_assoc(); // <-- BUSCA APENAS UMA VEZ

                            if ($row_bio && !empty($row_bio['BIO'])) {
                                echo htmlspecialchars($row_bio['BIO']);
                            } else {
                                echo "$nome_usuario não adicionou uma biografia ainda.";
                            }

                        }
                        
                        ?>
                    </p>
                </div>

                <div class="card">
                    <h3>Minhas postagens</h3>
                    <?php if ($user_posts && $user_posts->num_rows > 0):
                        while ($post = $user_posts->fetch_assoc()): ?>
                            <div class="post">
                                <div><?php echo nl2br(htmlspecialchars($post['TEXTO'])); ?></div>
                                <?php if (!empty($post['IMAGEM'])): ?>
                                    <div class="post-image">
                                        <img src="<?php echo htmlspecialchars($post['IMAGEM']); ?>" alt="Imagem da postagem" style="max-width: 100%; height: auto;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile;
                            else: ?>
                        <p>Voce ainda nao postou nada.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="sidebar-right">
                <div class="card">
                    <h3>Amigos</h3>
                    <ul class="menu-list">
<!-- Fazer consulta também aqui pra ver os amigos e exibir em lista de alguma forma -->
                        <li><a href="#">carlos</a></li>
                        <li><a href="#">fernando pessoa</a></li>
                        <li><a href="#">joão machado</a></li>
                        <li><a href="#">mickey mouse</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
