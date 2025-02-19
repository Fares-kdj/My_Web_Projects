// Fonction pour basculer le menu latéral
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
  }
  function navigateToOrder(name, image, oldPrice, newPrice, screenSize, memory, mainCamera, frontCamera, battery, os) {
    const product = {
      name: name,
      image: image,
      oldPrice: oldPrice,
      newPrice: newPrice,
      screenSize: screenSize,
      memory: memory,
      mainCamera: mainCamera,
      frontCamera: frontCamera,
      battery: battery,
      os: os
    };
    localStorage.setItem('selectedProduct', JSON.stringify(product));
  
    console.log("Produit sauvegardé dans LocalStorage:", product); // Debug pour voir les données
    window.location.href = 'order.html'; // Redirection
  }
  
  


// Fonction pour ajouter un produit au panier
function addToCart(name, image, oldPrice, newPrice, screenSize, memory, mainCamera, frontCamera, battery, os) {
  const product = {
    name: name,
    image: image,
    oldPrice: oldPrice,
    newPrice: newPrice,
    screenSize: screenSize,
    memory: memory,
    mainCamera: mainCamera,
    frontCamera: frontCamera,
    battery: battery,
    os: os
  };

  // Récupérer le panier actuel depuis localStorage (ou un tableau vide si aucun panier)
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Ajouter le produit au panier
  cart.push(product);

  // Sauvegarder le panier mis à jour dans localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  // Sauvegarder le dernier produit sélectionné dans localStorage
  localStorage.setItem('selectedProduct', JSON.stringify(product));

  console.log("Produit ajouté au panier et sauvegardé dans LocalStorage:", product);

  // Mettre à jour l'affichage du panier (compteur)
  updateCartCount();

  alert(`تمت إضافة ${name} إلى السلة!`);
}





function updateCartCount() {
  // Récupérer le tableau du panier depuis localStorage
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Mettre à jour l'élément HTML du compteur de panier
  const cartCount = document.getElementById('cart-count');
  cartCount.textContent = cart.length;  // Met à jour le compteur avec le nombre d'articles dans le panier
}




