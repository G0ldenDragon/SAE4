function signUp()
{
    // Attend que le contenu de la page soit chargé
    document.addEventListener('DOMContentLoaded', function() {

        // Affiche les cases uniquement si l'on coche la case "competitions"
        document.getElementById('competitions').addEventListener('change', function() {
            document.getElementById('niveauFormGroup').style.display = checkbox.checked ? 'block' : 'none';
        });

        // --------------------------------------------------
        
        // Ajoute un écouteur d'événements sur la soumission du formulaire
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            // Récupère la valeur du champ de mot de passe
            var password = document.getElementById('signup-password').value;
            
            // Récupère la valeur du champ de confirmation du mot de passe
            var confirmPassword = document.getElementById('password-confirm').value;
            
            // Vérifie si le mot de passe et la confirmation du mot de passe ne correspondent pas
            if (password !== confirmPassword) {
                e.preventDefault(); // Empêche la soumission du formulaire
                // Affiche un message d'erreur ou marque les champs comme incorrects
                document.getElementById('password-mismatch-error').style.display = 'block';
            }
        });
    });
}