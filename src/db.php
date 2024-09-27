<?php
require_once '../config.php';

function conectarDB() {
    $conn = pg_connect("host=".DB_HOST." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASS);
    if (!$conn) {
        die("Erro na conexão: " . pg_last_error());
    }
    return $conn;
}
?>
