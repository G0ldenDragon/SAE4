<?php
    // Inclure le fichier de connexion à la base de données
    

    // Vérifier si la requête est de type POST et si la variable POST 'participer' est définie
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['participer'])) 
    {
        // Récupérer l'identifiant de la compétition à laquelle l'utilisateur souhaite s'inscrire
        $compet_id = $_POST['compet_id'];
        
        // Récupérer l'identifiant de l'utilisateur à partir de la session (assurez-vous qu'il est connecté);
        $user_id = $_SESSION['user_id'];

        // Vérifier si l'utilisateur n'est pas déjà inscrit à cette compétition
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_has_compétitions WHERE User_user_id = :user_id AND Compétitions_compet_id = :compet_id");
        $stmt->execute(['user_id' => $user_id, 'compet_id' => $compet_id]);
        $alreadyRegistered = $stmt->fetchColumn() > 0;

        if (!$alreadyRegistered) 
        {
            // Si l'utilisateur n'est pas déjà inscrit, insérer une nouvelle inscription en attente de confirmation
            $stmt = $pdo->prepare("INSERT INTO user_has_compétitions (User_user_id, Compétitions_compet_id, confirmer) VALUES (:user_id, :compet_id, 0)");
            $stmt->execute(['user_id' => $user_id, 'compet_id' => $compet_id]);

            // Définir un message de succès
            $_SESSION['inscription_message'] = "Demande d'inscription envoyée. En attente de confirmation.";
        } 
        else 
        {
            // Si l'utilisateur est déjà inscrit à cette compétition, définir un message d'erreur
            $_SESSION['inscription_message'] = "Vous êtes déjà inscrit à ce tournoi.";
        }

        // Rediriger vers la page 'tournois.php' (liste des tournois) après le traitement
        header('Location: tournois.php');
        
        // Terminer l'exécution du script
        exit;
    }
?>
