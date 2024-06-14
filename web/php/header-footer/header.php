<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Votre Site Web</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <div class="header">
            <button class="nav-toggle" aria-label="toggle navigation">Menu</button>
            <img src="../../images/logo.png" alt="Logo Karting" class="header-logo">
            <div class="name">Vosges Karting Club</div>
            <ul class="nav">
                <li><a href="../index/index.php">Accueil</a></li>
                <li><a href="../piste/piste.php">Piste et kart</a></li>
                <li><a href="../pictures/pictures.php">Photos</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && $_SESSION['competition'] == 1) : ?>
                    <li><a href="../tournament/tournois.php">Tournois</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                    <li><a href="../event/event.php">Événements</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && $_SESSION['admin'] == 1) : ?>
                    <li><a href="../tournament/gestion_tournois.php">Gestion Tournois</a></li>
                    <li><a href="../user-management/gestion_ad.php">Gestion Adhérents</a></li>
                <?php endif; ?>
                <li><a href="../contact/contact.php">Contact</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                    <li><a href="../account/mon_compte.php">Mon compte</a></li>
                <?php else : ?>
                    <li><a href="#" id="openPopup">Se connecter</a></li>
                    <div id="loginPopup" class="login-popup">
                        <div class="login-content">
                            <span class="close">&times;</span>
                            <h2>Connexion</h2>
                            <form id="loginForm" action="../login-logout-signup/login.php" method="post" class="login-form">
                                <div class="form-group">
                                    <label for="login-email">Email :</label>
                                    <input type="email" id="login-email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="login-password">Mot de passe :</label>
                                    <input type="password" id="login-password" name="password" required>
                                    <input type="checkbox" id="show-login-password"> Afficher mot de passe
                                </div>
                                <button type="submit">Se connecter</button>
                            </form>
                        </div>
                    </div>
                    <li><a href="#" id="switchToSignup">S'inscrire</a></li>
                    <div id="signupPopup" class="signup-popup">
                        <div class="signup-content">
                            <span class="close">&times;</span>
                            <h2>Inscription</h2>
                            <form id="signupForm" action="../login-logout-signup/signup.php" method="post" class="signup-form" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="profile_picture">Image de profil :</label>
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Nom :</label>
                                        <input type="text" id="name" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="firstname">Prénom :</label>
                                        <input type="text" id="firstname" name="firstname" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email">Email :</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="numtel">Numéro de téléphone :</label>
                                        <input type="tel" id="numtel" name="numtel" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="birthdate">Date de naissance :</label>
                                        <input type="date" id="birthdate" name="birthdate" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="adresse">Adresse :</label>
                                        <input type="text" id="adresse" name="adresse" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="competitions">Participer aux tournois :</label>
                                    <input type="checkbox" id="competitions" name="competitions" value="1">
                                </div>

                                <div class="form-group" id="niveauFormGroup">
                                    <select name="niveau" id="niveau" required>
                                        <option value="">Choisir un niveau</option>
                                        <option value="Débutant">Débutant(e)</option>
                                        <option value="Intermédiaire">Intermédiaire</option>
                                        <option value="Avancé">Avancé(e)</option>
                                        <option value="Expert">Expert(e)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="medical_certificate">Certificat médical :</label>
                                    <input type="file" id="medical_certificate" name="medical_certificate" accept=".pdf" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="password">Mot de passe :</label>
                                        <input type="password" id="signup-password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}" title="Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial." required>
                                        <input type="checkbox" id="show-signup-password"> Afficher mot de passe
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm">Confirmation :</label>
                                        <input type="password" id="password-confirm" name="password-confirm" required>
                                        <input type="checkbox" id="show-password-confirm"> Afficher mot de passe
                                        <div id="password-mismatch-error" style="display:none;">
                                            Les mots de passe ne correspondent pas.
                                        </div>
                                    </div>
                                </div>
                                <button type="submit">S'inscrire</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </ul>
        </div>
        <script defer src="../../js/connect.js"></script>
        <script defer src="../../js/menu.js"></script>
        <script defer src="../../js/show_password.js"></script>
        <script defer src="../../js/confirm_password.js"></script>
        <script defer src="../../js/showLevel.js"></script>
    </body>
</html>