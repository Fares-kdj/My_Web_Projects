<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php'; // Autoload via Composer

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

// Vérification si un ID de facture est passé
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Récupérer les informations de la facture depuis la base de données
    $sql = "SELECT * FROM factures WHERE id = '$invoice_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les détails de la facture
        $row = $result->fetch_assoc();

        // Créer un objet TCPDF
        $pdf = new TCPDF();

        // Ajouter une page
        $pdf->AddPage();

        // Définir la police, ici en utilisant DejaVuSans qui supporte l'arabe
        $pdf->SetFont('dejavusans', '', 12);

        // Activer l'écriture RTL (droite à gauche)
        $pdf->setRTL(true);

        // Créer le contenu HTML du PDF avec le texte arabe
        $html = '
<style>
    body {
        font-family: "Cairo", sans-serif;
        direction: rtl;
        margin: 0;
        padding: 0;
    }
    h2 {
        text-align: center;
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
        font-weight: bold;
    }
    p {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin: 5px 0;
    }
    .invoice-container {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f9f9f9;
    }
    .invoice-header {
        text-align: center;
        font-size: 18px;
        margin-bottom: 30px;
        color: #004a7f;
    }
    .invoice-info {
        margin-top: 20px;
    }
    .invoice-info p {
        font-size: 16px;
    }
    .invoice-info .label {
        font-weight: bold;
        width: 200px;
        display: inline-block;
        margin-right: 10px;
    }
    .invoice-info .value {
        font-size: 16px;
        display: inline-block;
        color: #333;
    }
    .invoice-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        color: #777;
    }
</style>

<div class="invoice-container">
    <h2>استمارة رقم: ' . $row['id'] . '</h2>
    <div class="invoice-header">
        <h3>تفاصيل الاستمارة</h3>
    </div>
    <div class="invoice-info">
        <p><span class="label">اسم مقدم الطلب:</span><span class="value"> ' . $row['applicant_name'] . '</span></p>
        <p><span class="label">رقم التعريف الوطني:</span><span class="value"> ' . $row['national_id'] . '</span></p>
        <p><span class="label">تاريخ الميلاد:</span><span class="value"> ' . $row['birthdate'] . '</span></p>
        <p><span class="label">العمر:</span><span class="value"> ' . $row['age'] . ' سنة</span></p>
        <p><span class="label">الجنس:</span><span class="value"> ' . $row['gender'] . '</span></p>
        <p><span class="label">الحالة الاجتماعية:</span><span class="value"> ' . $row['marital_status'] . '</span></p>
        <p><span class="label">المستوى التعليمي:</span><span class="value"> ' . $row['education_level'] . '</span></p>
        <p><span class="label">مكان العمل:</span><span class="value"> ' . $row['work_place'] . '</span></p>
        <p><span class="label">الوظيفة:</span><span class="value"> ' . $row['job'] . '</span></p>
        <p><span class="label">عدد سنوات الخدمة:</span><span class="value"> ' . $row['years_of_service'] . '</span></p>
        <p><span class="label">الراتب الإجمالي:</span><span class="value"> ' . $row['total_salary'] . ' دينار</span></p>
        <p><span class="label">مبلغ القرض:</span><span class="value"> ' . $row['total_loan'] . ' دينار</span></p>
        <p><span class="label">القسط الشهري:</span><span class="value"> ' . $row['monthly_payment'] . ' دينار</span></p>
        <p><span class="label">عدد الأشهر:</span><span class="value"> ' . $row['months_count'] . '</span></p>
        <p><span class="label">تاريخ التسجيل:</span><span class="value"> ' . $row['created_at'] . '</span></p>
    </div>
    <div class="invoice-footer">
        <p>تم إصدار هذه الاستمارة بواسطة النظام الإلكتروني.</p>
    </div>
</div>
';

        // Charger le contenu HTML dans TCPDF
        $pdf->writeHTML($html);

        // Générer et afficher le PDF
        $pdf->Output('facture_' . $row['applicant_name'] . '.pdf', 'D'); // 'D' pour télécharger le PDF
    } else {
        echo "لا يوجد اي استمارة.";
    }
} else {
    echo "Aucun ID de facture spécifié.";
}

$conn->close();
?>
