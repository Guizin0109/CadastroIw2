<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "cadastro";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $conn->connect_error]));
}
//linha detecta o metodo utilizado (post, get, delete ou put)
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET["pesquisa"])) {
            $pesquisa = "%" . $GET['pesquisa'] . "%";
            $stmt = $conn->prepare ("SELECT * FROM usuario WHERE LOGIN LIKE ? OR NAME LIKE ?");

            $stmt->bind_param ("ss", pesquisa, $pesquisa);

            $stmt_>execute();

            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM usuarios order by ID desc");
        }

        $retorno = [];

        while ($linha = $result ->fetch_assoc()) {
            $retorno [] = $linha;
        }

        echo json_encode ($retorno);
        break;

case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    
    $stmt = $conn->prepare("INSERT INTO usuarios (LOGIN, NOME, EMAIL, SENHA, ATIVO) VALUES (?,?,?,?,?)");

    $stmt->bind_param("ssssi", $data['LOGIN'], $data['NOME'],$data['EMAIL'], $data['SENHA'], $data['ATIVO']);

    $stmt->execute();

    echo json_encode(["status" => "ok", "insert_id" => $stmt->insert_id]);
    break;

case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    
    $stmt = $conn->prepare("UPDATE usuarios SET LOGIN=?, NOME=?, EMAIL=?, SENHA=?, ATIVO=? WHERE ID=?");

    $stmt->bind_param("ssssi", $data['LOGIN'], $data['NOME'],$data['EMAIL'], $data['SENHA'], $data['ATIVO'], $data('ID'));

    $stmt->execute();

    echo json_encode(["status" => "ok"]);
    break;

case 'DELETE':
    $id = $_GET['ID'];
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE ID=?");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    echo json_encode(["status" => "ok"]);
    break;
}