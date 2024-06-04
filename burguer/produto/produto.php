<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
</head>
<body>
    <?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Cefet";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao se conectar com o banco de dados: " . $e->getMessage();
    throw $e;
}

class Produto{
    public function getBD(int $id, string $nomeColuna): string {
        $conn = $GLOBALS['connection'];

        $sql = "SELECT $nomeColuna FROM produto WHERE id = :id";
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

    public function getPrecoByNome(string $nomeProduto){
        $conn = $GLOBALS['connection'];

        $sql = "SELECT preco FROM produto WHERE nome = :nome";
        $stmt = $conn->prepare($sql);
    
        $stmt->bindValue(":nome", $nomeProduto);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            return $resultado['preco'];
        } else {
            return "";
        }
    }

    public function getTamanho(){
        $conn = $GLOBALS['connection'];

        $sql = "SELECT COUNT(*) as tamanho FROM produto";
        $stmt = $conn->prepare($sql);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            return $resultado['tamanho'];
        } 
    }

    public function getDetalhes(int $id): string{
        return "<strong>Nome</strong> do lanche: " . $this->getBD($id,"nome") .
            "; <strong>preço</strong> do lanche: R$" . $this->getBD($id,"preco");
    }

}


// $produto = new Produto();
// echo var_dump($produto->getTamanho());
?>
</body>
</html>