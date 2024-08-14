<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario por defecto de XAMPP
$password = ""; // Sin contraseña por defecto
$dbname = "babychat"; // Tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vacuna = $_POST['vaccine-name'];
    $enfermedad_previene = $_POST['disease'];
    $dosis = $_POST['dose'];
    $edad_frecuencia = $_POST['age-frequency'];
    $fecha_aplicacion = $_POST['application-date'];
    $lote = $_POST['batch-number'];
    $id_bebe = 1; // Reemplaza esto con el ID real del bebé si lo tienes disponible

    // Insertar datos en la base de datos
    $sql = "INSERT INTO seguimiento_vacunacion (vacuna, enfermedad_previene, dosis, edad_frecuencia, fecha_aplicacion, lote, id_bebe)
    VALUES ('$vacuna', '$enfermedad_previene', '$dosis', '$edad_frecuencia', '$fecha_aplicacion', '$lote', '$id_bebe')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vacunas</title>
    <style>
        /* Tu código CSS aquí */
    </style>
</head>
<body>

    <div class="table-container">
        <h2>Registro de Vacunas</h2>
        <div class="latest-info">
            <div class="latest-vaccine" id="latest-vaccine">Vacuna: -</div>
            <div class="latest-date" id="latest-date">Fecha: -</div>
        </div>
        <a href="#" id="add-btn" class="add-btn">Agregar <span class="add-btn-icon">✎</span></a>
        
        <table>
            <thead>
                <tr>
                    <th>Nombre de la Vacuna</th>
                    <th>Enfermedad que Previene</th>
                    <th>Dosis</th>
                    <th>Edad y Frecuencia</th>
                    <th>Fecha de Aplicación</th>
                    <th>Número de Lote</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody id="vaccine-table">
                <!-- Aquí se insertarán las filas -->
            </tbody>
        </table>

        <div id="empty-message" class="empty-message">
            Ingresa datos para mejorar la comprensión de BabyChat sobre las dudas acerca de tus bebés.
        </div>

        <button class="delete-all-btn">Eliminar todos los registros</button>
    </div>

    <!-- Fondo semitransparente para el efecto de oscurecimiento -->
    <div id="overlay" class="overlay"></div>

    <!-- Formulario popup -->
    <div id="form-container" class="form-container hidden">
        <button class="close-btn" id="close-btn">X</button>
        <h3>Registro de Vacuna</h3>
        <form id="vaccine-form" method="POST" action="">
            <label for="vaccine-name">Nombre de la Vacuna:</label>
            <input type="text" id="vaccine-name" name="vaccine-name" required>

            <label for="disease">Enfermedad que Previene:</label>
            <input type="text" id="disease" name="disease" required>

            <label for="dose">Dosis:</label>
            <input type="text" id="dose" name="dose" required>

            <label for="age-frequency">Edad y Frecuencia:</label>
            <input type="text" id="age-frequency" name="age-frequency" required>

            <label for="application-date">Fecha de Aplicación:</label>
            <input type="date" id="application-date" name="application-date" required>

            <label for="batch-number">Número de Lote:</label>
            <input type="text" id="batch-number" name="batch-number" required>

            <button type="submit">Guardar</button>
        </form>
    </div>

    <script>
        const addBtn = document.getElementById('add-btn');
        const formContainer = document.getElementById('form-container');
        const closeBtn = document.getElementById('close-btn');
        const vaccineForm = document.getElementById('vaccine-form');
        const vaccineTable = document.getElementById('vaccine-table');
        const latestVaccine = document.getElementById('latest-vaccine');
        const latestDate = document.getElementById('latest-date');
        const emptyMessage = document.getElementById('empty-message');
        const overlay = document.getElementById('overlay');

        addBtn.addEventListener('click', () => {
            formContainer.classList.remove('hidden');
            overlay.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            formContainer.classList.add('hidden');
            overlay.style.display = 'none';
        });

        overlay.addEventListener('click', () => {
            formContainer.classList.add('hidden');
            overlay.style.display = 'none';
        });

        vaccineForm.addEventListener('submit', (e) => {
            // Este bloque maneja la visualización de la tabla después de guardar el registro
            const name = document.getElementById('vaccine-name').value;
            const applicationDate = document.getElementById('application-date').value;

            latestVaccine.textContent = Vacuna: ${name};
            latestDate.textContent = Fecha: ${applicationDate};

            formContainer.classList.add('hidden');
            overlay.style.display = 'none';
            vaccineForm.reset();
            updateTableVisibility();
        });

        function updateTableVisibility() {
            if (vaccineTable.rows.length === 0) {
                emptyMessage.style.display = 'block';
            } else {
                emptyMessage.style.display = 'none';
            }
        }

        updateTableVisibility();
    </script>
    
</body>
</html>