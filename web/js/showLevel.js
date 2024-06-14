// Attend que le contenu de la page soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne l'élément avec l'ID 'competitions' (la case à cocher)
    var checkbox = document.getElementById('competitions');
    // Sélectionne l'élément avec l'ID 'niveauFormGroup' (le groupe de formulaire pour le niveau)
    var niveauFormGroup = document.getElementById('niveauFormGroup');

    // Fonction pour basculer l'affichage du groupe de formulaire de niveau
    function toggleNiveauDisplay() {
        // Définit l'affichage du groupe de formulaire de niveau en 'block' si la case à cocher est cochée, sinon en 'none'
        niveauFormGroup.style.display = checkbox.checked ? 'block' : 'none';
    }

    // Définit l'état initial du groupe de formulaire de niveau lorsque la page se charge
    toggleNiveauDisplay();

    // Ajoute un écouteur d'événement pour détecter les changements d'état de la case à cocher
    checkbox.addEventListener('change', toggleNiveauDisplay);
});
