<?php
require_once 'db.php'; // Assegure-se de que está chamando o arquivo db.php

function getPerguntas($limit, $offset) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, s.nome as setor_nome FROM perguntas p JOIN setores s ON p.setor_id = s.id LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adicionarPergunta($pergunta, $setor_id) {
    $conn = dbConnect();
    $sql = "INSERT INTO perguntas (pergunta, setor_id) VALUES (:pergunta, :setor_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pergunta', $pergunta);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->execute();
}

function editarPergunta($id, $nova_pergunta, $setor_id, $feedback_required) {
    $conn = dbConnect();
    $sql = "UPDATE perguntas SET pergunta = :nova_pergunta, setor_id = :setor_id, feedback_required = :feedback_required WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nova_pergunta' => $nova_pergunta, ':setor_id' => $setor_id, ':feedback_required' => $feedback_required, ':id' => $id]);
}

function buscarPerguntaPorId($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM perguntas WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function excluirPergunta($id) {
    global $pdo;

    // Excluir respostas associadas a esta pergunta
    $stmt = $pdo->prepare("DELETE FROM respostas WHERE pergunta_id = :pergunta_id");
    $stmt->execute(['pergunta_id' => $id]);

    // Agora, excluir a pergunta
    $stmt = $pdo->prepare("DELETE FROM perguntas WHERE id = :id");
    $stmt->execute(['id' => $id]);
}
?>
