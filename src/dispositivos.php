<?php
require_once 'db.php'; // Inclua o arquivo db.php para usar a conexão

function getDispositivos() {
    $conn = dbConnect(); // Chama a função dbConnect
    $sql = "SELECT dispositivos.*, setores.nome AS setor FROM dispositivos JOIN setores ON dispositivos.setor_id = setores.id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adicionarDispositivo($dispositivo, $setor_id) {
    $conn = dbConnect();
    $sql = "INSERT INTO dispositivos (nome, setor_id) VALUES (:nome, :setor_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $dispositivo);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->execute();
}

function editarDispositivo($id, $novo_nome, $setor_id) {
    $conn = dbConnect();
    $sql = "UPDATE dispositivos SET nome = :novo_nome, setor_id = :setor_id WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':novo_nome' => $novo_nome, ':setor_id' => $setor_id, ':id' => $id]);
}

function excluirDispositivo($dispositivo_id) {
    $conn = dbConnect();
    $stmt = $conn->prepare("DELETE FROM dispositivos WHERE id = :dispositivo_id");
    $stmt->execute(['dispositivo_id' => $dispositivo_id]);
}
?>
