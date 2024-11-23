<?php
session_start();
include("conexão.php");

// Lista de produtos disponíveis
$produtos = "SELECT * from produtos";
$resultado_produtos = mysqli_query($conexao, $produtos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Compras</title>
    <style>
        /* Estilos gerais */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        /* Estilo para o header */
        header {
            background-color: #f8c8d2;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .menu__link {
            text-decoration: none;
            color: #9b4d96;
            font-weight: bold;
            margin: 0 15px;
            transition: color 0.3s;
        }

        header .menu__link:hover {
            color: #e3a0c1;
        }

        /* Estilos para quando o usuário estiver logado */
        #logado, #deslogado {
            display: inline-block;
            font-size: 1.1em;
        }

        /* Título da página */
        h1 {
            text-align: center;
            color: #9b4d96;
            font-size: 2.5em;
            margin-top: 20px;
        }

        /* Estilo para a lista de produtos */
        ul {
            list-style-type: none;
            padding: 0;
            margin: 30px auto;
            max-width: 800px;
        }

        ul li {
            background-color: #ffffff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li a {
            text-decoration: none;
            color: #9b4d96;
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #f8c8d2;
            transition: background-color 0.3s;
        }

        ul li a:hover {
            background-color: #e3a0c1;
        }

        /* Estilos adicionais para responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }
            ul li {
                flex-direction: column;
                align-items: flex-start;
            }
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
            <?php if(isset($_SESSION['id'])) {echo '<a href="perfil.php" class="menu__link">Perfil</a>';}?>
            <a href="logout.php" class="menu__link">Sair</a>
            <a href="carrinho.php" class="menu__link">Ver Carrinho</a>
        </div>
    </header>

    <?php
    if (isset($_SESSION['id'])) {
    ?>
    <h1>Produtos Disponíveis</h1>

    <ul>
        <?php
        if (mysqli_num_rows($resultado_produtos) > 0) {
            while ($rows_produto = mysqli_fetch_array($resultado_produtos)) {
                $id = $rows_produto['id'];
                $nome = $rows_produto['nome'];
                $preco = $rows_produto['preco'];

                echo "<li>";
                echo "<span><strong>$nome</strong> - R$ $preco</span>";
                echo "<a href='adicionar_carrinho.php?id=" . $id . "'>Adicionar ao Carrinho</a>";
                echo "</li>";
            }
        } else {
            echo "<p>Nenhum produto disponível.</p>";
        }
        ?>
    </ul>
    <?php
    }
    ?>

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
