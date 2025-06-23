document.addEventListener('DOMContentLoaded', function() {
    
    // Seleciona os elementos do DOM necessários
    const userIcon = document.getElementById('userIconTrigger');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    // Se os elementos não existirem, não faz nada.
    if (!userIcon || !dropdownMenu) {
        console.error('Elementos do dropdown não encontrados. Verifique os IDs "userIconTrigger" e "userDropdownMenu" no seu HTML.');
        return;
    }

    // Ação ao clicar no ícone do usuário
    userIcon.addEventListener('click', function(event) {
        // Impede que o clique se propague para outros elementos (como a janela)
        event.stopPropagation();
        // Adiciona ou remove a classe 'show' do menu
        dropdownMenu.classList.add('show');
    });

    // Ação ao clicar em qualquer lugar da janela
    window.addEventListener('click', function(event) {
        // Se o menu está visível E o clique foi FORA dele, esconde o menu.
        if (dropdownMenu.classList.contains('show')) {
            dropdownMenu.classList.remove('show');
        }
    });

    // Impede que cliques DENTRO do menu o fechem
    dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});