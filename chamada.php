<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Chamadas</title>
    <link href="css/style.css" rel="stylesheet">
    <script src="js/script.js" defer></script>
</head>
<body>

    <div class="barraSuperior">
    <a href="index.php"><img src="img/ceara.png" class="logo"><a>
        <div>
            <span class="texto">CRAS</span><br>
            <span class="subtitulo">Centro de Referência de Assistência Social <strong>2024</strong></span>
        </div>
    </div>

    <div class="container">
        <div class="col-left">
            <div class="senhaAtual">
                <div class="row">
                    <div class="col-xs-12">
                        <span class="senhaAtualTexto">NOME:</span>
                        <span id="nomeAtual">Nome do Usuário</span>
                    </div>
                </div>
            </div>

            <div class="ultimaSenha">
                <table class="table-simples">
                    <thead>
                        <tr>
                            <th>ÚLTIMAS PESSOAS CHAMADAS</th>
                        </tr>
                    </thead>
                    <tbody id="ultimaChamada">
                        <!-- As últimas chamadas serão inseridas aqui -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-right">
            <h3>LISTA DE ESPERA</h3>
            <table class="table-simples">
                <thead>
                    <tr>
                        <th>NOMES</th>
                    </tr>
                </thead>
                <tbody id="listaProximos">
                    <!-- A lista de espera será inserida aqui -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="formContainer">
        <form id="formChamada">
            <div class="form-group">
                <label for="inputNome">Insira o nome:</label>
                <input type="text" id="inputNome" class="form-control" placeholder="Nome da Pessoa">
            </div>
            <br>
            <button type="button" id="btnAdicionar" class="btn btn-primary">Adicionar</button>
            <button type="button" id="btnRemover" class="btn btn-danger">Remover</button>
            <button type="button" id="btnMover" class="btn btn-success">Chamar</button>
            <button type="button" id="btnChamarDeNovo" class="btn btn-info">Chamar de Novo</button>
        </form>
    </div>
    <br><br>

    <footer class="footer">
        <div class="container">
            <p id="txtFooter">&copy; 2024 CRAS - Centro de Referência de Assistência Social. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputNome = document.getElementById('inputNome');
            const btnAdicionar = document.getElementById('btnAdicionar');
            const btnRemover = document.getElementById('btnRemover');
            const btnMover = document.getElementById('btnMover');
            const btnChamarDeNovo = document.getElementById('btnChamarDeNovo');

            function atualizarLocalStorage() {
                const lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                const chamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];

                localStorage.setItem('lista_espera', JSON.stringify(lista));
                localStorage.setItem('ultimas_chamadas', JSON.stringify(chamadas));
            }

            function adicionarNome() {
                const nome = inputNome.value.trim();
                if (nome === '') {
                    alert('Por favor, insira um nome.');
                    return;
                }

                let lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                if (!lista.includes(nome)) {
                    lista.push(nome);
                    localStorage.setItem('lista_espera', JSON.stringify(lista));
                    inputNome.value = '';
                } else {
                    alert('Nome já está na lista.');
                }
            }

            function removerNome() {
                const nome = inputNome.value.trim();
                if (nome === '') {
                    alert('Por favor, insira um nome.');
                    return;
                }

                let lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                lista = lista.filter(item => item !== nome);
                localStorage.setItem('lista_espera', JSON.stringify(lista));
                inputNome.value = '';
            }

            function moverParaChamada() {
                let lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                const nome = inputNome.value.trim();
                if (nome === '' || !lista.includes(nome)) {
                    alert('Nome não encontrado na lista.');
                    return;
                }

                let chamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];
                chamadas.unshift(nome);
                chamadas = chamadas.slice(0, 5);
                localStorage.setItem('ultimas_chamadas', JSON.stringify(chamadas));

                lista = lista.filter(item => item !== nome);
                localStorage.setItem('lista_espera', JSON.stringify(lista));

                localStorage.setItem('nome_atual', nome);
                inputNome.value = '';
            }

            function chamarDeNovo() {
                let chamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];
                if (chamadas.length > 0) {
                    const nome = chamadas.shift();
                    chamadas.push(nome);
                    localStorage.setItem('ultimas_chamadas', JSON.stringify(chamadas));
                    localStorage.setItem('nome_atual', nome);
                }
            }

            btnAdicionar.addEventListener('click', adicionarNome);
            btnRemover.addEventListener('click', removerNome);
            btnMover.addEventListener('click', moverParaChamada);
            btnChamarDeNovo.addEventListener('click', chamarDeNovo);

            const nomeAtual = document.getElementById('nomeAtual');
            const listaProximos = document.getElementById('listaProximos');
            const ultimaChamada = document.getElementById('ultimaChamada');

            function atualizarListas() {
                const lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                listaProximos.innerHTML = '';
                lista.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    listaProximos.appendChild(row);
                });

                const chamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];
                ultimaChamada.innerHTML = '';
                chamadas.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    ultimaChamada.appendChild(row);
                });

                const atual = localStorage.getItem('nome_atual');
                nomeAtual.textContent = atual || 'Nenhum nome chamado';
            }

            setInterval(atualizarListas, 5000);
            atualizarListas();
        });
    </script>
</body>
</html>
