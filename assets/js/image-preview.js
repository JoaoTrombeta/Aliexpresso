/**
 * ARQUIVO: assets/js/image-preview.js
 * Contém a função reutilizável para a pré-visualização de imagem.
 */
function setupImagePreview(inputId, previewId) {
    const imageInput = document.getElementById(inputId);
    const imagePreview = document.getElementById(previewId);

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                imagePreview.classList.remove('hidden');
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }
}