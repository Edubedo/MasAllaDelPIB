document.addEventListener('DOMContentLoaded', function () {
    const icon = document.getElementById('search-icon');
    const input = document.getElementById('search-input');
    const resultsBox = document.getElementById('search-results');
    const searchContainer = document.querySelector('.search-container');

    // Mostrar/ocultar input al hacer clic en el ícono
    icon.addEventListener('click', (e) => {
        e.stopPropagation(); // Previene que el click en el ícono se propague al document
        input.classList.add('show');
        input.focus();
    });

    // Ocultar input y resultados al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!searchContainer.contains(e.target)) {
            input.classList.remove('show');
            resultsBox.style.display = 'none';
        }
    });

    // Buscar cuando se escribe
    input.addEventListener('input', function () {
        const query = this.value;

        if (query.length >= 2) {
            fetch(`/views/ajax/buscar-posts.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length === 0) {
                        resultsBox.innerHTML = '<p style="padding:1rem; font-size:1.5rem; color:#000">No se encontraron resultados.</p>';
                    } else {
                        data.forEach(post => {
                            const link = document.createElement('a');
                            link.href = `/views/post.php?id=${post.Id_posts}`;
                            link.textContent = post.title;
                            resultsBox.appendChild(link);
                        });
                    }
                    resultsBox.style.display = 'block';
                });
        } else {
            resultsBox.innerHTML = '';
            resultsBox.style.display = 'none';
        }
    });
});
