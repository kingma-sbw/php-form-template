<?php declare(strict_types=1);

require './inc/lib.php';

$fachManager = new FachManager();
$lbManager   = new LbManager();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fach LB</title>
  <link rel="stylesheet" href="./styles/main.css">
</head>

<body>

  <h2>Neues Fach</h2>
  <form method="POST" action="./fach-handler.php">
    <input type="text" placeholder="Fachname" name="Fach_Name"><br>
    <?= $lbManager->makeSelect( 0, "LB_Name" ) ?>
    <button name="action" value="create">Make</button>
  </form>

  <?php
  /**
   * Liste von Fächer mit LB name (aus der view lb_fach)
   * Jeder Zeile hat ein tiny-form um ein Datensätz zu löschen an Hand der ID (makeDeleteForm)
   * Und einen Link auf das update formular
   */
  ?>
  <table id="fach-table">
    <?php foreach( $fachManager->findAll() as $fach ) : ?>

      <tr>
        <td><?= TableManager::makeDeleteForm( 'Fach_ID', $fach['Fach_ID'] ) ?></td>
        <td>
          <a href="./fach-update.php?Fach_ID=<?= $fach['Fach_ID'] ?>">
            <?= $fach['Fach_Name'] ?>
          </a>
        </td>
        <td><?= $fach['LB_Name'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>

</html>