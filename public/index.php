<?php
require_once __DIR__ . '/../includes/conexao.php';;
require_once __DIR__ . '/../includes/funcoes.php';;

$noticias = getNoticias();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultura Underground | Notícias da Cena Alternativa</title>
 <!-- Caminho CORRETO para estrutura organizada -->
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
                    <a href="..admin/logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="cadastro.php">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="destaque">
            <h2 class="section-title">ÚLTIMAS DA CENA</h2>
            <div class="noticias-grid">
                <?php foreach ($noticias as $noticia): ?>
                    <article class="noticia-card">
                        <?php if ($noticia['imagem']): ?>
                            <div class="noticia-imagem" style="background-image: url('<?= $noticia['imagem'] ?>')"></div>
                        <?php else: ?>
                            <div class="noticia-imagem" style="background-image: url('imagens/default.jpg')"></div>
                        <?php endif; ?>
                        <div class="noticia-info">
                            <span class="noticia-categoria"><?= $noticia['categoria'] ?></span>
                            <h3><a href="noticia.php?id=<?= $noticia['id'] ?>"><?= $noticia['titulo'] ?></a></h3>
                            <p><?= substr($noticia['noticia'], 0, 150) ?>...</p>
                            <div class="noticia-meta">
                                <span class="autor">Por <?= $noticia['autor_nome'] ?></span>
                                <span class="data"><?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
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