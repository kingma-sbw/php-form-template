<?php declare(strict_types=1);

require './inc/lib.php';
require './classes/FachManager.php';
require './classes/LbManager.php';


checkParam( [ 'Fach_ID' ], $_GET );
$fachManager = new FachManager();
$lbManager = new LbManager();

if( $fach = $fachManager->findById( (int) $_GET['Fach_ID'] ) ) :

  ?>
  <script type="module" src="./scripts/main.js"></script>
  <h2>Update Fach</h2>
  <form method="POST" action="./fach-handler.php">

    <input type="hidden" name="Fach_ID" value="<?= $_GET['Fach_ID'] ?>">
    <input type="text" placeholder="Fachname" name="Fach_Name" value="<?= $fach['Fach_Name'] ?>" size="50"><br>
    <?= $lbManager->makeSelect( $fach['LB_ID'], "LB_Name" ) ?>

    <button name="action" value="update">Update</button>
  </form>


<?php else : ?>
  <h2>Fach nicht gefunden</h2>
<?php endif;