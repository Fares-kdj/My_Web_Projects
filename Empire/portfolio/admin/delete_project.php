<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    include '../includes/db.php';
    $id = $_GET['id'];

    // Supprimer le projet
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    header('Location: dashboard.php');
}
?>