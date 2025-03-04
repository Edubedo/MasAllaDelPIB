<?php
// Recoger los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar que los campos no estén vacíos
if (empty($email) || empty($password)) {
    die("Todos los campos son obligatorios.");
}

// Configuración de Supabase
$project_url = 'https://zjoundxfzxtwlweyrjnk.supabase.co';
$api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inpqb3VuZHhmenh0d2x3ZXlyam5rIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDA1OTgyNzcsImV4cCI6MjA1NjE3NDI3N30.bD6kaMLSoSxGsCYojc5i0aIm3JcTBX4kjT152r4Z6aI';

// Endpoint para buscar el usuario por email
$endpoint = $project_url . '/rest/v1/users?select=id_usuario,email,password&email=eq.' . urlencode($email);

// Configurar la solicitud HTTP
$options = [
    'http' => [
        'method' => 'GET',
        'header' => [
            'apikey: ' . $api_key,
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ],
    ],
];

$context = stream_context_create($options);

// Hacer la solicitud
$response = file_get_contents($endpoint, false, $context);

if ($response === FALSE) {
    die("Error al hacer la solicitud a la API de Supabase.");
}

// Decodificar la respuesta JSON
$data = json_decode($response, true);

// Verificar si el usuario existe
if (empty($data)) {
    die("El usuario no existe.");
}

// Obtener el primer usuario (debería ser único si el email es único)
$user = $data[0];

// Verificar la contraseña
if (password_verify($password, $user['password'])) {
    echo "Inicio de sesión exitoso. Bienvenido, " . $user['email'];
    // Aquí puedes redirigir al usuario o iniciar una sesión
} else {
    die("Contraseña incorrecta.");
}
?>