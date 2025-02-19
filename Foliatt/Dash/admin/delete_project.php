<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    include '../includes/db.php';
    $id = $_GET['id'];

    // Récupérer le titre du projet avant de le supprimer
    $sql = "SELECT title FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($project) {
        $title = $project['title'];

        // Supprimer le projet
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        // Rediriger vers le dashboard avec un message de succès incluant le titre
        header('Location: dashboard.php?success=delete&title=' . urlencode($title));
        exit();
    } else {
        // Si le projet n'existe pas
        header('Location: dashboard.php?error=notfound');
        exit();
    }
}
?>
