<?php
session_start();
require '../connection/connection.php';

// Assurez-vous que l'utilisateur est autorisé à accéder à ce fichier
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || $_SESSION['admin'] != 1) {
    die("Accès refusé");
}

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Préparation de la requête pour récupérer le BLOB
$stmt = $pdo->prepare("SELECT medical_certificate FROM user WHERE user_id = :userid");
$stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
$stmt->execute();

$record = $stmt->fetch(PDO::FETCH_ASSOC);

if ($record && $record['medical_certificate']) {
    // Définition des en-têtes pour le téléchargement
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="certificat_medical_user_' . $userId . '.pdf"');
    header('Content-Length: ' . strlen($record['medical_certificate']));
    // Envoi du contenu du BLOB
    echo $record['medical_certificate'];
} else {
    // Gestion de l'erreur si le fichier n'est pas trouvé
    echo "Aucun certificat médical n'a été trouvé pour cet utilisateur, ou le fichier est corrompu.";
}
exit();
