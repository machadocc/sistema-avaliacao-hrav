<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit();
}

require_once '../src/funcoes.php';
require_once '../src/perguntas.php';

// Lidar com adição de perguntas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'adicionarPergunta') {
        $texto = $_POST['texto_pergunta'];
        $ativo = isset($_POST['ativo']) ? true : false;
        $adicionado = adicionarPergunta($texto, $ativo);
        if ($adicionado) {
            echo "Pergunta adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar pergunta.";
        }
    }
}

// Listar perguntas e dispositivos
$perguntas = listarPerguntas();
$dispositivos = listarDispositivos(); // Se você tiver essa função
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Painel Administrativo</title>
</head>
<body>
    <h1>Painel Administrativo</h1>
    
    <!-- Gerenciamento de Perguntas -->
    <section id="perguntas">
        <h2>Gerenciamento de Perguntas</h2>
        <form method="POST" action="">
            <input type="text" name="texto_pergunta" placeholder="Nova pergunta" required>
            <label>
                <input type="checkbox" name="ativo" checked>
                Ativo
            </label>
            <input type="hidden" name="acao" value="adicionarPergunta">
            <button type="submit">Adicionar Pergunta</button>
        </form>
        <ul>
            <?php foreach ($perguntas as $pergunta): ?>
                <li>
                    <?= htmlspecialchars($pergunta['texto']) ?>
                    <a href="editarPergunta.php?id=<?= $pergunta['id'] ?>">Editar</a>
                    <a href="removerPergunta.php?id=<?= $pergunta['id'] ?>">Remover</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <!-- Gerenciamento de Dispositivos -->
    <section id="dispositivos">
        <h2>Gerenciamento de Dispositivos</h2>
        <form method="POST" action="adicionarDispositivo.php">
            <input type="text" name="nome_dispositivo" placeholder="Nome do dispositivo" required>
            <button type="submit">Adicionar Dispositivo</button>
        </form>
        <ul>
            <?php foreach ($dispositivos as $dispositivo): ?>
                <li>
                    <?= htmlspecialchars($dispositivo['nome']) ?>
                    <a href="removerDispositivo.php?id=<?= $dispositivo['id'] ?>">Remover</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <!-- Adicione mais seções conforme necessário -->
    
</body>
</html>
