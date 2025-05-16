document.addEventListener("DOMContentLoaded", () => {
    try {
        const navItem = document.querySelector(".nav__items"); // Selecting the nav items
        const openNavBtn = document.querySelector("#open__nav-btn"); // Selecting the open nav button
        const closeNavBtn = document.querySelector("#close__nav-btn"); // Selecting the close nav button

        const openNav = () => {
            navItem.style.display = "flex";
            openNavBtn.style.display = "none";
            closeNavBtn.style.display = "inline-block";
        };

        const closeNav = () => {
            navItem.style.display = "none";
            openNavBtn.style.display = "inline-block";
            closeNavBtn.style.display = "none";
        };

        openNavBtn.addEventListener("click", openNav);
        closeNavBtn.addEventListener("click", closeNav);

        const handleResize = () => {
            if (window.innerWidth > 1024) {
                navItem.style.display = "flex";
                openNavBtn.style.display = "none";
                closeNavBtn.style.display = "none";
            } else {
                navItem.style.display = "none";
                openNavBtn.style.display = "inline-block";
                closeNavBtn.style.display = "none";
            }
        };

        window.addEventListener("resize", handleResize);
        handleResize(); // Call once to set initial state
    } catch (error) {
        console.error("Error occurred in toggle functionality:", error);
    }

    if (window.innerWidth <= 1024) {
        try {
            const sidebar = document.querySelector("aside");
            const showSidebarBtn = document.querySelector("#show__sidebar-btn");
            const hideSidebarBtn = document.querySelector("#hide__sidebar-btn");

            if (showSidebarBtn && hideSidebarBtn) {
                const showSidebar = () => {
                    sidebar.style.left = "0";
                    showSidebarBtn.style.display = "none";
                    hideSidebarBtn.style.display = "inline-block";
                };

                const hideSidebar = () => {
                    sidebar.style.left = "-100%";
                    showSidebarBtn.style.display = "inline-block";
                    hideSidebarBtn.style.display = "none";
                };

                showSidebarBtn.addEventListener("click", showSidebar);
                hideSidebarBtn.addEventListener("click", hideSidebar);

                const handleSidebarResize = () => {
                    if (window.innerWidth > 1024) {
                        sidebar.style.left = "0";
                        showSidebarBtn.style.display = "none";
                        hideSidebarBtn.style.display = "none";
                    } else {
                        sidebar.style.left = "-100%";
                        showSidebarBtn.style.display = "inline-block";
                        hideSidebarBtn.style.display = "none";
                    }
                };

                window.addEventListener("resize", handleSidebarResize);
                handleSidebarResize(); // Call once to set initial state
            } else {
                console.error("Sidebar buttons not found in the DOM.");
            }
        } catch (error) {
            console.error("Error occurred in sidebar functionality:", error);
        }
    }
    
});

document.addEventListener('DOMContentLoaded', function () {
  const botonesEliminar = document.querySelectorAll('.eliminarComentario');

  botonesEliminar.forEach(boton => {
    boton.addEventListener('click', function () {
      const commentId = this.getAttribute('data-id');

      fetch('eliminar_comentario.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'comment_id=' + encodeURIComponent(commentId)
      })
      .then(response => response.text())
      .then(data => {
        if (data.trim() === 'ok') {
            const comentario = document.getElementById('comentario-' + commentId);
            if (comentario) {
            comentario.remove();

            // Actualizar contador
            const contador = document.getElementById('contador-comentarios');
            if (contador) {
                // Obtener el número actual del contador (quitando paréntesis)
                let numeroActual = parseInt(contador.textContent.replace(/[()]/g, ''));
                numeroActual = isNaN(numeroActual) ? 0 : numeroActual;
                // Reducir en 1 y actualizar el texto con formato (n)
                contador.textContent = `(${numeroActual - 1})`;
            }
            }
        } else {
            console.error('Error al eliminar comentario:', data);
        }
        })
      .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
      });
    });
  });

  
});

