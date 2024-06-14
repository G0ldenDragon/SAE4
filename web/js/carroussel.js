// Déclaration de la variable currentIndex pour suivre l'index de l'image actuellement affichée
let currentIndex = 0;

// Sélection de toutes les images dans la classe 'carousel-images'
const images = document.querySelectorAll('.carousel-images img');

// Stockage du nombre total d'images
const totalImages = images.length;

// Déclaration de la variable pour gérer l'intervalle de défilement automatique
let autoSlideInterval;

// Ajout d'un écouteur d'événements sur le bouton précédent ('prev')
document.querySelector('.prev').addEventListener('click', function () {
    // Appelle la fonction pour déplacer le diaporama vers la gauche
    moveSlide(-1);

    // Réinitialise l'intervalle de défilement automatique
    resetAutoSlide();
});

// Ajout d'un écouteur d'événements sur le bouton suivant ('next')
document.querySelector('.next').addEventListener('click', function () {
    // Appelle la fonction pour déplacer le diaporama vers la droite
    moveSlide(1);

    // Réinitialise l'intervalle de défilement automatique
    resetAutoSlide();
});

// Fonction pour déplacer le diaporama dans la direction spécifiée
function moveSlide(direction) {
    // Met à jour l'index actuel en fonction de la direction
    currentIndex += direction;

    // Vérifie si l'index est inférieur à zéro (au début du diaporama)
    if (currentIndex < 0) {
        // Ramène à la dernière image
        currentIndex = totalImages - 1;
    }
    // Vérifie si l'index dépasse le nombre total d'images (à la fin du diaporama)
    else if (currentIndex >= totalImages) {
        // Ramène au début du diaporama
        currentIndex = 0;
    }

    // Modifie la propriété de style pour déplacer les images horizontalement
    document.querySelector('.carousel-images').style.transform = `translateX(-${currentIndex * 100}%)`;
}

// Fonction pour démarrer le défilement automatique
function startAutoSlide() {
    // Définit un intervalle pour appeler la fonction moveSlide toutes les 5000 millisecondes (5 secondes)
    autoSlideInterval = setInterval(function () {
        moveSlide(1); // Défilement vers la droite
    }, 5000);
}

// Fonction pour réinitialiser l'intervalle de défilement automatique
function resetAutoSlide() {
    clearInterval(autoSlideInterval); // Efface l'intervalle existant
    startAutoSlide(); // Redémarre le défilement automatique
}

// Démarre le défilement automatique lorsque la page se charge
startAutoSlide();
