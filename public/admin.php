<?php
require_once '../src/db.php';
require_once '../src/perguntas.php';
require_once '../src/setores.php';
require_once '../src/dispositivos.php';
require_once '../src/respostas.php';

$pdo = dbConnect(); // Obtém a conexão com o banco de dados

// Inicializa variáveis para a paginação
$limit = 10; // Número de perguntas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Obtém o número da página atual
$offset = ($page - 1) * $limit; // Calcula o deslocamento

// Obtém as perguntas com limite e deslocamento
$perguntas = getPerguntas($limit, $offset);
$setores = getSetores();
$dispositivos = getDispositivos();

// Conta o total de perguntas para calcular o número de páginas
$totalPerguntas = $pdo->query("SELECT COUNT(*) FROM perguntas")->fetchColumn();
$totalPages = ceil($totalPerguntas / $limit);

// Adicionar nova pergunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_pergunta'])) {
    $nova_pergunta = $_POST['nova_pergunta'];
    $setor_id = $_POST['setor_id'];
    adicionarPergunta($nova_pergunta, $setor_id);
    header("Location: admin.php");
    exit;
}

// Editar pergunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_pergunta'])) {
    $nova_pergunta = $_POST['nova_pergunta'];
    $pergunta_id = $_POST['pergunta_id'];
    $setor_id = $_POST['setor_id'];
    $feedback_required = isset($_POST['feedback_required']) ? 1 : 0; // 1 se checked, 0 se não

    editarPergunta($pergunta_id, $nova_pergunta, $setor_id, $feedback_required); 
    header("Location: admin.php");
    exit;
}

// Excluir Pergunta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_pergunta'])) {
    $pergunta_id = $_POST['pergunta_id'];
    $stmt = $pdo->prepare("DELETE FROM respostas WHERE pergunta_id = :pergunta_id");
    $stmt->execute(['pergunta_id' => $pergunta_id]);
    excluirPergunta($pergunta_id);
    
    header("Location: admin.php");
    exit;
}

// Lógica de seleção do dispositivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selecionar_dispositivo'])) {
    $dispositivo_id = $_POST['dispositivo_id'];
    $stmt = $pdo->prepare("SELECT setor_id FROM dispositivos WHERE id = :id");
    $stmt->execute(['id' => $dispositivo_id]);
    $setor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($setor) {
        header("Location: index.php?setor_id=" . $setor['setor_id']);
        exit;
    }
}


// Adicionar novo setor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_setor'])) {
    $novo_setor = $_POST['novo_setor'];
    adicionarSetor($novo_setor);
    header("Location: admin.php");
    exit;
}

// Editar setor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_setor'])) {
    $novo_setor = $_POST['novo_setor'];
    $setor_id = $_POST['setor_id'];
    editarSetor($setor_id, $novo_setor);
    header("Location: admin.php");
    exit;
}

// Excluir setor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_setor'])) {
    $setor_id = $_POST['setor_id'];
    excluirSetor($setor_id);
    header("Location: admin.php");
    exit;
}

// Adicionar novo dispositivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_dispositivo'])) {
    $novo_dispositivo = $_POST['novo_dispositivo'];
    $setor_id = $_POST['setor_id_dispositivo'];
    adicionarDispositivo($novo_dispositivo, $setor_id); // Chama a função para adicionar
    header("Location: admin.php");
    exit;
}

// Editar dispositivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_dispositivo'])) {
    $dispositivo_id = $_POST['dispositivo_id'];
    $novo_nome = $_POST['novo_dispositivo'];
    $setor_id = $_POST['setor_id']; // Captura o setor selecionado
    editarDispositivo($dispositivo_id, $novo_nome, $setor_id); // Chama a função para editar
    header("Location: admin.php");
    exit;
}

// Excluir dispositivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_dispositivo'])) {
    $dispositivo_id = $_POST['dispositivo_id'];
    excluirDispositivo($dispositivo_id); // Chama a função para excluir
    header("Location: admin.php");
    exit;
}

// Lógica de seleção do dispositivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selecionar_dispositivo'])) {
    $dispositivo_id = $_POST['dispositivo_id'];
    // Obtenha o setor associado ao dispositivo
    $stmt = $pdo->prepare("SELECT setor_id FROM dispositivos WHERE id = :id");
    $stmt->execute(['id' => $dispositivo_id]);
    $setor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($setor) {
        // Redirecione para o index do setor
        header("Location: index.php?setor_id=" . $setor['setor_id']);
        exit;
    }
}

