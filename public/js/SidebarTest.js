let arrows = document.querySelectorAll(".arrow");
arrows.forEach(arrow => {
  arrow.addEventListener("click", (e) => {
    // Ferme tous les sous-menus sauf celui du clic
    arrows.forEach(a => {
      if (a !== arrow) {
        let parent = a.parentElement.parentElement;
        parent.classList.remove("showMenu");
      }
    });

    // Ouvre ou ferme le sous-menu du clic
    let arrowParent = e.target.parentElement.parentElement;
    arrowParent.classList.toggle("showMenu");
  });
});



// Sélectionnez tous les éléments ayant la classe "item" (avec ou sans sous-menu)
const items = document.querySelectorAll(".nav-links li.item");

// Ajoutez un écouteur d'événement pour chaque élément
items.forEach(item => {
  const iconLink = item.querySelector('.iocn-link'); // L'élément cliquable
  const arrow = item.querySelector('.arrow');       // L'icône de flèche

  const toggleMenu = () => {
    // Supprime la classe 'showMenu' sur tous les autres éléments
    items.forEach(i => {
      if (i !== item) {
        i.classList.remove('showMenu');
      }
    });

    // Basculer 'showMenu' sur l'élément actuel
    item.classList.toggle('showMenu');
  };

  // Écouter le clic sur l'ensemble de l'élément (et ses enfants)
  if (iconLink) {
    iconLink.addEventListener('click', toggleMenu);
  }
  if (arrow) {
    arrow.addEventListener('click', toggleMenu);
  }
});






