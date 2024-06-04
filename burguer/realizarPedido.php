<?php session_start(); 
require "produto/produto.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Página de Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<?php if(!(array_key_exists('nome', $_SESSION))) {
        header('location: acessoNegado.php');
    }?>
<body>
    <div class="container">
    <button onclick="window.location.href='index.php'">Clique para retornar a página principal</button>
        <h1>Minha Página de Pedidos</h1>
        <p>Bem-vindo à nossa loja virtual! Confira nossos produtos abaixo:</p>
        <ul>
                <?php $produto = new Produto();
                for ($i=1; $i <= $produto->getTamanho(); $i++) {
                    echo "<li>" . $produto->getDetalhes($i) . "</li>"; 
                    }?>
        </ul>
        <form action="pedido/pedido.php" method="post">
    <label for="produto">Selecione o produto:</label>
    <select name="produto" id="produto">
        <?php
        $produto = new Produto();
        for ($i = 1; $i <= $produto->getTamanho(); $i++) {
            $preco = $produto->getBD($i, "preco");
            echo "<option data-preco='$preco'>" . $produto->getBD($i, "nome") . "</option>";
        }
        ?>
    </select>
    <br>
    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" min="1" value="1">
    <br>
    <p>Preço Total: <span id="precoTotal">0</span></p>
    <input type="hidden" name="precoTotal" id="precoTotalHidden" value="0">
    <input type="submit" value="Enviar Pedido">
</form>

<script>
    const produtoSelect = document.getElementById('produto');
    const quantidadeInput = document.getElementById('quantidade');
    const precoTotalSpan = document.getElementById('precoTotal');

    function atualizarPrecoTotal() {
        const produtoOption = produtoSelect.options[produtoSelect.selectedIndex];
        const precoUnitario = parseFloat(produtoOption.getAttribute('data-preco'));
        const quantidade = parseInt(quantidadeInput.value);

        const precoTotal = quantidade * precoUnitario;
        document.getElementById("precoTotalHidden").value = precoTotal.toFixed(2);
        precoTotalSpan.textContent = precoTotal.toFixed(2);
    }

    produtoSelect.addEventListener('change', atualizarPrecoTotal);
    quantidadeInput.addEventListener('input', atualizarPrecoTotal);

    atualizarPrecoTotal();
</script>
</body>
</html>