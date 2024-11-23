<?php
session_start();
include("conexão.php");

// Recupera o carrinho do cookie, ou inicializa como string vazia se o cookie não existir
$carrinho = isset($_COOKIE['carrinho']) ? $_COOKIE['carrinho'] : '';

// Lista de produtos disponíveis, onde a chave é o ID do produto e o valor é o nome do produto
$produtos = "SELECT * from produtos";
$resultado_produtos = mysqli_query($conexao, $produtos);

// Inicializa um array associativo para armazenar os itens do carrinho
$carrinhoArray = [];
if ($carrinho) {
    // Divide a string do carrinho em itens individuais usando a vírgula como delimitador
    $itens = explode(',', $carrinho);
    
    // Percorre cada item do carrinho
    foreach ($itens as $item) {
        // Divide cada item em ID e quantidade usando os dois pontos como delimitador
        list($id, $quantidade) = explode(':', $item);
        
        // Adiciona o ID e a quantidade ao array associativo $carrinhoArray
        // Converte a quantidade para inteiro para garantir que seja manipulada corretamente
        $carrinhoArray[$id] = (int)$quantidade;
    }
}

// Verifica se um produto foi removido do carrinho
if (isset($_GET['remover_id'])) {
    $idRemover = $_GET['remover_id'];
    
    // Remove o produto específico do array do carrinho
    unset($carrinhoArray[$idRemover]);

    // Atualiza o cookie com o carrinho modificado
    $novoCarrinho = [];
    foreach ($carrinhoArray as $id => $quantidade) {
        $novoCarrinho[] = "$id:$quantidade";  // Adiciona os itens restantes no carrinho
    }

    // Define o cookie com o carrinho atualizado
    setcookie('carrinho', implode(',', $novoCarrinho), time() + (30 * 24 * 60 * 60), "/");  // 30 dias
    header("Location: carrinho.php");  // Redireciona de volta para a página do carrinho
    exit();
}

// Verifica se o usuário clicou em "Finalizar compra"
if (isset($_POST['finalizar_compra'])) {
    if (isset($_SESSION['id'])) {
        $cliente_id = $_SESSION['id'];

        // Adiciona os produtos ao banco de dados (carrinho_compras)
        foreach ($carrinhoArray as $id => $quantidade) {
            $pedido = "INSERT INTO carrinho_compras (`cliente_id`, `produto_id`, `quantidade`) VALUES ('$cliente_id', '$id', '$quantidade')";
            mysqli_query($conexao, $pedido);
        }

        // Após adicionar os pedidos, redireciona para o resumo do pedido
        header("Location: resumo_pedido.php");
        exit();
    } else {
        echo "<p>Você precisa estar logado para finalizar a compra.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #f8c8d2;
            padding: 10px;
            text-align: center;
        }

        .menu__link {
            text-decoration: none;
            color: #9b4d96;
            margin: 0 10px;
            font-weight: bold;
        }

        .menu__link:hover {
            color: #e3a0c1;
        }

        .container {
            margin: 30px auto;
            max-width: 1000px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #9b4d96;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #f0e6f3;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        ul li a {
            color: #9b4d96;
            text-decoration: none;
            font-weight: bold;
        }

        ul li a:hover {
            color: #e3a0c1;
        }

        .btn {
            background-color: #9b4d96;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e3a0c1;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #9b4d96;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }

        .links a:hover {
            color: #e3a0c1;
        }
    </style>
</head>
<body>
<header>
        <div id="deslogado">
            <a href="login.php" class="menu__link">Login</a>
            <a href="cadastro.php" class="menu__link">Cadastre-se</a>
        </div>
        <div id="logado">
            <?php if(isset($_SESSION['id'])) {echo '<a href="index.php" class="menu__link">Area inicial</a>';}?>
            <a href="logout.php" class="menu__link">Sair</a>
            <a href="perfil.php" class="menu__link">Perfil</a>
        </div>
    </header>

<div class="container">
    <h1>Seu Carrinho</h1>

    <?php if (empty($carrinhoArray)): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <ul>
            <?php 
            // Para cada item no carrinho
            foreach ($carrinhoArray as $id => $quantidade): 
                // Consulta para pegar o nome do produto baseado no ID
                $produto_query = "SELECT nome FROM produtos WHERE id = $id";
                $produto_result = mysqli_query($conexao, $produto_query);
                
                if ($produto_result) {
                    $produto = mysqli_fetch_assoc($produto_result); // Recupera o nome do produto
                    $nome_produto = $produto['nome'];
                } else {
                    $nome_produto = 'Produto não encontrado';
                }
            ?>
                <li>
                    <?php echo $nome_produto; ?> - Quantidade: <?php echo $quantidade; ?>
                    <a href="carrinho.php?remover_id=<?php echo $id; ?>">Excluir</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="links">
        <a href="index.php">Continuar Comprando</a> | 
        <a href="esvaziar_carrinho.php">Esvaziar Carrinho</a>
    </div>

    <!-- Formulário de Finalizar Compra -->
    <form action="carrinho.php" method="post" style="text-align: center; margin-top: 30px;">
        <button type="submit" name="finalizar_compra" class="btn">Finalizar Compra</button>
    </form>
</div>

</body>
<script>    
        //fução para a index do usuario logado
        function logado() {
            document.getElementById('logado').style.display='';
            document.getElementById('deslogado').style.display='none';
        }
        //função para o usuario deslogado
        function deslogado() {
            document.getElementById('logado').style.display='none';
            document.getElementById('deslogado').style.display='';
        }
    </script>
      <?php
        //verifica o login e muda o index
        if (isset($_SESSION['id'])) {
            echo '<script> logado() </script>';
        } else {
            echo '<script> deslogado() </script>';
        }
    ?>
</html>
