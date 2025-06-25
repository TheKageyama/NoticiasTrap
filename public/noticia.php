<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$noticia = getNoticia($_GET['id']);

if (!$noticia) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $noticia['titulo'] ?> | Cultura Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">CULTURA UNDERGROUND</h1>
            <nav class="nav">
                <a href="index.php">Home</a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../admin/dashboard.php">Dashboard</a>
                    <a href="../admin/nova_noticia.php">Nova Notícia</a>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="cadastro.php">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <article class="noticia-completa">
            <?php if ($noticia['imagem']): ?>
               <div class="noticia-banner" style="background-image: url('../assets/images/uploads/<?= $noticia['imagem'] ?>')"></div>
            <?php endif; ?>
            <div class="noticia-conteudo">
                <span class="noticia-categoria"><?= $noticia['categoria'] ?></span>
                <h1><?= $noticia['titulo'] ?></h1>
                <div class="noticia-meta">
                    <span class="autor">Por <?= $noticia['autor_nome'] ?></span>
                    <span class="data"><?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
                </div>
                <div class="noticia-texto">
                    <?= nl2br(htmlspecialchars($noticia['noticia'])) ?>
                </div>
                
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $noticia['autor']): ?>
                    <div class="noticia-acoes">
                        <a href="../admin/editar_noticia.php?id=<?= $noticia['id'] ?>" class="btn-editar">Editar</a>
                        <a href="../admin/excluir_noticia.php?id=<?= $noticia['id'] ?>" class="btn-excluir">Excluir</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <footer class="autor">
                <h3>Sobre o autor</h3>
                <p><?= $noticia['autor_bio'] ?? 'Autor não forneceu uma biografia.' ?></p>
            </footer>
        </article>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>