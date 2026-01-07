<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("No hay datos");
    }

    if (isset($data['id']) && $data['id'] != '') {
        // Update
        $stmt = $pdo->prepare("UPDATE productos SET categoria_id = ?, nombre = ?, descripcion = ?, precio = ?, imagen_url = ? WHERE id = ?");
        $stmt->execute([$data['categoria_id'], $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen_url'], $data['id']]);
        echo json_encode(['status' => 'success', 'message' => 'Producto actualizado']);
    } else {
        // Create
        $stmt = $pdo->prepare("INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['categoria_id'], $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen_url']]);
        echo json_encode(['status' => 'success', 'message' => 'Producto creado', 'id' => $pdo->lastInsertId()]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
