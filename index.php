<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';

?>

<h2>new fach</h2>
<form method="POST" action="./form-handler.php">
  <input type="text" placeholder="Fachname" name="fach-name"><br>
  <input type="text" placeholder="LB Id" name="lb-id"><br> 
  <button name="action" value="create">Make</button>
</form>

<dialog id="new-fach">
  <form method="POST" action="./form-handler.php">
  <input type="hidden" name="fach-id" id="new-fach-id><br>
  <input type="text" placeholder="Fachname" name="fach-name" id="new-fach-name"><br>
  <input type="text" placeholder="LB Id" name="lb-id" id="new-lib-id"><br>
  <button name="action" value="update">Update</button>
</form>
</dialog>
<?php

$fachManager = new FachManager();
echo '<table id="fach-table">';
foreach( $fachManager->findAll() as $fach ) { 
  echo '<tr>';
  echo '<td>';
  echo $fach['Fach_ID'];
  echo '<td>';
  echo '<a href="./update-fach.php?fach-id=' . $fach['Fach_ID'] . '">';
  echo $fach['Fach_Name'];
  echo '</a>';
  echo '<td>' . $fach['LB_ID'];
  echo '<td>';
  makeDeleteForm( 'fach-id', $fach['Fach_ID'] );
}


function makeDeleteForm( string $key, int|string $id )
{
  ?>
  <form method="POST" action="./form-handler.php">
    <input type="hidden" name="<?= $key ?>" value="<?= $id ?>">
    <button name="action" value="delete">Delete</button>
  </form> <?php
}