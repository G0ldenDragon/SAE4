<?php
    //  pour gérer les sessions utilisateur

    // Inclure le fichier d'en-tête du site
    require_once("../header-footer/header.php");

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) 
    {
        // Récupérer l'ID de l'utilisateur à partir de la session
        $user_id = $_SESSION['user_id'];

        // Préparer la requête SQL pour vérifier si l'utilisateur est un administrateur
        $stmt = $pdo->prepare("SELECT admin FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur est un administrateur (1 signifie administrateur dans la base de données)
        $isAdmin = $user && $user['admin'] == 1;
    } 
    else 
    {
        // L'utilisateur n'est pas connecté ou la session n'est pas définie
        $isAdmin = false;
    }

    // Gestion de la soumission du formulaire de création de compétition
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_competition'])) 
    {
        // Récupérer les données du formulaire
        $nom_compet = $_POST['nom_compet'];
        $lieu_compet = $_POST['lieu_compet'];
        $date_compet = $_POST['date_compet'];
        $heure_compet = $_POST['heure_compet'];
        $niveau_compet = $_POST['niveau_compet'];
        $age_requis = $_POST['age_requis'];
        $nb_personne = $_POST['nb_personne'];

        // Préparation de la requête SQL pour insérer une nouvelle compétition dans la base de données
        $sql = "INSERT INTO compétitions (nom_compet, lieu_compet, date_compet, heure_compet, niveau_compet, age_requis, nb_personne) VALUES (:nom_compet, :lieu_compet, :date_compet, :heure_compet, :niveau_compet, :age_requis, :nb_personne)";

        $stmt = $pdo->prepare($sql);

        // Liaison des paramètres pour éviter les injections SQL
        $stmt->bindParam(':nom_compet', $nom_compet);
        $stmt->bindParam(':lieu_compet', $lieu_compet);
        $stmt->bindParam(':date_compet', $date_compet);
        $stmt->bindParam(':heure_compet', $heure_compet);
        $stmt->bindParam(':niveau_compet', $niveau_compet);
        $stmt->bindParam(':age_requis', $age_requis, PDO::PARAM_INT);
        $stmt->bindParam(':nb_personne', $nb_personne, PDO::PARAM_INT);

        // Exécution de la requête d'insertion
        try 
        {
            $stmt->execute();
            echo "Compétition enregistrée avec succès.";
        } 
        catch (PDOException $e) 
        {
            echo "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }

    // Récupérer l'ID de l'utilisateur à partir de la session (ou 0 si non défini)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    // Requête SQL pour récupérer les compétitions et leurs détails
    $sql = "
        SELECT 
            c.*,
            uhc.User_user_id AS is_participating,
            uhc.confirmer AS status
        FROM 
            compétitions c
        LEFT JOIN 
            user_has_compétitions uhc ON c.compet_id = uhc.Compétitions_compet_id AND uhc.User_user_id = :user_id
        WHERE 
            uhc.User_user_id IS NULL OR uhc.confirmer >= 0
        ORDER BY 
            c.date_compet ASC
    ";

    // Fonction pour obtenir les détails des courses d'une compétition
    function getCourseDetails($pdo, $competId)
    {
        try 
        {
            $sqlCourses = "
                SELECT 
                    cr.nom_piste,
                    cr.lieu_piste,
                    cr.horaire,
                    cr.gagnant,
                    cr.laptime
                FROM 
                    compétitions_has_courses c 
                JOIN 
                    courses cr ON c.course_id = cr.course_id 
                WHERE 
                    c.compet_id = :compet_id
                ORDER BY 
                    cr.horaire ASC
            ";

            $stmt = $pdo->prepare($sqlCourses);
            $stmt->bindParam(':compet_id', $competId, PDO::PARAM_INT);
            $stmt->execute();

            // Récupérer et retourner les détails des courses
            $courseDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courseDetails;
        } 
        catch (PDOException $e) 
        {
            echo "Erreur : " . $e->getMessage();
            return array(); // Retourner un tableau vide en cas d'erreur
        }
    }

    // Exécution de la requête SQL pour récupérer les compétitions
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <?php if ($isAdmin) : ?>
            <div class="new-tournois">
                <form action="tournois.php" method="post" class="competition-form">
                    <h1>Saisir un nouveau tournoi</h1>
                    <div>
                        <label for="nom_compet">Nom:</label>
                        <input type="text" id="nom_compet" name="nom_compet" required>
                    </div>
                    <div>
                        <label for="lieu_compet">Lieu:</label>
                        <input type="text" id="lieu_compet" name="lieu_compet" required>
                    </div>
                    <div>
                        <label for="date_compet">Date:</label>
                        <input type="date" id="date_compet" name="date_compet" required>
                    </div>
                    <div>
                        <label for="heure_compet">Heure:</label>
                        <input type="time" id="heure_compet" name="heure_compet" required>
                    </div>
                    <div>
                        <label for="niveau_compet">Niveau requis:</label>
                        <select class="niveau" name="niveau_compet">
                            <option>Choisir un niveau</option>
                            <option>Débutant(e)</option>
                            <option>Intermédiaire</option>
                            <option>Avancé(e)</option>
                            <option>Expert(e)</option>
                            <option>Tout Niveaux</option>
                        </select>
                    </div>
                    <div>
                        <label for="age_requis">Âge requis:</label>
                        <input type="number" id="age_requis" name="age_requis" required>
                    </div>
                    <div>
                        <label for="nb_personne">Nombre de personnes requises:</label>
                        <input type="number" id="nb_personne" name="nb_personne" required>
                    </div>
                    <button type="submit" name="submit_competition">Enregistrer le tournoi</button>
                </form>
            <?php endif; ?>
            </div>
            <div class="container">
                <h1>Liste des Tournois</h1>
                <?php foreach ($competitions as $competition) : ?>
                    <div class="competition-item">
                        <h2>
                            <?= htmlspecialchars($competition['nom_compet']) ?>
                        </h2>
                        <p>Lieu:
                            <?= htmlspecialchars($competition['lieu_compet']) ?>
                        </p>
                        <p>Date:
                            <?= htmlspecialchars($competition['date_compet']) ?>
                        </p>
                        <p>Heure:
                            <?= htmlspecialchars($competition['heure_compet']) ?>
                        </p>
                        <p>Niveau requis:
                            <?= htmlspecialchars($competition['niveau_compet']) ?>
                        </p>
                        <p>Âge requis:
                            <?= htmlspecialchars($competition['age_requis']) ?> ans
                        </p>
                        <p>Nombre de places:
                            <?= htmlspecialchars($competition['nb_personne']) ?> places
                        </p>
                        <?php if ($competition['is_participating'] && $competition['status'] === 1) : ?>
                            <span class='toggle-details' onclick="toggleDetails(this, <?= $competition['compet_id']; ?>);">&#9660;</span>
                            <?php $courseDetails = getCourseDetails($pdo, $competition['compet_id']); ?>
                            <div id="details-<?= $competition['compet_id']; ?>" class="tournament-details" style="display: none;">
                                <h3>Différentes courses du tournoi:</h3>
                                <table>
                                    <tr>
                                        <th>Nom </th>
                                        <th>Lieu </th>
                                        <th>Horaire</th>
                                        <th>Vainqueur</th>
                                        <th>Meilleur Temps</th>
                                    </tr>
                                    <?php foreach ($courseDetails as $courseDetail) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($courseDetail['nom_piste']); ?></td>
                                            <td><?= htmlspecialchars($courseDetail['lieu_piste']); ?></td>
                                            <td><?= htmlspecialchars($courseDetail['horaire']); ?></td>
                                            <td><?= $courseDetail['gagnant'] !== null ? htmlspecialchars($courseDetail['gagnant']) : 'Pas encore défini'; ?></td>
                                            <td><?= $courseDetail['laptime'] !== null ? htmlspecialchars($courseDetail['laptime']) : 'Pas encore défini'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php elseif ($competition['is_participating'] && $competition['status'] === 0) : ?>
                            <div class="wait-confirm">
                                <p>En attente de confirmation</p>
                            </div>
                        <?php elseif ($competition['nb_personne'] > 0) : ?>
                            <form action="demande_tournois.php" method="post" class="participation-form">
                                <input type="hidden" name="compet_id" value="<?php echo $competition['compet_id']; ?>">
                                <button type="submit" name="participer" class="participate-button" id="participate-button-<?= $competition['compet_id']; ?>">Participer</button>
                            </form>
                        <?php else : ?>
                            <div class="is-full">
                                <p>Tournois complet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <script>
                function toggleDetails(toggleElement, competId) {
                    var detailsDiv = document.getElementById('details-' + competId);
                    detailsDiv.style.display = detailsDiv.style.display === 'none' ? 'block' : 'none';
                    toggleElement.innerHTML = detailsDiv.style.display === 'none' ? '&#9660;' : '&#9650;'; // Flèche vers le haut
                }
            </script>
    </body>
</html>

<?php
    require_once("../header-footer/footer.php");
?>