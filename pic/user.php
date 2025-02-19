<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serveur = "sql204.infinityfree.com";
    $utilisateur = "if0_35366109";
    $mot_de_passe = "nQ4szBNVV0ccjMA";
    $base_de_donnees = "if0_35366109_polytech_club"; // Modifiez le nom de la base de données si nécessaire

    // Créez une connexion à la base de données
    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérez les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $specialite = $_POST['specialite'];
    $numero = $_POST['numero'];
    $date_ne = $_POST['date_ne'];

    if (empty($nom) || empty($prenom) || empty($email) || empty($specialite) || empty($numero) || empty($date_ne)) {
        echo '<script>alert("Tous les champs sont obligatoires.");</script>';
    } else {
        // Vérifiez d'abord si l'email existe déjà dans la base de données
        $verif_email = $conn->prepare("SELECT email FROM inscription_club WHERE email = ?");
        $verif_email->bind_param("s", $email);
        $verif_email->execute();
        $verif_email->store_result();
        $count = $verif_email->num_rows;

        if ($count > 0) {
            echo '<script>alert("L\'e-mail existe déjà. Vous êtes déjà inscrit."); window.location.href = "index.html";</script>';
        } else {
            // L'e-mail n'existe pas encore, nous pouvons l'insérer dans la base de données
            $requete = $conn->prepare("INSERT INTO inscription_club (nom, prenom, email, specialite, numero, date_ne) VALUES (?, ?, ?, ?, ?, ?)");
            $requete->bind_param("ssssss", $nom, $prenom, $email, $specialite, $numero, $date_ne);
        
            if ($requete->execute()) {
                echo '<script>alert("Inscription réussie. Merci !"); window.location.href = "index.html";</script>';
            } else {
                echo '<script>alert("Erreur lors de l\'inscription : ' . $requete->error . '");</script>';
            }
        
            $requete->close();
        }

        $verif_email->close();
    }

    // Fermez la connexion à la base de données
    $conn->close();
}
?>
