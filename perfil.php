<?php
session_start();
include("conexão.php");

if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Recupera o ID do cliente logado
$cliente_id = $_SESSION['id'];

// Consulta SQL para buscar as informações do cliente
$query_cliente = "
    SELECT 
        clientes.id AS cliente_id, 
        clientes.nome AS cliente_nome, 
        clientes.foto AS cliente_foto, 
        clientes.pdf AS cliente_pdf
    FROM clientes
    WHERE clientes.id = '$cliente_id'
";

$resultado_cliente = mysqli_query($conexao, $query_cliente);

if (!$resultado_cliente) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

$cliente = mysqli_fetch_assoc($resultado_cliente);

// Consulta SQL para buscar os pedidos do cliente
$query_pedidos = "
    SELECT 
        carrinho_compras.id AS id_pedido, 
        produtos.nome AS produto_nome, 
        produtos.preco AS produto_preco, 
        carrinho_compras.quantidade AS produto_quantidade
    FROM carrinho_compras
    JOIN produtos ON carrinho_compras.produto_id = produtos.id
    WHERE carrinho_compras.cliente_id = '$cliente_id'
";

$resultado_pedidos = mysqli_query($conexao, $query_pedidos);

if (!$resultado_pedidos) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Cliente</title>
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

        .profile-image {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .pdf-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            color: #9b4d96;
        }

        .pdf-link:hover {
            color: #e3a0c1;
        }

        .pedido {
            background-color: #f0e6f3;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .pedido h4 {
            color: #9b4d96;
        }

        .pedido p {
            color: #6a4e7f;
        }

        .pedido-total {
            font-weight: bold;
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
            <a href="carrinho.php" class="menu__link">Ver Carrinho</a>
        </div>
    </header>

<div class="container">
    <h1>Perfil de <?php echo htmlspecialchars($cliente['cliente_nome']); ?></h1>

    <!-- Foto de Perfil do Cliente -->
    <div class="profile-image">
        <?php if (!empty($cliente['cliente_foto'])): ?>
            <img src="<?php echo htmlspecialchars($cliente['cliente_foto']); ?>" alt="Foto de Perfil">
        <?php else: ?>
            <p>Foto de perfil não disponível.</p>
        <?php endif; ?>
    </div>

    <!-- PDF do Cliente -->
    <div>
        <?php if ($cliente['cliente_pdf']): ?>
            <a href="<?php echo htmlspecialchars($cliente['cliente_pdf']); ?>" target="_blank" class="pdf-link">Ver PDF</a>
        <?php else: ?>
            <p>Não há PDF disponível.</p>
        <?php endif; ?>
    </div>

    <h2>Seus Pedidos</h2>
    <?php
    // Verifica se o cliente tem pedidos
    $pedidosEncontrados = false;
    while ($pedido = mysqli_fetch_assoc($resultado_pedidos)) {
        $pedidosEncontrados = true;
        $nome_produto = htmlspecialchars($pedido['produto_nome']);
        $quantidade = $pedido['produto_quantidade'];
        $preco = $pedido['produto_preco'];
        $id_pedido = $pedido['id_pedido'];
        $total = $preco * $quantidade;
    ?>
        <div class="pedido">
            <h4>Pedido #<?php echo $id_pedido; ?></h4>
            <p>Produto: <?php echo $nome_produto; ?> - Quantidade: <?php echo $quantidade; ?> - Preço unitário: R$ <?php echo number_format($preco, 2, ',', '.'); ?> - Total: <span class="pedido-total">R$ <?php echo number_format($total, 2, ',', '.'); ?></span></p>
        </div>
    <?php
    }
    if (!$pedidosEncontrados) {
        echo "<p>Você ainda não fez nenhum pedido.</p>";
    }
    ?>
</div>

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
</body>
</html>
