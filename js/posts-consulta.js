document.getElementById("botonContinuar").addEventListener("click", function() {
    document.getElementById("modal").style.display = "none"; // Oculta el modal
    document.getElementById("overlay").style.display = "none"; // Quita la capa oscura
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