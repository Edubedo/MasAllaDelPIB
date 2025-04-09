<?php
session_start();
include('../../config/database.php');

$id = $_GET['id']; // Obtener el ID de la publicación

// Obtener los datos de la publicación actual
$sql = $conexion->query("SELECT * FROM posts WHERE Id_posts = $id");
$datos = $sql->fetch_object();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];

    // Verificar y actualizar el título
    if (!empty($_POST['titulo_posts'])) {
        $new_title = $conexion->real_escape_string($_POST['titulo_posts']);
        $updates[] = "title = '$new_title'";
    }

    // Verificar y actualizar el contenido
    if (!empty($_POST['contenido_posts'])) {
        $new_content = $conexion->real_escape_string($_POST['contenido_posts']);
        $updates[] = "content = '$new_content'";
    }

    // Verificar y actualizar las referencias (cambiado para manejar array)
    if (!empty($_POST['referencias_post'])) {
        $referenciasArray = $_POST['referencias_post'];
        $referenciasFiltradas = array_filter($referenciasArray, function($ref) {
            return trim($ref) !== '';
        });
        $new_references = implode("\n", $referenciasFiltradas);
        $updates[] = "referencia_posts = '" . $conexion->real_escape_string($new_references) . "'";
    }

    // Verificar y actualizar la categoría
    if (!empty($_POST['categoria_posts'])) {
        $new_category = $conexion->real_escape_string($_POST['categoria_posts']);
        $updates[] = "category = '$new_category'";
    }

    // Verificar y actualizar la fecha de publicación
    if (!empty($_POST['fecha_publicacion_posts'])) {
        $new_date = $conexion->real_escape_string($_POST['fecha_publicacion_posts']);
        $updates[] = "post_date = '$new_date'";
    }

    // Verificar y actualizar la imagen si se ha subido una nueva
    if (!empty($_FILES['imagen_posts']['name'])) {
        $imagen_nombre = $_FILES['imagen_posts']['name'];
        $imagen_temp = $_FILES['imagen_posts']['tmp_name'];
        $ruta_destino = "../../uploads/" . $imagen_nombre; // Ruta donde se guardará la imagen

        // Mover la imagen al servidor
        if (move_uploaded_file($imagen_temp, $ruta_destino)) {
            $updates[] = "image = '$ruta_destino'";
        }
    }

    // Si hay cambios, realizar la actualización en la base de datos
    if (count($updates) > 0) {
        $sql_update = "UPDATE posts SET " . implode(", ", $updates) . " WHERE Id_posts = $id";
        if ($conexion->query($sql_update)) {
            // Establecer un mensaje de éxito
            $_SESSION['success_message'] = "Publicación actualizada con éxito";
            header("Location: posts-modificar.php?id=" . $id);
            exit();
        } else {
            echo "Error al actualizar la publicación.";
        }
    } else {
        echo "No se realizaron cambios.";
    }
}
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar publicacion</title>
        <link rel="stylesheet" href="css/crear.css">
    </head>
    <body> 
        <div class="container">
            <div class="encabezado">
                <h1>Modificar publicación</h1>
            </div>

            <!-- Mostrar el mensaje de éxito si está disponible -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Eliminar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

            <form id="modificarForm" action="" name="modificar_post" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="usuario_posts" value="<?= htmlspecialchars($_SESSION['username']) ?>">

                <div class="contenedor-general">
                    <div class="izquierdo">
                        <h2>Configuración</h2>

                        <div class="categoria_div">
                            <label for="categoria">Categoría:</label>
                            <select name="categoria_posts" id="categoria" required>
                                <option value="" disabled hidden>Categorías</option>
                                <option value="crecimiento-economico" <?= $datos->category == 'crecimiento-economico' ? 'selected' : '' ?>>Crecimiento Económico</option>
                                <option value="emprendimiento-negocios" <?= $datos->category == 'emprendimiento-negocios' ? 'selected' : '' ?>>Emprendimiento Negocios</option>
                                <option value="mundo-laboral" <?= $datos->category == 'mundo-laboral' ? 'selected' : '' ?>>Mundo Laboral</option>
                            </select>
                        </div>

                        <div class="fecha_div">
                            <label for="fecha_publicacion">Fecha de Publicación:</label>
                            <input class="fecha" type="date" id="fecha_publicacion" name="fecha_publicacion_posts" value="<?= $datos->post_date ?>" required>
                        </div>

                        <div class="autor_div">
                            <?php if(isset($_SESSION['username'])): ?>
                                <label for="usuario">Usuario:</label>
                                <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="boton-div">
                            <a href="posts-consulta.php" class="btn-editar-publicacion">Regresar</a>
                            <button type="submit" name="modificar_post">Modificar Publicación</button>
                        </div>
                    </div>

                    <div class="derecho">
                        <div class="titulodelposts">
                            <label for="titulo">Título del post:</label>
                            <input type="text" id="titulo" name="titulo_posts" value="<?= htmlspecialchars($datos->title) ?>" required>
                        </div>
                        <div class="contenidodelposts">
                            <label for="contenido">Contenido:</label>
                            <textarea id="contenido" name="contenido_posts" rows="6" required><?= htmlspecialchars($datos->content) ?></textarea>
                        </div>
                        <div class="imagendelpost">
                            <label for="imagen">Imagen:</label>
                            <input type="file" id="imagen" name="imagen_posts" accept="image/*">
                            <label for="imagen_actual">Imagen actual:</label>
                            <img src="<?= htmlspecialchars($datos->image) ?>" alt="Imagen actual" width="150">
                        </div>
                        <div class="referenciadelpost">
                            <label for="referencias">Referencias:</label>
                            <div id="contenedorReferencias">
                                <!-- Los inputs se agregarán dinámicamente aquí -->
                            </div>
                            <button class="boton-agregar-referencia" type="button" onclick="agregarReferencia()">Agregar otra referencia</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="modal" class="fondo-alerta" style="display: none;">
            <div class="alerta">
                <p id="alert-message"></p>
                <button class="boton-alerta" onclick="cerrarAlerta()">Aceptar</button>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div id="modal-modificar" class="fondo-alerta-modificar" style="display: none;">
            <div class="alerta-modificar">
                <p id="alert-message-modificar"></p>
                <button class="boton-alerta-modificar" onclick="aceptarEnvio()">Aceptar</button>
                <button class="boton-alerta-cancelar" onclick="cerrarAlerta()">Cancelar</button>
            </div>
        </div>
        
        
        <script>
            function mostrarAlerta(mensaje) {
                document.getElementById("alert-message").textContent = mensaje;
                document.getElementById("modal").style.display = "flex";
            }

            function mostrarAlertaModificar(mensaje) {
                document.getElementById("alert-message-modificar").textContent = mensaje;
                document.getElementById("modal-modificar").style.display = "flex";
            }

            // Función para enviar el formulario cuando el usuario acepta
            function aceptarEnvio() {
                // Crear un formulario clonado para enviar
                const form = document.getElementById("modificarForm");
                const formData = new FormData(form);
                
                // Enviar el formulario manualmente
                fetch(form.action, {
                    method: form.method,
                    body: formData
                }).then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    }
                });
            }

            // Cerrar el modal de alerta
            function cerrarAlerta() {
                document.getElementById("modal").style.display = "none";
                document.getElementById("modal-modificar").style.display = "none";
            }

            // Función para agregar un nuevo campo de referencia
            function agregarReferencia(referencia = '') {
                const contenedor = document.getElementById("contenedorReferencias");
                const divWrapper = document.createElement("div");
                divWrapper.className = "referencia-input-container";
                
                const nuevoInput = document.createElement("input");
                nuevoInput.type = "text";
                nuevoInput.name = "referencias_post[]";
                nuevoInput.placeholder = "Escribe una referencia";
                nuevoInput.value = referencia;
                nuevoInput.required = true;
                nuevoInput.className = "input-referencia";
                
                const btnEliminar = document.createElement("button");
                btnEliminar.type = "button";
                btnEliminar.className = "btn-eliminar-referencia";
                btnEliminar.innerHTML = "×";
                btnEliminar.onclick = function() {
                    contenedor.removeChild(divWrapper);
                };
                
                divWrapper.appendChild(nuevoInput);
                divWrapper.appendChild(btnEliminar);
                contenedor.appendChild(divWrapper);
            }

            // Al cargar la página, procesamos las referencias existentes
            document.addEventListener('DOMContentLoaded', function() {
                // Obtenemos las referencias del campo (convertido a array)
                const referenciasExistentes = `<?= $datos->referencia_posts ?>`.split('\n');
                
                // Agregamos un input por cada referencia existente
                referenciasExistentes.forEach(ref => {
                    if (ref.trim() !== '') {
                        agregarReferencia(ref.trim());
                    }
                });
                
                // Si no había referencias, agregamos un campo vacío por defecto
                if (referenciasExistentes.length === 0 || (referenciasExistentes.length === 1 && referenciasExistentes[0].trim() === '')) {
                    agregarReferencia();
                }
            });

            // Validación antes de enviar el formulario
            document.getElementById("modificarForm").addEventListener("submit", function(e) {
                e.preventDefault();
                
                const titulo = document.getElementById("titulo").value.trim();
                const contenido = document.getElementById("contenido").value.trim();
                const inputsReferencias = document.querySelectorAll('input[name="referencias_post[]"]');
                
                let mensajeError = '';
                let referenciasValidas = false;
                
                // Validaciones
                if (titulo.length < 10) {
                    mensajeError = "El título debe tener al menos 10 caracteres.";
                } else if (contenido.length < 20) {
                    mensajeError = "El contenido debe tener al menos 20 caracteres.";
                } else {
                    // Validar referencias
                    inputsReferencias.forEach(input => {
                        if (input.value.trim().length >= 10) {
                            referenciasValidas = true;
                        }
                    });
                    
                    if (!referenciasValidas) {
                        mensajeError = "Debe haber al menos una referencia válida (mínimo 10 caracteres).";
                    }
                }
                
                // Mostrar error o confirmación
                if (mensajeError) {
                    mostrarAlerta(mensajeError);
                } else {
                    mostrarAlertaModificar("¿Estás seguro de que deseas modificar la publicación?");
                }
            });
        </script>
    </body>
</html>