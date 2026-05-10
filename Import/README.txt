1. Run `composer install` in this directory to install PhpSpreadsheet.
2. Update MySQL credentials in import_excel.php.
3. Create the database and table using:

CREATE DATABASE your_database;
USE your_database;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100)
);

4. Open index.html in a browser (served by Apache or PHP server) and upload an Excel file.