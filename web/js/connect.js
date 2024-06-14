// Attend que le contenu de la page soit entièrement chargé
document.addEventListener('DOMContentLoaded', function () {
    // Récupère les éléments HTML nécessaires par leur ID
    var loginPopup = document.getElementById("loginPopup");
    var signupPopup = document.getElementById("signupPopup");
    var openLogin = document.getElementById("openPopup");
    var switchToSignup = document.getElementById("switchToSignup");
    var closeLogin = document.querySelector(".login-popup .close");
    var closeSignup = document.querySelector(".signup-popup .close");

    // Ajoute un écouteur d'événements sur le clic du bouton "Ouvrir la fenêtre de connexion"
    openLogin.addEventListener('click', function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien
        loginPopup.style.display = "flex"; // Affiche la fenêtre de connexion
    });

    // Ajoute un écouteur d'événements sur le clic du lien "Passer à l'inscription"
    switchToSignup.addEventListener('click', function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien
        loginPopup.style.display = "none"; // Cache la fenêtre de connexion
        signupPopup.style.display = "flex"; // Affiche la fenêtre d'inscription
    });

    // Ajoute un écouteur d'événements sur le clic du bouton de fermeture de la fenêtre de connexion
    closeLogin.addEventListener('click', function () {
        loginPopup.style.display = "none"; // Cache la fenêtre de connexion
    });

    // Ajoute un écouteur d'événements sur le clic du bouton de fermeture de la fenêtre d'inscription
    closeSignup.addEventListener('click', function () {
        signupPopup.style.display = "none"; // Cache la fenêtre d'inscription
    });

    // Ajoute un écouteur d'événements sur le clic en dehors des fenêtres de connexion et d'inscription pour les fermer
    window.addEventListener('click', function (event) {
        if (event.target == loginPopup) {
            loginPopup.style.display = "none"; // Cache la fenêtre de connexion si l'utilisateur clique en dehors d'elle
        }
        if (event.target == signupPopup) {
            signupPopup.style.display = "none"; // Cache la fenêtre d'inscription si l'utilisateur clique en dehors d'elle
        }
    });
});
