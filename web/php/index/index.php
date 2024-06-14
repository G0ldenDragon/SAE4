<?php
    include_once("../header-footer/header.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <div class="carousel">
            <div class="carousel-images">
                <img src="../../images/karting1.png" alt="Image 1">
                <img src="../../images/karting2.png" alt="Image 2">
                <img src="../../images/karting3.png" alt="Image 3">
            </div>
            <button class="prev" aria-label="Previous">⟨</button>
            <button class="next" aria-label="Next">⟩</button>
        </div>
        <script defer src="../../js/carroussel.js"></script>
    </body>
</html>

<?php
    include_once("../header-footer/footer.php");
?>