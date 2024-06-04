<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
<?php


require "usuario/usuario.php";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    
    $usuario = new Usuario();
    if($usuario->autenticar($email, $senha)){
        $_SESSION['nome'] = $usuario->getBD($email, $senha, "nome");
        $_SESSION['idCliente'] = $usuario->getBD($email, $senha, "codigo");
        echo var_dump($usuario->getDetalhes($usuario->getBD($email, $senha, "codigo")));
    } else {
        echo "Email ou senha incorretos";
    }
    header('Location: index.php');
}
?>
</body>
</html>