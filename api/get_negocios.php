<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM negocios");
    $negocios = $stmt->fetchAll();
    echo json_encode($negocios);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
