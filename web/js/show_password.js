// Attend que le contenu de la page soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Ajoute un écouteur d'événement de changement à l'élément avec l'ID 'show-signup-password'
    document.getElementById('show-signup-password').addEventListener('change', function() {
        // Sélectionne l'élément avec l'ID 'signup-password'
        var passwordInput = document.getElementById('signup-password');
        // Change le type d'entrée de mot de passe en 'text' si la case à cocher est cochée, sinon en 'password'
        passwordInput.type = this.checked ? 'text' : 'password';
    });

    // Ajoute un écouteur d'événement de changement à l'élément avec l'ID 'show-login-password'
    document.getElementById('show-login-password').addEventListener('change', function() {
        // Sélectionne l'élément avec l'ID 'login-password'
        var passwordInput = document.getElementById('login-password');
        // Change le type d'entrée de mot de passe en 'text' si la case à cocher est cochée, sinon en 'password'
        passwordInput.type = this.checked ? 'text' : 'password';
    });

    // Ajoute un écouteur d'événement de changement à l'élément avec l'ID 'show-password-confirm'
    document.getElementById('show-password-confirm').addEventListener('change', function() {
        // Sélectionne l'élément avec l'ID 'password-confirm'
        var passwordInput = document.getElementById('password-confirm');
        // Change le type d'entrée de mot de passe en 'text' si la case à cocher est cochée, sinon en 'password'
        passwordInput.type = this.checked ? 'text' : 'password';
    });
});
