<?php
// Connexion à la base de données
$servername = "localhost";
$username = "samar";
$password = "Samar123*";
$dbname = "samar_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Vérifier si l'ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête SQL pour supprimer la facture avec l'ID donné
    $sql = "DELETE FROM factures WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #d4edda;
        color: #155724;
        padding: 40px;
        border: 2px solid #c3e6cb;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        text-align: center;
        font-family: Arial, sans-serif;
        font-size: 20px;
        z-index: 1000;
        width: 300px;
    '>
        تم حذف الاستمارة.
    </div>";
        header("refresh:2; url= index.html");
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

    $conn->close();
} else {
    echo "Aucun ID fourni.";
}
?>
