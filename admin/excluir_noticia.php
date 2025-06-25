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
    if ($noticia['imagem']) {
        $image_path = "../assets/images/uploads/" . $noticia['imagem'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    if ($stmt->execute([$noticia['id']])) {
        $_SESSION['sucesso'] = "Notícia excluída com sucesso!";
        header('Location: dashboard.php');
        exit();
    } else {
        $erro = "Erro ao excluir notícia. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Notícia | Cultura Underground</title>
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
        <section class="delete-section">
            <h2>Excluir Notícia</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            <div class="delete-confirm">
                <p>Tem certeza que deseja excluir a notícia <strong>"<?= htmlspecialchars($noticia['titulo']) ?>"</strong>?</p>
                <p>Esta ação não pode ser desfeita.</p>
                <form method="POST">
                    <div class="form-actions">
                        <button type="submit" class="btn-confirm-delete">Confirmar Exclusão</button>
                        <a href="dashboard.php" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>