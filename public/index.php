<?php
require_once '../src/db.php';
require_once '../src/perguntas.php';
require_once '../src/respostas.php';

// Conexão ao banco de dados
$pdo = dbConnect();

// Verifique se o setor_id está definido
if (!isset($_GET['setor_id'])) {
    header("Location: admin.php");
    exit;
}

$setor_id = $_GET['setor_id'];

// Obtenha as perguntas associadas ao setor
$stmt = $pdo->prepare("SELECT * FROM perguntas WHERE setor_id = :setor_id");
$stmt->execute(['setor_id' => $setor_id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica para salvar as respostas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_resposta'])) {
    $pergunta_id = $_POST['pergunta_id'];
    $nota = $_POST['nota'];
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : null;

    // Salvar resposta usando uma função definida em respostas.php
    salvarResposta($pergunta_id, $nota, $feedback);

    // Verifica se ainda há perguntas
    $next_index = $_POST['pergunta_index'] + 1;
    if (isset($perguntas[$next_index])) {
        // Redireciona para a próxima pergunta
        header("Location: index.php?setor_id=$setor_id&pergunta_index=$next_index");
    } else {
        // Se não houver mais perguntas, redireciona para a página de conclusão
        header("Location: obrigado.php?setor_id=$setor_id");
    }
    exit;
}

// Determine o índice da pergunta atual
$pergunta_index = isset($_GET['pergunta_index']) ? (int)$_GET['pergunta_index'] : 0;
$pergunta_atual = $perguntas[$pergunta_index] ?? null;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Respostas do Setor</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos para centralizar o conteúdo */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura total da tela */
            margin: 0; /* Remove a margem padrão */
            background-color: #f8f9fa; /* Cor de fundo para melhor contraste */
        }

        .container {
            text-align: center;
            padding: 20px;
            width: 95%; /* Largura do contêiner para preencher a tela */
            background-color: white; /* Cor de fundo do contêiner */
            border-radius: 10px; /* Bordas arredondadas */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra para dar profundidade */
            margin: 0 auto; /* Centraliza o contêiner na tela */
        }

        /* Estilização específica para a tabela de avaliações */
        .avaliacao-container {
            margin: 20px 0;
        }
        .avaliacao-titulo {
            font-size: 24px; /* Tamanho do título */
            margin-bottom: 20px;
        }
        .avaliacao-bolinha {
            display: inline-block;
            align-content: center;
            width: 75px;
            height: 75px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px; /* Centraliza o texto verticalmente */
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: white; /* Cor do texto dentro da bolinha */
            font-size: 20px;
            font-weight: bold; /* Negrito para os números */
        }
        .avaliacao-bolinha:hover {
            opacity: 0.8; /* Efeito ao passar o mouse */
        }

        /* Cores específicas para cada bolinha */
        .bola1 { background-color: #D9534F; } /* Vermelho forte */
        .bola2 { background-color: #F6B63A; } /* Vermelho claro */
        .bola3 { background-color: #F6A800; } /* Laranja */
        .bola4 { background-color: #F4D35E; } /* Amarelo */
        .bola5 { background-color: #A1D99A; } /* Verde claro */
        .bola6 { background-color: #70B47E; } /* Verde */
        .bola7 { background-color: #4D944F; } /* Verde moderado */
        .bola8 { background-color: #387F4C; } /* Verde escuro */
        .bola9 { background-color: #2B7A3B; } /* Verde muito escuro */
        .bola10 { background-color: #007A33; } /* Verde mais forte */

        .avaliacao-bolinha.selected {
            box-shadow: 0 0 10px 2px rgba(0, 0, 0, 0.5); /* Efeito para a bolinha selecionada */
        }
        .avaliacao-feedback-container {
            display: block; /* Exibe o feedback por padrão */
            margin-top: 15px;
            background-color: #f1f1f1; /* Cor de fundo da seção de feedback */
            border-radius: 8px; /* Bordas arredondadas */
            padding: 15px; /* Padding interno */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra para dar profundidade */
        }
        .avaliacao-feedback-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold; /* Negrito para o rótulo */
        }
        .avaliacao-feedback-container textarea {
            width: 100%; /* Largura total */
            height: 100px; /* Altura da caixa de texto */
            border-radius: 4px; /* Bordas arredondadas */
            border: 1px solid #ccc; /* Borda padrão */
            padding: 10px; /* Padding interno */
            font-size: 14px; /* Tamanho da fonte */
            margin-top: 5px; /* Margem acima da caixa de texto */
            resize: none; /* Impede o redimensionamento */
        }
        .avaliacao-feedback-mensagem {
            margin-bottom: 10px; /* Espaçamento abaixo da mensagem */
            font-size: 14px; /* Tamanho da fonte */
            color: #555; /* Cor do texto */
        }
        .avaliacao-botao {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #031b4e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .avaliacao-botao:hover {
            background-color: #0056b3;
        }
        .pergunta-titulo{
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($pergunta_atual): ?>
            <form action="index.php?setor_id=<?php echo $setor_id; ?>&pergunta_index=<?php echo $pergunta_index; ?>" method="POST" class="avaliacao-container">
                <h1 class=""><?php echo htmlspecialchars($pergunta_atual['pergunta']); ?></h1> <!-- Título da pergunta em h1 -->
                <input type="hidden" name="pergunta_id" value="<?php echo $pergunta_atual['id']; ?>">
                <input type="hidden" name="pergunta_index" value="<?php echo $pergunta_index; ?>">
                
                <div>
    <?php for ($i = 1; $i <= 10; $i++): ?>
        <div class="avaliacao-bolinha bola<?php echo $i; ?>" onclick="selectNota(<?php echo $i; ?>)" id="bola<?php echo $i; ?>">
            <?php echo $i; ?>
        </div>
        <input type="radio" id="nota<?php echo $i; ?>" name="nota" value="<?php echo $i; ?>" style="display: none;" required> <!-- Adicione 'required' aqui -->
    <?php endfor; ?>
</div>


                <div class="avaliacao-feedback-container" id="feedback-container">
                    <div class="avaliacao-feedback-mensagem">
                        Caso queira, nos conte mais sobre o seu feedback:
                    </div>
                    <label for="feedback">Feedback:</label>
                    <textarea name="feedback"></textarea>
                </div>
                
                <button type="submit" name="salvar_resposta" class="avaliacao-botao" onclick="return validarSelecao()">Salvar Resposta</button>
            </form>
        <?php else: ?>
            <h2>Obrigado por responder!</h2>
            <p>Você concluiu todas as perguntas deste setor.</p>
            <p>Você será redirecionado em breve.</p>
        <?php endif; ?>
    </div>

    <script>
            function selectNota(nota) {
        for (let i = 1; i <= 10; i++) {
            const bola = document.getElementById('bola' + i);
            const input = document.getElementById('nota' + i);
            if (i === nota) {
                bola.classList.add('selected');
                input.checked = true; // Marca o input como selecionado
            } else {
                bola.classList.remove('selected');
                input.checked = false; // Desmarca os outros inputs
            }
        }
        document.getElementById('feedback-container').style.display = 'block'; // Mostra a caixa de feedback
    }

    function validarSelecao() {
        const radios = document.getElementsByName('nota');
        let notaSelecionada = false;

        // Verifica se algum rádio está selecionado
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                notaSelecionada = true;
                break;
            }
        }

        // Se nenhuma nota estiver selecionada, alerta o usuário
        if (!notaSelecionada) {
            alert('Por favor, selecione uma nota antes de continuar.');
            return false; // Impede o envio do formulário
        }

        return true; // Permite o envio do formulário
    }
    
    </script>
</body>
</html>
