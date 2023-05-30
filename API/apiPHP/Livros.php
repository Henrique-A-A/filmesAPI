<?php
//-----------------------------------         LIVROS            -----------------------------------------

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

//ROTA PARA OBTER TODOS OS LIVROS

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // criando comando de select para consultar o banco
    $stmt = $conn->prepare('SELECT * FROM livros');

    //executando o select
    $stmt->execute();

    //recebdno dados do banco com PDO
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // tranformando dados em JSON
    echo json_encode($livros);
}

// INSERÇÃO DE DADOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano_publicacao = $_POST['ano_publicacao'];

    $stmt = $conn->prepare('INSERT INTO livros(titulo, autor, ano_publicacao) VALUES (:titulo, :autor, :ano_publicacao)');

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':ano_publicacao', $ano_publicacao);

    if ($stmt->execute()) {
        echo 'Livro criado com sucesso';
    } else {
        echo 'Erro ao criar um livro';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM livros WHERE id = :id");
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
    $novoAutor = $_PUT['autor'];
    $novoAno = $_PUT['ano_publicacao'];
    //add novos campos se necessario

    $stmt = $conn->prepare("UPDATE livros SET titulo = :titulo, autor = :autor, ano_publicacao = :ano_publicacao WHERE id = :id");

    $stmt->bindParam(':titulo', $novoTitulo);
    $stmt->bindParam(':autor', $novoAutor);
    $stmt->bindParam(':ano_publicacao', $novoAno);
    $stmt->bindParam(':id', $id);
    //add novos campos se necessario

    if ($stmt->execute()) {
        echo "Livro atualizado com sucesso";
    } else {
        echo "Erro ao atualizar o Livro";
    }
}
