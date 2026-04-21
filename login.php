<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $clave   = $conn->real_escape_string($_POST['clave']); 

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre_completo'];
        $_SESSION['rol'] = $row['rol'];
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Acceso denegado. Verifique sus credenciales.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica San Miguel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
    <div class="container login-screen">
        <div class="card login-container" style="padding: 3rem;">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <div class="logo" style="font-size: 1.5rem; margin-bottom: 0.5rem;">Clínica San Miguel</div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Ingrese al sistema hospitalario</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" style="font-size: 0.85rem; text-align: center;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Nombre de usuario">
                </div>
                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input type="password" id="clave" name="clave" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Iniciar Sesión</button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); font-size: 0.8rem; color: var(--text-muted); text-align: center;">
                <p>UGB San Miguel - Parcial de Programación IV</p>
            </div>
        </div>
    </div>
</body>
</html>
