<?php

$conn = new mysqli('127.0.0.1', 'root', '', 'pointage');

if (! empty($_POST["keyword"])) {
    $sql = $conn->prepare("SELECT * FROM stuf WHERE mecano LIKE  ? ORDER BY mecano LIMIT 0,6");
    $search = "{$_POST['keyword']}%";
    $sql->bind_param("s", $search);
    $sql->execute();
    $result = $sql->get_result();
    if (! empty($result)) {
        ?>
<ul id="country-list">
<?php
        foreach ($result as $mecano) {
            ?>
   <li
        onClick="selectCountry('<?php echo $mecano["mecano"]; ?>');">
      <?php echo $mecano["mecano"]; ?>
    </li>
<?php
        } // end for
        ?>
</ul>
<?php
    } // end if not empty
}
?>