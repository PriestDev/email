<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Dotenv\Exception\InvalidFileException;
 
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (InvalidPathException | InvalidFileException $dotenvException) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Environment load failed: ' . $dotenvException->getMessage()]);
    exit;
}

// Database configuration from .env
$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'email_logs';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$recipient = filter_var(trim($_POST['recipient'] ?? ''), FILTER_VALIDATE_EMAIL);
$senderName = trim($_POST['senderName'] ?? 'Crypto Email');
$senderEmailRaw = trim($_POST['senderEmail'] ?? '');
$senderEmail = filter_var($senderEmailRaw, FILTER_VALIDATE_EMAIL);
$subject = trim($_POST['subject'] ?? 'Crypto Email Template');
$body = $_POST['body'] ?? '';

$useCpanelSmtp = filter_var($_ENV['USE_CPANEL_SMTP'], FILTER_VALIDATE_BOOLEAN); // set to true once you configure cPanel SMTP below

if (!$recipient) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'A valid recipient email is required.']);
    exit;
}

if (empty($body)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email body cannot be empty.']);
    exit;
}

// Use your Gmail account and app password for current sending.
// After you configure cPanel, set $useCpanelSmtp = true and fill in the cPanel settings.
$gmailUsername = trim($_ENV['GMAIL_USERNAME'] ?? '');
$gmailPassword = trim($_ENV['GMAIL_PASSWORD'] ?? '');

$cpanelHost = trim($_ENV['CPANEL_HOST'] ?? '');
$cpanelUsername = trim($_ENV['CPANEL_USERNAME'] ?? '');
$cpanelPassword = trim($_ENV['CPANEL_PASSWORD'] ?? '');
$cpanelPort = (int)($_ENV['CPANEL_PORT'] ?? 587);
$cpanelEncryption = parseEncryptionValue(trim($_ENV['CPANEL_ENCRYPTION'] ?? ''));

function parseEncryptionValue(string $value): string {
    return match (strtolower($value)) {
        'phpmailer::encryption_starttls', 'starttls', 'tls' => \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS,
        'phpmailer::encryption_smtps', 'smtps', 'ssl' => \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS,
        '' => '',
        default => $value,
    };
}

$hasCpanelSettings = $cpanelHost !== '' && $cpanelUsername !== '' && $cpanelPassword !== '';
if (!$useCpanelSmtp && $hasCpanelSettings && ($gmailUsername === '' || $gmailPassword === '')) {
    $useCpanelSmtp = true;
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPAutoTLS = true;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    if ($useCpanelSmtp && $hasCpanelSettings) {
        $mail->Host = $cpanelHost;
        $mail->Username = $cpanelUsername;
        $mail->Password = $cpanelPassword;
        $mail->SMTPSecure = $cpanelEncryption ?: PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $cpanelPort;
        $mail->setFrom($cpanelUsername, $senderName);
        if ($senderEmail) {
            $mail->addReplyTo($senderEmail, $senderName);
        }
    } else {
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = $gmailUsername;
        $mail->Password = $gmailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom($gmailUsername, $senderName);
        if ($senderEmail) {
            $mail->addReplyTo($senderEmail, $senderName);
        }
    }

    $mail->addAddress($recipient);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->msgHTML($body, __DIR__);
    $mail->AltBody = strip_tags($body);

    try {
        $mail->send();
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        if (!$useCpanelSmtp && stripos($errorMessage, 'Could not connect to SMTP host') !== false) {
            $mail->smtpClose();
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmailUsername;
            $mail->Password = $gmailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom($gmailUsername, $senderName);
            if ($senderEmail) {
                $mail->addReplyTo($senderEmail, $senderName);
            }
            $mail->addAddress($recipient);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->msgHTML($body, __DIR__);
            $mail->AltBody = strip_tags($body);
            $mail->send();
        } else {
            throw $e;
        }
    }

    // Store in database
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("INSERT INTO sent_emails (recipient, sender_name, sender_email, subject, body, status) VALUES (?, ?, ?, ?, ?, 'sent')");
        $stmt->execute([$recipient, $senderName, $senderEmail, $subject, $body]);
    } catch (PDOException $e) {
        // Log DB error but don't fail the email send
        error_log('DB Error: ' . $e->getMessage());
    }

    echo json_encode(['status' => 'success', 'message' => 'Email sent successfully.']);
} catch (Exception $e) {
    // Store failed email in database
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("INSERT INTO sent_emails (recipient, sender_name, sender_email, subject, body, status) VALUES (?, ?, ?, ?, ?, 'failed')");
        $stmt->execute([$recipient, $senderName, $senderEmail, $subject, $body]);
    } catch (PDOException $dbE) {
        error_log('DB Error: ' . $dbE->getMessage());
    }

    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $e->getMessage()]);
}
