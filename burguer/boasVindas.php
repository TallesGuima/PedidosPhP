<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bem-vindo!</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      text-align: center;
    }
    h1 {
      font-size: 3em;
      color: #333;
    }
    p {
      font-size: 1.2em;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Bem-vindo <?php echo  $_SESSION['nome']; ?>!</h1>
    <p>Para ir a página inicial <a href="index.php">clique aqui</a>.</p>
    <br>
    <p>Para fazer log out <a href="sessionEnd.php">clique aqui</a>.</p>
  </div>
</body>
</html>