<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: ../index.php');
    exit;
}

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration from .env
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

$emails = [];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, recipient, sender_name, sender_email, subject, body, sent_at, status FROM sent_emails ORDER BY sent_at DESC");
    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Emails History</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 30px 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            font-size: 2.5em;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            color: #00d4ff;
            text-decoration: none;
            border: 2px solid rgba(0, 212, 255, 0.3);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .back-link:hover {
            border-color: #00d4ff;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            transform: translateX(-4px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
            font-size: 18px;
        }

        .table-wrapper {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #333;
        }

        thead {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
        }

        th {
            padding: 18px 16px;
            text-align: left;
            font-weight: 600;
            color: #1f2a3d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 16px;
            color: #495057;
            font-size: 14px;
        }

        .recipient-cell {
            font-weight: 500;
            color: #1f2a3d;
        }

        .subject-cell {
            color: #0099ff;
            font-weight: 500;
            word-break: break-word;
        }

        .timestamp-cell {
            color: #6c757d;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-sent {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .actions-cell {
            text-align: center;
        }

        .view-body {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.3s ease;
            border: none;
        }

        .view-body:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.3);
        }

        .view-body::before {
            content: '👁️';
            font-size: 14px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 16px 20px;
            border-radius: 10px;
            border-left: 4px solid #f5c6cb;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 900px;
            max-height: 85vh;
            overflow: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content h2 {
            color: #1f2a3d;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 28px;
            color: #999;
            transition: color 0.2s ease;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
        }

        .close:hover {
            color: #333;
        }

        #email-body {
            color: #333;
            line-height: 1.6;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            h1 {
                font-size: 1.8em;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 12px 8px;
            }

            .modal-content {
                max-width: 95%;
                padding: 20px;
            }

            .view-body {
                padding: 6px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>📧 Sent Emails History</h1>
            <a href="index.php" class="back-link">← Back to Templates</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif (empty($emails)): ?>
            <div class="empty-state">
                <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                <p>No sent emails found yet. Start by customizing and sending an email template.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Recipient</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Sent At</th>
                            <th>Status</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emails as $email): ?>
                            <tr>
                                <td style="font-weight: 600; color: #0099ff;">#<?php echo $email['id']; ?></td>
                                <td class="recipient-cell"><?php echo htmlspecialchars($email['recipient']); ?></td>
                                <td><?php echo htmlspecialchars($email['sender_name']); ?></td>
                                <td class="subject-cell"><?php echo htmlspecialchars(substr($email['subject'], 0, 40)) . (strlen($email['subject']) > 40 ? '...' : ''); ?></td>
                                <td class="timestamp-cell"><?php echo date('M d, Y H:i', strtotime($email['sent_at'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($email['status']); ?>">
                                        <?php echo $email['status']; ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <button class="view-body" onclick="showBody('<?php echo addslashes($email['body']); ?>')">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <button class="close" onclick="closeModal()">&times;</button>
            <h2>Email Body Preview</h2>
            <div id="email-body"></div>
        </div>
    </div>

    <script>
        function showBody(body) {
            document.getElementById('email-body').innerHTML = body;
            document.getElementById('modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('modal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>