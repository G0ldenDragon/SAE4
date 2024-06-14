<?php
    // pour gérer les sessions utilisateur

    // Inclure le fichier de connexion à la base de données
    require_once("../connection/connection.php");

    // Inclure le fichier d'en-tête du site
    require_once("../header-footer/header.php");

    // Récupérer l'ID de l'utilisateur à partir de la requête GET (ou 0 par défaut);
    $userId = $_GET['user_id'] ?? 0;

    // Vérifier si l'utilisateur est connecté et est administrateur
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['admin'] !== 1) 
    {
        echo "Vous n'avez pas l'autorisation d'accéder à cette page.";
        exit;
    }

    // Récupérer les informations de l'utilisateur en fonction de l'ID
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = :userid");
    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if (!$user) 
    {
        echo "Utilisateur introuvable.";
        exit;
    }

    // Traitement de la soumission du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Mise à jour ou suppression
        if (isset($_POST['update'])) 
        {
            // Récupération des nouvelles valeurs du formulaire
            $email = $_POST['email'] ?? $user['email'];
            $name = $_POST['name'] ?? $user['name'];
            $firstname = $_POST['firstname'] ?? $user['firstname'];
            $niveau = $_POST['niveau'] ?? $user['niveau'];
            $birthdate = $_POST['birthdate'] ?? $user['birthdate'];
            
            // Préparation de la requête de mise à jour des informations de l'utilisateur
            $updateStmt = $pdo->prepare("UPDATE user SET email = :email, name = :name, firstname = :firstname, birthdate = :birthdate, niveau = :niveau WHERE user_id = :userid");
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':name', $name);
            $updateStmt->bindParam(':firstname', $firstname);
            $updateStmt->bindParam(':birthdate', $birthdate);
            $updateStmt->bindParam(':niveau', $niveau);
            $updateStmt->bindParam(':userid', $userId, PDO::PARAM_INT);
            
            // Exécution de la requête de mise à jour
            $updateStmt->execute();
            
            // Recharger les informations de l'utilisateur après la mise à jour
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "Informations mises à jour avec succès.";

        } 
        elseif (isset($_POST['delete'])) 
        {
            // Supprimer l'utilisateur
            $deleteStmt = $pdo->prepare("DELETE FROM user WHERE user_id = :userid");
            $deleteStmt->bindParam(':userid', $userId, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Rediriger vers une autre page après la suppression
            header('Location: gestion_ad.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<html>
    <head>
        <meta charset="UTF-8">
        <title>Détails du Membre</title>
    </head>

    <body>
        <div class="container">
            <h1>Détails du Membre</h1>
            <div class="profile-section">
                <img src="user_image.php?user_id=<?= $userId; ?>" alt="Image de Profil" class="profile-picture"/>
            </div>
            
            <div class="info-section">
                <form method="post">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Nom:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Prénom:</label>
                        <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Date de naissance:</label>
                        <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Niveau:</label>
                        <input type="text" name="niveau" value="<?= htmlspecialchars($user['niveau']); ?>">
                    </div>
                    <div class="form-actions">
                        <input type="submit" name="update" value="Mettre à jour">
                        <input type="submit" name="delete" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    </div>
                </form>
                <a href="download_medical_certificate.php?user_id=<?= $userId; ?>" class="download-button">Télécharger le Certificat Médical</a>
            </div>
        </div>
    </body>
</html>
