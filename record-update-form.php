<?php declare(strict_types=1);

require './inc/lib.php';

checkParam( [ 'id' ], $_GET );
$id = $_GET['id'];

/*
$myTableManager = new MyTableManager();
$record = $myTableManager->findById($id);
$otherTableManager = new OtherTableManager();
*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update</title>
  <link href="./styles/main.css">
</head>

<body>

  <?php if( $record ) : 
    $Oldvalue1 = $record['!!fieldname1'];
    $OldValue2 = $record['!!fieldname2'];
  ?>
    <?php
    /**
     * Update Formular für fach
     * Auswahl der LB aus Table lb LbManager
     */
    ?>
    <h2>Update Fach</h2>
    <form method="POST" action="./fach-handler.php">

      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="text" placeholder="Somefield1" name="!!sfieldname1" value="<?= $OldValue1 ?>" size="50"><br>
      <input type="text" placeholder="Somefield2" name="!!sfieldname2" value="<?= $OldValue2 ?>" size="50"><br>
      <?= /* $otherTableManager->makeSelect( $oldForeinKey, 'Showfield' ) */ ?>

      <button name="action" value="update">Update</button>
    </form>


  <?php else : ?>
    <h2>Datensatz nicht gefunden</h2>
  <?php endif; ?>
</body>

</html>
