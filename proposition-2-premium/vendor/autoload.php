<?php
/**
 * Autoloader PSR-4 pour PHPMailer
 * Généré manuellement — ne pas modifier
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'PHPMailer\\PHPMailer\\';
    $base   = __DIR__ . '/phpmailer/phpmailer/src/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $file = $base . substr($class, strlen($prefix)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
