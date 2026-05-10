function exportTableToExcel(tableID, filename) {
    var table = document.getElementById(tableID);
    var wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    
    // Create an Excel file and trigger a download
    XLSX.writeFile(wb, filename);
  }    