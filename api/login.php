<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once 'db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['usuario']) || !isset($data['password'])) {
        throw new Exception("Datos incompletos");
    }

    $stmt = $pdo->prepare("SELECT id, usuario FROM usuarios WHERE usuario = ? AND password = ?");
    $stmt->execute([$data['usuario'], $data['password']]);
    $user = $stmt->fetch();

    if ($user) {
        // En un sistema real usaríamos JWT o Sessions seguras. 
        // Para este MVP retornamos un token simple que el frontend guardará.
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login exitoso',
            'token' => 'token_de_acceso_temp_' . $user['id'],
            'user' => $user
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
