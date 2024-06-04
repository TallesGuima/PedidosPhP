<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Cefet";

require "../produto/produto.php";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao se conectar com o banco de dados: " . $e->getMessage();
    throw $e;
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $quantidade = $_POST['quantidade'];
    $produto = $_POST['produto'];
    $precoTotal = $_POST['precoTotal'];
    $dataAtual = date("Y-m-d h:i.s");
}

class Pedido{
    public function adicionarItem(string $dataHora, int $idCliente, string $itens): bool{

        $conn = $GLOBALS['connection'];
        $sql = "INSERT INTO Pedido(dataHora, cliente, itens) VALUES(:dataHora, :cliente, :itens)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":dataHora", $dataHora);
        $stmt->bindValue(":cliente", $idCliente);
        $stmt->bindValue(":itens", $itens);
    
        //executa a inserção e se ela ocorreu retorna true
        if ($stmt->execute()) { 
            return true;
        } else {
            return false;
        }
    }

    public function removerItem(int $id): bool{
        $conn = $GLOBALS['connection'];
        $sql = "DELETE FROM Pedido WHERE id = :id";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":id", $id);

        //executa a remoção e se ela ocorreu retorna true
        if ($stmt->execute()) { 
            return true;
        } else {
            return false;
        }
    }

    public function calcularTotal($quantidade, $produto): float{
        $precoProduto = new Produto();
        $precoUnit = $precoProduto->getPrecoByNome($produto);
        return $quantidade*$precoUnit;
    }

    public function alterarQuantidade(int $qntAtual, int $idPedido){
        $conn = $GLOBALS['connection'];
        $itens = "$qntAtual X " . $GLOBALS['produto'] . " = " . $this->calcularTotal($qntAtual, $GLOBALS['produto']);
        echo $itens;
        $sql = "UPDATE Pedido SET itens = :itens WHERE id = :idPedido AND cliente = :cliente";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindValue(":itens", $itens);
        $stmt->bindValue(":idPedido", $idPedido);
        $stmt->bindValue(":cliente", $_SESSION['idCliente']);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }



    public function exibirDetalhes(int $idPedido, int $idCliente){
        $conn = $GLOBALS['connection'];

        $sql = "SELECT * FROM Pedido WHERE id = :id AND cliente = :cliente";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":id", $idPedido);
        $stmt->bindValue(":cliente", $idCliente);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetch();

        if ($resultado) {
            return "nome do cliente: " . $_SESSION['nome'] . "; pedido de id: " . $resultado['id']
                . "; lanche pedido: ". $resultado['itens'];
        } else {
            return "";
        }
    }

    public function selecionaByCliente(int $idCliente){
        $conn = $GLOBALS['connection'];

        $sql = "SELECT * FROM Pedido WHERE cliente = :cliente";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":cliente", $idCliente);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetchAll();

        if ($resultado) {
            return  $this->formatarPedidos($resultado);
        } else {
            return "";
        }
    }

    private function formatarPedidos(array $resultados) {
        $formatado = "";
    
        foreach ($resultados as $pedido) {
            $id = $pedido['id'];
            $dataHora = $pedido['dataHora'];
            $itens = $pedido['itens'];
    
            $formatado .= "Pedido #$id em $dataHora: $itens;\n";
        }
    
        return $formatado;
    }

    public function getBD(int $id, string $nomeColuna): string {
        $conn = $GLOBALS['connection'];

        $sql = "SELECT $nomeColuna FROM Pedido WHERE id = :id";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":id", $id);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetch();

        if ($resultado) {
            return $resultado[$nomeColuna];
        } else {
            return "";
        }
    }

}


$pedido = new Pedido();
// $pedido->adicionarItem($dataAtual, $_SESSION['idCliente'] ,$quantidade . " X " .$produto . " = " . $precoTotal);

// echo $pedido->exibirDetalhes(1, 3);

// echo $pedido->selecionaByCliente(3);
// echo $pedido->calcularTotal();;
// echo var_dump($pedido->alterarQuantidade(10, 13));



?>
</body>
</html>

