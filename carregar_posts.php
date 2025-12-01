<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/config.php';

$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;

if ($limit < 1) {
    $limit = 5;
}

// Evita solicitar listas muito grandes de uma vez
if ($limit > 20) {
    $limit = 20;
}

if ($offset < 0) {
    $offset = 0;
}

$fetch_limit = $limit + 1;

$stmt = $mysqli->prepare("
    SELECT 
        PO.ID AS id,
        PO.TEXTO AS texto,
        P.NOME AS nome
    FROM POSTAGEM PO
    JOIN PERFIL P ON P.ID = PO.PERFIL_ID
    ORDER BY PO.ID DESC
    LIMIT ? OFFSET ?
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao preparar consulta.']);
    exit();
}

$stmt->bind_param("ii", $fetch_limit, $offset);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar posts.']);
    exit();
}

$result = $stmt->get_result();
$rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$has_more = false;
if (count($rows) > $limit) {
    $has_more = true;
    array_pop($rows);
}

echo json_encode([
    'posts' => $rows,
    'hasMore' => $has_more
]);

$stmt->close();
