// Crée un élément div pour la fenêtre modale
var modal = document.createElement('div');
modal.classList.add('modal');

// Crée un élément img pour afficher l'image dans la fenêtre modale
var modalImg = document.createElement('img');
modalImg.classList.add('modal-content');
modal.appendChild(modalImg);

// Crée un élément div pour afficher le texte de la légende de l'image
var captionText = document.createElement('div');
captionText.classList.add('captionText');
captionText.style.color = "white";
captionText.style.alignItems = "center";
captionText.style.textAlign = "center";
modal.appendChild(captionText);

// Crée un bouton de fermeture
var closeSpan = document.createElement('span');
closeSpan.innerHTML = '&times;'; // Utilise un symbole "X" pour le bouton de fermeture
closeSpan.classList.add('close');
modal.appendChild(closeSpan);

// Ajoute la fenêtre modale à la fin du corps du document HTML
document.body.appendChild(modal);

// Fonction pour ouvrir la fenêtre modale avec une image et une légende
function openModal(src, alt) {
    modal.style.display = "block"; // Affiche la fenêtre modale
    modalImg.src = src; // Définit la source de l'image dans la fenêtre modale
    captionText.innerHTML = alt; // Affiche la légende de l'image
}

// Fonction pour fermer la fenêtre modale
function closeModal() {
    modal.style.display = "none"; // Cache la fenêtre modale
}

// Ajoute un événement de clic sur toutes les images de classe "photo"
document.querySelectorAll('.photo img').forEach(function(img) {
    img.onclick = function() {
        openModal(this.src, this.alt); // Appelle la fonction openModal avec la source et la légende de l'image cliquée
    };
});

// Ferme la fenêtre modale lorsque le bouton de fermeture est cliqué
closeSpan.onclick = function() {
    closeModal();
};

// Ferme la fenêtre modale lorsque l'utilisateur clique n'importe où en dehors de l'image
window.onclick = function(event) {
    if (event.target === modal) {
        closeModal();
    }
};
