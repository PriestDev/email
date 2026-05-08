<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Email Templates - Customizable</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 2.8em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .template-selector {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 28px;
            border: 2px solid rgba(0,212,255,0.3);
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
            color: #00d4ff;
        }
        
        .btn.active {
            background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
            color: #1a1a2e;
            border-color: #00d4ff;
            box-shadow: 0 0 20px rgba(0,212,255,0.5);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            border-color: #00d4ff;
            box-shadow: 0 0 15px rgba(0,212,255,0.3);
        }
        
        .email-preview {
            display: none;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: slideIn 0.4s ease;
        }
        
        .email-preview.active {
            display: block;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ===== DESIGN 1: Binance Style ===== */
        .binance-email {
            border: 2px solid #f3ba2f;
        }
        
        .binance-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 35px 30px;
            border-bottom: 3px solid #f3ba2f;
        }
        
        .binance-logo {
            color: #f3ba2f;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .binance-header h2 {
            color: #f3ba2f;
            font-size: 26px;
            margin-bottom: 8px;
        }
        
        .binance-content {
            padding: 35px 30px;
            background: white;
        }
        
        .binance-footer {
            background: #1a1a1a;
            color: #f3ba2f;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            border-top: 2px solid #f3ba2f;
        }
        
        /* ===== DESIGN 2: Coinbase Style ===== */
        .coinbase-email {
            border: 1px solid #0052ff;
        }
        
        .coinbase-header {
            background: linear-gradient(135deg, #0052ff 0%, #0066ff 100%);
            color: white;
            padding: 40px 30px;
        }
        
        .coinbase-logo {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        
        .coinbase-header h2 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .coinbase-content {
            padding: 35px 30px;
            background: white;
        }
        
        .coinbase-footer {
            background: #f7f8f9;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        
        /* ===== DESIGN 3: Trust Wallet Style ===== */
        .trust-email {
            border: 2px solid #3375bb;
        }
        
        .trust-header {
            background: linear-gradient(135deg, #3375bb 0%, #2563a8 100%);
            color: white;
            padding: 35px 30px;
        }
        
        .trust-logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .trust-header h2 {
            font-size: 26px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .trust-content {
            padding: 35px 30px;
            background: white;
        }
        
        .trust-footer {
            background: #f0f2f5;
            color: #3375bb;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            border-top: 2px solid #3375bb;
        }
        
        /* ===== DESIGN 4: Blockchain Tech Style ===== */
        .blockchain-email {
            border: 2px solid #00ff88;
            background: linear-gradient(135deg, rgba(0,255,136,0.05), transparent);
        }
        
        .blockchain-header {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: #00ff88;
            padding: 40px 30px;
            border-bottom: 3px solid #00ff88;
            position: relative;
            overflow: hidden;
        }
        
        .blockchain-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0,255,136,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .blockchain-logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
            letter-spacing: 3px;
        }
        
        .blockchain-header h2 {
            font-size: 26px;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
            text-shadow: 0 0 10px rgba(0,255,136,0.5);
        }
        
        .blockchain-content {
            padding: 35px 30px;
            background: white;
        }
        
        .blockchain-footer {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: #00ff88;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            border-top: 2px solid #00ff88;
        }
        
        /* Common Styles */
        .subject-line {
            background: #f5f5f5;
            padding: 15px 20px;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        
        .content-section {
            margin-bottom: 20px;
        }
        
        .content-section p,
        .content-section h3,
        .editable-text {
            color: #333;
            line-height: 1.6;
        }
        
        .content-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .content-section p {
            margin-bottom: 15px;
        }
        
        .editable-text {
            padding: 4px 8px;
            border-radius: 3px;
            cursor: text;
            transition: background 0.2s;
        }
        
        .editable-text:hover {
            background: rgba(0,212,255,0.1);
        }
        
        .editable-text:focus {
            outline: 2px solid #00d4ff;
            outline-offset: 2px;
            background: rgba(0,212,255,0.2);
        }
        
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
        }
        
        .toolbar {
            padding: 20px 30px;
            background: #f9f9f9;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .toolbar button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            background: white;
            color: #333;
        }
        
        .toolbar button:hover {
            background: #00d4ff;
            color: white;
            border-color: #00d4ff;
        }
        
        .toolbar button.danger {
            color: #ff4444;
            border-color: #ff4444;
        }
        
        .toolbar button.danger:hover {
            background: #ff4444;
            color: white;
        }
        
        .customizer {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(0,212,255,0.2);
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
            color: white;
        }
        
        .customizer h3 {
            color: #00d4ff;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .customizer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .color-input-group {
            display: flex;
            flex-direction: column;
        }
        
        .color-input-group label {
            font-size: 12px;
            margin-bottom: 8px;
            color: #00d4ff;
            font-weight: 600;
        }
        
        .color-input-group input[type="color"] {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .color-input-group input[type="text"] {
            padding: 8px 12px;
            border: 1px solid rgba(0,212,255,0.3);
            border-radius: 5px;
            background: rgba(0,0,0,0.3);
            color: white;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>� Crypto Email Templates</h1>
            <p>Customizable Professional Crypto Email Designs</p>
        </div>
        
        <div class="template-selector">
            <button class="btn active" onclick="showTemplate('binance')">⚡ Binance</button>
            <button class="btn" onclick="showTemplate('coinbase')">💎 Coinbase</button>
            <button class="btn" onclick="showTemplate('trust')">🔒 Trust Wallet</button>
            <button class="btn" onclick="showTemplate('blockchain')">⛓️ Blockchain Tech</button>
        </div>
        
        <!-- TEMPLATE 1: Binance Style -->
        <div id="binance" class="email-preview active binance-email">
            <div class="subject-line">Subject: Secure Your Assets - Account Verification Required</div>
            <div class="binance-header">
                <div class="binance-logo">BINANCE</div>
                <h2 contenteditable="true" class="editable-text">Important Security Update</h2>
                <p style="color: #f3ba2f; margin-top: 5px;" contenteditable="true" class="editable-text">Protect Your Digital Assets</p>
            </div>
            <div class="binance-content">
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Dear Valued User,</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">We've detected unusual activity on your account. For your security, we recommend verifying your identity and updating your security settings immediately.</p>
                </div>
                
                <div class="content-section">
                    <h3 contenteditable="true" class="editable-text">Why This Matters:</h3>
                    <p contenteditable="true" class="editable-text">• Protect your crypto assets from unauthorized access<br>
                    • Enable 2FA and biometric authentication<br>
                    • Receive real-time security alerts<br>
                    • Access exclusive security features</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Click the button below to secure your account now:</p>
                </div>
                
                <button style="display: inline-block; padding: 14px 35px; background: #f3ba2f; color: #1a1a1a; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: 700; border: none; cursor: pointer; font-size: 16px;">Verify Account Now</button>
                
                <div class="signature">
                    <p contenteditable="true" class="editable-text"><strong>Best regards,</strong><br>
                    Binance Security Team<br>
                    security@binance.com</p>
                </div>
            </div>
            <div class="binance-footer">
                <p contenteditable="true" class="editable-text">© 2026 Binance. This is a secure communication from Binance.</p>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('binance')">📋 Copy HTML</button>
                <button onclick="downloadCode('binance')">⬇️ Download</button>
                <button onclick="resetTemplate('binance')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 2: Coinbase Style -->
        <div id="coinbase" class="email-preview coinbase-email">
            <div class="subject-line">Subject: Transaction Confirmation - Your Secure Crypto Transfer</div>
            <div class="coinbase-header">
                <div class="coinbase-logo">COINBASE</div>
                <h2 contenteditable="true" class="editable-text">Transaction Confirmed</h2>
                <p style="color: rgba(255,255,255,0.9); margin-top: 5px;" contenteditable="true" class="editable-text">Safe & Secure Digital Currency Exchange</p>
            </div>
            <div class="coinbase-content">
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Hi User,</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Great news! Your transaction has been successfully processed. Your crypto is now secure in your wallet.</p>
                </div>
                
                <div class="content-section">
                    <h3 contenteditable="true" class="editable-text">Transaction Details:</h3>
                    <p contenteditable="true" class="editable-text">• Amount: 1.5 BTC<br>
                    • Status: Confirmed ✓<br>
                    • Network Fee: $12.50<br>
                    • Confirmation Time: 2 minutes 45 seconds</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">View your transaction details and manage your portfolio below:</p>
                </div>
                
                <button style="display: inline-block; padding: 14px 35px; background: #0052ff; color: white; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: 700; border: none; cursor: pointer; font-size: 16px;">View Transaction</button>
                
                <div class="signature">
                    <p contenteditable="true" class="editable-text"><strong>Coinbase Team</strong><br>
                    support@coinbase.com<br>
                    https://coinbase.com</p>
                </div>
            </div>
            <div class="coinbase-footer">
                <p contenteditable="true" class="editable-text">© 2026 Coinbase, Inc. Secure Crypto Exchange</p>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('coinbase')">📋 Copy HTML</button>
                <button onclick="downloadCode('coinbase')">⬇️ Download</button>
                <button onclick="resetTemplate('coinbase')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 3: Trust Wallet Style -->
        <div id="trust" class="email-preview trust-email">
            <div class="subject-line">Subject: Trust Wallet Withdrawal Processing - Transaction Initiated</div>
            <div class="trust-header">
                <div class="trust-logo">
                    <img src="https://trustwallet.com/assets/images/logo.svg" alt="Trust Wallet" style="height: 32px; width: auto; margin-bottom: 15px;">
                </div>
                <h2 contenteditable="true" class="editable-text">Withdrawal Request Processed</h2>
                <p style="color: rgba(255,255,255,0.9); margin-top: 8px; max-width: 620px;" contenteditable="true" class="editable-text">Your withdrawal has been initiated and is being processed on the blockchain network.</p>
            </div>
            <div class="trust-content">
                <div class="content-section">
                    <p contenteditable="true" class="editable-text"><strong>Hello [User],</strong></p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Your withdrawal request has been successfully processed and submitted to the blockchain network. The transaction is now pending confirmation.</p>
                </div>
                
                <div class="content-section" style="background: #f7fbff; border: 1px solid #dbe9ff; border-radius: 12px; padding: 20px;">
                    <h3 contenteditable="true" class="editable-text">Withdrawal Details</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; font-weight: 600; color: #1f3e6f;" contenteditable="true" class="editable-text">Amount:</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; text-align: right;" contenteditable="true" class="editable-text">2.5 ETH</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; font-weight: 600; color: #1f3e6f;" contenteditable="true" class="editable-text">Network:</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; text-align: right;" contenteditable="true" class="editable-text">Ethereum (ERC-20)</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; font-weight: 600; color: #1f3e6f;" contenteditable="true" class="editable-text">Network Fee:</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; text-align: right;" contenteditable="true" class="editable-text">0.005 ETH ($8.75)</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; font-weight: 600; color: #1f3e6f;" contenteditable="true" class="editable-text">Recipient Address:</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e0e6ff; text-align: right; font-family: monospace; font-size: 12px;" contenteditable="true" class="editable-text">0x742d35Cc6...a3c2</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; font-weight: 600; color: #1f3e6f;" contenteditable="true" class="editable-text">Transaction Hash:</td>
                            <td style="padding: 8px 0; text-align: right; font-family: monospace; font-size: 12px;" contenteditable="true" class="editable-text">0x8f7e2a1b9...d4e5</td>
                        </tr>
                    </table>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text"><strong>Processing Time:</strong> Your withdrawal typically confirms within 2-5 minutes on the Ethereum network. You can track the transaction status using the hash above on any blockchain explorer.</p>
                </div>
                
                <div class="content-section" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px;">
                    <p style="margin: 0; color: #856404;" contenteditable="true" class="editable-text"><strong>⚠️ Important:</strong> Never share your recovery phrase or private keys. Trust Wallet will never ask for this information.</p>
                </div>
                
                <a href="#" style="display: inline-block; padding: 14px 36px; background: #3375bb; color: white; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: 700; font-size: 16px;">Track Transaction</a>
                
                <div class="signature">
                    <p contenteditable="true" class="editable-text"><strong>Trust Wallet Support Team</strong><br>
                    support@trustwallet.com<br>
                    <a href="#" style="color: #3375bb; text-decoration: none;">Download Trust Wallet App</a></p>
                </div>
            </div>
            <div class="trust-footer">
                <p contenteditable="true" class="editable-text">© 2026 Trust Wallet. Your keys, your crypto. Never share your recovery phrase.</p>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('trust')">📋 Copy HTML</button>
                <button onclick="downloadCode('trust')">⬇️ Download</button>
                <button onclick="resetTemplate('trust')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- TEMPLATE 4: Blockchain Tech Style -->
        <div id="blockchain" class="email-preview blockchain-email">
            <div class="subject-line">Subject: 🚀 Blockchain Network Upgrade - Smart Contract Deployment Success</div>
            <div class="blockchain-header">
                <div class="blockchain-logo">BLOCKCHAIN TECH</div>
                <h2 contenteditable="true" class="editable-text">Smart Contract Deployed</h2>
                <p style="color: #00ff88; margin-top: 8px;" contenteditable="true" class="editable-text">Decentralized Innovation Framework</p>
            </div>
            <div class="blockchain-content">
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Developer Community Member,</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Congratulations! Your smart contract has been successfully deployed to the blockchain. Your decentralized application is now live and ready for interactions.</p>
                </div>
                
                <div class="content-section">
                    <h3 contenteditable="true" class="editable-text">Deployment Status:</h3>
                    <p contenteditable="true" class="editable-text">• Contract Address: 0x2f5...a3c2<br>
                    • Network: Ethereum Mainnet<br>
                    • Gas Used: 2,450,000 (Optimized)<br>
                    • Status: LIVE ✓</p>
                </div>
                
                <div class="content-section">
                    <p contenteditable="true" class="editable-text">Monitor your smart contract performance and analytics in real-time:</p>
                </div>
                
                <button style="display: inline-block; padding: 14px 35px; background: #00ff88; color: #0a0e27; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: 700; border: none; cursor: pointer; font-size: 16px;">View Dashboard</button>
                
                <div class="signature">
                    <p contenteditable="true" class="editable-text"><strong>Blockchain Dev Team</strong><br>
                    devs@blockchaintech.io<br>
                    Build the Future</p>
                </div>
            </div>
            <div class="blockchain-footer">
                <p contenteditable="true" class="editable-text">© 2026 Blockchain Technology. Decentralized. Transparent. Secure.</p>
            </div>
            <div class="toolbar">
                <button onclick="copyCode('blockchain')">📋 Copy HTML</button>
                <button onclick="downloadCode('blockchain')">⬇️ Download</button>
                <button onclick="resetTemplate('blockchain')" class="danger">🔄 Reset</button>
            </div>
        </div>
        
        <!-- Customizer Panel -->
        <div class="customizer">
            <h3>🎨 Quick Customizer</h3>
            <div class="customizer-grid" id="customizerPanel">
                <div class="color-input-group">
                    <label>Primary Color</label>
                    <input type="color" id="primaryColor" value="#00d4ff" onchange="updateCustomColors()">
                </div>
                <div class="color-input-group">
                    <label>Logo/Brand Text</label>
                    <input type="text" id="brandText" value="COMPANY NAME" placeholder="Enter your brand name">
                </div>
                <div class="color-input-group">
                    <label>Company Email</label>
                    <input type="text" id="companyEmail" value="support@company.com" placeholder="Enter company email">
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showTemplate(template) {
            const templates = document.querySelectorAll('.email-preview');
            templates.forEach(t => t.classList.remove('active'));
            
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(b => b.classList.remove('active'));
            
            document.getElementById(template).classList.add('active');
            event.target.classList.add('active');
            document.getElementById(template).scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        function copyCode(template) {
            const element = document.getElementById(template);
            const html = element.innerHTML;
            
            navigator.clipboard.writeText(html).then(() => {
                alert('✓ HTML copied to clipboard!');
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = html;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('✓ HTML copied to clipboard!');
            });
        }
        
        function downloadCode(template) {
            const element = document.getElementById(template);
            const html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Email Template</title>
</head>
<body>
${element.innerHTML}
</body>
</html>`;
            
            const blob = new Blob([html], { type: 'text/html' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `${template}-email-template.html`;
            link.click();
            window.URL.revokeObjectURL(url);
        }
        
        function resetTemplate(template) {
            if (confirm('Are you sure? This will reset all edits to the original template.')) {
                location.reload();
            }
        }
        
        function updateCustomColors() {
            const primaryColor = document.getElementById('primaryColor').value;
            const brandText = document.getElementById('brandText').value;
            const companyEmail = document.getElementById('companyEmail').value;
            
            document.documentElement.style.setProperty('--primary-color', primaryColor);
            
            const logos = document.querySelectorAll('.binance-logo, .coinbase-logo, .trust-logo, .blockchain-logo');
            logos.forEach(logo => {
                if (logo.textContent.includes('BINANCE') || logo.textContent.includes('COINBASE') || 
                    logo.textContent.includes('TRUST') || logo.textContent.includes('BLOCKCHAIN')) {
                    logo.textContent = brandText;
                }
            });
            
            const emails = document.querySelectorAll('[contenteditable="true"]');
            emails.forEach(el => {
                if (el.textContent.includes('@')) {
                    el.textContent = el.textContent.replace(/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/gi, companyEmail);
                }
            });
        }
        
        // Enable editing
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('editable-text')) {
                e.target.focus();
            }
        });
        
        // Prevent contenteditable from breaking on paste
        document.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            if (document.activeElement.contentEditable === 'true') {
                document.execCommand('insertText', false, text);
            }
        });
    </script>
</body>
</html>