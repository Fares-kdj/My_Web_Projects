<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./assets/logo.png"/>
    <title>Folia Store</title>
    <link rel="stylesheet" href="./assets/folia.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <header class="header">
        <!-- Conteneur pour afficher le message de succès -->

        <a href="index.php">
            <div class="logo"> 
                <img src="assets/Folia-white.png" alt="Logo" class="logo-img">
            </div>
        </a>
        <!-- Conteneur pour afficher le message de succès -->
            <div id="successMessage" class="success-message" style="display:none;">
                <span id="addedProductTitle"></span> has been added to your cart.
            </div>

        <div class="right-side">
            <div class="search-input">
                <input type="text" placeholder="Search">
                <i class="fas fa-search"></i> <!-- Icône FontAwesome -->
            </div>

            <div class="cart" onclick="window.location.href='./panier/panier.php';" style="cursor: pointer;">
    <i class="fas fa-shopping-cart cart-icon" style="font-size: 20px; color: #003859;"></i> <!-- Icône FontAwesome -->
    <span id="cart-count">0</span> <!-- Affichage du nombre d'articles -->
</div>


            <div class="cart admin" onclick="window.location.href='./Dash/admin/login.php';" style="cursor: pointer;">
                <i class="fas fa-user-cog" style="font-size: 30px; color: #003859;"></i>
            </div>

        </div>
    </header>
    
    <div class="content-wrapper">
    <section id="portfolio" class="portfolio-section">
        <div class="portfolio-container">
            <!-- Category Section -->
            <div class="filter-wrapper">
                <div class="portfolio-header">
                    <h2>Categories</h2>
                </div>
                <ul class="clearfix">
                    <li><a href="#" data-filter="*" class="selected opc-main-bg">All</a></li>
                    <li><a href="#" class="opc-main-bg" data-filter=".woman">Woman</a></li>
                    <li><a href="#" class="opc-main-bg" data-filter=".man">Man</a></li>
                    <li><a href="#" class="opc-main-bg" data-filter=".child">Child</a></li>
                    <li><a href="#" class="opc-main-bg" data-filter=".shoes">Shoes</a></li>
                </ul>
            </div>

            <!-- Gallery Section -->
            <div class="iso-box-section wow fadeIn" data-wow-delay="0.9s">
                <div class="iso-box-wrapper">
                    <?php
                    // Include database connection
                    include './Dash/includes/db.php';

                    // Fetch projects from the database
                    $sql = "SELECT * FROM projects";
                    $stmt = $conn->query($sql);
                    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($projects)) {
                        foreach ($projects as $project):
                            // Retrieve the image URLs
                            $image_urls = explode(',', $project['image_urls']); // Split the comma-separated image URLs
                    ?>
                            <div class="iso-box <?php echo $project['category']; ?>" data-project-id="<?php echo $project['id']; ?>">
                                <div class="image-gallery">
                                    <?php
                                    // Loop through the images and display them
                                    foreach ($image_urls as $index => $image_url):
                                        $filePath = './Dash/uploads/' . basename($image_url);
                                    ?>
                                        <img class="gallery-image <?php echo $index === 0 ? 'active' : ''; ?>" 
                                             src="<?php echo $filePath; ?>" 
                                             alt="Image <?php echo $index + 1; ?>">
                                    <?php endforeach; ?>
                                    <!-- Previous and Next buttons -->
                                    <button class="prev" onclick="changeImage('<?php echo $project['id']; ?>', -1)">&#10094;</button>
                                    <button class="next" onclick="changeImage('<?php echo $project['id']; ?>', 1)">&#10095;</button>
                                </div>
                                <h3><?php echo $project['title']; ?> - <span style="color: green; padding: 10px;"><?php echo number_format($project['price'], 2); ?> DZD</span></h3>
                                <p><?php echo $project['description']; ?></p>
                                    <div class="buttons-container">
                                         <!-- "Order Now" button -->
                                        <button class="custom-button" onclick="location.href='./order.php?product_id=<?php echo $project['id']; ?>'">
                                            Order Now
                                        </button>
                                
                                        <!-- "Add to Cart" button -->
                                        <button class="custom-button" onclick="addToCart('<?php echo $project['id']; ?>', '<?php echo $project['title']; ?>', <?php echo $project['price']; ?>)">
                                        <i class="fas fa-cart-plus"></i>Add to Cart
                                        </button>
                                    </div>
                            </div>
                        <?php endforeach;
                    } else {
                        echo "<p style='text-align: center; color: White'>No projects to display.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Fonction pour ajouter un produit au panier
    function addToCart(productId, productTitle, productPrice) {
        // Créer un objet pour stocker les détails du produit
        const product = {
            id: productId,
            title: productTitle,
            price: productPrice,
        };

        // Obtenir le panier actuel depuis localStorage (ou initialiser un panier vide)
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Ajouter le produit au panier
        cart.push(product);

        // Sauvegarder le panier mis à jour dans localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Mettre à jour le compteur du panier
        updateCartCount();

        // Optionnel : Afficher un message de confirmation
        alert(productTitle + ' has been added to your cart!');
    }

    // Fonction pour mettre à jour le nombre d'articles dans le panier
    function updateCartCount() {
        // Récupérer le panier depuis localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Mettre à jour le texte de l'élément span avec l'ID cart-count
        document.getElementById('cart-count').textContent = cart.length;
    }

    // Appel initial pour afficher le nombre d'articles au chargement de la page
    updateCartCount();
</script>
    <script>
        function changeImage(projectId, direction) {
            const gallery = document.querySelector(`.iso-box[data-project-id="${projectId}"] .image-gallery`);
            const images = gallery.querySelectorAll('.gallery-image');
            let currentIndex = -1;

            // Trouver l'index de l'image active
            images.forEach((img, index) => {
                if (img.classList.contains('active')) {
                    currentIndex = index;
                }
            });

            // Masquer l'image actuelle
            if (currentIndex !== -1) {
                images[currentIndex].classList.remove('active');
            }

            // Calculer le nouvel index
            let newIndex = (currentIndex + direction + images.length) % images.length;

            // Afficher la nouvelle image
            images[newIndex].classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialiser Isotope
            const isoBoxWrapper = document.querySelector('.iso-box-wrapper');
            const iso = new Isotope(isoBoxWrapper, {
                itemSelector: '.iso-box',
                layoutMode: 'fitRows'
            });

            // Gérer les clics sur les boutons de filtre
            const filterButtons = document.querySelectorAll('.filter-wrapper a');
            filterButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    filterButtons.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                    const filterValue = this.getAttribute('data-filter');
                    iso.arrange({ filter: filterValue });
                });
            });
        });
    </script>
    <script>
 function addToCart(productId, productTitle, productPrice) {
    // Récupérer le panier actuel depuis localStorage ou initialiser un panier vide
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Vérifier si le produit est déjà dans le panier
    let existingProduct = cart.find(item => item.id === productId);

    if (existingProduct) {
        // Si le produit existe, augmenter la quantité
        existingProduct.quantity += 1;
    } else {
        // Sinon, ajouter un nouveau produit avec quantité = 1
        cart.push({
            id: productId,
            title: productTitle,
            price: productPrice,
            quantity: 1
        });
    }

    // Sauvegarder le panier mis à jour dans localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Afficher un message de succès
    displaySuccessMessage(productTitle);
}

function displaySuccessMessage(productTitle) {
    const successMessage = document.getElementById('successMessage');
    const productTitleSpan = document.getElementById('addedProductTitle');

    // Mettre à jour le texte avec le titre du produit
    productTitleSpan.textContent = productTitle;
    successMessage.style.display = 'block';

    // Masquer le message après 3 secondes
    setTimeout(() => {
        successMessage.style.display = 'none';
    }, 3000);
}


</script>
    <script src="./assets/isotope.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
</body>
</html>