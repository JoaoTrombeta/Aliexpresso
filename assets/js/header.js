document.addEventListener('DOMContentLoaded', function() {
    const userIconTrigger = document.getElementById('userIconTrigger');
    const userDropdownMenu = document.getElementById('userDropdownMenu');

    if (userIconTrigger && userDropdownMenu) {
        userIconTrigger.addEventListener('click', function(event) {
            event.stopPropagation(); // Impede que o clique feche o menu imediatamente (ver abaixo)
            // Alterna a exibição do dropdown
            userDropdownMenu.style.display = userDropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Opcional: Fecha o dropdown se clicar fora dele
        document.addEventListener('click', function(event) {
            if (userDropdownMenu.style.display === 'block' && !userIconTrigger.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                userDropdownMenu.style.display = 'none';
            }
        });

        // Opcional: Fecha o dropdown se a tecla 'Escape' for pressionada
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && userDropdownMenu.style.display === 'block') {
                userDropdownMenu.style.display = 'none';
            }
        });
    }
});