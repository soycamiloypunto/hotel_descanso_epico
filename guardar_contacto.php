<?php
// Configuración de la base de datos
$host = "localhost";
$user = "root"; // Usuario predeterminado de XAMPP
$password = ""; // Sin contraseña por defecto en XAMPP
$dbname = "database_cun"; // Cambia esto al nombre de tu base de datos

// Habilitar excepciones en mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Conexión a la base de datos
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");

    // Verificar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Recibir y sanitizar datos
        $numero_documento = $_POST['numero_documento'] ?? '';
        $tipo_documento = $_POST['tipo_documento'] ?? '';
        $nombre_completo = $_POST['nombre_completo'] ?? '';
        $numero_celular = $_POST['numero_celular'] ?? '';
        $correo_electronico = $_POST['correo_electronico'] ?? '';
        $paquete_habitacion = $_POST['paquete_habitacion'] ?? '';

        // Validar campos
        if (
            empty($numero_documento) || empty($tipo_documento) || 
            empty($nombre_completo) || empty($numero_celular) || 
            empty($correo_electronico) || empty($paquete_habitacion)
        ) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El correo electrónico no es válido.");
        }

        if (!is_numeric($numero_documento) || !is_numeric($numero_celular)) {
            throw new Exception("El documento y el celular deben ser numéricos.");
        }

        // Preparar la consulta
        $stmt = $conn->prepare("INSERT INTO contacto (numero_documento, tipo_documento, nombre_completo, numero_celular, correo_electronico, paquete_habitacion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $numero_documento, $tipo_documento, $nombre_completo, $numero_celular, $correo_electronico, $paquete_habitacion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Datos guardados correctamente.";
        } else {
            throw new Exception("No se pudieron guardar los datos.");
        }
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
} finally {
    // Cerrar conexión
    if (isset($conn)) {
        $conn->close();
    }
}
?>
