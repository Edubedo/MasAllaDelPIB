document.addEventListener('DOMContentLoaded', function () {
    const icon = document.getElementById('search-icon');
    const input = document.getElementById('search-input');
    const resultsBox = document.getElementById('search-results');

    icon.addEventListener('click', () => {
        input.style.display = input.style.display === 'none' ? 'block' : 'none';
        input.focus();
        resultsBox.style.display = 'none';
    });

    input.addEventListener('input', function () {
        const query = this.value;

        if (query.length >= 2) {
            fetch(`/ajax/buscar-posts.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length === 0) {
                        resultsBox.innerHTML = '<p style="padding:10px;">No se encontraron resultados.</p>';
                    } else {
                        data.forEach(post => {
                            const link = document.createElement('a');
                            link.href = `/post.php?id=${post.Id_posts}`;
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

    // Ocultar resultados si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!document.querySelector('.search-container').contains(e.target)) {
            resultsBox.style.display = 'none';
            input.style.display = 'none';
        }
    });
});
