<?php declare(strict_types=1);

require './inc/lib.php';

checkParam( [ '!!some form id' ], $_GET );

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

  <?php if( /* findById() */ ) : ?>
    <?php
    /**
     * Update Formular für fach
     * Auswahl der LB aus Table lb LbManager
     */
    ?>
    <h2>Update Fach</h2>
    <form method="POST" action="./fach-handler.php">

      <input type="hidden" name="Fach_ID" value="<?= $_GET['!!soem form id'] ?>">
      <input type="text" placeholder="Fachname" name="!!some name!" value="<?= $table['!!some table field!'] ?>" size="50"><br>
      <?= /* makeSelect(  ) */ ?>

      <button name="action" value="update">Update</button>
    </form>


  <?php else : ?>
    <h2>Fach nicht gefunden</h2>
  <?php endif; ?>
</body>

</html>