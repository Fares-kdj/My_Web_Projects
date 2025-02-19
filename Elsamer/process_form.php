<?php
// Connexion à la base de données MySQL
$servername = "localhost";
$username = "samar";
$password = "Samar123*";
$dbname = "samar_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$applicant_name = $_POST['applicant_name'];
$national_id = $_POST['national_id'];
$birthdate = $_POST['birthdate'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$marital_status = $_POST['marital_status'];
$education_level = $_POST['education_level'];
$work_place = $_POST['work_place'];
$job = $_POST['job'];
$years_of_service = $_POST['years_of_service'];
$total_salary = $_POST['total_salary'];
$monthly_payment = $_POST['monthly_payment'];
$months_count = $_POST['months_count'];
$total_loan = $_POST['total_loan']; // Nouveau champ

// Préparer et exécuter la requête SQL pour insérer les données
$sql = "INSERT INTO factures (applicant_name, national_id, birthdate, age, gender, marital_status, education_level, work_place, job, years_of_service, total_salary, monthly_payment, months_count, total_loan) 
VALUES ('$applicant_name', '$national_id', '$birthdate', '$age', '$gender', '$marital_status', '$education_level', '$work_place', '$job', '$years_of_service', '$total_salary', '$monthly_payment', '$months_count', '$total_loan')";

if ($conn->query($sql) === TRUE) {
    // Message de succès et redirection vers fares.html
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
    تم التسجيل بنجاح.
</div>";

    header("refresh:2; url=fares.html"); // Rediriger après 2 secondes
    exit(); // Arrêter l'exécution après la redirection
} else {
    // Afficher une erreur si l'insertion échoue
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
