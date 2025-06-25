<?php

require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/verifica_login.php';
require_once __DIR__ . '/../includes/funcoes.php';

$usuario = getUsuario($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    
    if (empty($senha)) {
        $erro = "Por favor, confirme sua senha!";
    } elseif (!password_verify($senha, $usuario['senha'])) {
        $erro = "Senha incorreta!";
    } else {
        // Exclui todas as notícias do usuário primeiro (devido à chave estrangeira)
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE autor = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        // Agora exclui o usuário
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        
        if ($stmt->execute([$_SESSION['usuario_id']])) {
            session_destroy();
            header('Location: index.php');
            exit();
        } else {
            $erro = "Erro ao excluir conta. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta | Cultura Underground</title>
   <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">CULTURA UNDERGROUND</h1>
            <nav class="nav">
                <a href="index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="nova_noticia.php">Nova Notícia</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="delete-section">
            <h2>Excluir Conta</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <div class="delete-confirm">
                <p><strong>Atenção!</strong> Você está prestes a excluir sua conta permanentemente.</p>
                <p>Todas as suas notícias serão removidas e não poderão ser recuperadas.</p>
                <p>Para confirmar, digite sua senha:</p>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
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