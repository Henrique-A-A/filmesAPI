<?php
//-----------------------------------         filmes            -----------------------------------------

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// GET == recebe informações
// POST == envia informações
// PUT == edita informações "update"
// DELETE == deleta informações
// OPTIONS == relações de metodos disponiveis para uso

header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include 'conexao.php';

//ROTA PARA OBTER TODOS OS filmes

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // criando comando de select para consultar o banco
    $stmt = $conn->prepare('SELECT * FROM filmes');

    //executando o select
    $stmt->execute();

    //recebdno dados do banco com PDO
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // tranformando dados em JSON
    echo json_encode($filmes);
}

// INSERÇÃO DE DADOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $ano_lancamento = $_POST['ano_lancamento'];
    $genero = $_POST['genero'];

    $stmt = $conn->prepare('INSERT INTO filmes(titulo, diretor, ano_lancamento, genero) VALUES (:titulo, :diretor, :ano_lancamento, :genero)');

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':diretor', $diretor);
    $stmt->bindParam(':ano_lancamento', $ano_lancamento);
    $stmt->bindParam(':genero', $genero);

    if ($stmt->execute()) {
        echo 'filme criado com sucesso';
    } else {
        echo 'Erro ao criar um filme';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "excluiu";
    } else {
        echo "excluiu não";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    parse_str(file_get_contents("php://input"), $_PUT);

    $id = $_GET['id'];
    $novoTitulo = $_PUT['titulo'];
    $novodiretor = $_PUT['diretor'];
    $novoano = $_PUT['ano_lancamento'];
    $novogenero = $_PUT['genero'];
    //add novos campos se necessario

    $stmt = $conn->prepare("UPDATE filmes SET titulo = :titulo, diretor = :diretor, ano_lancamento = :ano_lancamento, genero = :genero WHERE id = :id");

    $stmt->bindParam(':titulo', $novoTitulo);
    $stmt->bindParam(':diretor', $novodiretor);
    $stmt->bindParam(':ano_lancamento', $novoano);
    $stmt->bindParam(':genero', $novogenero);
    $stmt->bindParam(':id', $id);
    //add novos campos se necessario

    if ($stmt->execute()) {
        echo "filme atualizado com sucesso";
    } else {
        echo "Erro ao atualizar o filme";
    }
}
