<?php
require_once '../includes/functions.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $smtpHost = sanitizeInput($_POST['smtp_host'] ?? '');
    $smtpPort = intval($_POST['smtp_port'] ?? 587);
    $smtpUsername = sanitizeInput($_POST['smtp_username'] ?? '');
    $smtpPassword = sanitizeInput($_POST['smtp_password'] ?? '');
    $smtpEncryption = sanitizeInput($_POST['smtp_encryption'] ?? 'tls');
    $contactEmail = sanitizeInput($_POST['contact_form_email'] ?? '');
    
    if (empty($smtpHost) || empty($smtpUsername) || empty($smtpPassword) || empty($contactEmail)) {
        throw new Exception('Please fill in all SMTP settings and contact email');
    }
    
    // Use PHPMailer if available, otherwise use basic mail with SMTP settings
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        // PHPMailer implementation
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->Port = $smtpPort;
        
        if ($smtpEncryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($smtpEncryption === 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }
        
        $mail->setFrom($smtpUsername, 'Portfolio Test');
        $mail->addAddress($contactEmail);
        $mail->Subject = 'SMTP Test Email - Portfolio System';
        $mail->Body = "This is a test email from your portfolio's SMTP configuration.\n\n";
        $mail->Body .= "If you received this email, your SMTP settings are working correctly!\n\n";
        $mail->Body .= "SMTP Settings Used:\n";
        $mail->Body .= "Host: $smtpHost\n";
        $mail->Body .= "Port: $smtpPort\n";
        $mail->Body .= "Encryption: $smtpEncryption\n";
        $mail->Body .= "Username: $smtpUsername\n\n";
        $mail->Body .= "Test sent at: " . date('Y-m-d H:i:s') . "\n";
        
        $mail->send();
        
    } else {
        // Basic PHP mail with ini_set for SMTP
        ini_set('SMTP', $smtpHost);
        ini_set('smtp_port', $smtpPort);
        ini_set('sendmail_from', $smtpUsername);
        
        $subject = 'SMTP Test Email - Portfolio System';
        $message = "This is a test email from your portfolio's SMTP configuration.\n\n";
        $message .= "If you received this email, your SMTP settings are working correctly!\n\n";
        $message .= "SMTP Settings Used:\n";
        $message .= "Host: $smtpHost\n";
        $message .= "Port: $smtpPort\n";
        $message .= "Encryption: $smtpEncryption\n";
        $message .= "Username: $smtpUsername\n\n";
        $message .= "Test sent at: " . date('Y-m-d H:i:s') . "\n";
        
        $headers = "From: $smtpUsername\r\n";
        $headers .= "Reply-To: $smtpUsername\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (!mail($contactEmail, $subject, $message, $headers)) {
            throw new Exception('Failed to send test email using PHP mail()');
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Test email sent successfully to ' . $contactEmail
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
