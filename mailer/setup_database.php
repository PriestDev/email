<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'email_logs';

try {
    // Connect without database to create it
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "✓ Database '$dbname' created/verified.\n";
    
    // Connect to the new database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create sent_emails table
    $sql = "CREATE TABLE IF NOT EXISTS `sent_emails` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `recipient` VARCHAR(255) NOT NULL,
        `sender_name` VARCHAR(255) NOT NULL,
        `sender_email` VARCHAR(255),
        `subject` VARCHAR(255) NOT NULL,
        `body` LONGTEXT NOT NULL,
        `status` VARCHAR(50) DEFAULT 'sent',
        `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY `idx_sent_at` (`sent_at`),
        KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✓ Table 'sent_emails' created/verified.\n";
    
    echo "\n✅ Database setup complete! You can now send emails and view sent history.\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
