<?php
/**
 * MAUNAIS MAINGARD — Formulaire de contact
 * Envoi via PHPMailer SMTP — config par variables d'environnement
 *
 * Phase test VPS  : MAIL_TO=max@mxl.digital (défini dans Coolify)
 * Phase prod IONOS: MAIL_TO=contact@maunais-maingard.fr
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

// ─── Configuration (variables d'environnement Coolify / IONOS) ───────────────
define('MAIL_TO',   getenv('MAIL_TO')    ?: 'max@mxl.digital');
define('SMTP_HOST', getenv('SMTP_HOST')  ?: 'smtp.gmail.com');
define('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));
define('SMTP_USER', getenv('SMTP_USER')  ?: '');
define('SMTP_PASS', getenv('SMTP_PASS')  ?: '');
define('SMTP_FROM', getenv('SMTP_FROM')  ?: getenv('SMTP_USER') ?: '');
define('HONEYPOT',  '_trap');
// ─────────────────────────────────────────────────────────────────────────────

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false]);
    exit;
}

// Honeypot anti-spam
if (!empty($_POST[HONEYPOT])) {
    echo json_encode(['success' => true]);
    exit;
}

// ─── Sanitize ────────────────────────────────────────────────────────────────
$nom     = trim(htmlspecialchars($_POST['nom']       ?? '', ENT_QUOTES, 'UTF-8'));
$prenom  = trim(htmlspecialchars($_POST['prenom']    ?? '', ENT_QUOTES, 'UTF-8'));
$tel     = trim(htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8'));
$email   = trim($_POST['email']   ?? '');
$service = trim(htmlspecialchars($_POST['service']   ?? '', ENT_QUOTES, 'UTF-8'));
$message = trim(htmlspecialchars($_POST['message']   ?? '', ENT_QUOTES, 'UTF-8'));

// ─── Validation ──────────────────────────────────────────────────────────────
if (empty($nom) || empty($prenom) || empty($tel)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => ['Champs obligatoires manquants.']]);
    exit;
}
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => ['Adresse email invalide.']]);
    exit;
}
if (strlen($message) > 2000) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => ['Message trop long.']]);
    exit;
}

// ─── Envoi PHPMailer SMTP ────────────────────────────────────────────────────
$nom_complet = $prenom . ' ' . $nom;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom(SMTP_FROM, 'Site MAUNAIS MAINGARD');
    $mail->addAddress(MAIL_TO, 'MAUNAIS MAINGARD');
    if (!empty($email)) {
        $mail->addReplyTo($email, $nom_complet);
    }

    $mail->Subject = '[Site web] Demande - ' . $nom_complet;
    $corps = "Nouvelle demande via le site web MAUNAIS MAINGARD\n";
    $corps .= str_repeat('-', 50) . "\n\n";
    $corps .= "Nom       : {$nom_complet}\n";
    $corps .= "Telephone : {$tel}\n";
    if (!empty($email)) $corps .= "Email     : {$email}\n";
    $corps .= "Service   : {$service}\n\n";
    $corps .= "Message :\n{$message}\n\n";
    $corps .= str_repeat('-', 50) . "\n";
    $corps .= "Envoye le " . date('d/m/Y a H:i') . " depuis le site web.\n";
    $mail->Body = $corps;

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Envoi impossible : ' . $mail->ErrorInfo]);
}
