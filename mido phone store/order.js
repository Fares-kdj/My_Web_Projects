document.addEventListener("DOMContentLoaded", function () {
  const product = JSON.parse(localStorage.getItem('selectedProduct'));
  if (product) {
    document.getElementById('product-image').src = product.image;
    document.getElementById('product-name').textContent = product.name;
    document.getElementById('old-price').textContent = product.oldPrice ;
    document.getElementById('new-price').textContent = product.newPrice ;

    // Remplir le tableau avec les caractéristiques
    document.getElementById('screen-size').textContent = product.screenSize;
    document.getElementById('memory').textContent = product.memory;
    document.getElementById('main-camera').textContent = product.mainCamera;
    document.getElementById('front-camera').textContent = product.frontCamera;
    document.getElementById('battery').textContent = product.battery;
    document.getElementById('os').textContent = product.os;
  } else {
    alert("لم يتم العثور على معلومات المنتج!");
  }
});



  