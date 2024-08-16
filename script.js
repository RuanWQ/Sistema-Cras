document.addEventListener("DOMContentLoaded", function() {
    const btnAdicionar = document.getElementById('btnAdicionar');
    const btnMover = document.getElementById('btnMover');
    const btnRemover = document.getElementById('btnRemover');
    const btnChamarDeNovo = document.getElementById('btnChamarDeNovo');
    const inputNome = document.getElementById('inputNome');
    const nomeAtual = document.getElementById('nomeAtual');
    const listaProximos = document.getElementById('listaProximos');
    const ultimaChamadaNome = document.getElementById('ultimaChamadaNome');
    const ultimaChamadaTexto = document.getElementById('ultimaChamadaTexto');
    let ultimaChamadaLista = [];
    let vozesFemininas = [];

    function adicionarNome() {
        const nome = inputNome.value.trim();
        if (nome) {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.textContent = nome;
            listaProximos.appendChild(li);
            inputNome.value = '';
        }
    }

    function falarNome(nome) {
        if ('speechSynthesis' in window) {
            const msg = new SpeechSynthesisUtterance(nome);
            msg.lang = 'pt-BR'; // Define o idioma para português

            // Selecionar a voz feminina
            if (vozesFemininas.length > 0) {
                msg.voice = vozesFemininas[0];
            }

            window.speechSynthesis.speak(msg);
        } else {
            alert('Seu navegador não suporta a API de fala.');
        }
    }

    function atualizarUltimasChamadas(nome) {
        if (nome && nome !== 'Nome da Pessoa') {
            ultimaChamadaLista.unshift(nome);
            if (ultimaChamadaLista.length > 5) {
                ultimaChamadaLista.pop();
            }
            ultimaChamadaTexto.innerHTML = "<ul>";
            ultimaChamadaLista.forEach(n => {
                ultimaChamadaTexto.innerHTML += `<li>${n}</li>`;
            });
            ultimaChamadaTexto.innerHTML += "</ul>";
        }
    }

    btnAdicionar.addEventListener('click', adicionarNome);

    btnMover.addEventListener('click', function() {
        const primeiroNome = listaProximos.querySelector('li');
        if (primeiroNome) {
            const nome = primeiroNome.textContent;
            nomeAtual.textContent = nome;
            atualizarUltimasChamadas(nome);
            listaProximos.removeChild(primeiroNome);
            falarNome(nome); // Só fala o nome quando ele vai para o campo "NOME"
        }
    });

    btnRemover.addEventListener('click', function() {
        if (listaProximos.lastElementChild) {
            listaProximos.removeChild(listaProximos.lastElementChild);
        }
    });

    btnChamarDeNovo.addEventListener('click', function() {
        const nome = nomeAtual.textContent;
        if (nome && nome !== 'Nome da Pessoa') {
            falarNome(nome); // Repete a fala do nome que está no campo "NOME"
        }
    });

    inputNome.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            adicionarNome();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.which === 39) {
            event.preventDefault();
            btnMover.click();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === '1') {
            event.preventDefault();
            btnChamarDeNovo.click();
        }
    });

    window.addEventListener('beforeunload', function(event) {
        event.preventDefault();
        // Mensagem padrão para navegadores modernos
        event.returnValue = 'Tem certeza que deseja sair ou recarregar a página? As alterações não salvas serão perdidas.';
    });

    // Carregar vozes e filtrar vozes femininas
    window.speechSynthesis.onvoiceschanged = function() {
        const vozes = window.speechSynthesis.getVoices();
        vozesFemininas = vozes.filter(voice => voice.name.toLowerCase().includes('female') || voice.name.toLowerCase().includes('feminine'));
    };
});
