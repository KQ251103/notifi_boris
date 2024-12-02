<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si tienes usuario y contraseña configurados
$password = "";
$dbname = "notifications_db"; // Asegúrate de que la base de datos exista y tenga la tabla notifications

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Obtener datos en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)) {
    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO notifications (packageName, title, text, timestamp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $data['packageName'], $data['title'], $data['text'], $data['timestamp']);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Notificación guardada"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al guardar: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No data received"]);
}

$conn->close();
?>