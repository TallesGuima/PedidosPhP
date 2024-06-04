<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Cefet";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao se conectar com o banco de dados: " . $e->getMessage();
    throw $e;
}


class usuarioBD{

    private function verifica_email(string $email, PDO $conn): bool{
        // Verifica se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return false;
        }
      
      //Prepara a consulta sql
      $sql = "SELECT COUNT(*) FROM usuario WHERE email = :email";
      $stmt = $conn->prepare($sql);
    
      //vincula o parametro :email ao valor de $email
      $stmt->bindValue(":email", $email);
    
      //executa a consulta
      $stmt->execute();
    
      //Obtem o numero de linhas iguais ao :email
      $numero_linhas = $stmt->fetchColumn();
    
      // se for igual a 0 significa que não tem esse email no bd
      return $numero_linhas==0;
      
      }

    public function cadastrarUsuario(string $nome, string $email, string $senha): bool {
        $conn = $GLOBALS['connection'];
        if($this->verifica_email($email, $conn)){
            $senha = password_hash($senha, PASSWORD_DEFAULT);
        try {
            // Preparar a consulta SQL
            $sql = "INSERT INTO usuario(nome, email, senha) 
                    VALUES (:nome, :email, :senha);";
            $stmt = $conn->prepare($sql);

            // Vincular parâmetros aos valores
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":senha", $senha);

            // Executa o comando sql preparado, se der erro vai return false
            if (!$stmt->execute()) {
            return false;
            }
            // Retornar true em caso de sucesso
            echo "cadastra";
            return true;
        } catch (PDOException $e) {
            echo("Erro ao inserir usuário: " . $e->getMessage());
            // Retornar false em caso de erro
            return false;
            }
        }
        echo "email: $email já cadastrado no sistema!";
        return false;
    }

    private function getSenhaHashed(string $email, PDO $conn) : string{

        $sql = "SELECT senha FROM usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
    
        //vincula o parametro :email ao valor de $email
        $stmt->bindValue(":email", $email);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem a senha onde o email é X
        $senha = $stmt->fetchColumn();
    
        // se for igual a 1 significa que tem esse email e senha no bd
        return $senha;
    }

    protected function autenticarUsuario(string $email, string $senha): bool{
        $conn = $GLOBALS['connection'];

        // Verifica se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // verifico se a senha que o usuario colocou está no bd
        if(!password_verify($senha, $this->getSenhaHashed($email, $conn))){
            return false;
        }

        // pego a senha que esta codificada no bd
        $senha = $this->getSenhaHashed($email, $conn);

        //Prepara a consulta sql
        $sql = "SELECT codigo FROM usuario WHERE email = :email AND senha = :senha";
        $stmt = $conn->prepare($sql);
    
        //vincula o parametro :email ao valor de $email
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":senha", $senha);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o numero de linhas iguais ao :email
        $idUser = $stmt->fetchColumn();
        
    
        // se o idUser for maior que zero, o usuario com aquela senha e email existe

        return $idUser>0;
    }

    public function getDetalhesUsuario(int $idUser){
        $conn = $GLOBALS['connection'];
        //Prepara a consulta sql
        $sql = "SELECT * FROM usuario WHERE codigo = :id";
        $stmt = $conn->prepare($sql);
    
        //vincula o parametro :email ao valor de $email
        $stmt->bindValue(":id", $idUser);
    
        //executa a consulta
        $stmt->execute();
        
        //Obtem o numero de linhas iguais ao :email
        $detalhes = $stmt->fetch();
    
        return "nome: " . $detalhes['nome'] . " email: " . $detalhes['email'] .
            " senha(criptografada): " . $detalhes['senha'] . " id: " . $detalhes['codigo'];
    }

    public function getBD(string $email, string $senha, string $nomeColuna): string {
        $conn = $GLOBALS['connection'];
    
        // verifico se a senha que o usuario colocou está no bd
        if(!password_verify($senha, $this->getSenhaHashed($email, $conn))){
            return false;
        }

        // pego a senha que esta codificada no bd
        $senha = $this->getSenhaHashed($email, $conn);

        $sql = "SELECT $nomeColuna FROM usuario WHERE email = :email AND senha = :senha";
        $stmt = $conn->prepare($sql);
    
        //vincula o parametro :email ao valor de $email
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":senha", $senha);
    
        //executa a consulta
        $stmt->execute();
    
        //Obtem o resultado da consulta
        $resultado = $stmt->fetch();
        //Verifica se encontrou o usuário
        if ($resultado) {
            //Retorna o nome do usuário
           
            return $resultado[$nomeColuna];
        } else {
            //Usuário não encontrado
            return "";
        }
    }
      
    

}