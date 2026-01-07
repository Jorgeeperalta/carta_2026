<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");

$negocio_id = $_GET['negocio_id'];

if (!$negocio_id) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE negocio_id = ?");
$stmt->execute([$negocio_id]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categorias);
