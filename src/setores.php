<?php
require_once 'db.php';

function getSetores() {
    $conn = dbConnect();
    $sql = "SELECT * FROM setores";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function adicionarSetor($setor) {
    $conn = dbConnect();
    $sql = "INSERT INTO setores (nome) VALUES (:nome)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $setor);
    $stmt->execute();
}

function editarSetor($id, $novo_nome) {
    $conn = dbConnect();
    $sql = "UPDATE setores SET nome = :novo_nome WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':novo_nome' => $novo_nome, ':id' => $id]);
}

function excluirSetor($id) {
    $conn = dbConnect();
    $sql = "DELETE FROM setores WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
}

?>