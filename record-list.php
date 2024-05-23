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