<?php

// Connexion à la base de données MySQL
$servername = "localhost";
$username = "samar";
$password = "Samar123*";
$dbname = "samar_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Vérifie si le formulaire de recherche a été soumis
if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
    $search_query = $conn->real_escape_string($_POST['search_query']);

    // Requête SQL pour rechercher une facture
    $sql = "SELECT * FROM factures WHERE national_id = '$search_query' OR applicant_name LIKE '%$search_query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2 style='
        text-align: center; 
        direction: rtl;
    '>نتائج البحث :</h2>";

        while ($row = $result->fetch_assoc()) {
            echo "<div style='
                padding: 20px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #f9f9f9;
                font-family: Arial, sans-serif;
                line-height: 1.6;
                direction: rtl;
            '>";
            echo "<p><strong>رقم الاستمارة :</strong> " . $row['id'] . "</p>";
            echo "<p><strong>اسم مقدم الطلب :</strong> " . $row['applicant_name'] . "</p>";
            echo "<p><strong>رقم التعريف الوطني :</strong> " . $row['national_id'] . "</p>";
            echo "<p><strong>تاريخ الميلاد :</strong> " . $row['birthdate'] . "</p>";
            echo "<p><strong>العمر :</strong> " . $row['age'] . " سنة</p>";
            echo "<p><strong>الجنس :</strong> " . $row['gender'] . "</p>";
            echo "<p><strong>الحالة الاجتماعية :</strong> " . $row['marital_status'] . "</p>";
            echo "<p><strong>المستوى التعليمي :</strong> " . $row['education_level'] . "</p>";
            echo "<p><strong>مكان العمل :</strong> " . $row['work_place'] . "</p>";
            echo "<p><strong>الوظيفة :</strong> " . $row['job'] . "</p>";
            echo "<p><strong>عدد سنوات الخدمة :</strong> " . $row['years_of_service'] . "</p>";
            echo "<p><strong>الراتب الإجمالي :</strong> " . $row['total_salary'] . " دينار</p>";
            echo "<p><strong>القسط الشهري :</strong> " . $row['monthly_payment'] . " دينار</p>";
            echo "<p><strong>عدد الأشهر :</strong> " . $row['months_count'] . "</p>";
            echo "<p><strong>القرض الإجمالي :</strong> " . $row['total_loan'] . " دينار</p>"; // Nouveau champ ajouté
            echo "<p><strong>تاريخ التسجيل :</strong> " . $row['created_at'] . "</p>";

            // Ajouter le bouton pour télécharger la facture
            echo "<p>
                    <a href='generate_pdf.php?id=" . $row['id'] . "' class='btn btn-primary' style='background-color: #4CAF50; color: white; padding: 14px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;'>تحميل الاستمارة</a>
                    <a href='delete_form.php?id=" . $row['id'] . "' class='btn btn-danger' style='background-color:rgb(255, 0, 0); color: white; padding: 14px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;'>حدف الاستمارة</a>
                  </p>";

            echo "</div>";
        }
    } else {
        echo  "<div style='
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #d4edda;
    color: red;
    padding: 40px;
    border: 2px solid #c3e6cb;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 20px;
    z-index: 1000;
    width: 300px;
'>لم يتم العثور على أي استمارة.</div>";
header("refresh:2; url=index.html"); // Rediriger après 2 secondes
    exit();
    }
} else {
    echo "<p style='color: red;'>يرجى إدخال قيمة للبحث.</p>";
}

$conn->close();
?>
