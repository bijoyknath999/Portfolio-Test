<?php
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Sanitize input data
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        throw new Exception('All fields are required');
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Prepare data for database
    $contactData = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    
    // Save to database
    if (saveContactMessage($contactData)) {
        // Send email notification (only if SMTP is enabled and configured)
        $adminEmail = getSiteSetting('contact_form_email');
        $smtpEnabled = getSiteSetting('smtp_enabled') === 'true';
        
        if ($adminEmail && $smtpEnabled) {
            try {
                $smtpHost = getSiteSetting('smtp_host');
                $smtpPort = getSiteSetting('smtp_port') ?: '587';
                $smtpUsername = getSiteSetting('smtp_username');
                $smtpPassword = getSiteSetting('smtp_password');
                $smtpEncryption = getSiteSetting('smtp_encryption') ?: 'tls';
                
                if ($smtpHost && $smtpUsername && $smtpPassword) {
                    // Configure SMTP settings
                    ini_set('SMTP', $smtpHost);
                    ini_set('smtp_port', $smtpPort);
                    ini_set('sendmail_from', $smtpUsername);
                    
                    $emailSubject = "New Contact Form Message: " . $subject;
                    $emailBody = "You have received a new message from your portfolio contact form.\n\n";
                    $emailBody .= "Name: $name\n";
                    $emailBody .= "Email: $email\n";
                    $emailBody .= "Subject: $subject\n\n";
                    $emailBody .= "Message:\n$message\n\n";
                    $emailBody .= "---\n";
                    $emailBody .= "IP Address: " . $contactData['ip_address'] . "\n";
                    $emailBody .= "User Agent: " . $contactData['user_agent'] . "\n";
                    $emailBody .= "Sent at: " . date('Y-m-d H:i:s') . "\n";
                    
                    $headers = "From: $smtpUsername\r\n";
                    $headers .= "Reply-To: $email\r\n";
                    $headers .= "X-Mailer: Portfolio Contact Form\r\n";
                    
                    // Send email with SMTP settings
                    @mail($adminEmail, $emailSubject, $emailBody, $headers);
                }
            } catch (Exception $e) {
                // Email failed but message is still saved - continue silently
                error_log("Email notification failed: " . $e->getMessage());
            }
        }
        
        $successMessage = getSiteSetting('contact_success_message') ?: 'Message sent successfully! I\'ll get back to you soon.';
        echo json_encode([
            'success' => true, 
            'message' => $successMessage
        ]);
    } else {
        throw new Exception('Failed to save message');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    $errorMessage = getSiteSetting('contact_error_message') ?: 'Failed to send message. Please try again.';
    echo json_encode([
        'success' => false, 
        'message' => $errorMessage
    ]);
}
?>
