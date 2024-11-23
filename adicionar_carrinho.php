<?php
// Verifica se o ID do produto foi enviado na URL
if (isset($_GET['id'])) {
    $idProduto = $_GET['id']; // Captura o ID do produto

    // Recupera o carrinho atual ou inicializa como string vazia
    $carrinho = isset($_COOKIE['carrinho']) ? $_COOKIE['carrinho'] : '';

    // Adiciona o produto ao carrinho ou incrementa a quantidade
    if (strpos($carrinho, $idProduto) !== false) {
        // Converte a string do carrinho em um array
        $itens = explode(',', $carrinho);
        foreach ($itens as &$item) {
            list($id, $quantidade) = explode(':', $item);
            if ($id == $idProduto) {
                // Incrementa a quantidade
                $quantidade++;
                $item = "$id:$quantidade"; // Atualiza o item no array
                break; // Saia do loop após encontrar o produto
            }
        }
        // Recria a string do carrinho
        $carrinho = implode(',', $itens);
    } else {
        // Se o produto não existe, adiciona ao carrinho com quantidade 1
        $carrinho .= ($carrinho ? "," : "") . "$idProduto:1"; // Concatena, separando por vírgula
    }
    
// Define o cookie 'carrinho' com o carrinho atualizado e expiração de 30 dias
setcookie('carrinho', $carrinho, time() + (30 * 24 * 60 * 60), "/");  // 30 dias em segundos

    // Redireciona de volta para a página principal
    header("Location: index.php");
    exit();
} else {
    // Mensagem de erro caso o produto não seja válido
    echo "Produto inválido.";
}
?>
