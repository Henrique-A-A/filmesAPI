<?php
//-----------------------------------         CONEXÃO            -----------------------------------------

$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = 'sucesso';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "Erro na conexão: " . $error->getMessage();
    die();
}
