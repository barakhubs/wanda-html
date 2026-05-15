<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

/**
 * Thin wrapper around PHPMailer.
 * Reads SMTP configuration from the site_settings table automatically.
 */
class Mailer
{
    /**
     * Send an HTML email.
     *
     * @param string $toEmail   Recipient email address
     * @param string $toName    Recipient display name
     * @param string $subject   Email subject
     * @param string $htmlBody  HTML body
     * @param string $textBody  Plain-text fallback; auto-derived from HTML when empty
     * @return true|string      true on success, error message on failure
     */
    public static function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $textBody = ''
    ): true|string {
        // .env MAIL_* variables take precedence over DB settings.
        // Useful for local development (e.g. Mailtrap) while production
        // continues to use the SMTP configured in Settings → Email & SMTP.
        $envHost = $_ENV['MAIL_HOST'] ?? '';

        if ($envHost !== '') {
            $host       = $envHost;
            $port       = (int)($_ENV['MAIL_PORT']         ?? 587);
            $encryption = $_ENV['MAIL_ENCRYPTION']         ?? 'tls';
            $username   = $_ENV['MAIL_USERNAME']           ?? '';
            $password   = $_ENV['MAIL_PASSWORD']           ?? '';
            $fromEmail  = $_ENV['MAIL_FROM_ADDRESS']       ?? '';
            $fromName   = $_ENV['MAIL_FROM_NAME']          ?? 'Admin';
        } else {
            $s          = (new \App\Models\SiteSettings())->getAll();
            $host       = $s['smtp_host']       ?? '';
            $port       = (int)($s['smtp_port'] ?? 587);
            $encryption = $s['smtp_encryption'] ?? 'tls';
            $username   = $s['smtp_username']   ?? '';
            $password   = $s['smtp_password']   ?? '';
            $fromEmail  = $s['smtp_from_email'] ?? '';
            $fromName   = $s['smtp_from_name']  ?? ($s['site_title'] ?? 'Admin');
        }

        if ($host === '' || $fromEmail === '') {
            return 'SMTP is not configured. Please complete the Email & SMTP settings.';
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host     = $host;
            $mail->Port     = $port;
            $mail->SMTPAuth = $username !== '';
            $mail->Username = $username;
            $mail->Password = $password;

            if ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = '';
                $mail->SMTPAutoTLS = false;
            }

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName ?: $toEmail);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $textBody !== '' ? $textBody : strip_tags($htmlBody);

            $mail->send();
            return true;
        } catch (MailException $e) {
            error_log('Mailer::send() — ' . $mail->ErrorInfo);
            return $mail->ErrorInfo;
        }
    }
}
