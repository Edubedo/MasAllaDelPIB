document.getElementById("botonContinuar").addEventListener("click", function () {
    document.getElementById("modal").style.display = "none"; // Oculta el modal
    document.getElementById("overlay").style.display = "none"; // Quita la capa oscura

    // Enviar petición a PHP para marcar que el modal ya se mostró
    fetch("ocultar_modal.php", { method: "POST" });
});


function editarPublicacion(id) {
    if (id) {
        // Redirige a la URL de edición con el ID de la publicación
        window.location.href = `posts-detalles.php?id=${id}`;
    }
}

// Función para eliminar una publicación
function eliminarPublicacion(id) {
    if (id && confirm('¿Estás seguro de eliminar esta publicación?')) {
        // Redirige a la URL de eliminación con el ID de la publicación
        window.location.href = `posts-eliminar.php?id=${id}`;
    }
}

document.getElementById("logo_admin").addEventListener("click", function (event) {
    let popup = document.getElementById("userPopup");
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
        popup.style.top = event.clientY + "px";
        popup.style.left = event.clientX + "px";
    }
});

document.addEventListener("click", function (event) {
    let popup = document.getElementById("userPopup");
    let logo = document.getElementById("logo_admin");
    if (popup.style.display === "block" && event.target !== popup && event.target !== logo) {
        popup.style.display = "none";
    }
});


document.getElementById('categoryFilter').addEventListener('change', function() {
    let selectedCategory = this.value;
    let rows = document.querySelectorAll('.postRow');

    // Si no se seleccionó ninguna categoría o se seleccionó todas
    rows.forEach(function(row) {
        let categoryCell = row.cells[2].innerText.toLowerCase(); // Índice 2 es la columna de categorías

        if (selectedCategory === '' || selectedCategory === 'todas las categorías' || categoryCell.includes(selectedCategory.toLowerCase())) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none'; 
        }
    });
});


// Función para el buscador general que filtra por cualquier palabra en cualquier columna
document.getElementById('searchInput').addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase();
    let rows = document.querySelectorAll('.postRow');

    rows.forEach(function(row) {
        let cells = row.getElementsByTagName('td');
        let matchFound = false;

        for (let i = 0; i < cells.length; i++) {
            if (cells[i].innerText.toLowerCase().includes(searchTerm)) {
                matchFound = true;
                break;
            }
        }

        if (matchFound) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
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