<?php
require_once 'db.php';

function getRespostas() {
    $conn = dbConnect();
    // Adicionando a cláusula WHERE para filtrar respostas com feedback
    $sql = "SELECT respostas.*, perguntas.pergunta FROM respostas 
            JOIN perguntas ON respostas.pergunta_id = perguntas.id 
            WHERE respostas.feedback IS NOT NULL AND respostas.feedback != ''";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function salvarResposta($pergunta_id, $nota, $feedback) {
    $conn = dbConnect(); // Chame sua função de conexão ao banco de dados
    $sql = "INSERT INTO respostas (pergunta_id, nota, feedback) VALUES (:pergunta_id, :nota, :feedback)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pergunta_id', $pergunta_id);
    $stmt->bindParam(':nota', $nota);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->execute();
}

function adicionarResposta($pergunta_id, $nota, $feedback) {
    $conn = dbConnect();
    $sql = "INSERT INTO respostas (pergunta_id, nota, feedback) VALUES (:pergunta_id, :nota, :feedback)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pergunta_id', $pergunta_id);
    $stmt->bindParam(':nota', $nota);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->execute();
}
function excluirRespostasPorDispositivo($dispositivo_id) {
    $conn = dbConnect();
    $sql = "DELETE FROM respostas WHERE dispositivo_id = :dispositivo_id"; // Verifique se você tem uma coluna 'dispositivo_id' na tabela respostas
    $stmt = $conn->prepare($sql);
    $stmt->execute(['dispositivo_id' => $dispositivo_id]);
}
?>
