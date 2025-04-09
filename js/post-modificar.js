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

function agregarReferencia(referencia = '') {
    const contenedor = document.getElementById("contenedorReferencias");
    const divWrapper = document.createElement("div");
    divWrapper.className = "referencia-input-container";
    
    // Crear contenedor flexible para input y botón
    const inputGroup = document.createElement("div");
    inputGroup.className = "referencia-input-group";
    
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
    btnEliminar.textContent = "Eliminar";
    btnEliminar.onclick = function() {
        contenedor.removeChild(divWrapper);
    };
    
    // Agregar elementos al grupo
    inputGroup.appendChild(nuevoInput);
    inputGroup.appendChild(btnEliminar);
    
    // Agregar grupo al wrapper
    divWrapper.appendChild(inputGroup);
    contenedor.appendChild(divWrapper);
}


// Al cargar la página, procesamos las referencias existentes
document.addEventListener('DOMContentLoaded', function() {
    const contenedor = document.getElementById("contenedorReferencias");
    const referenciasString = contenedor.dataset.referencias;
    const referenciasExistentes = referenciasString ? referenciasString.split('\n') : [];
    
    // Resto del código igual...
    referenciasExistentes.forEach(ref => {
        if (ref.trim() !== '') {
            agregarReferencia(ref.trim());
        }
    });
    
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
    let hayReferenciasVacios = false;
    let hayReferenciasCortas = false;
    let todasReferenciasValidas = true; // Nueva variable para verificar todas las referencias
    
    // Validaciones básicas
    if (titulo.length < 10) {
        mensajeError = "El título debe tener al menos 10 caracteres.";
    } else if (contenido.length < 20) {
        mensajeError = "El contenido debe tener al menos 20 caracteres.";
    } else {
        // Validación detallada de referencias
        inputsReferencias.forEach(input => {
            const valor = input.value.trim();
            if (valor.length >= 10) {
                referenciasValidas = true; // Al menos una referencia válida
            } else if (valor.length > 0) {
                hayReferenciasCortas = true;
                todasReferenciasValidas = false; // Marcar como inválida si alguna es corta
            } else {
                hayReferenciasVacios = true;
                todasReferenciasValidas = false; // Marcar como inválida si alguna está vacía
            }
        });
        
        // Mensajes específicos para referencias
        if (!referenciasValidas || !todasReferenciasValidas) {
            if (inputsReferencias.length === 0) {
                mensajeError = "Debes agregar al menos una referencia.";
            } else if (hayReferenciasCortas) {
                mensajeError = "Las referencias deben tener al menos 10 caracteres.";
            } 
        }
    }
    
    // Mostrar error o confirmación
    if (mensajeError) {
        mostrarAlerta(mensajeError);
        // Enfocar el primer campo de referencia si hay error
        if (inputsReferencias.length > 0) {
            inputsReferencias[0].focus();
        }
    } else {
        mostrarAlertaModificar("¿Estás seguro de que deseas modificar la publicación?");
    }
});