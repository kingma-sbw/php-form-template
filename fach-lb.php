<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';
require './classes/LbManager.php';

$fachManager = new FachManager();
$lbManager = new LbManager();
?>

<form method="POST" action="./fach-lb-handler.php">

  <?= $fachManager->makeSelect(0,'fach_name')?></br>
  <?= $lbManager->makeSelect(0, 'lb_name')?><br>
  <button name='action' value='create'>Make</button>