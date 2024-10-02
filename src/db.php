<?php
function dbConnect() {
    $host = 'localhost'; // Altere conforme necessário
    $dbname = 'sistemaavaliacao'; // Altere para o nome do seu banco de dados
    $user = 'postgres'; // Altere para o usuário do seu banco de dados
    $password = 'root'; // Altere para a senha do seu banco de dados

    // String de conexão correta
    $dsn = "pgsql:host=$host;dbname=$dbname"; 

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo; // Retorna a conexão
    } catch (PDOException $e) {
        echo 'Conexão falhou: ' . $e->getMessage();
        exit;
    }
}
?>
