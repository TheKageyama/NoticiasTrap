<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $noticia = trim($_POST['noticia'] ?? '');
    $autor = $_SESSION['usuario_id'];
    
    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImagem($_FILES['imagem']);
        if ($upload['success']) {
            $imagem = $upload['filename'];
        } else {
            $erro = $upload['message'];
        }
    }
    
    if (empty($titulo) || empty($categoria) || empty($noticia)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, categoria, noticia, imagem, autor, data) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$titulo, $categoria, $noticia, $imagem, $autor])) {
            $sucesso = "Notícia publicada com sucesso!";
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = "Erro ao publicar notícia. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Notícia | Cultura Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">CULTURA UNDERGROUND</h1>
            <nav class="nav">
                <a href="../public/index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="nova_noticia.php">Nova Notícia</a>
                <a href="../public/logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="auth-form">
            <h2>Nova Notícia</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione...</option>
                        <option value="Música">Música</option>
                        <option value="Arte">Arte</option>
                        <option value="Cinema">Cinema</option>
                        <option value="Literatura">Literatura</option>
                        <option value="Eventos">Eventos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagem">Imagem (opcional)</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="noticia">Notícia</label>
                    <textarea id="noticia" name="noticia" rows="10" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-publish">Publicar</button>
                    <a href="dashboard.php" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>