    <?php
	$connection = new mysqli('localhost', 'root', '', 'pointage'); // Replace with your database credentials

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Set the character encoding to UTF-8
    if (!$connection->set_charset("utf8")) {
        die("Error loading character set utf8: " . $connection->error);
    }
	?>