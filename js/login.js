document.getElementById("btn__Iniciar-Sesión").addEventListener("click", iniciarSecion);
document.getElementById("btn__registrarse").addEventListener("click", register);
window.addEventListener("resize", anchoPagina);

// Declaración de variables
var contenedor__Login_register = document.querySelector(".contenedor__Login-register");
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var caja__tracera_login = document.querySelector(".caja__tracera-login");
var caja__tracera_register = document.querySelector(".caja__tracera-register");
document.getElementById('foto-perfil-input').addEventListener('change', function(e) {
    const fileInput = this;
    const fileNameSpan = document.getElementById('file-name');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const customInput = fileInput.parentElement;

    if (fileInput.files && fileInput.files[0]) {
        // Mostrar nombre del archivo
        fileNameSpan.textContent = fileInput.files[0].name;
        
        // Mostrar vista previa de la imagen
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(fileInput.files[0]);
        
        // Cambiar estilo para indicar que hay archivo seleccionado
        customInput.classList.add('has-file');
    } else {
        // Restablecer si no hay archivo
        fileNameSpan.textContent = 'Selecciona foto de perfil';
        previewContainer.style.display = 'none';
        customInput.classList.remove('has-file');
    }
});
function anchoPagina() {
    if (window.innerWidth > 850) {
        caja__tracera_login.style.display = "block";
        caja__tracera_register.style.display = "block";
        formulario_login.style.display = "block";  // Aseguramos que el login se muestre correctamente
        formulario_register.style.display = "none"; // Aseguramos que el registro se oculte
    } else {
        caja__tracera_register.style.display = "block";
        caja__tracera_register.style.opacity = "1";
        caja__tracera_login.style.display = "none";
        formulario_login.style.display = "block"; // Aseguramos que se muestre el formulario de login
        formulario_register.style.display = "none"; // Ocultamos el formulario de registro
        contenedor__Login_register.style.left = "0px";
    }
}

anchoPagina();

function iniciarSecion() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "none";
        contenedor__Login_register.style.left = "10px";
        formulario_login.style.display = "block";  // Usamos display para mostrar el formulario
        caja__tracera_register.style.opacity = "1";
        caja__tracera_login.style.opacity = "0";
    } else {
        formulario_register.style.display = "none";
        contenedor__Login_register.style.left = "0px";
        formulario_login.style.display = "block";  // Aseguramos que se muestre el formulario
        caja__tracera_register.style.display = "block";
        caja__tracera_login.style.display = "none";
    }
}

function register() {
    if (window.innerWidth > 850) {
        formulario_register.style.display = "block";
        contenedor__Login_register.style.left = "410px";
        formulario_login.style.display = "none";  // Ocultamos el formulario de login
        caja__tracera_register.style.opacity = "0";
        caja__tracera_login.style.opacity = "1";
    } else {
        formulario_register.style.display = "block";
        contenedor__Login_register.style.left = "0px";
        formulario_login.style.display = "none";  // Ocultamos el formulario de login
        caja__tracera_register.style.display = "none";
        caja__tracera_login.style.display = "block";
        caja__tracera_login.style.opacity = "1";
    }
}
