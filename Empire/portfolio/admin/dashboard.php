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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./login.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard</h1>

        <table class="projects-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td><?php echo htmlspecialchars($project['category']); ?></td>
                        <td>
                            <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
         
        <button class="smoothScroll btn btn-default btn-lg" onclick="location.href='./add_project.php'">
            <span>Add Project</span>
        </button>
    </div>
</body>
</html>