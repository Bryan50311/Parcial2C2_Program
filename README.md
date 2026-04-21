# Parcial 2: Programación Computacional IV - Sistema de Gestión de Citas

Este proyecto es una aplicación web para la **Clínica Médica San Miguel**, ubicada en la zona oriental de El Salvador. Permite la administración eficiente de citas médicas mediante una interfaz segura y responsiva.

## Preguntas y Justificaciones Técnicas

### 1. Conexión a Base de Datos
**Manejo:** Se utiliza la extensión `mysqli` de PHP para establecer una conexión persistente con el servidor MariaDB/MySQL. La configuración reside en `db.php`.

**¿Qué pasa si los datos son incorrectos?**
Si el host, usuario, clave o nombre de la base de datos son erróneos, el sistema detecta el error mediante `$conn->connect_error` y detiene la ejecución inmediatamente usando `die()`, mostrando un mensaje amigable al administrador pero protegiendo la integridad del servidor. Esto evita que el script intente realizar consultas sobre una conexión nula, lo cual generaría errores fatales y posibles fugas de información de la ruta del servidor.

### 2. Diferencias entre GET y POST
| Característica | Método GET | Método POST |
| :--- | :--- | :--- |
| **Visibilidad** | Datos visibles en la URL. | Datos ocultos en el cuerpo de la petición. |
| **Capacidad** | Limitada (aprox. 2000 caracteres). | Casi ilimitada. |
| **Uso Ideal** | Obtención o filtrado de datos. | Envío de datos sensibles o creación/edición. |

**Ejemplos en este proyecto:**
- **GET:** Se utiliza en `index.php` para navegar o en `gestion.php?edit=5` para identificar qué cita queremos cargar en el formulario. Es ideal porque permite refrescar la página manteniendo el contexto de edición.
- **POST:** Se utiliza en el Login (`login.php`) para enviar la clave y usuario, y en el formulario de citas (`gestion.php`) para guardar la información. Esto evita que los datos personales del paciente o las credenciales queden grabados en el historial del navegador.

### 3. Seguridad y Riesgos en la Zona Oriental
Al manejar datos de salud en una zona tan importante como San Miguel, los riesgos digitales son críticos:
- **Inyección SQL:** Un atacante podría intentar borrar la tabla de citas ingresando código SQL en el campo del nombre del paciente. 
  - **Mitigación:** Usamos `$conn->real_escape_string()` para limpiar todas las entradas del usuario antes de que toquen la base de datos.
- **XSS (Cross-Site Scripting):** Podrían insertar scripts maliciosos en las observaciones de la cita para robar sesiones de otros administradores.
  - **Mitigación:** Se utiliza sanitización de salidas y validación de tipos de datos para asegurar que lo que se imprime en pantalla sea texto plano y no código ejecutable.

---

## Diccionario de Datos

| Tabla | Columna | Tipo de Dato | Límite | ¿Es Nulo? | Descripción |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `especialidades` | `id` | INT (AI, PK) | - | No | Identificador único de la rama médica. |
| `especialidades` | `nombre` | VARCHAR | 100 | No | Nombre de la especialidad (ej. Pediatría). |
| `especialidades` | `costo` | DECIMAL | 10,2 | No | Precio base de la consulta médica. |
| `citas` | `id` | INT (AI, PK) | - | No | Identificador único de la cita. |
| `citas` | `paciente` | VARCHAR | 150 | No | Nombre completo del paciente atendido. |
| `citas` | `id_especialidad` | INT (FK) | - | No | Relación con la tabla especialidades. |
| `citas` | `observaciones` | TEXT | - | **Sí** | Detalles adicionales o síntomas del paciente. |
| `usuarios` | `rol` | ENUM | - | No | Define si es 'admin' o 'visitante'. |

---

## Créditos
**Estudiante:** Kevin (Parcial 2)  
**Institución:** Universidad Gerardo Barrios (UGB)  
**Zona:** San Miguel, El Salvador.
