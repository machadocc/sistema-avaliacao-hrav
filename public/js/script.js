document.getElementById('setor').addEventListener('change', function() {
    const setorId = this.value;
    
    // Limpa a lista de dispositivos
    const dispositivoSelect = document.getElementById('dispositivo');
    dispositivoSelect.innerHTML = '<option value="">Escolha um dispositivo</option>';

    if (setorId) {
        // Fazer uma requisição AJAX para buscar os dispositivos do setor selecionado
        fetch('get_dispositivos.php?setor_id=' + setorId)
            .then(response => response.json())
            .then(data => {
                data.forEach(dispositivo => {
                    const option = document.createElement('option');
                    option.value = dispositivo.id;
                    option.text = dispositivo.nome;
                    dispositivoSelect.appendChild(option);
                });
            });
    }
});

function abrirModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function selectNota(nota) {
    // Desmarcar todas as bolinhas
    const bolinhas = document.querySelectorAll('.bolinha');
    bolinhas.forEach(bolinha => {
        bolinha.classList.remove('selected');
    });

    // Marcar a bolinha selecionada
    document.getElementById('nota' + nota).checked = true;
    document.querySelector(`.bolinha:nth-child(${nota})`).classList.add('selected');
}

function toggleFeedback() {
    const feedbackContainer = document.getElementById('feedback-container');
    feedbackContainer.style.display = feedbackContainer.style.display === 'none' ? 'block' : 'none';
}