<?php
session_start();
include("conexão.php");

if (isset($_SESSION['id'])) {
    $cliente_id = $_SESSION['id'];
}

$pedidos_feitos = "SELECT carrinho_compras.id as id_pedido, produtos.nome, produtos.preco, carrinho_compras.quantidade 
                   FROM carrinho_compras 
                   JOIN produtos ON carrinho_compras.produto_id = produtos.id 
                   WHERE carrinho_compras.cliente_id = '$cliente_id'";
$resultado_pedidos = mysqli_query($conexao, $pedidos_feitos);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resumo Pedido</title>
</head>
<body>
<header>
    <div id="deslogado">
        <a href="login.php" class="menu__link">Login</a>
        <a href="cadastro.php" class="menu__link">Cadastre-se</a>
    </div>
    <div id="logado">
        <?php if(isset($_SESSION['id'])) {echo '<a  href="perfil.php" class="menu__link">Perfil</a>';}?>
        <a href="logout.php" class="menu__link">Sair</a>
        <a href="carrinho.php">Ver Carrinho</a>
    </div>
</header>

<h1>Seus Pedidos</h1>

<?php
if (isset($_SESSION['id'])) {
    // Verifica se o cliente possui pedidos
    if ($resultado_pedidos && mysqli_num_rows($resultado_pedidos) > 0) {
        $totalPedido = 0; // Inicializa o total do pedido
        echo "<ul>";
        while ($rows_produto = mysqli_fetch_array($resultado_pedidos)) {
            $nome = $rows_produto['nome'];
            $preco = $rows_produto['preco'];
            $quantidade = $rows_produto['quantidade'];
            $id_pedido = $rows_produto['id_pedido'];
            $total = $preco * $quantidade;
            $totalPedido += $total; // Acumula o total do pedido

            echo "<li>";
            echo "<h4>Pedido #$id_pedido</h4>";
            echo "Produto: $nome - Quantidade: $quantidade - Preço unitário: R$ $preco - Total: R$ $total";
            echo "</li>";
        }
        echo "</ul>";
        echo "<h3>Total do Pedido: R$ $totalPedido</h3>"; // Exibe o total do pedido
    } else {
        echo "<p>Você ainda não fez nenhum pedido.</p>";
    }
} else {
    echo "<p>Você precisa estar logado para visualizar o pedido.</p>";
}
?>
</body>

<script>    
    function logado() {
        document.getElementById('logado').style.display='';
        document.getElementById('deslogado').style.display='none';
    }
    function deslogado() {
        document.getElementById('logado').style.display='none';
        document.getElementById('deslogado').style.display='';
    }
</script>
<?php
if (isset($_SESSION['id'])) {
    echo '<script> logado() </script>';
} else {
    echo '<script> deslogado() </script>';
}
?>
</html>
