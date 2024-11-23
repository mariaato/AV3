<?php
// Apaga o cookie 'carrinho' definindo uma data de expiração no passado
// Isso efetivamente remove o cookie do navegador do usuário
setcookie('carrinho', '', time() - 3600, "/"); // O caminho "/" indica que o cookie é acessível em todo o site

// Redireciona para a página principal após limpar o carrinho
header("Location: index.php"); // Envia um cabeçalho HTTP para redirecionar o navegador
exit(); // Interrompe a execução do script para garantir que nenhuma outra saída seja enviada
?>