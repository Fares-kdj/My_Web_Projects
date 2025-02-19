<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php';
$sql = "SELECT * FROM projects";
$stmt = $conn->query($sql);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Vérifier si un message de succès est passé via l'URL
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['title'])) {
    $title = htmlspecialchars($_GET['title']);
    $success_message = $title . " has been updated successfully!";}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/logo.png"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./dash.css">
    
</head>
<body>
    <div class="menu-container">
    <div class="menu-button" id="menu-button">☰ Menu</div>

    <div class="sidebar" id="sidebar">
    <!-- Ajout du logo -->
    <div class="sidebar-logo">
        <img src="../../assets/Folia-white.png" alt="Logo">
    </div>

    <a href="./dashboard.php" style="font-weight: 400; font-size: 20px;">
    <i class="fas fa-cube" style="margin-right: 10px;"></i>Products</a>
    <a href="./orders.php" style="font-weight: 400;  font-size: 20px;">
    <i class="fas fa-box" style="margin-right: 10px;"></i>Manage Orders</a>
</div>


    </div>
    <div class="dashboard-container">
        <h1 style="text-transform: uppercase; color: #003859">Products</h1>
        <?php // Vérifier si un message de succès est passé via l'URL
if (isset($_GET['success']) && $_GET['success'] == 'delete' && isset($_GET['title'])) {
    $title = htmlspecialchars($_GET['title']);
    $success_message = $title . " has been deleted successfully!";
} ?>
        <?php if (isset($success_message)) : ?>
            <p class="succes-delete-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <table class="projects-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price (DZD)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td><?php echo htmlspecialchars($project['category']); ?></td>
                        <td><?php echo number_format($project['price'], 2); ?> DZD</td>
                        <td class="actions">
                            <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                            <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="edit-link">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="./add_project.php" class="add-project-btn">Add Product</a>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const menuButton = document.getElementById("menu-button");
    const sidebar = document.getElementById("sidebar");

    menuButton.addEventListener("click", function() {
        sidebar.classList.toggle("open"); // Ouvre ou ferme la sidebar
        menuButton.classList.toggle("hidden"); // Cache ou montre le bouton
    });
});

</script>

</body>
</html>