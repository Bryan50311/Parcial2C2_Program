<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$mensaje = "";
$edit_data = null;

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM citas WHERE id = $id");
    header("Location: index.php");
    exit();
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM citas WHERE id = $id");
    $edit_data = $res->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente        = $conn->real_escape_string($_POST['paciente']);
    $telefono        = $conn->real_escape_string($_POST['telefono']);
    $especialidad    = intval($_POST['id_especialidad']);
    $fecha           = $_POST['fecha'];
    $hora            = $_POST['hora'];
    $urgencia        = $_POST['urgencia']; 
    $observaciones   = !empty($_POST['observaciones']) ? "'" . $conn->real_escape_string($_POST['observaciones']) . "'" : "NULL";

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $sql = "UPDATE citas SET 
                paciente='$paciente', telefono='$telefono', id_especialidad=$especialidad, 
                fecha='$fecha', hora='$hora', urgencia='$urgencia', observaciones=$observaciones 
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO citas (paciente, telefono, id_especialidad, fecha, hora, urgencia, observaciones) 
                VALUES ('$paciente', '$telefono', $especialidad, '$fecha', '$hora', '$urgencia', $observaciones)";
    }

    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "Error: " . $conn->error;
    }
}

$especialidades_res = $conn->query("SELECT * FROM especialidades ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Cita - Clínica San Miguel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="container" style="margin:0 auto;">
                <div class="logo">Clínica San Miguel</div>
                <div class="nav-links">
                    <a href="index.php">Cancelar y Volver</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <h2><?php echo $edit_data ? 'Actualizar Cita Médica' : 'Registrar Nueva Cita'; ?></h2>
            
            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="POST" action="gestion.php">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="paciente">Nombre Completo del Paciente</label>
                        <input type="text" id="paciente" name="paciente" required value="<?php echo $edit_data ? $edit_data['paciente'] : ''; ?>" placeholder="Ej. Kevin Antonio">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Número de Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required value="<?php echo $edit_data ? $edit_data['telefono'] : ''; ?>" placeholder="7788-9900">
                    </div>

                    <div class="form-group">
                        <label for="id_especialidad">Especialidad Médica</label>
                        <select id="id_especialidad" name="id_especialidad" required>
                            <option value="">Seleccione especialidad...</option>
                            <?php while($esp = $especialidades_res->fetch_assoc()): ?>
                                <option value="<?php echo $esp['id']; ?>" <?php echo ($edit_data && $edit_data['id_especialidad'] == $esp['id']) ? 'selected' : ''; ?>>
                                    <?php echo $esp['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="urgencia">Tipo de Emergencia / Prioridad</label>
                        <select id="urgencia" name="urgencia" required>
                            <option value="Normal" <?php echo ($edit_data && $edit_data['urgencia'] == 'Normal') ? 'selected' : ''; ?>>Normal (Consulta rutina)</option>
                            <option value="Alta" <?php echo ($edit_data && $edit_data['urgencia'] == 'Alta') ? 'selected' : ''; ?>>Alta (Dolor agudo)</option>
                            <option value="Emergencia" <?php echo ($edit_data && $edit_data['urgencia'] == 'Emergencia') ? 'selected' : ''; ?>>Emergencia (Riesgo vital)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha de Cita</label>
                        <input type="date" id="fecha" name="fecha" required value="<?php echo $edit_data ? $edit_data['fecha'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="hora">Hora de Cita</label>
                        <input type="time" id="hora" name="hora" required value="<?php echo $edit_data ? $edit_data['hora'] : ''; ?>">
                    </div>

                    <div class="form-group full">
                        <label for="observaciones">Observaciones Médicas (Opcional)</label>
                        <textarea id="observaciones" name="observaciones" rows="4" placeholder="Síntomas, antecedentes o notas adicionales..."><?php echo $edit_data ? $edit_data['observaciones'] : ''; ?></textarea>
                    </div>
                </div>

                <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_data ? 'Guardar Cambios' : 'Agendar Cita Ahora'; ?>
                    </button>
                    <a href="index.php" class="btn btn-ghost">Descartar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
