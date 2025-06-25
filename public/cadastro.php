<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } else {
        // Verifica se o email já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Cadastra o usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$nome, $email, $senha_hash])) {
                $sucesso = "Cadastro realizado com sucesso! Faça login.";
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Cultura Underground</title>
   <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">CULTURA UNDERGROUND</h1>
            <nav class="nav">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="cadastro.php">Cadastre-se</a>
            </nav>
        </div>
    </header>

    <main class="container auth-container">
        <section class="auth-form">
            <h2>Junte-se à Cena</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>
                <button type="submit" class="btn-register">Cadastrar</button>
            </form>
            <p class="auth-link">Já tem conta? <a href="login.php">Faça login</a></p>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>