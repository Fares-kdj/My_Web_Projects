<?php
header('Content-Type: application/json');

// Inclure la connexion à la base de données
include '../Dash/includes/db.php'; // Inclure le fichier db.php

try {
    // Récupérer les données du formulaire
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier si les données sont valides
    if (empty($data)) {
        throw new Exception("Données de commande invalides.");
    }

    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $phoneNumber = $data['phoneNumber'];
    $address = $data['address'];
    $orderDetails = $data['orderDetails'];

    $customerName = $firstName . ' ' . $lastName;

    // Préparer la requête SQL pour insérer les données dans la table `orders`
    $sql = "INSERT INTO orders (product_id, customer_name, customer_phone, customer_address, product_size, product_quantity, product_title, product_total, order_date)
            VALUES (:product_id, :customer_name, :customer_phone, :customer_address, :product_size, :product_quantity, :product_title, :product_total, NOW())";

    $stmt = $conn->prepare($sql);

    // Insérer chaque produit de la commande dans la table `orders`
    foreach ($orderDetails as $item) {
        $productId = $item['id']; // Assurez-vous que `id` est disponible dans `orderDetails`
        $productSize = $item['size'] ?? null; // Utilisez une valeur par défaut si `size` n'est pas disponible
        $productQuantity = $item['quantity'];
        $productTitle = $item['title'];
        $productTotal = $item['total'];

        // Lier les paramètres
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':customer_name', $customerName, PDO::PARAM_STR);
        $stmt->bindParam(':customer_phone', $phoneNumber, PDO::PARAM_STR);
        $stmt->bindParam(':customer_address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':product_size', $productSize, PDO::PARAM_STR);
        $stmt->bindParam(':product_quantity', $productQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':product_title', $productTitle, PDO::PARAM_STR);
        $stmt->bindParam(':product_total', $productTotal, PDO::PARAM_STR);

        // Exécuter la requête
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'insertion de la commande.");
        }
    }

    // Réponse JSON en cas de succès
    echo json_encode(['success' => true, 'message' => 'Commande soumise avec succès']);
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Fermer la connexion à la base de données
    $conn = null;
}
?>