<?php
// Database configuration
define('DB_HOST', 'missouridb.ghostnode.us');
define('DB_NAME', 's7_status');
define('DB_USER', 'u7_UNCEBabHsQ');
define('DB_PASS', 'nssDm6^EaNlYICL@KU@5lgtk');

// Application settings
define('BASE_URL', 'http://45.59.169.214:4500/');
define('SITE_NAME', 'GhostStatus');

// Session configuration
session_start();

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Include functions
require_once __DIR__ . '/functions.php';
