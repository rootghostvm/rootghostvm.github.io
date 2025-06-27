<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function getStatusColor($status) {
    switch ($status) {
        case 'operational': return 'bg-green-100 text-green-800';
        case 'degraded': return 'bg-yellow-100 text-yellow-800';
        case 'outage': return 'bg-red-100 text-red-800';
        case 'maintenance': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusIcon($status) {
    switch ($status) {
        case 'operational': return '✓';
        case 'degraded': return '!';
        case 'outage': return '✗';
        case 'maintenance': return '⚙';
        default: return '?';
    }
}

function getServices($pdo) {
    $stmt = $pdo->query("SELECT * FROM services WHERE is_monitored = TRUE");
    return $stmt->fetchAll();
}

function getRecentUpdates($pdo, $limit = 5) {
    $stmt = $pdo->prepare("
        SELECT su.*, s.name as service_name, u.username 
        FROM status_updates su
        JOIN services s ON su.service_id = s.id
        JOIN users u ON su.user_id = u.id
        ORDER BY su.created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCurrentStatus($pdo, $service_id) {
    $stmt = $pdo->prepare("
        SELECT status FROM status_updates 
        WHERE service_id = :service_id
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([':service_id' => $service_id]);
    return $stmt->fetchColumn() ?: 'unknown';
}
