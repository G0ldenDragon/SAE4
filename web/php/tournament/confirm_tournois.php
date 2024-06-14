<?php
    // Inclure le fichier de connexion à la base de données
    

    // Vérifier si la requête est de type POST et si les variables POST nécessaires sont définies
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['User_user_id'], $_POST['Compétitions_compet_id'])) 
    {
        // Récupérer les valeurs des variables POST
        $user_id = $_POST['User_user_id'];
        $compet_id = $_POST['Compétitions_compet_id'];
        $action = $_POST['action'];

        // Si l'action est "accepter"
        if ($action === 'accept') 
        {
            // Mettre à jour l'inscription pour la marquer comme confirmée dans la table de liaison "user_has_compétitions"
            $stmt = $pdo->prepare("UPDATE user_has_compétitions SET confirmer = 1 WHERE User_user_id = :user_id AND Compétitions_compet_id = :compet_id");
            $stmt->execute(['user_id' => $user_id, 'compet_id' => $compet_id]);

            // Diminuer le nombre de places disponibles dans la compétition correspondante dans la table "compétitions"
            $updatePlaces = $pdo->prepare("UPDATE compétitions SET nb_personne = nb_personne - 1 WHERE compet_id = :compet_id AND nb_personne > 0");
            $updatePlaces->execute(['compet_id' => $compet_id]);

            // Définir un message de confirmation
            $message = "Inscription confirmée.";
        } 
        elseif ($action === 'refuse') 
        {
            // Si l'action est "refuser", supprimer l'inscription en mettant à jour la valeur "confirmer" à -1 dans la table de liaison "user_has_compétitions"
            $stmt = $pdo->prepare("UPDATE user_has_compétitions SET confirmer = -1 WHERE User_user_id = :user_id AND Compétitions_compet_id = :compet_id");
            $stmt->execute(['user_id' => $user_id, 'compet_id' => $compet_id]);

            // Définir un message de refus
            $message = "Inscription refusée.";
        }

        // Rediriger vers la page de gestion des tournois avec un message en tant que paramètre d'URL
        header("Location: gestion_tournois.php?message=" . urlencode($message));

        // Terminer l'exécution du script
        exit;
    }
?>
