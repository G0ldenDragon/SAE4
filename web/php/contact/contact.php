<?php
    // Inclure le fichier de connexion à la base de données
    

    // Inclure l'en-tête de la page
    include_once("../header-footer/header.php");

    // Vérifier si le formulaire a été soumis (méthode POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Récupérer les données du formulaire
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $telephone = $_POST["telephone"];
        $sujet = $_POST["sujet"];
        $message = $_POST["message"];

        // Préparation de la requête SQL pour l'insertion des données
        $sql = "INSERT INTO messages (nom, email, telephone, sujet, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Exécution de la requête en liant les vraies valeurs aux paramètres
        $stmt->execute([$nom, $email, $telephone, $sujet, $message]);

        // Message de succès enregistré dans la session
        $_SESSION['success_message'] = 'Votre message a été envoyé avec succès.';
        header('Location: ../success/succes.php'); // Redirection vers la page de succès
        exit(); // Arrêter le script

        // Vérifier si un message de succès est présent dans la session
        if (isset($_SESSION['success_message'])) 
        {
            echo "<p>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']); // Supprimer le message de succès de la session
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Contactez-nous</title>
    </head>

    <body>
        <div class="form-container">
            <h1>Contactez-nous</h1>
            <form action="contact.php" method="post">
                <div class="form-field">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-field">
                    <label for="telephone">Téléphone:</label>
                    <input type="text" id="telephone" name="telephone">
                </div>
                <div class="form-field">
                    <label for="sujet">Sujet:</label>
                    <select id="sujet" name="sujet">
                        <option value="demande_information">Demande d'informations</option>
                        <option value="Récupération des données">Récupérer les données</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="message">Votre message:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <div class="form-field">
                    <button type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </body>
</html>

<?php
    include_once("../header-footer/footer.php");
?>