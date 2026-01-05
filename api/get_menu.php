<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");

$negocio_id = $_GET['id'];

// Obtener datos del negocio
$stmt = $pdo->prepare("SELECT * FROM negocios WHERE id = ?");
$stmt->execute([$negocio_id]);
$negocio = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener categorías y productos
$stmt = $pdo->prepare("SELECT c.nombre as categoria, p.* FROM categorias c 
                       JOIN productos p ON c.id = p.categoria_id 
                       WHERE c.negocio_id = ?");
$stmt->execute([$negocio_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['negocio' => $negocio, 'items' => $items]);