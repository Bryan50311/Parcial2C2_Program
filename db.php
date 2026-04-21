<?php
// Configuración de la base de datos basada en tu conexión funcional
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "clinica_medica"; // La base que acabamos de crear
$port = "8805"; // Tu puerto configurado en XAMPP

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("<div style='background:#fee2e2; color:#b91c1c; padding:20px; border-radius:8px; font-family:sans-serif; margin:20px;'>
            <strong>Error de Conexión:</strong> No se pudo conectar a la base de datos. <br>
            Error: " . $conn->connect_error . "
         </div>");
}

// Establecer charset a utf8
$conn->set_charset("utf8");
?>