<?php
session_start();

require("../header-footer/header.php");
require '../connection/connection.php';
$directoryPath = '../../images/uploads/';

// Récupérer tous les fichiers image du dossier
$images = glob($directoryPath . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Upload et Affichage d'Image</title>
</head>

<body>
    <div class="gallery-title">Galerie de photos</div>
    <div class="gallery-container">
        <?php foreach ($images as $image) : ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars(pathinfo($image, PATHINFO_FILENAME)); ?>" onclick="openModal(this.src, this.alt)">
        <?php endforeach; ?>
    </div>
    <script defer src="../../js/largerImage.js"></script>
</body>

</html>

<?php
require("../header-footer/footer.php");
?>