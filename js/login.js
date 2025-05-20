let modoActual = "login"; // Puede ser "login" o "registro"

document.getElementById("btn__Iniciar-Sesión").addEventListener("click", () => {
    modoActual = "login";
    iniciarSecion();
});
document.getElementById("btn__registrarse").addEventListener("click", () => {
    modoActual = "registro";
    register();
});
window.addEventListener("resize", ajustarFormularioSegunAncho);

const contenedor__Login_register = document.querySelector(".contenedor__Login-register");
const formulario_login = document.querySelector(".formulario__login");
const formulario_register = document.querySelector(".formulario__register");
const caja__tracera_login = document.querySelector(".caja__tracera-login");
const caja__tracera_register = document.querySelector(".caja__tracera-register");

// Vista previa de imagen de perfil
document.getElementById('foto-perfil-input').addEventListener('change', function () {
    const fileInput = this;
    const fileNameSpan = document.getElementById('file-name');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    if (fileInput.files && fileInput.files[0]) {
        fileNameSpan.textContent = fileInput.files[0].name;
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        fileNameSpan.textContent = 'Selecciona foto de perfil';
        previewContainer.style.display = 'none';
    }
});

function ajustarFormularioSegunAncho() {
    if (window.innerWidth > 850) {
        caja__tracera_login.style.display = "block";
        caja__tracera_register.style.display = "block";
        if (modoActual === "login") {
            iniciarSecion();
        } else {
            register();
        }
    } else {
        caja__tracera_register.style.display = "block";
        caja__tracera_register.style.opacity = "1";
        caja__tracera_login.style.display = "none";
        contenedor__Login_register.style.left = "0px";
        if (modoActual === "login") {
            formulario_login.style.display = "block";
            formulario_register.style.display = "none";
        } else {
            formulario_login.style.display = "none";
            formulario_register.style.display = "block";
        }
    }
}

function iniciarSecion() {
    formulario_register.style.display = "none";
    formulario_login.style.display = "block";

    if (window.innerWidth > 850) {
        contenedor__Login_register.style.left = "10px";
        caja__tracera_register.style.opacity = "1";
        caja__tracera_login.style.opacity = "0";
    } else {
        contenedor__Login_register.style.left = "0px";
        caja__tracera_register.style.display = "block";
        caja__tracera_login.style.display = "none";
    }
}

function register() {
    formulario_login.style.display = "none";
    formulario_register.style.display = "block";

    if (window.innerWidth > 850) {
        contenedor__Login_register.style.left = "410px";
        caja__tracera_register.style.opacity = "0";
        caja__tracera_login.style.opacity = "1";
    } else {
        contenedor__Login_register.style.left = "0px";
        caja__tracera_register.style.display = "none";
        caja__tracera_login.style.display = "block";
        caja__tracera_login.style.opacity = "1";
    }
}

// Validación de fortaleza de contraseña
function validarFortalezaPassword() {
    const password = this.value;
    const validaciones = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    const indicators = {
        length: document.getElementById('length'),
        uppercase: document.getElementById('uppercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
    };

    for (const key in indicators) {
        const element = indicators[key];
        if (element) {
            element.style.color = validaciones[key] ? 'green' : 'red';
            element.innerHTML = (validaciones[key] ? '✓' : '✗') + element.textContent.substring(1);
        }
    }
}

function validarPassword() {
    const password = document.getElementById('passwordRegistrarse').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return false;
    }

    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
    if (!regex.test(password)) {
        alert('La contraseña debe cumplir con los requisitos de seguridad.');
        return false;
    }

    return true;
}

function togglePassword(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);

    toggle.addEventListener('click', () => {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        toggle.classList.toggle('fa-eye');
        toggle.classList.toggle('fa-eye-slash');
    });
}

// Inicialización segura
document.addEventListener('DOMContentLoaded', () => {
    ajustarFormularioSegunAncho(); // Mostrar formulario correcto al cargar
    const passwordField = document.getElementById('passwordRegistrarse');
    if (passwordField) {
        passwordField.addEventListener('input', validarFortalezaPassword);
        validarFortalezaPassword.call(passwordField);
    }

    togglePassword('passwordLogin', 'toggle-password');
    togglePassword('passwordRegistrarse', 'toggle-passwordRegistrar');
});
