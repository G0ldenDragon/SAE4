<?php
// Inclusion du fichier de connexion à la base de données (assurez-vous que cela récupère $pdo)
require '../connection/connection.php';

// Vérifier si l'ID de l'utilisateur est défini dans la requête GET et est un entier
if (isset($_GET['user_id']) && ctype_digit($_GET['user_id'])) {
    // Récupérer l'ID de l'utilisateur depuis la requête GET
    $userId = $_GET['user_id'];

    // Préparation de la requête pour récupérer l'image de profil de l'utilisateur
    $stmt = $pdo->prepare("SELECT profile_picture FROM user WHERE user_id = :userid");
    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Définir le type de contenu HTTP en tant qu'image PNG (ou image/jpeg selon le type d'image stocké)
        header("Content-Type: image/png");
        // Afficher l'image de profil
        echo $row['profile_picture'];
    } else {
        // Si l'utilisateur n'a pas d'image de profil, vous pouvez envoyer une image par défaut
        header("Content-Type: image/png");
        // Lire et afficher l'image par défaut depuis un fichier
        readfile('../../images/default_profile_picture.png');
    }
} else {
    // Gérer l'erreur ou rediriger si l'ID de l'utilisateur n'est pas valide
    header('HTTP/1.0 404 Not Found'); // Envoyer un en-tête HTTP 404
    echo "Image non trouvée"; // Afficher un message d'erreur
}
?>