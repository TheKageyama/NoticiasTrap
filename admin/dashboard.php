<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

$usuario_id = $_SESSION['usuario_id'];
$noticias = getNoticiasPorAutor($usuario_id);
$usuario = getUsuario($usuario_id);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Cultura Underground</title>
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
        <div class="dashboard">
            <div class="profile-card">
                <h2>Seu Perfil</h2>
                <p><strong>Nome:</strong> <?= $usuario['nome'] ?></p>
                <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
                <div class="profile-actions">
                    <a href="editar_usuario.php" class="btn-edit">Editar Perfil</a>
                    <a href="excluir_usuario.php" class="btn-delete">Excluir Conta</a>
                </div>
            </div>

            <div class="user-news">
                <h2>Suas Notícias</h2>
                <?php if (count($noticias) > 0): ?>
                    <div class="news-list">
                        <?php foreach ($noticias as $noticia): ?>
                            <div class="news-item">
                                <h3><a href="../public/noticia.php?id=<?= $noticia['id'] ?>"><?= $noticia['titulo'] ?></a></h3>
                                <div class="news-meta">
                                    <span><?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
                                    <span><?= $noticia['categoria'] ?></span>
                                </div>
                                <div class="news-actions">
                                    <a href="editar_noticia.php?id=<?= $noticia['id'] ?>" class="btn-edit">Editar</a>
                                    <a href="excluir_noticia.php?id=<?= $noticia['id'] ?>" class="btn-delete">Excluir</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-news">Você ainda não publicou nenhuma notícia.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>