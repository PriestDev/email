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
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #00d4ff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #00d4ff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .view-body {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            max-height: 80%;
            overflow: auto;
        }
        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Templates</a>
        <h1>Sent Emails History</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php elseif (empty($emails)): ?>
            <p>No sent emails found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Recipient</th>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Sent At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emails as $email): ?>
                        <tr>
                            <td><?php echo $email['id']; ?></td>
                            <td><?php echo htmlspecialchars($email['recipient']); ?></td>
                            <td><?php echo htmlspecialchars($email['sender_name'] . ' (' . $email['sender_email'] . ')'); ?></td>
                            <td><?php echo htmlspecialchars($email['subject']); ?></td>
                            <td><?php echo $email['sent_at']; ?></td>
                            <td><?php echo $email['status']; ?></td>
                            <td><span class="view-body" onclick="showBody('<?php echo addslashes($email['body']); ?>')">View Body</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Email Body</h2>
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