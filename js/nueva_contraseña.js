
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', validarFortalezaPassword);
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', verificarCoincidenciaPassword);
    }
});

function validarFortalezaPassword() {
    const password = this.value;
    const indicators = {
        length: document.getElementById('length'),
        uppercase: document.getElementById('uppercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
    };

    const validaciones = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    for (const [key, element] of Object.entries(indicators)) {
        if (element) {
            element.style.color = validaciones[key] ? 'green' : 'red';
            element.innerHTML = (validaciones[key] ? '✓' : '✗') + 
                             ' ' + element.textContent.split(' ').slice(1).join(' ');
        }
    }
}

function verificarCoincidenciaPassword() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const confirmMsg = document.getElementById('confirm-msg');
    
    if (!confirmMsg) return;
    
    if (password && confirm) {
        confirmMsg.style.color = password === confirm ? 'green' : 'red';
        confirmMsg.textContent = password === confirm ? 
            '✓ Las contraseñas coinciden' : '✗ Las contraseñas no coinciden';
    }
}

function validarPassword() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        alert('Las contraseñas no coinciden');
        return false;
    }
    
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
    if (!regex.test(password)) {
        alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial');
        return false;
    }
    
    return true;
}


// Mostrar y ocultar contraseña del formulario de login
const passwordInputNueva = document.getElementById('password');
const togglePasswordTextNuevaNueva = document.getElementById('toggle-passwordNueva');

togglePasswordTextNuevaNueva.addEventListener('click', function () {
    if (passwordInputNueva.type === 'password') {
        passwordInputNueva.type = 'text';
        togglePasswordTextNueva.textContent = 'Ocultar contraseña';
    } else {
        passwordInputNueva.type = 'password';
        togglePasswordTextNuevaNueva.textContent = 'Mostrar contraseña';
    }
});