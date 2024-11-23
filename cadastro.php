<?php
include("conexão.php");

if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_FILES['imagem']) && isset($_FILES['file'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Recebimento imagem
    $NomeImagem = $_FILES['imagem']['name']; // Nome do arquivo
    $TipoImagem = $_FILES['imagem']['tmp_name']; // Caminho temporário do arquivo
    $TamanhoImagem = $_FILES['imagem']['size']; // Tamanho do arquivo
    $ImagemError = $_FILES['imagem']['error']; // Erro no upload
    $TiposImagem = ['image/jpeg', 'image/png', 'image/gif']; // Tipos de arquivos permitidos
    $TamanhoMax = 2 * 1024 * 1024; // Tamanho máximo de 2MB  

    // Recebimento PDF
    $fileName = $_FILES['file']['name']; // Nome do arquivo
    $fileTmpName = $_FILES['file']['tmp_name']; // Caminho temporário do arquivo
    $fileSize = $_FILES['file']['size']; // Tamanho do arquivo
    $fileError = $_FILES['file']['error']; // Erro no upload
    $TipoFile = ['application/pdf']; // Tipos de arquivos permitidos (apenas PDF)

    if ($ImagemError === 0 || $fileError === 0) { // Verifica se não houve erro no upload
        // Verifica se o tipo e o tamanho do arquivo de imagem estão corretos
        if (in_array($_FILES['imagem']['type'], $TiposImagem) && $TamanhoImagem <= $TamanhoMax) {
            // Verifica se o tipo e o tamanho do arquivo PDF estão corretos
            if (in_array($_FILES['file']['type'], $TipoFile) && $fileSize <= $TamanhoMax) {
                $pdf = 'uploads/' . basename($fileName); // Define o destino do arquivo com basename para evitar problemas
                $imagem = 'uploads/' . $NomeImagem;

                // Move o arquivo para a pasta de destino
                if (move_uploaded_file($TipoImagem, $imagem) && move_uploaded_file($fileTmpName, $pdf)) {
                    // Insere o caminho do arquivo no banco de dados
                    $sql = "INSERT INTO clientes (nome, email, senha, foto, pdf) VALUES ('$nome','$email', '$senha' , '$imagem' , '$pdf')";

                    // Executa a consulta e verifica se foi bem-sucedida
                    if ($conexao->query($sql) === TRUE) {
                        echo "Cadastro realizado com sucesso! <a href='login.php' class='btn btn-primary btn-sm ml-2'>Faça seu login</a>";
                    } else {
                        echo "Erro ao inserir no banco: " . $conn->error; // Exibe erro se houver
                    }
                } else {
                    echo "Falha ao mover o arquivo."; // Exibe erro se não conseguir mover o arquivo
                }
            } else {
                echo "O arquivo PDF deve ser do tipo PDF e ter tamanho máximo de 2MB."; // Exibe erro se o arquivo PDF não for válido
            }
        } else {
            echo "O arquivo de imagem deve ser JPEG, PNG ou GIF e ter tamanho máximo de 2MB."; // Exibe erro se o tipo de imagem for inválido
        }
    } else {
        echo "Erro no upload do arquivo!"; // Exibe erro no upload
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Cadastro de Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
        }

        .card-register {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8c8d2;
            color: #9b4d96;
            font-weight: bold;
            text-align: center;
            font-size: 1.5em;
            padding: 15px;
        }

        .card-body {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f0c1d1;
            background-color: #f9e7f1;
        }

        label {
            font-weight: bold;
            color: #9b4d96;
        }

        .btn-primary {
            background-color: #9b4d96;
            border-color: #9b4d96;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #e3a0c1;
            border-color: #e3a0c1;
        }

        .d-block {
            text-align: center;
            margin-top: 20px;
        }

        .d-block a {
            text-decoration: none;
            color: #9b4d96;
            font-weight: bold;
        }

        .d-block a:hover {
            color: #e3a0c1;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card card-register mx-auto col-8 px-0">
            <div class="card-header">Cadastro</div>
            <div class="card-body">
                <form action="cadastro.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-12">
                                <label for="Nome">Nome completo</label>
                                <input type="text" name="nome" class="form-control" placeholder="Digite seu nome completo" required>
                            </div>
                            <div class="col-12">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Digite seu email" required>
                            </div>
                            <div class="col-12">
                                <label for="senha">Senha</label>
                                <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                            </div>
                            <div class="col-12">
                                <label for="imagem">Escolha sua foto de perfil:</label>
                                <input type="file" name="imagem" id="imagem" required>
                            </div>
                            <div class="col-12">
                                <label for="file">Sua identidade em PDF:</label>
                                <input type="file" name="file" id="file" required>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Cadastrar</button>
                    <div class="text-center">
                        <a href="index.php" class="d-block mt-3">Página inicial</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
