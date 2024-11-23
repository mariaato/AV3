<?php
include('conexaoLogin.php');

if(isset($_POST['email']) || isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM clientes WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: index.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <div class="card-header">Acesse sua conta</div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <div class="col-12">
                            <label>E-mail</label>
                            <input type="text" name="email" class="form-control" placeholder="Digite seu e-mail" required>
                        </div>

                        <div class="col-12">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>

                        <p>
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </p>
                    </div>
                </form>
                <div class="text-center">
                    <a href="index.php" class="d-block mt-3">Página inicial</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
