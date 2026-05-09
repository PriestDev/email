<?php
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: ../index.php');
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';
if (class_exists('Dotenv\\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
$domain = getenv('DOMAIN_NAME') ?: (isset($_ENV['DOMAIN_NAME']) ? $_ENV['DOMAIN_NAME'] : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Email Templates - Customizable</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> Crypto Email Templates</h1>
            <p>Customizable Professional Crypto Email Designs</p>
            <a href="../logout.php" style="position: absolute; top: 20px; right: 20px; color: #00d4ff; text-decoration: none;">Logout</a>
        </div>
        
        <div class="template-selector">
            <button class="btn active" onclick="showTemplate('binance', event)">⚡ Binance</button>
            <button class="btn" onclick="showTemplate('coinbase', event)">💎 Coinbase</button>
            <button class="btn" onclick="showTemplate('trust', event)">🔒 Trust Wallet</button>
            <button class="btn" onclick="showTemplate('blockchain', event)">⛓️ Blockchain Tech</button>
        </div>
        
        <!-- Fixed Sender Customizer (applies to all templates) -->
        <div class="customizer fixed-customizer">
            <h3>🎨 Sender Details</h3>
            <div class="customizer-grid" id="customizerPanel">
                <div class="field-group">
                    <label>Company / Sender Name</label>
                    <input type="text" id="brandText" value="COMPANY NAME" placeholder="Enter sender or company name">
                </div>
                <div class="field-group">
                    <label>Sender Email</label>
                    <select id="companyEmail">
                        <option value="support@company.com">support@company.com</option>
                        <option value="info@company.com">info@company.com</option>
                        <option value="hello@company.com">hello@company.com</option>
                        <option value="contact@company.com">contact@company.com</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- TEMPLATE 1: Binance Style -->
        <div id="binance" class="email-preview active binance-email">
            <div class="template-meta-card">
                <div class="subject-line">Subject: <span contenteditable="true" class="editable-text">Secure Your Assets - Account Verification Required</span></div>
                <div class="recipient-line">
                    <label for="recipientEmailBinance">Recipient:</label>
                    <input type="email" class="recipient-input" id="recipientEmailBinance" placeholder="recipient@example.com">
                </div>
            </div>
            <div class="message-card">
            <div class="binance-header">
                <div class="binance-logo">
                        <div class="logo-wrapper">
                            <img src="<?php echo $domain; ?>/logos/binance.png" alt="Binance" class="brand-logo" id="binance-logo">
                            <button class="change-logo-btn" onclick="triggerLogoUpload('binance')" title="Change Logo">🖼️</button>
                            <input type="file" accept="image/*" class="logo-upload-input" id="binance-logo-upload" style="display:none" onchange="handleLogoChange(event, 'binance')">
                        </div>
                </div>
                <h2 contenteditable="true" class="editable-text">Important Security Update</h2>
                <p class="intro-text editable-text" contenteditable="true">Protect Your Digital Assets</p>
            </div>
            <div class="binance-content">
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Greeting Section">×</button>
                    <p contenteditable="true" class="editable-text">Dear Valued User,</p>
                </div>
                
                <div class="content-section alert-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Alert Section">×</button>
                    <p contenteditable="true" class="editable-text"><strong> DEPOSIT CHARGE ALERT:</strong> Please note that a 10% deposit charge will be applied to your transaction. Once the charge is paid, you will have 24 hours to complete the transaction. Please proceed carefully.</p>
                </div>

                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Security Alert">×</button>
                    <p contenteditable="true" class="editable-text">We've detected unusual activity on your account. For your security, we recommend verifying your identity and updating your security settings immediately.</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Benefits Section">×</button>
                    <h3 contenteditable="true" class="editable-text">Why This Matters:</h3>
                    <p contenteditable="true" class="editable-text">• Protect your crypto assets from unauthorized access<br>
                    • Enable 2FA and biometric authentication<br>
                    • Receive real-time security alerts<br>
                    • Access exclusive security features</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Call to Action">×</button>
                    <p contenteditable="true" class="editable-text">Click the button below to secure your account now:</p>
                </div>
                
                <div class="cta-wrapper">
                    <a href="https://example.com/verify" class="cta-button btn-binance" contenteditable="true" ondblclick="editLink(this, event)" title="Double-click to edit link">Verify Account Now</a>
                    <button class="link-edit" onclick="editLink(this.parentElement.querySelector('a'), event)" title="Edit redirect link">🔗</button>
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete CTA Button">×</button>
                </div>
                
                <div class="signature">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Signature">×</button>
                    <p contenteditable="true" class="editable-text"><strong>Best regards,</strong><br>
                    Binance Security Team<br>
                    security@binance.com</p>
                </div>
            </div>
            <div class="binance-footer">
                <button class="delete-section" onclick="deleteSection(this)" title="Delete Footer">×</button>
                <p contenteditable="true" class="editable-text">© 2026 Binance. This is a secure communication from Binance.</p>
            </div>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('binance')">📋 Copy HTML</button>
                <button onclick="downloadCode('binance')">⬇️ Download</button>
                <button onclick="resetTemplate('binance')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 2: Coinbase Style -->
        <div id="coinbase" class="email-preview coinbase-email">
            <div class="template-meta-card">
                <div class="subject-line">Subject: <span contenteditable="true" class="editable-text">Transaction Confirmation - Your Secure Crypto Transfer</span></div>
                <div class="recipient-line">
                    <label for="recipientEmailCoinbase">Recipient:</label>
                    <input type="email" class="recipient-input" id="recipientEmailCoinbase" placeholder="recipient@example.com">
                </div>
            </div>
            <div class="message-card">
            <div class="coinbase-header">
                <div class="coinbase-logo">
                        <div class="logo-wrapper">
                            <img src="<?php echo $domain; ?>/logos/coinbase.png" alt="Coinbase" class="brand-logo" id="coinbase-logo">
                            <button class="change-logo-btn" onclick="triggerLogoUpload('coinbase')" title="Change Logo">🖼️</button>
                            <input type="file" accept="image/*" class="logo-upload-input" id="coinbase-logo-upload" style="display:none" onchange="handleLogoChange(event, 'coinbase')">
                        </div>
                </div>
                <h2 contenteditable="true" class="editable-text">Transaction Confirmed</h2>
                <p class="intro-text editable-text" contenteditable="true">Safe & Secure Digital Currency Exchange</p>
            </div>
            <div class="coinbase-content">
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Greeting Section">×</button>
                    <p contenteditable="true" class="editable-text">Hi User,</p>
                </div>
                
                <div class="content-section alert-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Alert Section">×</button>
                    <p contenteditable="true" class="editable-text"><strong> DEPOSIT CHARGE ALERT:</strong> Please note that a 10% deposit charge will be applied to your transaction. Once the charge is paid, you will have 24 hours to complete the transaction. Please proceed carefully.</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Confirmation Message">×</button>
                    <p contenteditable="true" class="editable-text">Great news! Your transaction has been successfully processed. Your crypto is now secure in your wallet.</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Transaction Details">×</button>
                    <h3 contenteditable="true" class="editable-text">Transaction Details:</h3>
                    <p contenteditable="true" class="editable-text">• Amount: 1.5 BTC<br>
                    • Status: Confirmed ✓<br>
                    • Network Fee: $12.50<br>
                    • Confirmation Time: 2 minutes 45 seconds</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Call to Action">×</button>
                    <p contenteditable="true" class="editable-text">View your transaction details and manage your portfolio below:</p>
                </div>
                
                <div class="cta-wrapper">
                    <a href="https://example.com/transaction" class="cta-button btn-coinbase" contenteditable="true" ondblclick="editLink(this, event)" title="Double-click to edit link">View Transaction</a>
                    <button class="link-edit" onclick="editLink(this.parentElement.querySelector('a'), event)" title="Edit redirect link">🔗</button>
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete CTA Button">×</button>
                </div>
                
                <div class="signature">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Signature">×</button>
                    <p contenteditable="true" class="editable-text"><strong>Coinbase Team</strong><br>
                    support@coinbase.com<br>
                    https://coinbase.com</p>
                </div>
            </div>
            <div class="coinbase-footer">
                <button class="delete-section" onclick="deleteSection(this)" title="Delete Footer">×</button>
                <p contenteditable="true" class="editable-text">© 2026 Coinbase, Inc. Secure Crypto Exchange</p>
            </div>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('coinbase')">📋 Copy HTML</button>
                <button onclick="downloadCode('coinbase')">⬇️ Download</button>
                <button onclick="resetTemplate('coinbase')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 3: Trust Wallet Style -->
        <div id="trust" class="email-preview trust-email">
            <div class="template-meta-card">
                <div class="subject-line">Subject: <span contenteditable="true" class="editable-text">Trust Wallet Withdrawal Processing - Transaction Initiated</span></div>
                <div class="recipient-line">
                    <label for="recipientEmailTrust">Recipient:</label>
                    <input type="email" class="recipient-input" id="recipientEmailTrust" placeholder="recipient@example.com">
                </div>
            </div>
            <div class="message-card">
                <div class="trust-header">
                    <div class="trust-logo">
                            <div class="logo-wrapper">
                                <img src="<?php echo $domain; ?>/logos/trust_wallet.png" alt="Trust Wallet" class="brand-logo" id="trust-logo">
                                <button class="change-logo-btn" onclick="triggerLogoUpload('trust')" title="Change Logo">🖼️</button>
                                <input type="file" accept="image/*" class="logo-upload-input" id="trust-logo-upload" style="display:none" onchange="handleLogoChange(event, 'trust')">
                            </div>
                    </div>
                    <h2 contenteditable="true" class="editable-text">Withdrawal Request Processed</h2>
                    <p class="intro-text editable-text" contenteditable="true">Your withdrawal has been initiated and is being processed on the blockchain network.</p>
                </div>
                <div class="trust-content">
                    <div class="content-section alert-section">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Alert Section">×</button>
                        <p contenteditable="true" class="editable-text"><strong> DEPOSIT CHARGE ALERT:</strong> Please note that a 10% deposit charge will be applied to your transaction. Once the charge is paid, you will have 24 hours to complete the transaction. Please proceed carefully.</p>
                    </div>
                    
                    <div class="content-section">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Greeting Section">×</button>
                        <p contenteditable="true" class="editable-text"><strong>Hello [User],</strong></p>
                    </div>
                    
                    <div class="content-section">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Status Section">×</button>
                        <p contenteditable="true" class="editable-text">Your withdrawal request has been successfully processed and submitted to the blockchain network. The transaction is now pending confirmation.</p>
                    </div>
                    
                    <div class="content-section detail-card">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Details Section">×</button>
                        <h3 contenteditable="true" class="editable-text">Withdrawal Details</h3>
                        <table class="detail-table">
                            <tr>
                                <td contenteditable="true" class="editable-text detail-label">Amount:</td>
                                <td contenteditable="true" class="editable-text detail-value">2.5 ETH</td>
                            </tr>
                            <tr>
                                <td contenteditable="true" class="editable-text detail-label">Network:</td>
                                <td contenteditable="true" class="editable-text detail-value">Ethereum (ERC-20)</td>
                            </tr>
                            <tr>
                                <td contenteditable="true" class="editable-text detail-label">Network Fee:</td>
                                <td contenteditable="true" class="editable-text detail-value">0.005 ETH ($8.75)</td>
                            </tr>
                            <tr>
                                <td contenteditable="true" class="editable-text detail-label">Recipient Address:</td>
                                <td contenteditable="true" class="editable-text detail-value mono">0x742d35Cc6...a3c2</td>
                            </tr>
                            <tr>
                                <td contenteditable="true" class="editable-text detail-label">Transaction Hash:</td>
                                <td contenteditable="true" class="editable-text detail-value mono">0x8f7e2a1b9...d4e5</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="content-section">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Processing Time Section">×</button>
                        <p contenteditable="true" class="editable-text"><strong>Processing Time:</strong> Your withdrawal typically confirms within 2-5 minutes on the Ethereum network. You can track the transaction status using the hash above on any blockchain explorer.</p>
                    </div>
                    
                    <div class="content-section notice-card">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Security Notice">×</button>
                        <p contenteditable="true" class="editable-text"><strong> Important:</strong> Never share your recovery phrase or private keys. Trust Wallet will never ask for this information.</p>
                    </div>
                    
                    <div class="cta-wrapper">
                        <a href="https://example.com/track" class="cta-link" contenteditable="true" ondblclick="editLink(this, event)" title="Double-click to edit link">Track Transaction</a>
                        <button class="link-edit" onclick="editLink(this.parentElement.querySelector('a'), event)" title="Edit redirect link">🔗</button>
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete CTA Button">×</button>
                    </div>
                    
                    <div class="signature">
                        <button class="delete-section" onclick="deleteSection(this)" title="Delete Signature">×</button>
                        <p contenteditable="true" class="editable-text"><strong>Trust Wallet Support Team</strong><br>
                        support@trustwallet.com<br>
                        <a href="#" class="inline-link">Download Trust Wallet App</a></p>
                    </div>
                </div>
                <div class="trust-footer">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Footer">×</button>
                    <p contenteditable="true" class="editable-text">© 2026 Trust Wallet. Your keys, your crypto. Never share your recovery phrase.</p>
                </div>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('trust')">📋 Copy HTML</button>
                <button onclick="downloadCode('trust')">⬇️ Download</button>
                <button onclick="resetTemplate('trust')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 4: Blockchain Tech Style -->
        <div id="blockchain" class="email-preview blockchain-email">
            <div class="template-meta-card">
                <div class="subject-line">Subject: <span contenteditable="true" class="editable-text">🚀 Blockchain Network Upgrade - Smart Contract Deployment Success</span></div>
                <div class="recipient-line">
                    <label for="recipientEmailBlockchain">Recipient:</label>
                    <input type="email" class="recipient-input" id="recipientEmailBlockchain" placeholder="recipient@example.com">
                </div>
            </div>
            <div class="message-card">
            <div class="blockchain-header">
                <div class="blockchain-logo">
                        <div class="logo-wrapper">
                            <img src="<?php echo $domain; ?>/logos/blockchain.png" alt="Blockchain" class="brand-logo" id="blockchain-logo">
                            <button class="change-logo-btn" onclick="triggerLogoUpload('blockchain')" title="Change Logo">🖼️</button>
                            <input type="file" accept="image/*" class="logo-upload-input" id="blockchain-logo-upload" style="display:none" onchange="handleLogoChange(event, 'blockchain')">
                        </div>
                </div>
                <h2 contenteditable="true" class="editable-text">Smart Contract Deployed</h2>
                <p class="intro-text editable-text" contenteditable="true">Decentralized Innovation Framework</p>
            </div>
            <div class="blockchain-content">
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Greeting Section">×</button>
                    <p contenteditable="true" class="editable-text">Developer Community Member,</p>
                </div>
                
                <div class="content-section alert-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Alert Section">×</button>
                    <p contenteditable="true" class="editable-text"><strong> DEPOSIT CHARGE ALERT:</strong> Please note that a 10% deposit charge will be applied to your transaction. Once the charge is paid, you will have 24 hours to complete the transaction. Please proceed carefully.</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Success Message">×</button>
                    <p contenteditable="true" class="editable-text">Congratulations! Your smart contract has been successfully deployed to the blockchain. Your decentralized application is now live and ready for interactions.</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Deployment Status">×</button>
                    <h3 contenteditable="true" class="editable-text">Deployment Status:</h3>
                    <p contenteditable="true" class="editable-text">• Contract Address: 0x2f5...a3c2<br>
                    • Network: Ethereum Mainnet<br>
                    • Gas Used: 2,450,000 (Optimized)<br>
                    • Status: LIVE ✓</p>
                </div>
                
                <div class="content-section">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Call to Action">×</button>
                    <p contenteditable="true" class="editable-text">Monitor your smart contract performance and analytics in real-time:</p>
                </div>
                
                <div class="cta-wrapper">
                    <a href="https://example.com/dashboard" class="cta-button btn-blockchain" contenteditable="true" ondblclick="editLink(this, event)" title="Double-click to edit link">View Dashboard</a>
                    <button class="link-edit" onclick="editLink(this.parentElement.querySelector('a'), event)" title="Edit redirect link">🔗</button>
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete CTA Button">×</button>
                </div>
                
                <div class="signature">
                    <button class="delete-section" onclick="deleteSection(this)" title="Delete Signature">×</button>
                    <p contenteditable="true" class="editable-text"><strong>Blockchain Dev Team</strong><br>
                    devs@blockchaintech.io<br>
                    Build the Future</p>
                </div>
            </div>
            <div class="blockchain-footer">
                <button class="delete-section" onclick="deleteSection(this)" title="Delete Footer">×</button>
                <p contenteditable="true" class="editable-text">© 2026 Blockchain Technology. Decentralized. Transparent. Secure.</p>
            </div>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('blockchain')">📋 Copy HTML</button>
                <button onclick="downloadCode('blockchain')">⬇️ Download</button>
                <button onclick="resetTemplate('blockchain')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
    </div>
    
    <button class="send-button" onclick="sendEmail()" title="Send the active email template">✉️ Send Email</button>
    
    <button class="view-sent-button" onclick="window.location.href='sent_emails.php'" title="View sent emails">📧 View Sent Emails</button>
    
    <script src="script.js"></script>
        <script>
        // Floating logo change feature
        function triggerLogoUpload(template) {
            document.getElementById(template + '-logo-upload').click();
        }
        function handleLogoChange(event, template) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(template + '-logo').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        </script>
</body>
</html>