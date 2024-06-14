<?php
// Informations de connexion à la base de données
$servername = "localhost";   // Adresse du serveur MySQL
$username = "root";          // Nom d'utilisateur de la base de données
$password = "";              // Mot de passe de la base de données
$dbname = "sae_karting";     // Nom de la base de données
$charset = "utf8mb4";        // Jeu de caractères utilisé

// Options de configuration PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Activer la gestion des erreurs PDO
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Mode de récupération par défaut
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Désactiver la préparation émulée
];

// Chaîne de connexion DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

try {
    // Créer une instance de PDO et se connecter à la base de données
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // En cas d'erreur de connexion, générer une exception PDO
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
