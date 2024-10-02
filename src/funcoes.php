<?php
require_once 'db.php';

function getDispositivos() {
    $conn = dbConnect();
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

?>