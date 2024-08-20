<?php
include_once('config.php'); // Inclui o arquivo de configuração para conectar ao banco

if (isset($_POST['submit'])) { // Verifica se o formulário foi enviado

    // Coleta de dados do formulário
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $data_nasc = isset($_POST['data_nasc']) ? $_POST['data_nasc'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : ''; // Remove formatação do CPF
    $identidade = isset($_POST['identidade']) ? preg_replace('/\D/', '', $_POST['identidade']) : ''; // Remove formatação da Identidade

    // Verifica se algum campo obrigatório está vazio
    if (strlen($nome) == 0) {
        echo "<script>alert('Preencha seu nome');</script>";
    } else if (strlen($email) == 0) {
        echo "<script>alert('Preencha seu e-mail');</script>";
    } else if (strlen($telefone) == 0) {
        echo "<script>alert('Preencha seu telefone');</script>";
    } else if (strlen($sexo) == 0) {
        echo "<script>alert('Preencha seu sexo');</script>";
    } else if (strlen($data_nasc) == 0) {
        echo "<script>alert('Preencha a data de nascimento');</script>";
    } else if (strlen($endereco) == 0) {
        echo "<script>alert('Preencha seu endereço');</script>";
    } else {

        // Escapa dados para evitar SQL Injection
        $nome = $mysqli->real_escape_string($nome);
        $email = $mysqli->real_escape_string($email);
        $telefone = $mysqli->real_escape_string($telefone);
        $sexo = $mysqli->real_escape_string($sexo);
        $data_nasc = $mysqli->real_escape_string($data_nasc);
        $endereco = $mysqli->real_escape_string($endereco);
        $cpf = $mysqli->real_escape_string($cpf);
        $identidade = $mysqli->real_escape_string($identidade);

        // Inserção dos dados na tabela 'usuarios'
        $sql_code = "INSERT INTO usuarios (nome, email, telefone, sexo, data_nasc, endereco, cpf, identidade) 
                    VALUES ('$nome', '$email', '$telefone', '$sexo', '$data_nasc', '$endereco', '$cpf', '$identidade')";

        if ($mysqli->query($sql_code)) {
            echo "<script>alert('Dados inseridos com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao inserir os dados: " . $mysqli->error . "');</script>";
        }
    }

    // Fechamento da conexão com o banco de dados
    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
        }
        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #45a049;
        }
        .hidden {
            display: none;
        }

        .logo {
            max-height: 90px;
            margin-left: 80%;
        }

        .divSubtitulo {
            margin-left: 61%;
        }

        .texto {
            font-size: 24px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 18px;
        }

        .barraSuperior {
            padding: 20px;
            background-color: #f8f8f8;
            border-bottom: 2px solid #003366;
            display: flex;
            align-items: center;
            /* Adiciona espaço entre os itens, mas permite flexibilidade */
        }

        .footer {
            background-color: #003366;
            padding: 10px;
            color: white;
            text-align: center;
            position: relative; 
            bottom: 0; 
            width: 98%;
            margin-top: 50px; 
        }
    </style>
    <script>
        function alternarDocumento() {
            const tipoDocumento = document.querySelector('input[name="tipo_documento"]:checked').value;
            const campoCpf = document.getElementById('cpf');
            const campoIdentidade = document.getElementById('identidade');

            if (tipoDocumento === 'cpf') {
                campoCpf.classList.remove('hidden');
                campoIdentidade.classList.add('hidden');
            } else {
                campoCpf.classList.add('hidden');
                campoIdentidade.classList.remove('hidden');
            }
        }

        function formatarCPF(cpf) {
            return cpf
                .replace(/\D/g, '') // Remove caracteres não numéricos
                .replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1-$2-$3-$4'); // Adiciona a formatação
        }

        function aplicarFormatoCPF(event) {
            const input = event.target;
            input.value = formatarCPF(input.value);
        }

        function formatarIdentidade(identidade) {
            return identidade
                .replace(/\D/g, '') // Remove caracteres não numéricos
                .replace(/^(\d{10})(\d{1})$/, '$1-$2'); // Adiciona a formatação
        }

        function aplicarFormatoIdentidade(event) {
            const input = event.target;
            input.value = formatarIdentidade(input.value);
        }
    </script>
</head>
<body>
    <div class="barraSuperior">
        <a href="./index.php"><img src="img/ceara.png" class="logo"></a>
        <div class="divSubtitulo">
            <span class="texto">CRAS</span><br>
            <span class="subtitulo">Centro de Referência de Assistência Social <strong>2024</strong></span>
        </div>
    </div>

    <br><br><br>
    <form action="cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required>

        <label for="sexo">Sexo:</label>
        <input type="text" id="sexo" name="sexo" required>

        <label for="data_nasc">Data de Nascimento:</label>
        <input type="date" id="data_nasc" name="data_nasc" required>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>

        <label>Escolha o tipo de documento:</label>
        <label>
            <input type="radio" name="tipo_documento" value="cpf" onclick="alternarDocumento()" checked> CPF
        </label>
        <label>
            <input type="radio" name="tipo_documento" value="identidade" onclick="alternarDocumento()"> Identidade
        </label>

        <div id="cpf">
            <label for="cpf_input">CPF:</label>
            <input type="text" id="cpf_input" name="cpf" placeholder="000-000-000-00" pattern="\d{3}-\d{3}-\d{3}-\d{2}" inputmode="numeric" title="Digite um CPF válido no formato 000-000-000-00" oninput="aplicarFormatoCPF(event)">
        </div>

        <div id="identidade" class="hidden">
            <label for="identidade_input">Identidade (RG):</label>
            <input type="text" id="identidade_input" name="identidade" placeholder="0000000000-0" pattern="\d{10}-\d{1}" inputmode="numeric" title="Digite um RG válido no formato 0000000000-0" oninput="aplicarFormatoIdentidade(event)">
        </div>

        <input type="submit" name="submit" value="Cadastrar" class="submit-button">
    </form>

    <footer class="footer">
        <div class="container">
            <p id="txtFooter">&copy; 2024 CRAS - Centro de Referência de Assistência Social. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>