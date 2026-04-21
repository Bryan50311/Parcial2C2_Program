<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
$nombre_usuario = $_SESSION['nombre'];

// Obtener todas las citas
$sql = "SELECT c.*, e.nombre as especialidad 
        FROM citas c 
        JOIN especialidades e ON c.id_especialidad = e.id 
        ORDER BY FIELD(c.urgencia, 'Emergencia', 'Alta', 'Normal'), c.fecha ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Médico - Clínica San Miguel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="container" style="margin:0 auto;">
                <div class="logo">Clínica San Miguel</div>
                <div class="nav-links">
                    <a href="index.php">Dashboard</a>
                    <?php if ($rol == 'admin'): ?>
                        <a href="gestion.php">Nueva Cita</a>
                    <?php endif; ?>
                    <a href="logout.php" style="color: var(--danger);">Salir</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="welcome-bar">
            <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Bienvenido, <?php echo $nombre_usuario; ?></h1>
            <p style="opacity: 0.9;">Gestión profesional de servicios médicos - San Miguel, El Salvador.</p>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Control de Pacientes</h2>
                <?php if ($rol == 'admin'): ?>
                    <a href="gestion.php" class="btn btn-primary">Agendar Nueva Cita</a>
                <?php endif; ?>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Especialidad</th>
                            <th>Urgencia</th>
                            <th>Fecha y Hora</th>
                            <th>Observaciones</th>
                            <?php if ($rol == 'admin'): ?>
                                <th style="text-align: right;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;"><?php echo $row['paciente']; ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo $row['telefono']; ?></div>
                                    </td>
                                    <td><?php echo $row['especialidad']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['urgencia']); ?>">
                                            <?php echo $row['urgencia']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo date('h:i A', strtotime($row['hora'])); ?></div>
                                    </td>
                                    <td style="max-width: 200px;">
                                        <div style="font-size: 0.85rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?php echo $row['observaciones'] ? $row['observaciones'] : "—"; ?>
                                        </div>
                                    </td>
                                    <?php if ($rol == 'admin'): ?>
                                        <td style="text-align: right;">
                                            <a href="gestion.php?edit=<?php echo $row['id']; ?>" class="btn btn-ghost" style="padding: 0.5rem;">Editar</a>
                                            <a href="gestion.php?delete=<?php echo $row['id']; ?>" class="btn btn-ghost" style="padding: 0.5rem; color: var(--danger);" onclick="return confirm('¿Eliminar cita?')">Borrar</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 4rem; color: var(--text-muted);">No hay citas registradas para hoy.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer style="text-align: center; color: var(--text-muted); padding: 3rem 0; font-size: 0.8rem;">
        &copy; 2026 Clínica Médica San Miguel | Sistema de Gestión Hospitalaria
    </footer>
</body>
</html>
