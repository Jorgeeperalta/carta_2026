<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");
    $stmt = $pdo->query("SELECT * FROM negocios");
    $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($negocios);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
