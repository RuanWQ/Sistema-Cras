document.addEventListener('DOMContentLoaded', function() {
    const inputNome = document.getElementById('inputNome');
    const btnAdicionar = document.getElementById('btnAdicionar');
    const btnRemover = document.getElementById('btnRemover');
    const btnMover = document.getElementById('btnMover');
    const btnChamarDeNovo = document.getElementById('btnChamarDeNovo');
    const listaProximos = document.getElementById('listaProximos');
    const ultimaChamada = document.getElementById('ultimaChamada');
    const nomeAtual = document.getElementById('nomeAtual');

    function atualizarLista() {
        fetch('funcoes.php?action=listar_espera')
            .then(response => response.json())
            .then(data => {
                listaProximos.innerHTML = '';
                data.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    listaProximos.appendChild(row);
                });
            })
            .catch(error => console.error('Erro ao atualizar lista de espera:', error));
    }

    function atualizarUltimasChamadas() {
        fetch('funcoes.php?action=ultimas_chamadas')
            .then(response => response.json())
            .then(data => {
                ultimaChamada.innerHTML = '';
                data.forEach(nome => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.textContent = nome;
                    row.appendChild(cell);
                    ultimaChamada.appendChild(row);
                });
            })
            .catch(error => console.error('Erro ao atualizar últimas chamadas:', error));
    }

    function adicionarNome() {
        const nome = inputNome.value.trim();
        if (nome === '') {
            alert('Por favor, insira um nome.');
            return;
        }

        fetch('funcoes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'action': 'adicionar',
                'nome': nome
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Nome adicionado com sucesso!');
                atualizarLista();
                inputNome.value = '';
            } else {
                alert('Erro ao adicionar nome.');
            }
        })
        .catch(error => console.error('Erro ao adicionar nome:', error));
    }

    function removerNome() {
        const nome = inputNome.value.trim();
        if (nome === '') {
            alert('Por favor, insira um nome.');
            return;
        }

        fetch('funcoes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'action': 'remover',
                'nome': nome
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Nome removido com sucesso!');
                atualizarLista();
                inputNome.value = '';
            } else {
                alert('Erro ao remover nome.');
            }
        })
        .catch(error => console.error('Erro ao remover nome:', error));
    }

    function moverParaChamada() {
        fetch('funcoes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'action': 'mover',
                'nome': inputNome.value.trim()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nomeAtual.textContent = data.nome;
                alert('Nome chamado com sucesso!');
                atualizarLista();
                atualizarUltimasChamadas();
                inputNome.value = '';
            } else {
                alert('Erro ao chamar nome.');
            }
        })
        .catch(error => console.error('Erro ao chamar nome:', error));
    }

    function chamarDeNovo() {
        fetch('funcoes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'action': 'chamar_novamente'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nomeAtual.textContent = data.nome;
                alert('Nome chamado novamente com sucesso!');
                atualizarLista();
                atualizarUltimasChamadas();
            } else {
                alert('Erro ao chamar nome novamente.');
            }
        })
        .catch(error => console.error('Erro ao chamar nome novamente:', error));
    }

    btnAdicionar.addEventListener('click', adicionarNome);
    btnRemover.addEventListener('click', removerNome);
    btnMover.addEventListener('click', moverParaChamada);
    btnChamarDeNovo.addEventListener('click', chamarDeNovo);

    // Inicializa as listas quando a página é carregada
    atualizarLista();
    atualizarUltimasChamadas();
});
