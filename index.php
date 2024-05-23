<?php declare(strict_types=1);

require './inc/lib.php';
/*
$myTableManger = new MyTableManager();
*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fach LB</title>
  <link href="./styles/main.css">
</head>

<body>
  <?php
  /**
   * Erstelle neues Record mit Formular
   */
  ?>
  <h2>Neues Fach</h2>
  <?php include 'create-form.php' ?>

  <?php include 'table-list.php' ?>


</body>

</html>
