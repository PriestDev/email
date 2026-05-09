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
    <link rel="stylesheet" href="styles.css">
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
            padding: 40px 20px;
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
            margin-bottom: 50px;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            font-size: 2.8em;
            font-weight: 700;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            color: #00d4ff;
            text-decoration: none;
            border: 2px solid rgba(0, 212, 255, 0.4);
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .back-link:hover {
            border-color: #00d4ff;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
            transform: translateX(-3px);
            background: rgba(0, 212, 255, 0.05);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #8899aa;
            font-size: 18px;
        }

        .table-wrapper {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(25px);
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
            background: linear-gradient(135deg, #f7f8fa 0%, #eef1f6 100%);
            border-bottom: 2px solid #dee2e6;
        }

        th {
            padding: 20px 18px;
            text-align: left;
            font-weight: 700;
            color: #1f2a3d;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fb 0%, #f1f3f6 100%);
            box-shadow: inset 4px 0 0 #00d4ff, 0 4px 20px rgba(0, 212, 255, 0.1);
            transform: translateX(2px);
        }

        tbody tr:hover td {
            color: #1a1a2e;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 18px;
            color: #495057;
            font-size: 14px;
            line-height: 1.5;
        }

        .recipient-cell {
            font-weight: 600;
            color: #1f2a3d;
            letter-spacing: 0.2px;
        }

        .subject-cell {
            color: #0099ff;
            font-weight: 500;
            word-break: break-word;
            max-width: 250px;
        }

        .timestamp-cell {
            color: #7a8a9d;
            font-size: 13px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
            padding: 9px 16px;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 12px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 212, 255, 0.2);
            letter-spacing: 0.3px;
        }

        .view-body:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 212, 255, 0.35);
        }

        .view-body:active {
            transform: translateY(0);
        }

        .view-body::before {
            content: '👁️';
            font-size: 14px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 18px 22px;
            border-radius: 12px;
            border-left: 4px solid #f5c6cb;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 0.2px;
            box-shadow: 0 4px 12px rgba(114, 28, 36, 0.1);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 30, 46, 0.95);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 40px;
            border-radius: 20px;
            max-width: 1000px;
            max-height: 90vh;
            overflow: auto;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(0, 212, 255, 0.1);
            animation: modalSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(0, 212, 255, 0.2);
            position: relative;
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #00d4ff 0%, #0099ff 50%, #0052cc 100%);
            border-radius: 20px 20px 0 0;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.92);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content h2 {
            color: #1f2a3d;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            position: relative;
        }

        .modal-content h2::after {
            content: '📧';
            position: absolute;
            right: -40px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            opacity: 0.7;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            font-size: 28px;
            color: #666;
            transition: all 0.3s ease;
            background: rgba(0, 212, 255, 0.1);
            border: none;
            padding: 8px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .close:hover {
            color: #fff;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.4);
        }

        #email-body {
            color: #333;
            line-height: 1.8;
            padding: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            border: 2px solid rgba(0, 212, 255, 0.1);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
            max-height: none;
            overflow: visible;
            position: relative;
        }

        #email-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00d4ff 0%, #0099ff 50%, #0052cc 100%);
            border-radius: 16px 16px 0 0;
        }

        @media (max-width: 1024px) {
            .container {
                max-width: 95%;
            }

            th {
                padding: 18px 12px;
                font-size: 12px;
            }

            td {
                padding: 16px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 25px 15px;
            }

            .header-section {
                flex-direction: column;
                align-items: stretch;
                margin-bottom: 35px;
            }

            h1 {
                font-size: 2em;
                text-align: center;
            }

            .back-link {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
                font-size: 15px;
            }

            .table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                min-width: 800px;
                font-size: 12px;
            }

            th {
                padding: 14px 10px;
                font-size: 11px;
                white-space: nowrap;
            }

            td {
                padding: 14px 10px;
                white-space: nowrap;
            }

            .recipient-cell {
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .modal-content {
                max-width: 95%;
                padding: 30px;
                max-height: 90vh;
            }

            .modal-content h2 {
                font-size: 24px;
                margin-bottom: 25px;
            }

            .modal-content h2::after {
                right: -35px;
                font-size: 20px;
            }

            .close {
                top: 15px;
                right: 15px;
                width: 36px;
                height: 36px;
                font-size: 24px;
            }

            #email-body {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 10px;
            }

            h1 {
                font-size: 1.8em;
            }

            .table-wrapper {
                border-radius: 12px;
            }

            table {
                min-width: 700px;
            }

            th {
                padding: 12px 8px;
                font-size: 10px;
            }

            td {
                padding: 12px 8px;
                font-size: 11px;
            }

            .recipient-cell {
                max-width: 120px;
            }

            .modal-content {
                padding: 25px;
                max-width: 98%;
                max-height: 95vh;
            }

            .modal-content h2 {
                font-size: 22px;
                margin-bottom: 20px;
            }

            .modal-content h2::after {
                display: none;
            }

            .close {
                top: 12px;
                right: 12px;
                width: 32px;
                height: 32px;
                font-size: 20px;
            }

            #email-body {
                padding: 15px;
                font-size: 14px;
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
                                    <button class="view-body" data-body="<?php echo htmlspecialchars(addslashes($email['body'])); ?>" onclick="showBody(this)">View</button>
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
        function showBody(button) {
            const body = button.getAttribute('data-body');
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