// Inicializa variáveis para a paginação
$limit = 10; // Número de perguntas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Obtém o número da página atual
$offset = ($page - 1) * $limit; // Calcula o deslocamento

// Obtém as perguntas com limite e deslocamento
$perguntas = getPerguntas($limit, $offset);

// Buscar respostas do banco de dados
$respostas = getRespostas(); // Supondo que você tenha uma função para isso

// Conta o total de perguntas para calcular o número de páginas
$totalPerguntas = $pdo->query("SELECT COUNT(*) FROM perguntas")->fetchColumn();
$totalPages = ceil($totalPerguntas / $limit);
// Lógica para edição e exclusão pode ser implementada aqui

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - HRAV</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Painel de Administração - HRAV</h1>

        <nav>
            <ul>
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="#avaliacoes">Avaliações</a></li>
                <li><a href="#perguntas">Gerenciar Perguntas</a></li>
                <li><a href="#setores">Gerenciar Setores</a></li>
                <li><a href="#dispositivos">Gerenciar Dispositivos</a></li>
            </ul>
        </nav>

        <!-- Seção de Seleção de Dispositivos -->
        <section id="selecionar-dispositivo">
            <h2>Selecionar Dispositivo</h2>
            <form action="admin.php" method="POST">
                <select name="dispositivo_id">
                    <?php foreach ($dispositivos as $dispositivo): ?>
                        <option value="<?php echo $dispositivo['id']; ?>"><?php echo htmlspecialchars($dispositivo['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="botao" type="submit" name="selecionar_dispositivo">Selecionar Dispositivo</button>
            </form>
        </section>

        <section id="avaliacoes">
    <h2>Avaliações Recebidas com Feedback</h2>
    <table class="tabela-avaliacoes">
        <thead>
            <tr>
                <th>Pergunta</th>
                <th>Nota</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($respostas) && is_array($respostas) && count($respostas) > 0): ?>
                <?php foreach ($respostas as $resposta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($resposta['pergunta']); ?></td>
                        <td><?php echo htmlspecialchars($resposta['nota']); ?></td>
                        <td><?php echo htmlspecialchars($resposta['feedback']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhuma avaliação recebida.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- Paginação -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo ($i === $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Próximo &raquo;</a>
        <?php endif; ?>
    </div>
</section>

<section id="perguntas">
            <h2>Gerenciar Perguntas</h2>
            <form action="admin.php" method="POST">
                <input type="text" name="nova_pergunta" required placeholder="Digite a nova pergunta">
                <select name="setor_id" required>
                    <option value="">Selecione um setor</option>
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?php echo $setor['id']; ?>"><?php echo htmlspecialchars($setor['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="botao" type="submit">Adicionar Pergunta</button>
            </form>

            <h3>Perguntas Cadastradas</h3>
            <ul>
                <?php if (!empty($perguntas)): ?>
                    <?php foreach ($perguntas as $pergunta): ?>
                        <li class="lista-de-informacoes">
                            <?php echo htmlspecialchars($pergunta['pergunta']); ?>
                            (Setor: <?php 
                                $setor_id = $pergunta['setor_id'];
                                $setor = array_filter($setores, function($s) use ($setor_id) {
                                    return $s['id'] == $setor_id;
                                });
                                echo htmlspecialchars($setor ? reset($setor)['nome'] : 'Desconhecido'); 
                            ?>)
                            <div class="container-botoes">
                            <button onclick="abrirModal('modalEditarPergunta<?php echo $pergunta['id']; ?>')">Editar</button>
                            <form method="POST" action="admin.php" style="display:inline;">
                                <input type="hidden" name="pergunta_id" value="<?php echo $pergunta['id']; ?>">
                                <button class="botao botao-excluir" type="submit" name="excluir_pergunta" onclick="return confirm('Tem certeza?');">Excluir</button>
                            </form>
                            </div>
                        </li>
                        <div id="modalEditarPergunta<?php echo $pergunta['id']; ?>" class="modal">
                            <div class="modal-conteudo">
                                <h2>Editar Pergunta</h2>
                                <form action="admin.php" method="POST">
                                    <input type="hidden" name="pergunta_id" value="<?php echo $pergunta['id']; ?>">
                                    <input type="text" name="nova_pergunta" value="<?php echo htmlspecialchars($pergunta['pergunta']); ?>" required>
                                    <select name="setor_id" required>
                                        <option value="">Selecione um setor</option>
                                        <?php foreach ($setores as $setor): ?>
                                            <option value="<?php echo $setor['id']; ?>" <?php echo ($setor['id'] == $pergunta['setor_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($setor['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="botao" type="submit" name="editar_pergunta">Salvar</button>
                                </form>
                                <span onclick="fecharModal('modalEditarPergunta<?php echo $pergunta['id']; ?>')">&times;</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Nenhuma pergunta cadastrada.</li>
                <?php endif; ?>
            </ul>

            <!-- Paginação -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php echo ($i === $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Próximo &raquo;</a>
                <?php endif; ?>
            </div>
        </section>

    <section id="setores">
        <h2>Gerenciar Setores</h2>
        <form action="admin.php" method="POST">
            <input type="text" name="novo_setor" placeholder="Digite o nome do novo setor" required>
            <button class="botao" type="submit">Adicionar Setor</button>
        </form>

        <h3>Setores Cadastrados</h3>
        <ul>
            <?php foreach ($setores as $setor): ?>
                <li  class="lista-de-informacoes">
                    <?php echo htmlspecialchars($setor['nome']); ?>
                    <div class="container-botoes">
                    <button class="botao" onclick="abrirModal('modalEditarSetor<?php echo $setor['id']; ?>')">Editar</button>
                    <form action="admin.php" method="POST" style="display:inline;">
                        <input type="hidden" name="setor_id" value="<?php echo $setor['id']; ?>">
                        <button class="botao botao-excluir" type="submit" name="excluir_setor" onclick="return confirm('Tem certeza que deseja excluir este setor?');">Excluir</button>
                    </form>
                    </div>
                </li>

                <!-- Modal de Edição de Setor -->
                <div id="modalEditarSetor<?php echo $setor['id']; ?>" class="modal">
                    <div class="modal-conteudo modal-setor">
                        <span class="fechar" onclick="fecharModal('modalEditarSetor<?php echo $setor['id']; ?>')">&times;</span>
                        <h2>Editar Setor</h2>
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="setor_id" value="<?php echo $setor['id']; ?>">
                            <input type="text" name="novo_setor" value="<?php echo htmlspecialchars($setor['nome']); ?>" required>
                            <button class="botao" type="submit" name="editar_setor">Salvar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </ul>
    </section>

    <section id="dispositivos">
            <h2>Gerenciar Dispositivos</h2>
            <form action="admin.php" method="POST">
                <input type="text" name="novo_dispositivo" placeholder="Digite o nome do novo dispositivo" required>
                <select name="setor_id_dispositivo">
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?php echo $setor['id']; ?>"><?php echo htmlspecialchars($setor['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="botao" type="submit">Adicionar Dispositivo</button>
            </form>
            <h3>Dispositivos Cadastrados</h3>
            <ul>
                <?php foreach ($dispositivos as $dispositivo): ?>
                    <li  class="lista-de-informacoes">
                        <?php echo htmlspecialchars($dispositivo['nome']); ?> (Setor: <?php echo htmlspecialchars($dispositivo['setor']); ?>)
                        <div class="container-botoes">
                        <button class="botao" onclick="abrirModal('modalEditarDispositivo<?php echo $dispositivo['id']; ?>')">Editar</button>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="dispositivo_id" value="<?php echo $dispositivo['id']; ?>">
                            <button class="botao botao-excluir" type="submit" name="excluir_dispositivo" onclick="return confirm('Tem certeza que deseja excluir este dispositivo?');">Excluir</button>
                        </form>
                        </div>
                    </li>

                    <!-- Modal de Edição de Dispositivo -->
                    <div id="modalEditarDispositivo<?php echo $dispositivo['id']; ?>" class="modal">
                        <div class="modal-conteudo modal-dispositivo">
                            <span class="fechar" onclick="fecharModal('modalEditarDispositivo<?php echo $dispositivo['id']; ?>')">&times;</span>
                            <h2>Editar Dispositivo</h2>
                            <form action="admin.php" method="POST">
                                <input type="hidden" name="dispositivo_id" value="<?php echo $dispositivo['id']; ?>">
                                <input type="text" name="novo_dispositivo" value="<?php echo htmlspecialchars($dispositivo['nome']); ?>" required>
                                <select name="setor_id" required>
                                    <?php foreach ($setores as $setor): ?>
                                        <option value="<?php echo $setor['id']; ?>" <?php echo ($setor['id'] == $dispositivo['setor_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($setor['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="botao" type="submit" name="editar_dispositivo">Salvar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
    <script src="js/script.js"></script>
</body>
</html>