<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$noticia = getNoticia($_GET['id']);

if (!$noticia || $noticia['autor'] != $_SESSION['usuario_id']) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $noticia_texto = trim($_POST['noticia'] ?? '');
    
    $imagem = $noticia['imagem'];
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImagem($_FILES['imagem']);
        if ($upload['success']) {
            // Remove a imagem antiga se existir
            if ($imagem) {
                $old_image_path = "../assets/images/uploads/" . $imagem;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $imagem = $upload['filename'];
        } else {
            $erro = $upload['message'];
        }
    }
    
    if (empty($titulo) || empty($categoria) || empty($noticia_texto)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, categoria = ?, noticia = ?, imagem = ? WHERE id = ?");
        if ($stmt->execute([$titulo, $categoria, $noticia_texto, $imagem, $noticia['id']])) {
            $sucesso = "Notícia atualizada com sucesso!";
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = "Erro ao atualizar notícia. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia | Cultura Underground</title>
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
            <h2>Editar Notícia</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione...</option>
                        <option value="Música" <?= $noticia['categoria'] === 'Música' ? 'selected' : '' ?>>Música</option>
                        <option value="Arte" <?= $noticia['categoria'] === 'Arte' ? 'selected' : '' ?>>Arte</option>
                        <option value="Cinema" <?= $noticia['categoria'] === 'Cinema' ? 'selected' : '' ?>>Cinema</option>
                        <option value="Literatura" <?= $noticia['categoria'] === 'Literatura' ? 'selected' : '' ?>>Literatura</option>
                        <option value="Eventos" <?= $noticia['categoria'] === 'Eventos' ? 'selected' : '' ?>>Eventos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagem">Imagem (opcional)</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <?php if ($noticia['imagem']): ?>
                        <p>Imagem atual: <a href="../../assets/images/uploads/<?= $noticia['imagem'] ?>" target="_blank"><?= $noticia['imagem'] ?></a></p>
                        <label><input type="checkbox" name="remover_imagem"> Remover imagem</label>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="noticia">Notícia</label>
                    <textarea id="noticia" name="noticia" rows="10" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Salvar Alterações</button>
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