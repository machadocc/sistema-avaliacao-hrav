<?php
require_once '../src/db.php';

$setor_id = isset($_GET['setor_id']) ? $_GET['setor_id'] : null;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Obrigado!</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura total da tela */
            margin: 0; /* Remove a margem padrão */
            background-color: #f0f4f8; /* Cor de fundo suave */
            font-family: 'Arial', sans-serif; /* Fonte moderna */
        }

        .thank-you-container {
            background-color: white;
            border-radius: 12px; /* Bordas arredondadas */
            padding: 40px; /* Espaçamento interno aumentado */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Sombra mais suave */
            max-width: 400px; /* Largura máxima do contêiner */
            text-align: center; /* Centraliza o texto */
        }

        .thank-you-title {
            font-size: 36px; /* Aumenta o tamanho do título */
            color: #007bff; /* Cor do título */
            margin-bottom: 15px; /* Espaçamento abaixo do título */
        }

        .thank-you-message {
            font-size: 20px; /* Aumenta o tamanho da mensagem */
            margin-bottom: 25px; /* Espaçamento abaixo da mensagem */
            color: #333; /* Cor do texto */
        }

        .return-button {
            padding: 12px 25px; /* Aumenta o padding do botão */
            background-color: #007bff; /* Cor do botão */
            color: white;
            border: none;
            border-radius: 6px; /* Bordas arredondadas do botão */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s; /* Transição suave */
            font-size: 18px; /* Aumenta o tamanho da fonte do botão */
        }

        .return-button:hover {
            background-color: #0056b3; /* Cor do botão ao passar o mouse */
            transform: translateY(-2px); /* Efeito de levitar */
        }

        .countdown {
            font-size: 12px; /* Aumenta o tamanho da contagem */
            color: #666; /* Cor do texto da contagem */
            margin-top: 15px; /* Espaçamento acima da contagem */
        }

        @media (max-width: 600px) {
            .thank-you-container {
                width: 90%; /* Largura responsiva em telas menores */
                padding: 20px; /* Ajusta o padding em telas menores */
            }

            .thank-you-title {
                font-size: 28px; /* Reduz o tamanho do título em telas pequenas */
            }

            .thank-you-message, .countdown {
                font-size: 16px; /* Reduz o tamanho da mensagem e contagem em telas pequenas */
            }

            .return-button {
                font-size: 16px; /* Reduz o tamanho do botão em telas pequenas */
                padding: 10px 20px; /* Ajusta o padding do botão */
            }
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1 class="thank-you-title">Obrigado por sua participação!</h1>
        <p class="thank-you-message">Suas respostas foram registradas com sucesso.</p>
        <button class="return-button" id="returnButton">Voltar ao início</button>
        <p class="countdown" id="countdown">Redirecionando em <span id="timer">10</span> segundos...</p>
    </div>

    <script>
        let countdown = 10;
        const timerElement = document.getElementById('timer');
        const returnButton = document.getElementById('returnButton');

        const countdownInterval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "index.php?setor_id=<?php echo htmlspecialchars($setor_id); ?>"; // Redireciona para o index com setor_id
            }
        }, 1000);

        returnButton.addEventListener('click', () => {
            clearInterval(countdownInterval);
            window.location.href = "index.php?setor_id=<?php echo htmlspecialchars($setor_id); ?>"; // Redireciona para o index com setor_id
        });
    </script>
</body>
</html>
