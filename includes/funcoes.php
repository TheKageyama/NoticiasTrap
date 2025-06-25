<?php
require_once 'conexao.php';

function verificaLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../public/login.php');
        exit();
    }
}

function getUsuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getNoticias($limit = null) {
    global $pdo;
    $sql = "SELECT n.*, u.nome as autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id ORDER BY n.data DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNoticia($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome, u.bio as autor_bio FROM noticias n JOIN usuarios u ON n.autor = u.id WHERE n.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getNoticiasPorAutor($autor_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE autor = ? ORDER BY data DESC");
    $stmt->execute([$autor_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function uploadImagem($file) {
    $target_dir = "../assets/images/uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Verifica se é uma imagem real
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ["success" => false, "message" => "O arquivo não é uma imagem."];
    }
    
     if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            return ["success" => false, "message" => "Falha ao criar diretório de upload."];
        }
    }
    // Verifica tamanho do arquivo (5MB máximo)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "O arquivo é muito grande (máximo 5MB)."];
    }
    
    // Permite apenas certos formatos
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        return ["success" => false, "message" => "Apenas JPG, JPEG, PNG e GIF são permitidos."];
    }
    
    // Gera um nome único para evitar sobrescrita
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_path = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_path)) {
        return ["success" => true, "filename" => $new_filename];
    } else {
        return ["success" => false, "message" => "Erro ao fazer upload da imagem."];
    }
}
?>