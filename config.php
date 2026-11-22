<?php
declare(strict_types=1);
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_inventory');
define('DB_USER', 'root');
define('DB_PASS', '');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        // Adds only the admin account table. Existing products and sales are untouched.
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(120) NOT NULL,
            username VARCHAR(60) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");
    }
    return $pdo;
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent = $_POST['csrf_token'] ?? '';
        if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
            http_response_code(400); exit('Invalid request token. Please go back and try again.');
        }
    }
}
function is_logged_in(): bool { return !empty($_SESSION['admin_id']); }
function require_login(): void {
    if (!is_logged_in()) { header('Location: login.php'); exit; }
}
function admin_count(): int { return (int)db()->query("SELECT COUNT(*) FROM admin_users")->fetchColumn(); }
function current_admin(): array {
    $s = db()->prepare("SELECT id,full_name,username FROM admin_users WHERE id=?");
    $s->execute([(int)($_SESSION['admin_id'] ?? 0)]);
    return $s->fetch() ?: ['full_name'=>'Shop Admin','username'=>''];
}
function notice_redirect(string $message): void {
    header('Location: index.php?notice='.urlencode($message)); exit;
}
