window.onload = function () {
    // Verificar si el modal ya ha sido mostrado en esta sesión
    if (!localStorage.getItem('cookiesAccepted')) {
        setTimeout(function () {
            fetch('./views/layout/cookies.php')
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById('cookiesContainer');
                    if (container) {
                        container.innerHTML = data;
                        container.classList.add('mostrar'); // Mostrar modal con fondo opaco
                        
                        // Bloquear scroll
                        document.body.style.overflow = 'hidden';

                        // Agregar eventos a los botones
                        container.addEventListener('click', function (event) {
                            if (event.target.classList.contains('aceptarBoton') || 
                                event.target.classList.contains('rachazarBoton')) {
                                container.classList.remove('mostrar'); // Ocultar modal
                                
                                // Restaurar scroll
                                document.body.style.overflow = 'auto';

                                // Marcar que las cookies han sido aceptadas
                                localStorage.setItem('cookiesAccepted', 'true');
                            }
                        });
                    } else {
                        console.error("El elemento #cookiesContainer no se encontró.");
                    }
                })
                .catch(error => console.error("Error al cargar el modal de cookies:", error));
        }, 3000); // 3 segundos
    }
};
