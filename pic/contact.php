<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serveur = "sql204.infinityfree.com";
    $utilisateur = "if0_35366109";
    $mot_de_passe = "nQ4szBNVV0ccjMA";
    $base_de_donnees = "if0_35366109_contact_club"; // Modifiez le nom de la base de données si nécessaire

    // Créez une connexion &agrave; la base de données
    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion &agrave; la base de données a échoué; : " . $conn->connect_error);
    }

    // Récupérez les données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $messager = $_POST['messager'];

    if (empty($nom) || empty($email) || empty($messager)) {
        echo '<script>alert("Tous les champs sont obligatoires.");</script>';
    } else {
        $requete = $conn->prepare("INSERT INTO contact_table (nom, email, messager) VALUES (?, ?, ?)");
        $requete->bind_param("sss", $nom, $email, $messager);

        if ($requete->execute()) {
            echo '<script>alert("Votre message a été envoyé. Merci !"); window.location.href = "index.html";</script>';
        } else {
            echo '<script>alert("Erreur : ' . $requete->error . '");</script>';
        }
        

        $requete->close();
    }

    // Fermez la connexion &agrave; la base de données
    $conn->close();
}
?>
