<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
session_start();
require('connection.php');
?>
<link rel="stylesheet" href="StyleSheet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<title>Historique d'accès</title>
<style>
:root {
  --primary-color: #3498db;
  --secondary-color: #2c3e50;
  --accent-color: #e74c3c;
  --light-color: #ecf0f1;
  --dark-color: #34495e;
  --success-color: #2ecc71;
  --warning-color: #f39c12;
  --danger-color: #e74c3c;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f7fa;
  color: #333;
  line-height: 1.6;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.header-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--secondary-color);
  color: white;
  padding: 15px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.header-bar h1 {
  font-size: 1.5rem;
  margin: 0;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  color: white;
  text-decoration: none;
  font-weight: bold;
  padding: 8px 15px;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.logout-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.page-title {
  text-align: center;
  margin: 20px 0;
  color: var(--secondary-color);
  font-size: 2rem;
}

.controls-panel {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filter-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-group label {
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--dark-color);
}

.filter-input {
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

.filter-input:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.actions-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
}

.export-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: var(--success-color);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  transition: background-color 0.3s;
}

.export-btn:hover {
  background-color: #27ae60;
}

.stats-section {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.stat-card {
  background-color: white;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  min-width: 150px;
  text-align: center;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: bold;
  color: var(--primary-color);
}

.stat-label {
  font-size: 0.9rem;
  color: var(--dark-color);
  margin-top: 5px;
}

.table-container {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 20px;
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background-color: var(--secondary-color);
  color: white;
  padding: 15px;
  text-align: right;
  font-weight: 600;
  position: sticky;
  top: 0;
}

.data-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
}

.data-table tr:hover {
  background-color: #f9f9f9;
}

.data-table tr:last-child td {
  border-bottom: none;
}

.login-id {
  font-weight: 600;
  color: var(--primary-color);
}

.login-date {
  direction: ltr;
  text-align: left;
}

