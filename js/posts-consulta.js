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

