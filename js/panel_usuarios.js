document.getElementById("logo_admin").addEventListener("click", function(event) {
    let popup = document.getElementById("userPopup");
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
        popup.style.top = event.clientY + "px";
        popup.style.left = event.clientX + "px";
    }
});

document.addEventListener("click", function(event) {
    let popup = document.getElementById("userPopup");
    let logo = document.getElementById("logo_admin");
    if (popup.style.display === "block" && event.target !== popup && event.target !== logo) {
        popup.style.display = "none";
    }
});


// Asignar el evento de clic a todos los botones de eliminar
document.querySelectorAll('.btn.eliminar').forEach(function(btn) {
    btn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada (redirección)
        showDeleteModal(this); // Mostrar el modal
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Variables del modal
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    let deleteLink = null;

    // Función para mostrar el modal
    function showDeleteModal(link) {
        deleteLink = link; // Guardamos el enlace de eliminación
        deleteModal.style.display = 'flex'; // Mostrar el modal
        document.body.style.overflow = 'hidden'; // Evitar que el fondo se desplace
    }

    // Función para cerrar el modal
    function closeDeleteModal() {
        deleteModal.style.display = 'none'; // Ocultar el modal
        document.body.style.overflow = ''; // Restaurar el desplazamiento del fondo
    }

    // Confirmar eliminación
    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteLink) {
            // Redirigir al enlace de eliminación después de un pequeño retraso para que el modal se cierre primero
            closeDeleteModal(); // Cerrar el modal
            setTimeout(function() {
                window.location.href = deleteLink.href; // Redirigir al enlace de eliminación
            }, 200); // Retrasar para evitar que el modal desaparezca demasiado rápido
        }
    });

    // Cancelar eliminación
    cancelDeleteBtn.addEventListener('click', function() {
        closeDeleteModal(); // Cerrar el modal sin eliminar
    });

    // Asignar el evento de clic a todos los botones de eliminar
    document.querySelectorAll('.btn.eliminar').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir la acción predeterminada (redirección)
            showDeleteModal(this); // Mostrar el modal
        });
    });
});