<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'db.php';

$negocio_id = $_GET['negocio_id'] ?? null;

if (!$negocio_id) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE negocio_id = ?");
$stmt->execute([$negocio_id]);
$categorias = $stmt->fetchAll();

echo json_encode($categorias);
