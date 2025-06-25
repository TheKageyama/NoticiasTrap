<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/verifica_login.php';
require_once __DIR__ . '/../includes/funcoes.php';

$usuario = getUsuario($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Validações básicas
    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios!";
    } else {
        // Verifica se o email já existe (para outro usuário)
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['usuario_id']]);
        
        if ($stmt->fetch()) {
            $erro = "Este e-mail já está em uso por outro usuário!";
        } else {
            // Atualiza os dados básicos
            $sql = "UPDATE usuarios SET nome = ?, email = ?, bio = ?";
            $params = [$nome, $email, $bio];
            
            // Se informou senha, verifica e atualiza
            if (!empty($senha_atual)) {
                if (!password_verify($senha_atual, $usuario['senha'])) {
                    $erro = "Senha atual incorreta!";
                } elseif ($nova_senha !== $confirmar_senha) {
                    $erro = "As novas senhas não coincidem!";
                } elseif (strlen($nova_senha) < 6) {
                    $erro = "A nova senha deve ter pelo menos 6 caracteres!";
                } else {
                    $sql .= ", senha = ?";
                    $params[] = password_hash($nova_senha, PASSWORD_DEFAULT);
                }
            }
            
            if (!isset($erro)) {
                $sql .= " WHERE id = ?";
                $params[] = $_SESSION['usuario_id'];
                
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $sucesso = "Perfil atualizado com sucesso!";
                    $_SESSION['usuario_nome'] = $nome;
                    $usuario = getUsuario($_SESSION['usuario_id']);
                } else {
                    $erro = "Erro ao atualizar perfil. Tente novamente.";
                }
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
    <title>Editar Perfil | Cultura Underground</title>
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
        <section class="form-section">
            <h2>Editar Perfil</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Biografia</label>
                    <textarea id="bio" name="bio" rows="4"><?= htmlspecialchars($usuario['bio'] ?? '') ?></textarea>
                </div>
                
                <h3 class="form-subtitle">Alterar Senha</h3>
                <p class="form-info">Preencha apenas se desejar alterar sua senha</p>
                
                <div class="form-group">
                    <label for="senha_atual">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual">
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha">
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha">
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