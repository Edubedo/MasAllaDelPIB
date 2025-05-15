document.addEventListener('DOMContentLoaded', function () {
    // Modal inicial
    document.getElementById("botonContinuar")?.addEventListener("click", function () {
        document.getElementById("modal").style.display = "none";
        document.getElementById("overlay").style.display = "none";

        // Enviar petición a PHP para marcar que el modal ya se mostró
        fetch("ocultar_modal.php", { method: "POST" });
    });

    // Redirección para editar publicación
    window.editarPublicacion = function (id) {
        if (id) {
            window.location.href = `posts-detalles.php?id=${id}`;
        }
    };

    // Menú emergente de usuario
    const logoAdmin = document.getElementById("logo_admin");
    const userPopup = document.getElementById("userPopup");

    logoAdmin?.addEventListener("click", function (event) {
        if (userPopup.style.display === "block") {
            userPopup.style.display = "none";
        } else {
            userPopup.style.display = "block";
            userPopup.style.top = event.clientY + "px";
            userPopup.style.left = event.clientX + "px";
        }
    });

    document.addEventListener("click", function (event) {
        if (
            userPopup?.style.display === "block" &&
            event.target !== userPopup &&
            event.target !== logoAdmin
        ) {
            userPopup.style.display = "none";
        }
    });

    // Función general para aplicar todos los filtros
    function aplicarFiltros() {
        const categoria = document.getElementById('categoryFilter')?.value?.toLowerCase() || '';
        const usuario = document.getElementById('userFilter')?.value?.toLowerCase() || '';
        const termino = document.getElementById('searchInput')?.value?.toLowerCase() || '';
        const filas = document.querySelectorAll('.postRow');

        filas.forEach(row => {
            const cat = row.cells[2]?.innerText.toLowerCase();
            const user = row.cells[5]?.innerText.toLowerCase();
            const texto = row.innerText.toLowerCase();

            const coincideCategoria = !categoria || categoria === 'todas las categorías' || cat.includes(categoria);
            const coincideUsuario = !usuario || user.includes(usuario);
            const coincideBusqueda = !termino || texto.includes(termino);

            row.style.display = (coincideCategoria && coincideUsuario && coincideBusqueda) ? '' : 'none';
        });
    }

    document.getElementById('categoryFilter')?.addEventListener('change', aplicarFiltros);
    document.getElementById('userFilter')?.addEventListener('change', aplicarFiltros);
    document.getElementById('searchInput')?.addEventListener('input', aplicarFiltros);

    // Modal de confirmación para eliminar publicación
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    let deleteLink = null;

    function showDeleteModal(link) {
        deleteLink = link;
        deleteModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    confirmDeleteBtn?.addEventListener('click', function () {
        if (deleteLink) {
            closeDeleteModal();
            setTimeout(function () {
                window.location.href = deleteLink.href;
            }, 200);
        }
    });

    cancelDeleteBtn?.addEventListener('click', closeDeleteModal);

    document.querySelectorAll('.btn.eliminar').forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            event.preventDefault();
            showDeleteModal(this);
        });
    });
});
