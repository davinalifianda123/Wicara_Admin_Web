<?php
require 'config.php'; // Pastikan koneksi database benar

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$notifId = $input['id'] ?? null;

if (!$notifId) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

$query = "UPDATE kejadian SET status_notif = 'terbaca' WHERE id_kejadian = ?";
$stmt = $db->koneksi->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param('i', $notifId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$db->koneksi->close();
?>
