<?php
    // Inclure le fichier de connexion à la base de données
    require_once("../connection/connection.php");

    // Inclure l'en-tête de la page
    require_once("../header-footer/header.php");

    // Initialisation de la variable pour éviter l'erreur undefined
    $evenements = [];

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) 
    {
        $user_id = $_SESSION['user_id'];

        // Vérifier si l'utilisateur est admin et si le formulaire a été soumis en POST
        if (isset($_SESSION['admin']) && $_SESSION['admin'] && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_event'])) 
        {
            $nom_event = $_POST['nom_event'];
            $lieu_event = $_POST['lieu_event'];
            $date_event = $_POST['date_event'];
            $resultat_event = $_POST['description_event'];

            // Préparation de la requête d'insertion
            $sql = "INSERT INTO evènements (nom_event, lieu_event, date_event, description_event, User_user_id) VALUES (:nom_event, :lieu_event, :date_event, :description_event, :user_id)";
            $stmt = $pdo->prepare($sql);

            // Lier les paramètres
            $stmt->bindParam(':nom_event', $nom_event);
            $stmt->bindParam(':lieu_event', $lieu_event);
            $stmt->bindParam(':date_event', $date_event);
            $stmt->bindParam(':description_event', $resultat_event);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Exécution de la requête
            try 
            {
                $stmt->execute();
                echo "Événement enregistré avec succès.";
                // Redirection vers la même page pour éviter le rechargement du formulaire
                header('Location: event.php');
                exit(); // Assurez-vous d'appeler exit après une redirection
            } 
            catch (PDOException $e) 
            {
                echo "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }

        // Récupération des événements pour tous les utilisateurs connectés
        $sql1 = "SELECT * FROM evènements ORDER BY date_event DESC";
        $stmt = $pdo->prepare($sql1);
        $stmt->execute();
        $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    else 
    {
        // Redirigez l'utilisateur non connecté ou affichez un message
        echo "Veuillez vous connecter pour voir les événements.";
        header('Location: ../index/index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin']) : ?>
            <div class="new-event">
                <h1>Créer un nouvel événement</h1>
                <form action="event.php" method="post">
                    <div>
                        <label for="nom_event">Nom de l'événement:</label>
                        <input type="text" id="nom_event" name="nom_event" required>
                    </div>
                    <div>
                        <label for="lieu_event">Lieu:</label>
                        <input type="text" id="lieu_event" name="lieu_event" required>
                    </div>
                    <div>
                        <label for="date_event">Date:</label>
                        <input type="date" id="date_event" name="date_event" required>
                    </div>
                    <div>
                        <label for="description_event">Description:</label>
                        <input type="text" id="description_event" name="description_event">
                    </div>
                    <button type="submit" name="submit_event">Créer l'événement</button>
                </form>
            </div>
        <?php endif; ?>
        <div class="events-container">
            <?php foreach ($evenements as $evenement) : ?>
                <div class="event">
                    <h2><?= htmlspecialchars($evenement['nom_event']) ?> - <?= htmlspecialchars($evenement['date_event']) ?></h2>
                    <p>Lieu: <?= htmlspecialchars($evenement['lieu_event']) ?></p>
                    <p>Description: <?= htmlspecialchars($evenement['description_event']) ?></p>
                    <button onclick="location.href='../pictures/pictures.php?evenement_id=<?= $evenement['event_id'] ?>'">Voir photos de l'événement</button>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>

<?php
    require_once("../header-footer/footer.php");
?>