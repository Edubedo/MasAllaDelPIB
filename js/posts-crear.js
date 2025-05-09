// Obtener la fecha actual en formato YYYY-MM-DD y establecerla 
const hoy = new Date().toISOString().split('T')[0];
document.getElementById('fecha_publicacion').value = hoy;

// Asignar la fecha actual como valor mínimo
document.getElementById('fecha_publicacion').setAttribute('min', today);

// Mostrar el modal de alerta cuando no se cumple la validación
function mostrarAlerta(mensaje) {
    const fondo = document.createElement("div");
    fondo.classList.add("fondo-alerta");

    const alerta = document.createElement("div");
    alerta.classList.add("alerta");

    const texto = document.createElement("p");
    texto.textContent = mensaje;

    const boton = document.createElement("button");
    boton.textContent = "Aceptar";
    boton.classList.add("boton-alerta");

    boton.onclick = function () {
        document.body.removeChild(fondo);
    };

    alerta.appendChild(texto);
    alerta.appendChild(boton);
    fondo.appendChild(alerta);
    document.body.appendChild(fondo);
}

// Validaciones antes de enviar el formulario
document.getElementById("crearForm").addEventListener("submit", function (e) {
    const titulo = document.getElementById("titulo").value.trim();
    const contenido = document.getElementById("contenido").value.trim();
    const inputsReferencias = document.querySelectorAll('input[name="referencias_post[]"]');

    // Validar título
    if (titulo.length < 10) {
        e.preventDefault(); // Evita que se envíe el formulario
        mostrarAlerta("El título debe tener al menos 10 caracteres.");
        return;
    }

    // Validar contenido
    if (contenido.length < 20) {
        e.preventDefault();
        mostrarAlerta("El contenido debe tener al menos 20 caracteres.");
        return;
    }

    // Validar referencias
    let referenciasValidas = false; // Al menos una referencia válida
    let todasReferenciasValidas = true; // Todas las referencias deben ser válidas

    inputsReferencias.forEach(input => {
        const valor = input.value.trim();
        if (valor.length >= 10) {
            referenciasValidas = true; // Al menos una referencia válida
        } else if (valor.length > 0) {
            todasReferenciasValidas = false; // Marcar como inválida si alguna es corta
        } else {
            todasReferenciasValidas = false; // Marcar como inválida si alguna está vacía
        }
    });

    if (!referenciasValidas || !todasReferenciasValidas) {
        e.preventDefault();
        if (inputsReferencias.length === 0) {
            mostrarAlerta("Debes agregar al menos una referencia.");
        } else {
            mostrarAlerta("Todas las referencias deben tener al menos 10 caracteres.");
        }
        return;
    }
});

function agregarReferencia() {
    const contenedor = document.getElementById("contenedorReferencias");

    // Crear un contenedor para la referencia y el botón
    const divWrapper = document.createElement("div");
    divWrapper.className = "referencia-input-container";

    // Crear contenedor flexible para input y botón
    const inputGroup = document.createElement("div");
    inputGroup.className = "referencia-input-group";

    // Crear el input para la referencia
    const nuevoInput = document.createElement("input");
    nuevoInput.type = "text";
    nuevoInput.name = "referencias_post[]";
    nuevoInput.placeholder = "Escribe una referencia";
    nuevoInput.required = true;
    nuevoInput.className = "input-referencia";

    // Crear el botón para eliminar la referencia
    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.className = "btn-eliminar-referencia";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.onclick = function () {
        contenedor.removeChild(divWrapper);
    };

    // Agregar el input y el botón al contenedor flexible
    inputGroup.appendChild(nuevoInput);
    inputGroup.appendChild(btnEliminar);

    // Agregar el grupo al contenedor principal
    divWrapper.appendChild(inputGroup);
    contenedor.appendChild(divWrapper);
}


document.getElementById('imagen').addEventListener('change', function (event) {
    const input = event.target;
    const preview = document.getElementById('preview-imagen');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
