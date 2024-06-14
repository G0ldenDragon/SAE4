<?php
    // Inclure le fichier de connexion à la base de données
    session_start();
    require_once("../connection/connection.php");

    // Vérifier si les données ont été soumises
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Récupérer et nettoyer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Préparer la requête pour obtenir l'utilisateur par email
        $sql = "SELECT user_id, password, competition, admin FROM user WHERE email = :email"; // Assurez-vous que le nom de la table et des colonnes sont corrects
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres à la déclaration
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            if (password_verify($password, $row['password'])) 
            {
                // Connexion réussie, stocker l'ID de l'utilisateur dans la session
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['competition'] = $row['competition'];
                $_SESSION['admin'] = $row['admin'];

                // Redirection vers la page d'accueil
                header("Location: ../index/index.php");
                exit;
            } 
            else 
            {
                // Mot de passe incorrect
                echo "Mot de passe invalide";
                exit;
            }
        } 
        else 
        {
            // Aucun utilisateur trouvé avec cet email
            echo "Aucun compte trouvé avec cet email";
            exit;
        }
    }
    else 
    {
        // Gérer le cas où la méthode n'est pas POST
        echo "Demande invalide";
        exit;
    }

    // Pas besoin de fermer le statement ou la connexion avec PDO
?>
