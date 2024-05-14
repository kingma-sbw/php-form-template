<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';
require './classes/LbManager.php';
$fachManager = new FachManager();
$lbManager = new LbManager();
?>

<h2>Neues Fach</h2>
<form method="POST" action="./fach-handler.php">
  <input type="text" placeholder="Fachname" name="Fach_Name"><br>
  <?= $lbManager->makeSelect(0, "LB_Name") ?>
  <button name="action" value="create">Make</button>
</form>

<?php


/**
 * Liste von Fächer mit LB name (aus der view lb_fach)
 * Jeder Zeile hat ein tiny-form um ein Datensätz zu löschen an Hand der ID (makeDeleteForm)
 * Und einen Link auf das update formular
 */
echo '<table id="fach-table">';
foreach( $fachManager->findAll() as $fach ) {
  ?>
  <tr>
  <td><?= makeDeleteForm( 'Fach_ID', $fach['Fach_ID'] ) ?>
  <td>
    <a href="./update-fach.php?Fach_ID=<?= $fach['Fach_ID'] ?>">
      <?= $fach['Fach_Name'] ?>
    </a>
  <td><?= $fach['LB_Name'] ?>

  <?php
}


/**
 * Create a form the sends a delete action for a fach table
 * @param $key the PK to create the delete
 * @param $id the value of the PK that will be deleted
 * 
 */
function makeDeleteForm( string $key, int|string $id ): void
{
  ?>
  <form method="POST" action="./fach-handler.php">
    <input type="hidden" name="<?= $key ?>" value="<?= $id ?>">
    <button name="action" value="delete">Delete</button>
  </form> <?php
}