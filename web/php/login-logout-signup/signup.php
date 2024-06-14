<?php
    include '../connection/connection.php';

    // Vérifier si la méthode de requête est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Récupérer les données du formulaire
        $name = $_POST['name'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $numtel = $_POST['numtel'];
        $birthdate = $_POST['birthdate'];
        $adresse = $_POST['adresse'];
        $niveau = $_POST['niveau'];
        $password = $_POST['password'];
        $confirm_password = $_POST['password-confirm'];
        $profile_picture_blob = null;

        // Vérifier si les mots de passe correspondent
        if ($password !== $confirm_password) 
        {
            echo "Les mots de passe ne correspondent pas.";
        } 
        else 
        {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Vérifier si la case "Participer aux tournois" est cochée
        $compete = isset($_POST['competitions']) ? 1 : 0;

        // Définition d'autres valeurs par défaut
        $member = 1;
        $admin = 0;

        // Gestion de l'image de profil (profile_picture);
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) 
        {
            // Récupération des informations sur le fichier
            $fileName = $_FILES['profile_picture']['name'];
            $tmpName  = $_FILES['profile_picture']['tmp_name'];
            $fileSize = $_FILES['profile_picture']['size'];
            $fileType = $_FILES['profile_picture']['type'];

            // Lecture du contenu du fichier dans une variable
            $fp      = fopen($tmpName, 'r');
            $profile_picture_blob = fread($fp, filesize($tmpName));
            fclose($fp);
        }

        // Gestion du certificat médical (medical_certificate);
        $medical_certificate_blob = null;

        if (isset($_FILES['medical_certificate']) && $_FILES['medical_certificate']['error'] == 0) 
        {
            // Vérification que le fichier est au format PDF
            $fileType = $_FILES['medical_certificate']['type'];
            if ($fileType != 'application/pdf') 
            {
                echo "Le fichier doit être au format PDF.";
                exit;
            }

            $tmpName  = $_FILES['medical_certificate']['tmp_name'];

            $fp = fopen($tmpName, 'r');
            $medical_certificate_blob = fread($fp, filesize($tmpName));
            fclose($fp);
        }

        // Création d'une instance de la date d'aujourd'hui
        $today = new DateTime();

        // Création d'une instance de la date de naissance à partir de la valeur du formulaire
        $birthdateObj = new DateTime($birthdate);

        // Vérification si la date de naissance est dans le futur
        if ($birthdateObj > $today) 
        {
            echo "La date de naissance ne peut pas être dans le futur.";
            exit;
        }

        // Calcul de l'âge en années
        $age = $today->diff($birthdateObj)->y;

        // Vérification si l'utilisateur a au moins 12 ans
        if ($age < 12) 
        {
            echo "Vous devez avoir au moins 12 ans pour vous inscrire.";
            exit;
        }

        // Vérification de l'unicité du numéro de téléphone dans la base de données
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE numtel = :numtel");
        $stmt->bindParam(':numtel', $numtel, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) 
        {
            echo "Ce numéro de téléphone est déjà utilisé.";
            exit;
        }

        // Requête SQL pour insérer les données dans la base de données
        $sql = "
            INSERT INTO user 
                (name, firstname, email, numtel, birthdate, adresse, password, competition, niveau, membre, admin, profile_picture, medical_certificate) 
            VALUES 
                (:name, :firstname, :email, :numtel, :birthdate, :adresse, :password, :competition, :niveau, :membre, :admin, :profile_picture, :medical_certificate)
            ";

        $stmt = $pdo->prepare($sql);

        // Liaison des paramètres, y compris les données BLOB pour les images
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':numtel', $numtel, PDO::PARAM_STR);
        $stmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':competition', $compete, PDO::PARAM_INT);
        $stmt->bindParam(':niveau', $niveau, PDO::PARAM_STR);
        $stmt->bindParam(':membre', $member, PDO::PARAM_INT);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
        $stmt->bindParam(':profile_picture', $profile_picture_blob, PDO::PARAM_LOB);
        $stmt->bindParam(':medical_certificate', $medical_certificate_blob, PDO::PARAM_LOB);

        try 
        {
            // Exécution de la requête d'insertion
            $stmt->execute();

            // Redirection vers une page de succès après l'inscription
            $_SESSION['success_message'] = 'Votre message a été envoyé avec succès.';
            header('Location: ../success/succes.php');
            exit();
        } 
        catch (PDOException $e) 
        {
            echo "Erreur : " . $e->getMessage();
        }
    }
?>
