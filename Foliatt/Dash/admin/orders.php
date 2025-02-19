<?php
include '../includes/db.php';

// Handle order deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM orders WHERE id = :delete_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $message = "<p class='success-message'>Order deleted successfully!</p>";
    } else {
        $message = "<p class='error-message'>❌ An error occurred while deleting the order</p>";
    }
}

// Fetch orders with total price calculation
$sql = "SELECT orders.*, projects.title, projects.price, orders.product_size, orders.product_quantity, 
               (orders.product_quantity * projects.price) AS total_price 
        FROM orders 
        JOIN projects ON orders.product_id = projects.id 
        ORDER BY order_date DESC";
$stmt = $conn->query($sql);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders List</title>
    <link rel="icon" type="image/png" href="../../assets/logo.png"/>
    <link rel="stylesheet" href="./dash.css">
    <style>
        .dashboard-container {
    max-width: 1000px; /* Limite la largeur du conteneur */
    width: 90%; /* Permet de s'adapter aux écrans plus petits */
    overflow-x: auto; /* Ajoute un défilement horizontal si nécessaire */
}

.projects-table {
    width: 100%; /* Fait en sorte que la table prenne toute la largeur disponible */
    table-layout: fixed; /* Empêche la table de dépasser les limites */
    word-wrap: break-word; /* Évite que le texte déborde */
}

    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="menu-container">
    <div class="menu-button" id="menu-button">☰ Menu</div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="../../assets/Folia-white.png" alt="Logo">
        </div>
        <a href="./dashboard.php" style="font-weight: 400; font-size: 20px;">
            <i class="fas fa-cube" style="margin-right: 10px;"></i>Products</a>
        <a href="./orders.php" style="font-weight: 400; font-size: 20px;">
            <i class="fas fa-box" style="margin-right: 10px;"></i>Manage Orders</a>
    </div>
</div>

<div class="dashboard-container">
    <h1 style="text-transform: uppercase; color: #003859">Orders List</h1>
    <div class="message-container">
        <?php echo isset($message) ? $message : ''; ?>
    </div>
    <table class="projects-table">
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['title']; ?></td>
                    <td><?php echo number_format($order['price'], 2); ?> DZD</td>
                    <td><?php echo htmlspecialchars($order['product_size']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_quantity']); ?></td>
                    <td><?php echo number_format($order['total_price'], 2); ?> DZD</td>
                    <td><?php echo $order['customer_name']; ?></td>
                    <td><?php echo $order['customer_phone']; ?></td>
                    <td><?php echo $order['customer_address']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $order['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');">
                            <button class="delete-link">Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="11">No orders available.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuButton = document.getElementById("menu-button");
        const sidebar = document.getElementById("sidebar");

        menuButton.addEventListener("click", function() {
            sidebar.classList.toggle("open");
            menuButton.classList.toggle("hidden");
        });
    });
</script>
</body>
</html>