.ip-address {
  font-family: monospace;
  color: var(--dark-color);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-badge.online {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.status-badge.offline {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.status-badge i {
  font-size: 0.75rem;
}

.last-login-badge {
  background-color: var(--warning-color);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

.pagination button {
  background-color: white;
  border: 1px solid #ddd;
  padding: 8px 15px;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}

.pagination button:hover {
  background-color: #f5f5f5;
}

.pagination button.active {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.no-data {
  text-align: center;
  padding: 40px;
  color: var(--dark-color);
  font-size: 1.2rem;
}

.refresh-btn {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: background-color 0.3s;
}

.refresh-btn:hover {
  background-color: #2980b9;
}

.last-seen {
  font-size: 0.7rem;
  color: #666;
  margin-top: 3px;
}

@media (max-width: 768px) {
  .header-bar {
    flex-direction: column;
    gap: 15px;
  }
  
  .filter-section {
    grid-template-columns: 1fr;
  }
  
  .actions-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .stats-section {
    justify-content: center;
  }
  
  .data-table {
    font-size: 0.9rem;
  }
  
  .data-table th, .data-table td {
    padding: 10px 8px;
  }
}
</style>
</head>
<body>
<div class="container">
  <div class="header-bar">
    <h1>نظام متابعة الولوج</h1>
    <a href="logout.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      <span>خروج</span>
    </a>
  </div>

  <?php include('menu.php'); ?>

  <h2 class="page-title">متابعة الولوج إلى التطبيقة</h2>

  <?php
  if (!isset($_SESSION['congidGA'])) {
    echo '<script>document.location.replace("index.php");</script>';
  }
  ?>

  <div class="controls-panel">
    <div class="filter-section">
      <div class="filter-group">
        <label for="loginFilter">البحث بمعرف الولوج</label>
        <input type="text" id="loginFilter" class="filter-input" placeholder="أدخل معرف الولوج...">
      </div>
      <div class="filter-group">
        <label for="dateFilter">البحث بالتاريخ</label>
        <input type="date" id="dateFilter" class="filter-input">
      </div>
      <div class="filter-group">
        <label for="yearFilter">تصفية حسب السنة</label>
        <select id="yearFilter" class="filter-input">
          <option value="">جميع السنوات</option>
          <?php
          $currentYear = date('Y');
          for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            $selected = ($year == $currentYear) ? 'selected' : '';
            echo "<option value='$year' $selected>$year</option>";
          }
          ?>
        </select>
      </div>
      <div class="filter-group">
        <label for="statusFilter">تصفية حسب الحالة</label>
        <select id="statusFilter" class="filter-input">
          <option value="all">جميع المستخدمين</option>
          <option value="online">متصل الآن</option>
          <option value="offline">غير متصل</option>
        </select>
      </div>
      <div class="filter-group">
        <label for="sortFilter">ترتيب حسب</label>
        <select id="sortFilter" class="filter-input">
          <option value="date-desc" selected>أحدث الولوج أولاً</option>
          <option value="date-asc">أقدم الولوج أولاً</option>
          <option value="login">معرف الولوج</option>
          <option value="status">الحالة</option>
        </select>
      </div>
    </div>
    
    <div class="actions-section">
      <div style="display: flex; gap: 10px;">
        <button class="export-btn" onclick="exportTableToExcel('myTable', 'LoginHistory')">
          <i class="fas fa-file-excel"></i>
          <span>تصدير إلى Excel</span>
        </button>
        <button class="refresh-btn" onclick="checkOnlineStatus()">
          <i class="fas fa-sync-alt"></i>
          <span>تحديث الحالة</span>
        </button>
      </div>
      
      <div class="stats-section">
        <div class="stat-card">
          <div class="stat-value" id="totalLogins">0</div>
          <div class="stat-label">إجمالي المستخدمين</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="onlineUsers">0</div>
          <div class="stat-label">متصل الآن</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="offlineUsers">0</div>
          <div class="stat-label">غير متصل</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="lastMonth">0</div>
          <div class="stat-label">آخر 30 يوم</div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-container">
    <table id="myTable" class="data-table">
      <thead>
        <tr>
          <th style="width: 20%;">معرف الولوج</th>
          <th style="width: 20%;">تاريخ آخر دخول</th>
          <th style="width: 20%;">آخر عنوان IP</th>
          <th style="width: 15%;">الحالة</th>
          <th style="width: 15%;">آخر نشاط</th>
          <th style="width: 10%;"></th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php 
        if (isset($_SESSION['congidGA'])) {
          $departement = $_SESSION['departement'];
          
          // Charger TOUTES les données historiques
          if ($_SESSION['departement'] == "admin") {
            $re = mysqli_query($connection, "SELECT * FROM loginhistory ORDER BY datelogin DESC");
          } else {
            $re = mysqli_query($connection, "SELECT * FROM loginhistory WHERE departement = '$departement' ORDER BY datelogin DESC");
          }
          
          // Stocker toutes les données dans un tableau PHP
          $allData = array();
          while ($r = mysqli_fetch_row($re)) {
            $allData[] = $r;
          }
          
          // Traiter les données pour n'afficher que la dernière connexion par utilisateur
          $userLastLogin = array();
          foreach ($allData as $r) {
            $loginId = $r[1]; // Colonne 1 = identifiant de connexion
            $loginDate = $r[2]; // Colonne 2 = date de connexion
            
            if (!isset($userLastLogin[$loginId]) || $loginDate > $userLastLogin[$loginId]['date']) {
              $userLastLogin[$loginId] = array(
                'data' => $r,
                'date' => $loginDate
              );
            }
          }
          
          // Trier par date décroissante
          usort($userLastLogin, function($a, $b) {
            return $b['date'] <=> $a['date'];
          });
          
          // Afficher les données
          if (count($userLastLogin) > 0) {
            foreach ($userLastLogin as $user) {
              $r = $user['data'];
              $loginId = $r[1];
              $lastDate = $r[2];
              
              // Calculer le temps écoulé depuis la dernière connexion
              $lastTimestamp = strtotime($lastDate);
              $now = time();
              $diffMinutes = floor(($now - $lastTimestamp) / 60);
              
              // Considérer comme en ligne si activité dans les 5 dernières minutes
              $isOnline = ($diffMinutes < 5);
              $statusClass = $isOnline ? 'online' : 'offline';
              $statusText = $isOnline ? 'متصل' : 'غير متصل';
              $statusIcon = $isOnline ? 'fa-circle' : 'fa-circle';
              
              // Formater le temps écoulé
              if ($diffMinutes < 60) {
                $lastActivity = $diffMinutes . ' دقيقة مضت';
              } elseif ($diffMinutes < 1440) {
                $lastHours = floor($diffMinutes / 60);
                $lastActivity = $lastHours . ' ساعة مضت';
              } else {
                $lastDays = floor($diffMinutes / 1440);
                $lastActivity = $lastDays . ' يوم مضت';
              }
              
              echo '
              <tr data-login="' . $loginId . '" data-lastactivity="' . $lastTimestamp . '">
                <td class="login-id">' . $loginId . '</td>
                <td class="login-date">' . $lastDate . '</td>
                <td class="ip-address">' . $r[3] . '</td>
                <td>
                  <span class="status-badge ' . $statusClass . '">
                    <i class="fas ' . $statusIcon . '"></i>
                    <span>' . $statusText . '</span>
                  </span>
                </td>
                <td class="last-seen-cell">' . $lastActivity . '</td>
                <td><span class="last-login-badge">آخر دخول</span></td>
              </tr>
              ';
            }
          } else {
            echo '<tr><td colspan="6" class="no-data">لا توجد بيانات للعرض</td></tr>';
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  
  <div class="pagination" id="pagination">
    <!-- La pagination sera générée par JavaScript -->
  </div>
</div>

<script>
// Données globales pour le filtrage et la pagination
let allLogins = [];
let filteredLogins = [];
let currentPage = 1;
const rowsPerPage = 15;
let statusCheckInterval;

// Fonction pour vérifier le statut en ligne/hors ligne
function checkOnlineStatus() {
  const now = Math.floor(Date.now() / 1000);
  const rows = document.querySelectorAll('#myTable tbody tr');
  
  rows.forEach(row => {
    const lastActivity = parseInt(row.getAttribute('data-lastactivity'));
    if (lastActivity) {
      const diffMinutes = Math.floor((now - lastActivity) / 60);
      const isOnline = diffMinutes < 5;
      const statusSpan = row.querySelector('.status-badge');
      const statusText = isOnline ? 'متصل' : 'غير متصل';
      const statusIcon = isOnline ? 'fa-circle' : 'fa-circle';
      const statusClass = isOnline ? 'online' : 'offline';
      
      // Mettre à jour l'affichage du statut
      statusSpan.className = `status-badge ${statusClass}`;
      statusSpan.innerHTML = `<i class="fas ${statusIcon}"></i><span>${statusText}</span>`;
      
      // Mettre à jour le dernier activité
      const lastSeenCell = row.querySelector('.last-seen-cell');
      if (diffMinutes < 60) {
        lastSeenCell.textContent = diffMinutes + ' دقيقة مضت';
      } else if (diffMinutes < 1440) {
        const lastHours = Math.floor(diffMinutes / 60);
        lastSeenCell.textContent = lastHours + ' ساعة مضت';
      } else {
        const lastDays = Math.floor(diffMinutes / 1440);
        lastSeenCell.textContent = lastDays + ' يوم مضت';
      }
    }
  });
  
  // Mettre à jour les statistiques après vérification
  updateStats();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
  // Récupérer toutes les données du tableau
  const tableRows = document.querySelectorAll('#myTable tbody tr');
  allLogins = Array.from(tableRows).map(row => {
    const cells = row.querySelectorAll('td');
    const statusBadge = row.querySelector('.status-badge');
    const isOnline = statusBadge ? statusBadge.classList.contains('online') : false;
    return {
      login: cells[0].textContent,
      date: cells[1].textContent,
      ip: cells[2].textContent,
      status: isOnline ? 'online' : 'offline',
      lastActivity: parseInt(row.getAttribute('data-lastactivity')),
      isLast: cells[5].innerHTML.includes('last-login-badge'),
      element: row
    };
  });
  
  // Initialiser les statistiques
  updateStats();
  
  // Appliquer les filtres initiaux
  applyFilters();
  
  // Ajouter les écouteurs d'événements pour les filtres
  document.getElementById('loginFilter').addEventListener('input', applyFilters);
  document.getElementById('dateFilter').addEventListener('change', applyFilters);
  document.getElementById('yearFilter').addEventListener('change', applyFilters);
  document.getElementById('statusFilter').addEventListener('change', applyFilters);
  document.getElementById('sortFilter').addEventListener('change', applyFilters);
  
  // Vérifier le statut toutes les 30 secondes
  statusCheckInterval = setInterval(checkOnlineStatus, 30000);
});

// Fonction pour appliquer tous les filtres
function applyFilters() {
  const loginFilter = document.getElementById('loginFilter').value.toLowerCase();
  const dateFilter = document.getElementById('dateFilter').value;
  const yearFilter = document.getElementById('yearFilter').value;
  const statusFilter = document.getElementById('statusFilter').value;
  const sortFilter = document.getElementById('sortFilter').value;
  
  // Filtrer les données
  filteredLogins = allLogins.filter(login => {
    // Filtre par identifiant de connexion
    const loginMatch = login.login.toLowerCase().includes(loginFilter);
    
    // Filtre par date spécifique
    let dateMatch = true;
    if (dateFilter) {
      const loginDate = new Date(login.date).toISOString().split('T')[0];
      dateMatch = loginDate === dateFilter;
    }
    
    // Filtre par année
    let yearMatch = true;
    if (yearFilter) {
      const loginYear = new Date(login.date).getFullYear().toString();
      yearMatch = loginYear === yearFilter;
    }
    
    // Filtre par statut
    let statusMatch = true;
    if (statusFilter !== 'all') {
      statusMatch = login.status === statusFilter;
    }
    
    return loginMatch && dateMatch && yearMatch && statusMatch;
  });
  
  // Trier les données
  sortLogins(sortFilter);
  
  // Mettre à jour l'affichage
  currentPage = 1;
  displayPage();
  updateStats();
}

// Fonction de tri
function sortLogins(sortType) {
  switch(sortType) {
    case 'date-desc':
      filteredLogins.sort((a, b) => new Date(b.date) - new Date(a.date));
      break;
    case 'date-asc':
      filteredLogins.sort((a, b) => new Date(a.date) - new Date(b.date));
      break;
    case 'login':
      filteredLogins.sort((a, b) => a.login.localeCompare(b.login));
      break;
    case 'status':
      filteredLogins.sort((a, b) => {
        if (a.status === 'online' && b.status !== 'online') return -1;
        if (a.status !== 'online' && b.status === 'online') return 1;
        return 0;
      });
      break;
  }
}

// Fonction pour afficher la page actuelle
function displayPage() {
  const tableBody = document.getElementById('tableBody');
  const pagination = document.getElementById('pagination');
  
  // Vider le tableau
  tableBody.innerHTML = '';
  
  // Calculer les indices de début et de fin
  const startIndex = (currentPage - 1) * rowsPerPage;
  const endIndex = Math.min(startIndex + rowsPerPage, filteredLogins.length);
  
  // Afficher les lignes de la page actuelle
  for (let i = startIndex; i < endIndex; i++) {
    const login = filteredLogins[i];
    tableBody.appendChild(login.element.cloneNode(true));
  }
  
  // Gérer le cas où il n'y a pas de données
  if (filteredLogins.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="6" class="no-data">لا توجد بيانات تطابق معايير البحث</td></tr>';
  }
  
  // Mettre à jour la pagination
  updatePagination();
}

// Fonction pour mettre à jour la pagination
function updatePagination() {
  const pagination = document.getElementById('pagination');
  const totalPages = Math.ceil(filteredLogins.length / rowsPerPage);
  
  // Vider la pagination
  pagination.innerHTML = '';
  
  if (totalPages <= 1) return;
  
  // Bouton précédent
  const prevButton = document.createElement('button');
  prevButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
  prevButton.disabled = currentPage === 1;
  prevButton.addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      displayPage();
    }
  });
  pagination.appendChild(prevButton);
  
  // Pages
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
  
  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    const pageButton = document.createElement('button');
    pageButton.textContent = i;
    pageButton.className = i === currentPage ? 'active' : '';
    pageButton.addEventListener('click', () => {
      currentPage = i;
      displayPage();
    });
    pagination.appendChild(pageButton);
  }
  
  // Bouton suivant
  const nextButton = document.createElement('button');
  nextButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
  nextButton.disabled = currentPage === totalPages;
  nextButton.addEventListener('click', () => {
    if (currentPage < totalPages) {
      currentPage++;
      displayPage();
    }
  });
  pagination.appendChild(nextButton);
}

// Fonction pour mettre à jour les statistiques
function updateStats() {
  document.getElementById('totalLogins').textContent = filteredLogins.length;
  
  // Compter les utilisateurs en ligne et hors ligne
  const onlineCount = filteredLogins.filter(login => login.status === 'online').length;
  const offlineCount = filteredLogins.filter(login => login.status === 'offline').length;
  document.getElementById('onlineUsers').textContent = onlineCount;
  document.getElementById('offlineUsers').textContent = offlineCount;
  
  // Compter les connexions du dernier mois
  const lastMonth = new Date();
  lastMonth.setMonth(lastMonth.getMonth() - 1);
  const lastMonthLogins = filteredLogins.filter(login => new Date(login.date) >= lastMonth);
  document.getElementById('lastMonth').textContent = lastMonthLogins.length;
}

// Fonction d'export Excel
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>
</body>
</html>