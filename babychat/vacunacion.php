<?php
// Conexión a la base de datos
include 'conexion.php';

$id_bebe = $_GET['id_bebe']; // El ID del bebé seleccionado

// Consulta para obtener las vacunas del bebé seleccionado
$sql = "SELECT * FROM seguimiento_vacunacion WHERE id_bebe = '$id_bebe'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vacunas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .table-container {
            width: 100%;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .table-container h2 {
            margin-top: 0;
            font-size: 24px;
        }

        .latest-info {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .latest-info .latest-vaccine {
            text-align: left;
        }

        .latest-info .latest-date {
            text-align: right;
        }

        .table-container .add-btn {
            color: #007bff;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .table-container .add-btn-icon {
            margin-left: 5px;
            vertical-align: middle;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-container table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
            color: #333;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table-container .action-icons {
            font-size: 18px;
            cursor: pointer;
            color: #007bff;
        }

        .table-container .delete-all-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .table-container .delete-all-btn:hover {
            background-color: #c82333;
        }

        .empty-message {
            font-style: italic;
            color: #888;
            margin-top: 20px;
            display: none;
        }

        .hidden {
            display: none;
        }

        .form-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 320px;
            z-index: 1000;
        }

        .form-container h3 {
            margin-top: 0;
            color: #333;
        }

        .form-container .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #666;
        }

        .form-container .close-btn:hover {
            color: #000;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .form-container input:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Fondo semitransparente para el efecto de oscurecimiento */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
    </style>
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
            e.preventDefault();

            const name = document.getElementById('vaccine-name').value;
            const disease = document.getElementById('disease').value;
            const dose = document.getElementById('dose').value;
            const ageFrequency = document.getElementById('age-frequency').value;
            const applicationDate = document.getElementById('application-date').value;
            const batchNumber = document.getElementById('batch-number').value;

            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>${name}</td>
                <td>${disease}</td>
                <td>${dose}</td>
                <td>${ageFrequency}</td>
                <td>${applicationDate}</td>
                <td>${batchNumber}</td>
                <td>
                    <span class="action-icons">✎</span>
                    <span class="action-icons">🗑️</span>
                </td>
            `;

            vaccineTable.insertBefore(newRow, vaccineTable.firstChild);

            latestVaccine.textContent = `Vacuna: ${name}`;
            latestDate.textContent = `Fecha: ${applicationDate}`;

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
    <script>
        function loadProfilePicture(event) {
            const image = document.getElementById('profileImage');
            image.src = URL.createObjectURL(event.target.files[0]);
        }

        function closeModal() {
        window.parent.postMessage('closeModal', '*');
       }
    </script>
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
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['vacuna']}</td>
                            <td>{$row['enfermedad_previene']}</td>
                            <td>{$row['dosis']}</td>
                            <td>{$row['edad_frecuencia']}</td>
                            <td>{$row['fecha_aplicacion']}</td>
                            <td>{$row['lote']}</td>
                            <td>
                                <span class='action-icons edit-btn' data-id='{$row['id_vacuna']}'>✎</span>
                                <span class='action-icons delete-btn' data-id='{$row['id_vacuna']}'>🗑️</span>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' id='empty-message'>No hay registros disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <button class="delete-all-btn">Eliminar todos los registros</button>
    </div>

    <!-- Formulario popup para agregar/editar -->
    <div id="form-container" class="form-container hidden">
        <button class="close-btn" id="close-btn">X</button>
        <h3 id="form-title">Registro de Vacuna</h3>
        <form id="vaccine-form" method="POST" action="procesar_vacuna.php">
            <input type="hidden" id="id_vacuna" name="id_vacuna">
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

            <input type="hidden" name="id_bebe" value="<?php echo $id_bebe; ?>">
            <button type="submit">Guardar</button>
        </form>
        <button type="button" class="cancel" onclick="closeModal()">Cerrar</button>
    </div>

    <script>
        // Mostrar formulario para agregar
        addBtn.addEventListener('click', () => {
            document.getElementById('form-title').textContent = 'Agregar Vacuna';
            formContainer.classList.remove('hidden');
            overlay.style.display = 'block';
        });

        // Editar un registro existente
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                // Aquí deberías hacer una solicitud AJAX para obtener los datos del registro
                // y llenar el formulario con ellos, luego mostrar el formulario.
                // Por simplicidad, asumo que ya tienes los datos disponibles en el frontend.
                document.getElementById('id_vacuna').value = id;
                // Llenar el formulario con los datos del registro...

                document.getElementById('form-title').textContent = 'Editar Vacuna';
                formContainer.classList.remove('hidden');
                overlay.style.display = 'block';
            });
        });

        // Eliminar un registro
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                    // Realizar la eliminación mediante una solicitud AJAX o redirigir a una página PHP
                    window.location.href = `eliminar_vacuna.php?id=${id}`;
                }
            });
        });
    </script>
    
</body>
</html>
