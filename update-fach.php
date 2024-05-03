<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';

checkParam(['fach-id'], $_GET);
$fachManager = new FachManager();

$fach = $fachManager->findById($_GET['fach-id']);

?>
<script type="module" src="./scripts/main.js"></script>
<h2>Update Fach</h2>
<form method="POST" action="./form-handler.php">
  <input type="hidden" name="fach-id" value="<?= $_GET['fach-id'] ?>">
  <input type="text" placeholder="Fachname" name="fach-name" value="<?= $fach['Fach_Name'] ?>" size="50"><br>
  <input type="text" placeholder="LB Id" name="lb-id" value="<?= $fach['LB_ID'] ?>"><br> 
  <button name="action" value="update">Update</button>
</form>