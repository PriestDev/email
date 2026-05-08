function showTemplate(template, evt) {
    const templates = document.querySelectorAll('.email-preview');
    templates.forEach(t => t.classList.remove('active'));

    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(b => b.classList.remove('active'));

    document.getElementById(template).classList.add('active');
    if (evt && evt.currentTarget) evt.currentTarget.classList.add('active');
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
    const brandText = document.getElementById('brandText').value;
    const companyEmail = document.getElementById('companyEmail').value;

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

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('editable-text')) {
        e.target.focus();
    }
});

function deleteSection(button) {
    const section = button.parentElement;
    const sectionType = getSectionType(section);

    const confirmed = showDeleteConfirmation(sectionType);

    if (confirmed) {
        section.style.transition = 'all 0.3s ease';
        section.style.opacity = '0';
        section.style.transform = 'translateX(-20px)';

        setTimeout(() => {
            section.remove();
            showUndoNotification(sectionType);
        }, 300);
    }
}

function getSectionType(section) {
    if (section.classList.contains('subject-line')) return 'Subject Line';
    if (section.querySelector('h3')) return 'Content Section';
    if (section.classList.contains('signature')) return 'Signature';
    if (section.classList.contains('trust-footer')) return 'Footer';
    return 'Section';
}

function showDeleteConfirmation(sectionType) {
    return confirm(`🗑️ Delete ${sectionType}?\n\nThis action cannot be undone. The ${sectionType.toLowerCase()} will be permanently removed from the email template.`);
}

function showUndoNotification(sectionType) {
    const notification = document.createElement('div');
    notification.className = 'notification-toast';
    notification.textContent = `✓ ${sectionType} deleted successfully`;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

async function sendEmail() {
    const activeTemplate = document.querySelector('.email-preview.active');
    if (!activeTemplate) {
        alert('Please select a template before sending.');
        return;
    }

    const recipientInput = activeTemplate.querySelector('.recipient-input');
    const recipient = recipientInput ? recipientInput.value.trim() : '';
    if (!recipient || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(recipient)) {
        alert('Please enter a valid recipient email address.');
        recipientInput && recipientInput.focus();
        return;
    }

    const senderName = document.getElementById('brandText').value.trim() || 'Crypto Email';
    const senderEmail = document.getElementById('companyEmail').value;
    const subject = getTemplateSubject(activeTemplate);
    const cssText = await fetch('styles.css').then(response => response.text()).catch(() => '');
    const body = getTemplateHtmlForEmail(activeTemplate, cssText);

    const formData = new FormData();
    formData.append('recipient', recipient);
    formData.append('senderName', senderName);
    formData.append('senderEmail', senderEmail);
    formData.append('subject', subject);
    formData.append('body', body);

    const sendButton = document.querySelector('.send-button');
    sendButton.disabled = true;
    sendButton.textContent = 'Sending...';
    showStatus('Sending email…', 'loading');

    try {
        const response = await fetch('send_email.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showStatus(data.message || 'Email sent successfully.', 'success');
        } else {
            showStatus(data.message || 'Failed to send email.', 'error');
        }
    } catch (error) {
        showStatus('Unable to send email: ' + error.message, 'error');
    } finally {
        sendButton.disabled = false;
        sendButton.textContent = '✉️ Send Email';
    }
}

function showStatus(message, type = 'info') {
    removeStatus();

    const notification = document.createElement('div');
    notification.className = `status-toast ${type}`;
    notification.innerHTML = type === 'loading'
        ? `<span class="status-spinner"></span><span>${message}</span>`
        : `<span>${message}</span>`;
    document.body.appendChild(notification);

    if (type !== 'loading') {
        setTimeout(() => notification.remove(), 5000);
    }
}

function removeStatus() {
    const existing = document.querySelector('.status-toast');
    if (existing) {
        existing.remove();
    }
}

function getTemplateSubject(template) {
    const subjectSpan = template.querySelector('.subject-line .editable-text');
    return subjectSpan ? subjectSpan.textContent.trim() : 'Crypto Email Template';
}

function getTemplateHtmlForEmail(template, cssText) {
    const clone = template.cloneNode(true);
    clone.querySelectorAll('.template-meta-card, .recipient-line, .toolbar, .delete-section, .link-edit').forEach(el => el.remove());
    clone.querySelectorAll('[contenteditable]').forEach(el => el.removeAttribute('contenteditable'));

    const bodyInlineStyles = inlineCssStyles(clone, cssText);
    return `<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><style>${cssText}</style></head><body style="${bodyInlineStyles}">${clone.innerHTML}</body></html>`;
}

function inlineCssStyles(root, cssText) {
    const cleaned = cssText.replace(/\/\*[\s\S]*?\*\//g, '');
    const rules = cleaned.split('}').map(rule => rule.trim()).filter(Boolean);
    let bodyStyles = '';

    rules.forEach(rule => {
        const parts = rule.split('{');
        if (parts.length !== 2) return;

        const selectorText = parts[0].trim();
        const declarations = parts[1].trim();
        if (!selectorText || !declarations) return;

        selectorText.split(',').map(s => s.trim()).forEach(selector => {
            if (!selector || selector.startsWith('@') || selector.includes(':') || selector.includes('::')) {
                return;
            }

            if (selector === 'body') {
                bodyStyles += declarations + ' ';
                return;
            }

            if (selector === '.email-preview.active') {
                applyStyle(root, declarations);
                return;
            }

            if (selector === '.email-preview') {
                applyStyle(root, declarations);
                return;
            }

            try {
                const elements = root.querySelectorAll(selector);
                elements.forEach(el => applyStyle(el, declarations));
            } catch (error) {
                // Ignore invalid selectors that aren't email-safe.
            }
        });
    });

    return bodyStyles.trim();
}

function applyStyle(el, declarations) {
    const existing = el.getAttribute('style') || '';
    const newStyle = existing ? `${existing.trim()}; ${declarations}` : declarations;
    el.setAttribute('style', newStyle);
}

function editLink(element, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    if (!element || element.tagName !== 'A') {
        return;
    }

    const currentHref = element.getAttribute('href') || '';
    const newHref = prompt('Enter the redirect link for this button:', currentHref);
    if (newHref === null) {
        return;
    }

    const trimmed = newHref.trim();
    if (trimmed) {
        element.setAttribute('href', trimmed);
    }
}
