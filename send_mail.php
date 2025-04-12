<?php
// Debug modu kapalı
error_reporting(0);
ini_set('display_errors', 0);

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Sadece POST isteklerine izin ver
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit(json_encode(['status' => 'error', 'message' => 'Geçersiz istek']));
}

// JSON header
header('Content-Type: application/json');

try {
    // Form verilerini al
    $name = strip_tags($_POST['name'] ?? '');
    $email = strip_tags($_POST['email'] ?? '');
    $subject = strip_tags($_POST['subject'] ?? '');
    $message = strip_tags($_POST['message'] ?? '');

    $mail = new PHPMailer(true);
    
    // Debug modu kapalı
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'mail.webcenter.com.tr';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@webcenter.com.tr';
    $mail->Password = 'Wc12345678.,.';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // SSL ayarları
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Mail ayarları
    $mail->setFrom('info@webcenter.com.tr', 'Web Center');
    $mail->addAddress('info@webcenter.com.tr');
    $mail->Subject = "İletişim Formu: $subject";
    $mail->isHTML(true);
    $mail->Body = "
        <h3>İletişim Formu Mesajı</h3>
        <p><strong>İsim:</strong> {$name}</p>
        <p><strong>E-posta:</strong> {$email}</p>
        <p><strong>Konu:</strong> {$subject}</p>
        <p><strong>Mesaj:</strong></p>
        <p>{$message}</p>
    ";

    // Gönder
    if($mail->send()) {
        echo json_encode([
            'status' => 'success',
            'title' => 'Başarılı!',
            'message' => 'Mesajınız başarıyla gönderildi.',
            'icon' => 'success'
        ]);
        exit;
    } else {
        throw new Exception("Mail gönderilemedi: " . $mail->ErrorInfo);
    }

} catch (Exception $e) {
    error_log("Mail Hatası: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'title' => 'Hata!',
        'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.',
        'icon' => 'error'
    ]);
    exit;
}