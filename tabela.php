<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chamadas do CRAS</title>
    <link href="css/style.css" rel="stylesheet">
    <script src="js/script.js" defer></script>
</head>
<body>

    <div class="barraSuperior">
        <a href="index.php"><img src="img/ceara.png" class="logo"></a>
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

    <footer class="footer">
        <div class="container">
            <p id="txtFooter">&copy; 2024 CRAS - Centro de Referência de Assistência Social. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nomeAtual = document.getElementById('nomeAtual');
            const listaProximos = document.getElementById('listaProximos');
            const ultimaChamada = document.getElementById('ultimaChamada');

            function falarNome(nome) {
                const synth = window.speechSynthesis;
                const utterThis = new SpeechSynthesisUtterance(nome);

                synth.onvoiceschanged = () => {
                    const voices = synth.getVoices();
                    utterThis.voice = voices.find(voice => 
                        voice.name.includes('Google português do Brasil') || 
                        voice.name.includes('Microsoft Maria') || 
                        voice.name.includes('Microsoft Leticia') || 
                        voice.lang === 'pt-BR'
                    );
                    utterThis.rate = 1;
                    utterThis.pitch = 1;
                    synth.speak(utterThis);
                };
            }

            function atualizarListas() {
                // Atualiza a lista de espera
                const lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
                listaProximos.innerHTML = '';
                lista.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    listaProximos.appendChild(row);
                });

                // Atualiza as últimas chamadas
                const chamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];
                ultimaChamada.innerHTML = '';
                chamadas.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    ultimaChamada.appendChild(row);
                });

                // Atualiza o nome atual e chama a API de fala
                const atual = localStorage.getItem('nome_atual');
                if (atual) {
                    nomeAtual.textContent = atual;
                    falarNome(atual);
                } else {
                    nomeAtual.textContent = 'Nenhum nome chamado';
                }
            }

            // Atualiza as listas a cada 5 segundos
            setInterval(atualizarListas, 5000);

            // Atualiza a lista imediatamente ao carregar a página
            atualizarListas();
        });

        function moverNomeParaAtual() {
            const lista = JSON.parse(localStorage.getItem('lista_espera')) || [];
            if (lista.length > 0) {
                const nomeAtual = lista.shift();
                localStorage.setItem('nome_atual', nomeAtual);
                localStorage.setItem('lista_espera', JSON.stringify(lista));

                // Adiciona o nome atual às últimas chamadas
                const ultimasChamadas = JSON.parse(localStorage.getItem('ultimas_chamadas')) || [];
                if (nomeAtual) {
                    ultimasChamadas.unshift(nomeAtual);
                    if (ultimasChamadas.length > 5) {
                        ultimasChamadas.pop(); // Limita a 5 últimas chamadas
                    }
                    localStorage.setItem('ultimas_chamadas', JSON.stringify(ultimasChamadas));
                }

                // Fala o nome assim que é movido
                falarNome(nomeAtual);
            }
        }
    </script>
</body>
</html>
