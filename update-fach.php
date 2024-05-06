<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';


checkParam(['Fach_ID'], $_GET);
$fachManager = new FachManager();

$fach = $fachManager->findById($_GET['Fach_ID']);

?>
<script type="module" src="./scripts/main.js"></script>
<h2>Update Fach</h2>
<form method="POST" action="./form-handler.php">

  <input type="hidden" name="Fach_ID" value="<?= $_GET['Fach_ID'] ?>">
  <input type="text" placeholder="Fachname" name="Fach_Name" value="<?= $fach['Fach_Name'] ?>" size="50"><br>
  <?= $fachManager->makeSelect($fach['LB_ID'], "lb", "LB_ID", "LB_Name") ?>

  <button name="action" value="update">Update</button>
</form>


<?php 

