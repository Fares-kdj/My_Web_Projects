<?php
include '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Project not found.");
}

$id = $_GET['id'];
$sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    die("Project not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Mise à jour du projet dans la base de données
    $sql = "UPDATE projects SET title = ?, description = ?, category = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $description, $category, $price, $id]);

    // Rediriger vers dashboard.php avec un message de succès
    // Après la mise à jour du produit
header("Location: dashboard.php?success=true&title=" . urlencode($title));
exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../../assets/logo.png"/>
    <title>Edit Project</title>
    <link rel="stylesheet" href="./addpro.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Project</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($project['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($project['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="woman" <?php echo $project['category'] == 'woman' ? 'selected' : ''; ?>>Woman</option>
                    <option value="man" <?php echo $project['category'] == 'man' ? 'selected' : ''; ?>>Man</option>
                    <option value="child" <?php echo $project['category'] == 'child' ? 'selected' : ''; ?>>Child</option>
                    <option value="shoes" <?php echo $project['category'] == 'shoes' ? 'selected' : ''; ?>>Shoes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (DZD):</label>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo number_format($project['price'], 2); ?>" required>
            </div>
            <div class="button-container">
                <button class="smoothScroll btn btn-default btn-lg" type="submit">
                    <span>Save Changes</span>
                </button>
                <button class="smoothScroll btn btn-default btn-lg" type="button" onclick="window.location.href='dashboard.php'">
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
