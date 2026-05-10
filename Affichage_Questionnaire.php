<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="CSS/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
    /* Styles pour assurer l'affichage sur une page A4 */
    @page {
        size: A4;
        margin: 1.5cm;
    }
    
    body {
        font-family: Arial, sans-serif;
        width: 21cm; /* Largeur A4 */
        min-height: 29.7cm; /* Hauteur A4 */
        margin: 0 auto;
        padding: 1.5cm;
        box-sizing: border-box;
        line-height: 1.4;
        background-color: #fff;
    }
    
    .a4-container {
        width: 100%;
        max-width: 100%;
        height: 26.7cm; /* 29.7cm - 2*1.5cm de marges */
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd; /* Pour visualiser les limites */
    }
    
    .content-section {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .answer-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .signature-lines {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        margin: 10px 0;
        min-height: 200px; /* Hauteur minimale garantie */
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    
    td {
        padding: 5px;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    p {
        font-size: 14px;
        margin: 5px 0;
        line-height: 1.4;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-left {
        text-align: left;
    }
    
    .border-bottom {
        border-bottom: 1px solid #000;
    }
    
    .signature-line {
        border-bottom: 2px dotted #000; /* Ligne pointillée */
        height: 25px; /* Hauteur fixe pour chaque ligne */
        margin: 8px 0;
        width: 100%;
        display: block;
    }
    
    .separator {
        text-align: center;
        margin: 10px 0;
        border-top: 1px dashed #ccc;
        padding-top: 10px;
    }
    
    .no-print {
        text-align: center;
        margin-top: 20px;
    }
    
    button {
        padding: 5px 15px;
        background: #99e0b2;
        border: 0 none;
        cursor: pointer;
        border-radius: 5px;
        font-size: medium;
        margin: 5px;
    }
    
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        
        .no-print {
            display: none;
        }
        
        .a4-container {
            border: none;
        }
        
        .signature-line {
            border-bottom: 2px dotted #000; /* Assurer l'impression des pointillés */
        }
    }
</style>
</head>

<body>
<?php
session_start();
require('connection.php');

$id = $_GET['id'];
$reqNom = mysqli_query($connection, "SELECT mecano, nom, grade, datefaute, datequestionnaire, faute FROM sanctions WHERE idsanction='".$id."'");

$num_rows = mysqli_num_rows($reqNom);
if ($num_rows > 0) {
    while($rNom = mysqli_fetch_row($reqNom)) {
        $nom = $rNom[1];
        $mecano = $rNom[0];
        $grade = $rNom[2];
        $datefaute = $rNom[3];
        $datequestionnaire = $rNom[4];
        $faute = $rNom[5];
    }
}
?>

<div class="a4-container">
    <div class="content-section">
        <table dir="rtl">
            <tr>
                <td colspan="6" class="text-right">
                    <p>الشركة الجهويّة للنقل بقابس</p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-center">
                    <h2><u>الإستجواب</u></h2>
                </td>
            </tr>
            <tr>
                <td class="text-right" width="12%">
                    <p><b><u>العون : </u></b></p>
                </td>
                <td width="25%">
                    <p><b><?php echo $nom; ?></b></p>
                </td>
                <td class="text-right" width="12%">
                    <p><b><u>الرتبة : </u></b></p>
                </td>
                <td width="18%">
                    <p><b><?php echo $grade; ?></b></p>
                </td>
                <td class="text-right" width="18%">
                    <p><b><u>الرقم الآلي : </u></b></p>
                </td>
                <td width="15%">
                    <p><b><?php echo $mecano; ?></b></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-right">
                    <p><b><u>السؤال :</u></b></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-right">
                    <p><?php echo $faute; ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-right">
                    <p><b>*إن عدم الإجابة عن الاستجواب في ظرف ثلاثة أيام من تاريخ تسلمه لا يمنع من مواصلة التتبعات الإدارية وذلك طبقا للفصل 40 من النظام الأساسي الخاص.</b></p>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-left">
                    <p><b>قابس في :</b></p>
                </td>
                <td class="text-right">
                    <p><b><?php echo $datequestionnaire; ?></b></p>
                </td>
            </tr>
			<tr>
                <td colspan="6">
                    <p></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="separator">
                    <p>***********************</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="answer-section">
        <table dir="rtl">
            <tr>
                <td colspan="6" class="text-right">
                    <p><b><u>الإجابة :</u></b></p>
                </td>
            </tr>
        </table>
        
        <div class="signature-lines" id="signatureLines">
            <!-- Les lignes de signature seront générées dynamiquement par JavaScript -->
        </div>
        
        <!-- Séparateur fixe en bas de la zone de signature -->
        <div class="separator" id="fixedSeparator">
            <p>°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°</p>
        </div>
        
        <table dir="rtl">
           <!-- <tr>
                <td colspan="6">
                    <p><u>ملاحظات الرئيس المباشر :</u></p>
                    <div class="signature-line"></div>
                </td>
            </tr> -->
            <tr>
                <td colspan="6">
                    <p><u>رأي رئيس المصلحة :</u></p>
                    <div class="signature-line"></div>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <p><u>قرار الرئيس المدير العام :</u></p>
                    <div class="signature-line"></div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="no-print">
    <button onclick="window.print()">طباعة</button>
    <button onclick="adjustSignatureLines()">تعديل عدد الأسطر</button>
    <button onclick="addSignatureLine()">إضافة سطر</button>
    <button onclick="removeSignatureLine()">حذف سطر</button>
</div>

<script>
    // Variable pour suivre le nombre de lignes
    let signatureLineCount = 0;
    
    // Fonction pour ajuster dynamiquement le nombre de lignes de signature
    function adjustSignatureLines() {
        const container = document.querySelector('.a4-container');
        const signatureSection = document.getElementById('signatureLines');
        
        // Réinitialiser les lignes
        signatureSection.innerHTML = '';
        signatureLineCount = 0;
        
        // Calculer l'espace disponible de manière plus précise
        const containerHeight = container.offsetHeight;
        const usedHeight = container.scrollHeight;
        const availableHeight = containerHeight - usedHeight;
        
        console.log("Espace disponible: " + availableHeight + "px");
        
        // Calculer le nombre de lignes nécessaires
        const lineHeight = 35; // Hauteur d'une ligne avec marge
        const neededLines = Math.max(5, Math.floor(availableHeight / lineHeight));
        
        // Générer les lignes
        for (let i = 0; i < neededLines; i++) {
            addSignatureLine();
        }
    }
    
    // Fonction pour ajouter manuellement une ligne AU-DESSUS du séparateur
    function addSignatureLine() {
        const signatureSection = document.getElementById('signatureLines');
        const line = document.createElement('div');
        line.className = 'signature-line';
        signatureSection.appendChild(line);
        signatureLineCount++;
        
        console.log("Ligne pointillée ajoutée. Total: " + signatureLineCount);
    }
    
    // Fonction pour supprimer une ligne
    function removeSignatureLine() {
        const signatureSection = document.getElementById('signatureLines');
        const lines = signatureSection.getElementsByClassName('signature-line');
        if (lines.length > 0) {
            signatureSection.removeChild(lines[lines.length - 1]);
            signatureLineCount--;
            console.log("Ligne supprimée. Total: " + signatureLineCount);
        }
    }
    
    // Ajuster les lignes au chargement de la page
    window.addEventListener('load', function() {
        // Petit délai pour s'assurer que tout est chargé
        setTimeout(adjustSignatureLines, 100);
    });
    
    // Ajuster également lors du redimensionnement
    window.addEventListener('resize', adjustSignatureLines);
</script>

</body>
</html>