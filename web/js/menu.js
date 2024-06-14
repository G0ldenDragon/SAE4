// Sélectionne l'élément avec la classe "nav-toggle" et ajoute un écouteur d'événements de clic
document.querySelector('.nav-toggle').addEventListener('click', function() {
    // Sélectionne l'élément avec la classe "nav" et ajoute ou supprime la classe "is-active" pour basculer son état
    document.querySelector('.nav').classList.toggle('is-active');
});
