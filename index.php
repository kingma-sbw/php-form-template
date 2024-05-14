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
  <form method="POST" action="./table-handler.php">
    <input type="text" placeholder="!!!some placeholder" name="!!! some fieldname"><br>

    <button name="action" value="create">Make</button>
  </form>

  <?php
  /**
   * Liste von Fächer mit LB name (aus der view lb_fach)
   * Jeder Zeile hat ein tiny-form um ein Datensätz zu löschen an Hand der ID (makeDeleteForm)
   * Und einen Link auf das update formular
   */
  ?>
  <table id="data-table">
    <?php foreach( $myTableManager->findAll() as $record ) : ?>

      <tr>
        <td><?= $record['!!!show this field'] ?></td>
      </tr>

    <?php endforeach; ?>
  </table>
</body>

</html>
