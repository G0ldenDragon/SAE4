<?php
// Démarrer la session pour gérer les sessions utilisateur
session_start();

// Inclure le fichier de connexion à la base de données
require '../connection/connection.php';

// Inclure le fichier d'en-tête
require('../header-footer/header.php');

// Définir la limite d'affichage d'utilisateurs par page
$limit = 10; // Nombre d'utilisateurs par page

// Récupérer le numéro de page actuel à partir de la requête GET (ou 1 par défaut)
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculer l'offset (décalage) pour la requête
$offset = ($page - 1) * $limit;

// Obtenir le nombre total d'utilisateurs dans la base de données
$totalQuery = $pdo->query("SELECT COUNT(*) FROM user");
$totalUsers = $totalQuery->fetchColumn();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalUsers / $limit);

// Récupérer les utilisateurs pour la page actuelle
$stmt = $pdo->prepare("SELECT user_id, name, firstname, profile_picture FROM user LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Adhérents</title>
</head>

<body>
    <div class="container">
        <h1>Gestion des Adhérents</h1>
        <div class="form-group-ad">
            <input type="text" id="search-input" class="form-control" placeholder="Rechercher par nom ou prénom">
        </div>
        <div id="members-list" class="members-list">
            <?php foreach ($members as $member) : ?>
                <div class="member" data-name="<?= strtolower($member['name'] . ' ' . $member['firstname']); ?>" onclick="location.href='details_member.php?user_id=<?= $member['user_id']; ?>'">
                    <img src="user_image.php?user_id=<?= $member['user_id']; ?>" alt="Profil Image" class="profile-picture" />
                    <h2><?= htmlspecialchars($member['name']) . ' ' . htmlspecialchars($member['firstname']); ?></h2>
                </div>
            <?php endforeach; ?>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Previous Page Link -->
                <?php if ($page > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next Page Link -->
                <?php if ($page < $totalPages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const members = document.querySelectorAll('.member');

            searchInput.addEventListener('keyup', function() {
                const searchValue = searchInput.value.toLowerCase();

                members.forEach(member => {
                    const name = member.getAttribute('data-name');
                    if (name.includes(searchValue)) {
                        member.style.display = '';
                    } else {
                        member.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
<?php
require('../header-footer/footer.php');
?>