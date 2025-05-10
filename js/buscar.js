document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchIcon.addEventListener('click', function() {
        console.log('Icono de búsqueda clickeado');
        // Alterna la clase 'show' para manejar la visibilidad
        searchInput.classList.toggle('show');
        searchResults.style.display = 'none'; // Oculta los resultados de búsqueda
    });

    searchInput.addEventListener('input', function() {
        searchResults.style.display = 'block'; // Mostrar los resultados
        searchResults.innerHTML = '<p>Resultados...</p>'; // Puedes llenar los resultados dinámicamente
    });

    // Cerrar la búsqueda si el usuario hace clic fuera del área de búsqueda
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-container')) {
            searchInput.classList.remove('show');  // Oculta el input al hacer clic fuera
            searchResults.style.display = 'none';
        }
    });
});
