<?php
/**
 * MAUNAIS MAINGARD — Formulaire de contact
 *
 * Phase test  : EMAIL_DESTINATION = max@mxl.digital
 * Phase prod  : Remplacer par contact@maunais-maingard.fr
 *
 * Compatible IONOS (Apache + PHP 7.4+)
 */

// ─── Configuration ───────────────────────────────────────────────────────────
const EMAIL_DESTINATION = 'max@mxl.digital';      // ← changer en prod
const EMAIL_FROM        = 'noreply@maunais-maingard.fr';
const EMAIL_SUBJECT     = '[Site web] Nouvelle demande de contact';
const HONEYPOT_FIELD    = '_trap';
// ─────────────────────────────────────────────────────────────────────────────

header('Content-Type: application/json; charset=utf-8');

// Uniquement POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

// Vérification honeypot anti-spam
if (!empty($_POST[HONEYPOT_FIELD])) {
    // Bot détecté — répondre OK pour ne pas alerter le bot
    echo json_encode(['success' => true]);
    exit;
}

// ─── Lecture et sanitization des inputs ──────────────────────────────────────
$nom      = trim(htmlspecialchars($_POST['nom']      ?? '', ENT_QUOTES, 'UTF-8'));
$prenom   = trim(htmlspecialchars($_POST['prenom']   ?? '', ENT_QUOTES, 'UTF-8'));
$tel      = trim(htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8'));
$email    = trim($_POST['email']   ?? '');
$service  = trim(htmlspecialchars($_POST['service']  ?? '', ENT_QUOTES, 'UTF-8'));
$message  = trim(htmlspecialchars($_POST['message']  ?? '', ENT_QUOTES, 'UTF-8'));

// ─── Validations ─────────────────────────────────────────────────────────────
$errors = [];

if (empty($nom))    $errors[] = 'Le nom est requis.';
if (empty($prenom)) $errors[] = 'Le prénom est requis.';
if (empty($tel))    $errors[] = 'Le téléphone est requis.';

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Adresse email invalide.';
}

if (strlen($message) > 2000) {
    $errors[] = 'Message trop long (2000 caractères max).';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// ─── Composition de l'email ──────────────────────────────────────────────────
$reply_to = !empty($email) ? $email : EMAIL_FROM;
$nom_complet = $prenom . ' ' . $nom;

$corps = "Nouvelle demande via le site web MAUNAIS MAINGARD\n";
$corps .= str_repeat('─', 50) . "\n\n";
$corps .= "Nom       : {$nom_complet}\n";
$corps .= "Téléphone : {$tel}\n";
if (!empty($email)) {
    $corps .= "Email     : {$email}\n";
}
$corps .= "Service   : {$service}\n\n";
$corps .= "Message :\n{$message}\n\n";
$corps .= str_repeat('─', 50) . "\n";
$corps .= "Envoyé le " . date('d/m/Y à H:i') . " depuis le site web.\n";

$headers  = "From: " . EMAIL_FROM . "\r\n";
$headers .= "Reply-To: {$reply_to}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// ─── Envoi ───────────────────────────────────────────────────────────────────
$sujet_encode = '=?UTF-8?B?' . base64_encode(EMAIL_SUBJECT . ' — ' . $nom_complet) . '?=';

$envoye = mail(
    EMAIL_DESTINATION,
    $sujet_encode,
    $corps,
    $headers
);

if ($envoye) {
    echo json_encode(['success' => true, 'message' => 'Votre demande a bien été envoyée.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => "L'envoi a échoué. Veuillez nous appeler directement."]);
}
