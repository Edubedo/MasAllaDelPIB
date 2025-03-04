<?php
//Conexion de la api 
$project_url = 'https://zjoundxfzxtwlweyrjnk.supabase.co';
$api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inpqb3VuZHhmenh0d2x3ZXlyam5rIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDA1OTgyNzcsImV4cCI6MjA1NjE3NDI3N30.bD6kaMLSoSxGsCYojc5i0aIm3JcTBX4kjT152r4Z6aI';
//Datos que tomaremos de la bd
$endpoint = $project_url . '/rest/v1/users?select=id_usuario,username,email';
"\n";
//Configuracion de la solicitud 
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
//Manda la solicitud de la validacion
$response = file_get_contents($endpoint, false, $context);

//Verifica la conexion en la solicitud de la api
if ($response === FALSE) {
    die("Error al hacer la solicitud a la API de Supabase.");
}

echo "Respuesta de la API: " . $response;

/*
Decodificar la respuesta JSON
$data = json_decode($response, true);

if (empty($data)) {
    echo "No hay datos en la tabla.";
} else {
    echo "Datos de la tabla 'users':\n";
    foreach ($data as $row) {
        echo "ID: " . $row['id_usuario'] . "\n";
        echo "Username: " . $row['username'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "----------------------------\n";
    }
}*/
?>|


