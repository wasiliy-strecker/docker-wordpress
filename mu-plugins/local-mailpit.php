<?php

/**
 * Plugin Name: Local Mailpit Transport
 * Description: Routes WordPress email to the Mailpit container in this local stack.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('phpmailer_init', static function (PHPMailer\PHPMailer\PHPMailer $mailer): void {
    $mailer->isSMTP();
    $mailer->Host = getenv('WORDPRESS_SMTP_HOST') ?: 'mailpit';
    $mailer->Port = (int) (getenv('WORDPRESS_SMTP_PORT') ?: 1025);
    $mailer->SMTPAuth = false;
    $mailer->SMTPSecure = '';
    $mailer->SMTPAutoTLS = false;
});
