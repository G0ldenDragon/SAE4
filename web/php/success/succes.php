<?php
    include_once("../header-footer/header.php");
    
    if (!isset($_SESSION['success_message'])) 
    {
        header('Location: ../index/index.php');
        exit();
    }

    $message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="3;url=../index/index.php">
        <title>Opération Réussie</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f7f7f7;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                border-radius: 8px;
            }

            .message {
                font-size: 1.2em;
                margin-bottom: 20px;
            }

            .home-link {
                display: inline-block;
                padding: 10px 20px;
                margin-top: 20px;
                background-color: #050507;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                transition: background-color 0.3;
            }

            .home-link:hover {
                background-color: #E3311D;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="message">
                Votre opération a été effectuée avec succès ! Vous serez redirigé vers la page d'accueil dans un instant.
            </div>
            <!-- Le lien ci-dessous n'est pas nécessaire si vous avez la redirection automatique, mais il est utile si l'utilisateur a désactivé les méta-balises ou si le JavaScript est désactivé -->
            <a href="../index/index.php" class="home-link">Retour à la page d'accueil'</a>
        </div>
    </body>
</html>