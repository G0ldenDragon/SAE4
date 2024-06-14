<?php
    require_once("../header-footer/header.php");
     // Ce fichier doit retourner l'objet PDO $pdo

    // Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) 
    {
        echo "utlisateur pas connecté";
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Préparer la requête PDO
    if ($stmt = $pdo->prepare("SELECT profile_picture, email, name, firstname, birthdate, adresse, numtel, niveau FROM user WHERE user_id = :userid")) 
    {
        // Lier le paramètre ":userid" à la variable $userId
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats dans des variables
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $profilePictureData = $row['profile_picture']; // Assurez-vous que le nom de colonne correspond à celui de la BDD
            $email = $row['email'];
            $name = $row['name'];
            $firstname = $row['firstname'];
            $birthdate = $row['birthdate'];
            $adresse = $row['adresse'];
            $numtel = $row['numtel'];
            $niveau = $row['niveau'];
        } 
        else 
        {
            echo "Aucune information disponible pour l'utilisateur.";
        }
    } 
    else 
    {
        echo "Erreur : " . $pdo->errorInfo(); // Affiche une erreur s'il y a un problème avec la requête
    }
?>

<!DOCTYPE html>
<html lang="fr">
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mon Compte</title>
    </head>

    <body>
        <div class="container">
            <h1>Mon Compte</h1>

            <div class="profile-info">
                <img src="../user-management/user_image.php?user_id=<?= $userId; ?>" alt="Profil Image" class="profile-picture" />
                <p><b>Email:</b>
                    <?php echo htmlspecialchars($email ?? ''); ?>
                </p>
                <p><b>Nom:</b>
                    <?php echo htmlspecialchars($name ?? ''); ?>
                </p>
                <p><b>Prénom:</b>
                    <?php echo htmlspecialchars($firstname ?? ''); ?>
                </p>
                <p><b>Date de naissance:</b>
                    <?php echo htmlspecialchars($birthdate ?? ''); ?>
                </p>
                <p><b>Adresse:</b>
                    <?php echo htmlspecialchars($adresse ?? ''); ?>
                </p>
                <p><b>Numéro de téléphone:</b> 
                    <?php echo htmlspecialchars($numtel ?? ''); ?>
                </p>
                <p><b>Niveau:</b>
                    <?php echo htmlspecialchars($niveau ?? ''); ?>
                </p>
            </div>

            <a href="../login-logout-signup/logout.php" class="logout-button">Déconnexion</a>
        </div>
    </body>
</html>

<?php
    require_once("../header-footer/footer.php");
?>