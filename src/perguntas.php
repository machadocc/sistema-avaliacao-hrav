<?php
require_once 'db.php';

function listarPerguntas() {
    $conn = conectarDB();
    $query = "SELECT * FROM perguntas WHERE ativo = true ORDER BY id"; // Alterado para 'ativo'
    $result = pg_query($conn, $query);
    $perguntas = pg_fetch_all($result);
    pg_close($conn); // Fecha a conexão
    return $perguntas;
}

function adicionarPergunta($texto, $ativo) {
    $conn = conectarDB();
    $query = 'INSERT INTO perguntas (texto, ativo) VALUES ($1, $2)';
    $result = pg_query_params($conn, $query, array($texto, $ativo));

    if ($result) {
        pg_close($conn); // Fecha a conexão
        return true;
    } else {
        pg_close($conn); // Fecha a conexão
        return false;
    }
}

function editarPergunta($id, $novoTexto, $ativo) { // Adicionando a flag 'ativo'
    $conn = conectarDB(); 
    $query = "UPDATE perguntas SET texto = $1, ativo = $2 WHERE id = $3"; // Adicionando a atualização do 'ativo'
    $result = pg_query_params($conn, $query, array($novoTexto, $ativo, $id));
    pg_close($conn); // Fecha a conexão
    return $result !== false; // Retorna verdadeiro ou falso
}

function removerPergunta($id) {
    $conn = conectarDB(); 
    $query = "UPDATE perguntas SET ativo = false WHERE id = $1"; // Atualiza 'ativo' para false
    $result = pg_query_params($conn, $query, array($id));
    pg_close($conn); // Fecha a conexão
    return $result !== false; // Retorna verdadeiro ou falso
}
?>
