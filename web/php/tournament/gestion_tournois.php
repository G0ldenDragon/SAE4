<?php

    // Inclure le fichier d'en-tête
    include_once("../header-footer/header.php");

    // Vérifier si la requête est de type POST et si l'action 'delete_participant' est définie dans la variable POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_participant' && isset($_POST['selected_participant'], $_POST['selected_tournament'])) 
    {
        // Récupérer l'identifiant de l'utilisateur et de la compétition à partir de la variable POST
        $user_id = $_POST['selected_participant'];
        $compet_id = $_POST['selected_tournament'];

        // Vérifier que les identifiants sont numériques
        if (is_numeric($user_id) && is_numeric($compet_id)) 
        {
            // Préparer la requête SQL pour marquer l'inscription comme refusée (-1);
            $sql = "UPDATE user_has_compétitions SET confirmer = -1 WHERE User_user_id = :user_id AND Compétitions_compet_id = :compet_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':compet_id', $compet_id, PDO::PARAM_INT);

            try 
            {
                // Exécuter la requête SQL
                $stmt->execute();
                $message = "Participant supprimé avec succès.";
            } 
            catch (PDOException $e) 
            {
                $message = "Erreur lors de la suppression du participant : " . $e->getMessage();
            }
        } 
        else 
        {
            $message = "Erreur : ID de tournoi ou de participant invalide.";
        }
    }


    // --------------------------------------------------------
    // Requête SQL pour récupérer les inscriptions en attente de confirmation
    $sql = "
    SELECT 
        uhc.User_user_id, 
        uhc.Compétitions_compet_id, 
        uhc.confirmer, 
        u.name AS user_nom, 
        u.firstname AS user_prenom,
        u.birthdate,
        u.niveau,
        c.nom_compet,
        c.age_requis,
        c.niveau_compet 
    FROM 
        user_has_compétitions uhc
    JOIN 
        user u ON uhc.User_user_id = u.user_id
    JOIN 
        compétitions c ON uhc.Compétitions_compet_id = c.compet_id
    WHERE 
        uhc.confirmer = 0
    ";
    $inscriptions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


    // --------------------------------------------------------
    // Requête SQL pour récupérer les tournois en cours
    $sqlTournois = "
    SELECT 
        c.compet_id,
        c.nom_compet,
        c.lieu_compet,
        c.date_compet,
        c.heure_compet,
        u.user_id,
        u.name AS user_nom,
        u.firstname AS user_prenom,
        uhc.confirmer
    FROM 
        compétitions c
    LEFT JOIN 
        user_has_compétitions uhc ON c.compet_id = uhc.Compétitions_compet_id AND uhc.confirmer != -1
    LEFT JOIN 
        user u ON uhc.User_user_id = u.user_id
    WHERE 
        c.date_compet >= CURDATE()
    ORDER BY 
        c.date_compet, c.heure_compet
    ";
    $rawTournois = $pdo->query($sqlTournois)->fetchAll(PDO::FETCH_ASSOC);


    // --------------------------------------------------------
    // Préparation du tableau des tournois avec les participants
    $tournois = null;
    foreach ($rawTournois as $row) 
    {
        $tournoiId = $row['compet_id'];

        if (!isset($tournois[$tournoiId])) 
        {
            $tournois[$tournoiId] = [
                'nom_compet' => $row['nom_compet'],
                'date_compet' => $row['date_compet'],
                'participants' => []
            ];
        }

        // Ajoutez les participants si confirmer n'est pas égal à -1
        if ($row['user_id'] && $row['confirmer'] != -1) 
        {
            $tournois[$tournoiId]['participants'][$row['user_id']] = $row['user_nom'] . ' ' . $row['user_prenom'];
        }
    }


    // --------------------------------------------------------
    // Requête SQL pour récupérer la liste des tournois
    $tournamentsQuery = "SELECT compet_id, nom_compet FROM compétitions ORDER BY date_compet ASC";
    $tournaments = $pdo->query($tournamentsQuery)->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si la requête est de type POST et si l'identifiant du tournoi sélectionné est disponible
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_tournament'])) 
    {
        // L'ID du tournoi sélectionné est disponible
        $selectedTournamentId = $_POST['selected_tournament'];

        // Récupérez les participants du tournoi sélectionné
        $participantsQuery = "
            SELECT u.user_id, u.name AS user_nom, u.firstname AS user_prenom
            FROM user_has_compétitions uhc
            JOIN user u ON uhc.User_user_id = u.user_id
            WHERE uhc.Compétitions_compet_id = :tournamentId AND uhc.confirmer = 1
        ";
        $stmt = $pdo->prepare($participantsQuery);
        $stmt->bindParam(':tournamentId', $selectedTournamentId, PDO::PARAM_INT);
        $stmt->execute();
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // --------------------------------------------------------
    // Vérifier si la requête est de type POST et si l'action 'update_places' est définie dans la variable POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_places' && isset($_POST['update_tournament'], $_POST['new_places'])) 
    {
        // Récupérer l'identifiant du tournoi à mettre à jour et le nouveau nombre de places
        $tournament_id = $_POST['update_tournament'];
        $new_places = $_POST['new_places'];

        // Vérifier que les identifiants sont numériques
        if (is_numeric($tournament_id) && is_numeric($new_places)) 
        {
            // Mettre à jour le nombre de places dans la table 'compétitions'
            $sqlUpdatePlaces = "UPDATE compétitions SET nb_personne = :new_places WHERE compet_id = :tournament_id";
            $stmtUpdatePlaces = $pdo->prepare($sqlUpdatePlaces);
            $stmtUpdatePlaces->bindParam(':new_places', $new_places, PDO::PARAM_INT);
            $stmtUpdatePlaces->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);

            try 
            {
                // Exécuter la requête SQL
                $stmtUpdatePlaces->execute();
                $message = "Le nombre de places a été mis à jour avec succès.";
            } 
            catch (PDOException $e) 
            {
                $message = "Erreur lors de la mise à jour du nombre de places : " . $e->getMessage();
            }
        } 
        else 
        {
            $message = "Erreur : ID de tournoi ou nombre de places invalide.";
        }

        // Redirection vers la page 'gestion_tournois.php' avec un message
        header("Location: gestion_tournois.php?message=" . urlencode($message));
        exit;
    }


    // --------------------------------------------------------
    // Fonction pour calculer l'âge à partir de la date de naissance
    function calculateAge($birthdate) 
    {
        $birthdate = new DateTime($birthdate);
        $today = new DateTime('today');
        return $birthdate->diff($today)->y;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestion des tournois</title>
    </head>

    <body>
        <div class="tournois-list">
            <h2>Tournois en cours</h2>

            <?php foreach ($tournois as $tournoiId => $tournoi) : ?>

                <div class="tournois-item">
                    <div class="tournois-header">
                        <h3>
                            <?= htmlspecialchars($tournoi['nom_compet']); ?>
                        </h3>
                        <span class="toggle-details">&#9660;</span>
                    </div>
                    <div class="tournois-details" style="display: none;">
                        <p>Date:
                            <?= htmlspecialchars($tournoi['date_compet']); ?>
                        </p>
                        <p>Participants:</p>
                        <ul>

                            <?php foreach ($tournoi['participants'] as $participant_id => $participant_name) : ?>

                                <li>
                                    <?= htmlspecialchars($participant_name); ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
        <div class="inscription-list">
            <h2>Inscriptions en attente de confirmation</h2>

            <?php foreach ($inscriptions as $inscription) : ?>

                <div class="inscription-item" id="inscription-<?= $inscription['User_user_id']; ?>">
                    <h3>Demande d'inscription</h3>
                    <p>Utilisateur :
                        <?= htmlspecialchars($inscription['user_nom']) . " " . htmlspecialchars($inscription['user_prenom']); ?>
                    </p>
                    <p>Âge :
                        <?= htmlspecialchars(calculateAge($inscription['birthdate'])); ?> ans
                    </p>
                    <p>Tournoi :
                        <?= htmlspecialchars($inscription['nom_compet']); ?>
                    </p>
                    <p>Âge requis pour le tournoi :
                        <?= htmlspecialchars($inscription['age_requis']); ?> ans
                    </p>
                    <p>Niveau Utilisateur:
                        <?= htmlspecialchars($inscription['niveau']); ?>
                    </p>
                    <p>Niveau Compétition:
                        <?= htmlspecialchars($inscription['niveau_compet']); ?>
                    </p>
                    <form action='confirm_tournois.php' method='post'>
                        <input type='hidden' name='User_user_id' value='<?= $inscription['User_user_id']; ?>'>
                        <input type='hidden' name='Compétitions_compet_id' value='<?= $inscription['Compétitions_compet_id']; ?>'>
                        <button type='submit' class="confirm-button" name='action' value='accept'>Confirmer</button>
                        <button type='submit' class="refuse-button" name='action' value='refuse'>Refuser</button>
                    </form>
                </div>

            <?php endforeach; ?>

        </div>
        <div class="delete-user">
            <form action="gestion_tournois.php" method="post">
                <select name="selected_tournament" id="selected_tournament">
                    <option value="">Sélectionnez un tournoi</option>

                    <?php foreach ($tournaments as $tournament) : ?>

                        <option value="<?= htmlspecialchars($tournament['compet_id']); ?>" <?= (isset($selectedTournamentId) && $selectedTournamentId == $tournament['compet_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($tournament['nom_compet']); ?>
                        </option>

                    <?php endforeach; ?>

                </select>
                <input type="submit" value="Charger les participants" />
            </form>

            <!-- Formulaire pour sélectionner et supprimer un participant -->
            <?php if (!empty($participants)) : ?>

                <form action="gestion_tournois.php" method="post">
                    <input type="hidden" name="selected_tournament" value="<?= htmlspecialchars($selectedTournamentId); ?>">
                    <select name="selected_participant" id="selected_participant">
                        <option value="">Sélectionnez un participant</option>

                        <?php foreach ($participants as $participant) : ?>

                            <option value="<?= htmlspecialchars($participant['user_id']); ?>">
                                <?= htmlspecialchars($participant['user_nom']) . ' ' . htmlspecialchars($participant['user_prenom']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                    <button type="submit" name="action" value="delete_participant">Supprimer le participant</button>
                </form>

            <?php endif; ?>

            <form action="gestion_tournois.php" method="post">
                <select name="update_tournament" id="update_tournament">
                    <option value="">Sélectionnez un tournoi pour mettre à jour</option>

                    <?php foreach ($tournaments as $tournament) : ?>

                        <option value="<?= htmlspecialchars($tournament['compet_id']); ?>">
                            <?= htmlspecialchars($tournament['nom_compet']); ?>
                        </option>

                    <?php endforeach; ?>
                    
                </select>
                <input type="number" name="new_places" min="1" placeholder="Nouveau nombre de places" required>
                <button type="submit" name="action" value="update_places">Mettre à jour les places</button>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                document.querySelectorAll('.tournois-header').forEach(header => {
                    header.addEventListener('click', function() {
                        this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none';
                        this.querySelector('.toggle-details').style.transform = this.nextElementSibling.style.display === 'none' ? 'rotate(0deg)' : 'rotate(180deg)';
                    });
                });
            });
        </script>
    </body>
</html>

<?php
    include_once("../header-footer/footer.php");
?>