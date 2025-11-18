<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/config.php';

$user_id = $_SESSION['user_id']; // ID único do usuário
$nome_usuario = $_SESSION['nome_usuario'];

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_novo = $_POST['nome'];
    $email_novo = $_POST['email'];
    $data_nasc_novo = $_POST['data_nasc'];
    $bio_novo = $_POST['bio'];
    $foto_path = null;
    
    // Processar upload da foto se foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_nome = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        
        // Criar pasta uploads se não existir
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Gerar nome único para a foto
        $extensao = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $novo_nome = 'perfil_' . $user_id . '_' . time() . '.' . $extensao;
        $foto_path = 'uploads/' . $novo_nome;
        
        // Mover arquivo para a pasta uploads
        move_uploaded_file($foto_tmp, $foto_path);
    }
    
    // Atualizar no banco
    if ($foto_path) {
        $update = "UPDATE PERFIL SET NOME='$nome_novo', EMAIL='$email_novo', DATA_NASC='$data_nasc_novo', BIO='$bio_novo', FOTO='$foto_path' WHERE ID=$user_id";
    } else {
        $update = "UPDATE PERFIL SET NOME='$nome_novo', EMAIL='$email_novo', DATA_NASC='$data_nasc_novo', BIO='$bio_novo' WHERE ID=$user_id";
    }

    if ($mysqli->query($update) === TRUE) {
        $_SESSION['nome_usuario'] = $nome_novo;
        header("Location: perfil.php");
        exit();
    } else {
        echo "Erro ao atualizar: " . $mysqli->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conect24 - Editar Perfil</title>
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
            </div>
            
            <div class="main-content">
                <div class="card">
                    <h2>Editar Perfil</h2>
                    <br>
                    <form method="POST" action="editar_perfil.php" enctype="multipart/form-data">
                        <label for="foto">Foto de Perfil:</label>
                        <div style="margin: 15px 0;">
                            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #ddd; margin-bottom: 10px;">
                                <img id="preview-foto" src="<?php 
                                    // Buscar foto atual do banco
                                    $query_foto = $mysqli->query("SELECT FOTO FROM PERFIL WHERE ID=$user_id");
                                    $foto_atual = $query_foto->fetch_assoc()['FOTO'];
                                    echo $foto_atual ? htmlspecialchars($foto_atual) : 'https://via.placeholder.com/120'; 
                                ?>" alt="Foto de perfil" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <input type="file" id="foto" name="foto" accept="image/*">
                        </div>
                        <br>
                        
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome_usuario); ?>" required>
                        <br><br>
                        
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                        <br><br>
                        
                        <label for="data_nasc">Data de Nascimento:</label>
                        <input type="date" id="data_nasc" name="data_nasc" required>
                        <br><br>
                        
                        <label for="bio">Biografia:</label>
                        <textarea id="bio" name="bio" rows="5" style="width: 100%;"></textarea>
                        <br><br>
                        
                        <button type="submit" class="btn">Salvar Alterações</button>
                        <a href="perfil.php" style="text-decoration: none;">
                            <button type="button" class="btn">Cancelar</button>
                        </a>
                    </form>
                </div>
            </div>

            <!--COLUNA DA DIREITA-->
            <div class="sidebar-right">
            </div>
        </div>
    </div>
</body>
</html>