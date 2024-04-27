function toggleDropdown(element) {
    element.classList.toggle('active'); // Agrega o quita la clase 'active' al elemento clicado
            
    // Cambiar el color de la imagen cuando se hace clic
    var img = element.querySelector('img');
    if (img) {
        img.classList.toggle('white-color'); // Agrega o quita la clase 'white-color' a la imagen
    }
}