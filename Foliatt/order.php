<?php
include './Dash/includes/db.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Retrieve product details
    $sql = "SELECT * FROM projects WHERE id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<p class='error-message'>Product not found</p>";
        exit;
    }

    // Retrieve the image URLs (split by commas)
    $image_urls = explode(',', $product['image_urls']);
} else {
    echo "<p class='error-message'>Product ID not found</p>";
    exit;
}

$message = ""; // Variable to store message

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];
    $product_size = $_POST['product_size'];
    $product_quantity = $_POST['product_quantity'];
    $product_id = $_POST['product_id'];

    $sql = "INSERT INTO orders (product_id, customer_name, customer_phone, customer_address, product_size, product_quantity) 
            VALUES (:product_id, :customer_name, :customer_phone, :customer_address, :product_size, :product_quantity)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':customer_phone', $customer_phone);
    $stmt->bindParam(':customer_address', $customer_address);
    $stmt->bindParam(':product_size', $product_size);
    $stmt->bindParam(':product_quantity', $product_quantity);

    if ($stmt->execute()) {
        $message = "<p class='success-message'>✅ Order placed successfully!</p>";
    } else {
        $message = "<p class='error-message'>❌ An error occurred while placing the order</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Order</title>
    <link rel="icon" type="image/png" href="./assets/logo.png"/>
    <link rel="stylesheet" href="./assets/orderfolia.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
        <a href="index.php">
            <div class="logo"> 
                <img src="assets/Folia-white.png" alt="Logo" class="logo-img">
            </div>
        </a>
    <div class="form-container">
        <div class="message-container">
            <?php echo $message; ?>
        </div>

        <h2>Product Details</h2>
        <div class="carousel-container">
            <div class="carousel">
                <?php foreach ($image_urls as $index => $image_url): ?>
                    <div class="carousel-item">
                        <img src="./Dash/uploads/<?php echo basename($image_url); ?>" alt="Image <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <h3><?php echo $product['title']; ?> - <span style="color: green;"> <?php echo number_format($product['price'], 2); ?> DZD</span></h3>
        <p><?php echo $product['description']; ?></p>

        <h2>Order Information</h2>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="customer_phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="customer_address" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label>Size:</label>
                <input type="text" name="product_size" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="product_quantity" class="form-control" required>
            </div>

            <div class="button-container">
                <button type="submit" class="smoothScroll"><span>Submit Order</span></button>
                <button type="button" onclick="window.location.href='index.php#portfolio';" class="smoothScroll"><span>Cancel</span></button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').slick({
                dots: true,
                arrows: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
            });
        });
    </script>
</body>
</html>
