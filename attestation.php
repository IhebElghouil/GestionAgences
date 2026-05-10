<!DOCTYPE html>
<html lang="fr">
<head>
<?php session_start(); ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Attestation de travail</title>
  <!-- Font Awesome 6 (CDN officiel) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Google Fonts : alliance de sérieux et raffinement -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: white;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      font-family: 'Inter', sans-serif;
    }

    /* Carte principale attestation */
    .attest-card {
      max-width: 1050px;
      width: 100%;
      background: #ffffff;
      border-radius: 0px;
      box-shadow: none;
      overflow: hidden;
      transition: all 0.2s;
      border: 1px solid #cccccc;
    }

    /* En-tête chic */
    .header-premium {
      background: #ffffff;
      padding: 1.8rem 2.5rem;
      position: relative;
      border-bottom: 2px solid #000000;
    }

    .header-premium::after {
      content: "✦";
      position: absolute;
      bottom: -12px;
      right: 40px;
      font-size: 22px;
      color: #000000;
      background: #ffffff;
      padding: 0 8px;
      font-family: monospace;
    }

    .flex-header-premium {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .brand h2 {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 2rem;
      color: #000000;
      letter-spacing: -0.2px;
      margin-bottom: 0.2rem;
    }

    .brand p {
      color: #666666;
      font-size: 0.8rem;
      font-weight: 400;
    }

    /* LE STYLE DE <i> spécifique pour l'icône edit */
    .edit-style-badge {
      background: #f5f5f5;
      backdrop-filter: none;
      padding: 0.6rem 1.3rem;
      border-radius: 0px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
      font-size: 0.85rem;
      color: #000000;
      border: 1px solid #cccccc;
      transition: 0.2s;
      cursor: default;
    }

    .edit-style-badge i.fa-edit {
      font-size: 1.4rem;
      color: #000000;
      filter: none;
      transition: all 0.25s ease;
    }

    .edit-style-badge:hover i.fa-edit {
      transform: rotate(8deg) scale(1.1);
      color: #333333;
    }

    /* Corps attestation */
    .attest-body {
      padding: 2.5rem 2.8rem;
      background: #ffffff;
    }

    .title-section {
      text-align: center;
      margin-bottom: 2rem;
      border-bottom: 2px solid #cccccc;
      padding-bottom: 1rem;
    }

    .title-section h1 {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      font-weight: 600;
      color: #000000;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 0.4rem;
    }

    .title-section .sub {
      font-size: 0.75rem;
      color: #666666;
      letter-spacing: 0.5px;
    }

    .intro-text {
      background: #f9f9f9;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      line-height: 1.45;
      margin-bottom: 2rem;
      color: #000000;
      font-weight: 500;
      text-align: justify;
    }

    .intro-text .signataire-fonction-inline {
      display: inline;
      font-weight: bold;
      color: #000000;
    }

    /* Grille info moderne */
    .info-modern-grid {
      margin: 1.5rem 0 1rem;
      border-radius: 0px;
      background: #ffffff;
      box-shadow: none;
    }

    .info-row {
      display: flex;
      padding: 0.5rem 0;
      flex-wrap: wrap;
      gap: 0.4rem;
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .info-label {
      width: 250px;
      font-weight: 700;
      color: #000000;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .info-label i {
      width: 28px;
      font-size: 1.15rem;
      color: #000000;
    }

    .info-value {
      flex: 1;
      color: #000000;
      font-weight: 500;
    }

    .mention-box {
      border-radius: 0px;
      padding: 1.2rem 1.5rem;
      margin: 1.8rem 0;
      font-style: normal;
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .mention-box i {
      font-size: 1.6rem;
      color: #000000;
    }

    /* Style pour la signature avec sélecteur */
    .signature-foot {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      margin-top: 2rem;
      padding-top: 1.8rem;
      border-top: 2px solid #cccccc;
      align-items: flex-end;
    }

    .date-part p {
      margin-bottom: 5px;
      font-weight: 500;
    }

    .signature-part {
      text-align: right;
      flex: 1;
    }

    .signature-part .signataire-label {
      font-size: 1.1rem;
      font-weight: bold;
      color: #000000;
      margin-bottom: 5px;
    }

    .signature-part .signataire-nom {
      font-size: 1.1rem;
      font-weight: bold;
      color: #000000;
      margin-bottom: 5px;
    }

    .signature-part .signataire-fonction {
      font-size: 0.85rem;
      color: #666666;
      font-weight: normal;
    }

    .signature-line {
      width: 220px;
      height: 2px;
      background: #000000;
      margin: 0.7rem 0 0.3rem 0;
    }

    /* Style du sélecteur de signataire */
    .signataire-selector {
      margin-top: 20px;
      padding: 15px;
      background: #f9f9f9;
      border: 1px solid #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 15px;
      flex-wrap: wrap;
    }

    .signataire-selector label {
      font-weight: 600;
      color: #000000;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .signataire-selector select {
      padding: 8px 12px;
      font-family: 'Inter', sans-serif;
      font-size: 0.9rem;
      border: 1px solid #cccccc;
      background: white;
      color: #000000;
      cursor: pointer;
      border-radius: 0px;
      min-width: 200px;
    }

    .signataire-selector select:focus {
      outline: none;
      border-color: #000000;
    }

    .cachet-edit {
      font-size: 0.7rem;
      color: #666666;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 5px;
      margin-top: 6px;
    }

    /* footer avec encore une icône edit stylée */
    .footer-elegant {
      padding: 1rem 2.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      font-size: 0.7rem;
      color: #000000;
    }

    .edit-footer-style {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .edit-footer-style i.fa-edit {
      font-size: 0.9rem;
      color: #000000;
      transition: 0.2s;
    }

    .edit-footer-style:hover i.fa-edit {
      color: #333333;
      transform: scale(1.1);
    }

    .print-action {
      background: none;
      font-weight: 500;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      font-size: 0.75rem;
      color: #000000;
      padding: 4px 12px;
      transition: 0.2s;
    }

    .print-action i {
      margin-right: 6px;
    }

    .print-action:hover {
      background: #e0e0e0;
      color: #000000;
    }

    /* STYLES D'IMPRESSION */
    @media print {
      /* Cacher le bouton d'impression et le sélecteur */
      .print-action,
      .signataire-selector {
        display: none !important;
      }
      
      /* Supprimer les fonds gris pour économiser l'encre */
      body {
        background: white;
        padding: 0;
        margin: 0;
      }
      
      .attest-card {
        border: none;
        box-shadow: none;
        max-width: 100%;
      }
      
      .intro-text, .mention-box, .footer-elegant {
        background: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      /* Assurer que les couleurs noires restent noires */
      * {
        color: black !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      /* Éviter les coupures de page à l'intérieur des blocs importants */
      .info-modern-grid, .mention-box, .intro-text {
        page-break-inside: avoid;
      }
      
      /* Forcer l'affichage du signataire sélectionné */
      .signature-part {
        display: block !important;
      }
    }

    @media (max-width: 700px) {
      .attest-body {
        padding: 1.5rem;
      }
      .info-label {
        width: 140px;
      }
      .title-section h1 {
        font-size: 1.7rem;
      }
      .signature-part {
        text-align: left;
        margin-top: 1rem;
      }
      .signature-foot {
        flex-direction: column;
        gap: 1rem;
      }
      .signataire-selector {
        justify-content: flex-start;
      }
    }

    /* Style additionnel : une petite animation subtile pour toute icône edit dans la page */
    i.fa-edit {
      transition: transform 0.2s ease, color 0.2s;
    }
    i.fa-edit:hover {
      transform: scale(1.07);
      color: #333333 !important;
    }
    
    /* Message si aucune donnée trouvée */
    .error-message {
      background: #f5f5f5;
      padding: 2rem;
      text-align: center;
      color: #000000;
      font-weight: 500;
      border-radius: 0px;
    }
    
    .gender-badge {
      display: inline-block;
      background: #f0f0f0;
      padding: 0.2rem 0.8rem;
      border-radius: 0px;
      font-size: 0.8rem;
      margin-left: 8px;
      font-weight: normal;
    }
  </style>
</head>
<body>
<?php
// Démarrer la session si ce n'est pas déjà fait (pour $_SESSION['congidGA'])
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('DbConnexion.php');

// Vérifier si l'utilisateur est authentifié
if (isset($_SESSION['congidGA'])) {

    // Requête améliorée avec jointures pour récupérer toutes les informations nécessaires
    $query = "
        SELECT DISTINCT 
            social.nomFr, 
            social.ncnss, 
            stuf.daten, 
            stuf.daterec, 
            titres.libelletFr AS titre_libelle,
            stuf.sexe,
            dep.dep,
            stuf.cin,
            stuf.contrastage          
        FROM stuf
        LEFT JOIN dep ON stuf.dep = dep.id
        LEFT JOIN social ON social.mecano = stuf.mecano
        LEFT JOIN titres ON titres.id = stuf.titre
        
        WHERE stuf.contrastage IN (0,1,3)
        AND stuf.mecano=".$_GET['mecano']."
        LIMIT 1
    ";

    $stmt = mysqli_prepare($connection, $query);
    
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $nomFr, $ncnss, $daten, $daterec, $titre_libelle, $sexe, $dep, $cin, $contrastage);
        
        if (mysqli_stmt_num_rows($stmt) > 0 && mysqli_stmt_fetch($stmt)) {
            
            // Déterminer le genre
            $gender = ($sexe == 'M' || $sexe == 'm') ? 'Homme' 
                     : (($sexe == 'F' || $sexe == 'f') ? 'Femme' : 'Non spécifié');
            
            // Formater la date de naissance
            $date_naissance_formatee = !empty($daten) ? date('d/m/Y', strtotime($daten)) : 'Non renseignée';
            $date_embauche_formatee = !empty($daterec) ? date('d/m/Y', strtotime($daterec)) : 'Non renseignée';
            
            // Nettoyer le nom (enlever les espaces inutiles)
            $nom_complet = htmlspecialchars(trim($nomFr ?? 'Non renseigné'));
            
            // Déterminer le titre (M./Mme/...) en fonction du genre
            $civilite = '';
            if ($sexe == 'M' || $sexe == 'm') {
                $civilite = 'M.';
            } elseif ($sexe == 'F' || $sexe == 'f') {
                $civilite = 'Mme';
            }
            
            // Poste occupé (titre du poste)
            $poste_occupe = !empty($titre_libelle) ? htmlspecialchars($titre_libelle) : 'Non renseigné';
            
            // Type de contrat
            switch($contrastage){
                case 0:
                    $type_contrat_val = "Permanent";
                    break;
                case 1:
                    $type_contrat_val = "Stagiaire";
                    break;
                case 3:
                    $type_contrat_val = "Détaché";
                    break;
                default:
                    $type_contrat_val = "Non défini";
            }
            
            // Site d'affectation (délégation)
            $site_affectation = !empty($dep) ? htmlspecialchars($dep) : 'Non renseigné';
            
            // CIN pour information supplémentaire
            $cin_val = !empty($cin) ? htmlspecialchars($cin) : 'Non renseigné';
            $adresse_val = !empty($adresse) ? htmlspecialchars($adresse) : 'Non renseignée';
            
        } else {
            $error = "Aucune donnée trouvée pour les employés actifs.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Erreur de préparation de la requête: " . mysqli_error($connection);
    }
    
} else {
    $error = "Accès non autorisé. Veuillez vous connecter.";
}

// Liste des signataires possibles
$signataires = [
    ['nom' => '', 'fonction' => 'Président Directeur Général'],
    ['nom' => '', 'fonction' => 'Directeur des Ressources Humaines'],
    ['nom' => '', 'fonction' => 'Directeur Administratif et Financier'],
    ['nom' => '', 'fonction' => 'Dirigeant Délégataire']
];
?>

<div class="attest-card">
  <div class="attest-body">
    <div style="height:20px"></div>
    
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <div>
        <b>N° : ........../<?php echo date("Y"); ?></b>
      </div>
      <div>
        
		<strong>Gabès :</strong> le <span id="liveDate"></span>
      </div>
    </div>
    
    <div style="height:50px"></div>
    
    <div class="title-section">
      <h1>Attestation de travail</h1>
    </div>

    <?php if (isset($error)): ?>
      <div class="error-message">
        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
        <p><?php echo htmlspecialchars($error); ?></p>
        <p style="margin-top: 1rem; font-size: 0.85rem;">Veuillez contacter l'administrateur système.</p>
      </div>
    <?php else: ?>
    
    <div class="intro-text">
      
      Je soussigné, le <span class="signataire-fonction-inline" id="signataireFonctionInline">Président Directeur Général</span> de la Société Régionale de Transport de Gabès <strong>'SOTREGAMES'</strong>, la présente de la véracité des informations suivantes :
    </div>

    <div class="info-modern-grid">
      <div class="info-row">
        <div class="info-label"><i class="fas fa-user-circle"></i> Nom & Prénom :</div>
        <div class="info-value">
          <?php echo $civilite . ' ' . $nom_complet; ?>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-id-card"></i> Carte d'identité :</div>
        <div class="info-value"><?php echo $cin_val; ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-calendar"></i> Date de naissance :</div>
        <div class="info-value"><?php echo $date_naissance_formatee; ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-passport"></i> N° Sécurité sociale :</div>
        <div class="info-value"><?php echo htmlspecialchars($ncnss ?? 'Non renseigné'); ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-briefcase"></i> Grade :</div>
        <div class="info-value"><?php echo $poste_occupe; ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-calendar-alt"></i> Date d'embauche :</div>
        <div class="info-value"><?php echo $date_embauche_formatee; ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-file-contract"></i> Statut :</div>
        <div class="info-value"><?php echo $type_contrat_val; ?></div>
      </div>
      <div class="info-row">
        <div class="info-label"><i class="fas fa-building"></i> Site d'affectation :</div>
        <div class="info-value"><?php echo $site_affectation; ?></div>
      </div>
    </div>
    
    <div class="mention-box">
      <div>
	  <?php
	   if ($sexe == 'M' || $sexe == 'm') {
        echo 'La présente attestation est délivrée à l’intéressé pour servir et valoir ce que de droit.';
	   }elseif ($sexe == 'F' || $sexe == 'f')
	   {
		    echo 'La présente attestation est délivrée à l’intéressée pour servir et valoir ce que de droit.';
	   }
		
		
		?>
	 </div>
    </div>

   <div class="signature-foot">
  <div class="date-part">
    <!-- Espace réservé pour la date si nécessaire -->
  </div>
  <div class="signature-part" id="signaturePart">
    <div class="signataire-nom" id="signataireDisplay">P/Le <span id="signataireFonctionSignature">Président Directeur Général</span></div>
  </div>
</div>
    
    <!-- Sélecteur de signataire (caché à l'impression) -->
    <div class="signataire-selector">
      <label>
        <i class="fas fa-signature"></i> Choisir le signataire :
      </label>
      <select id="signataireSelect">
        <?php foreach($signataires as $index => $signataire): ?>
          <option value="<?php echo $index; ?>" data-nom="<?php echo htmlspecialchars($signataire['nom']); ?>" data-fonction="<?php echo htmlspecialchars($signataire['fonction']); ?>">
            <?php echo htmlspecialchars($signataire['nom']) . ' - ' . htmlspecialchars($signataire['fonction']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <?php endif; ?>
  </div>

  <div class="footer-elegant">
    <button class="print-action" onclick="window.print();">
      <i class="fas fa-print"></i> Imprimer / PDF
    </button>
  </div>
</div>

<script>
  // date du jour format français (pour la signature)
  const today = new Date();
  const optionsDate = { year: 'numeric', month: 'long', day: 'numeric' };
  const dateElement = document.getElementById('liveDate');
  if (dateElement) {
    dateElement.innerText = today.toLocaleDateString('fr-FR', optionsDate);
  }
  
  // Gestion du changement de signataire
  const signataireSelect = document.getElementById('signataireSelect');
  const signataireFonctionInline = document.getElementById('signataireFonctionInline');
  const signataireFonctionSignature = document.getElementById('signataireFonctionSignature');
  
  if (signataireSelect) {
    signataireSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const fonction = selectedOption.getAttribute('data-fonction');
      const nom = selectedOption.getAttribute('data-nom');
      
      // Mettre à jour la fonction dans la phrase d'introduction
      if (signataireFonctionInline) signataireFonctionInline.textContent = fonction;
      
      // Mettre à jour la fonction dans la signature (P/Le XXXX)
      if (signataireFonctionSignature) signataireFonctionSignature.textContent = fonction;
      
      // Optionnel: si vous voulez aussi afficher le nom quelque part
      // Vous pouvez ajouter un élément pour le nom si nécessaire
    });
  }
  
  // Optionnel: amélioration de l'impression
  window.onbeforeprint = function() {
    document.body.style.background = "white";
  };
  window.onafterprint = function() {
    document.body.style.background = "";
  };
</script>

<?php
// Fermer la connexion à la base de données si elle existe
if (isset($connection)) {
    mysqli_close($connection);
}
?>

</body>
</html>