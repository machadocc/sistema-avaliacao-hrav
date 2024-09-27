<?php

$host = 'localhost';
$dbname = 'sistemaavaliacao';
$user = 'postgres';
$password = 'root';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    function executeQuery($query, $params = []) {
        global $pdo;
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    function closeConnection() {
        global $pdo;
        $pdo = null;
    }

} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>