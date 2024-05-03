<?php declare(strict_types=1);
require './inc/lib.php';

checkParam( [ 'action' ] );

require './classes/FachManager.php';
switch($_POST['action']) {
  case 'create':
    create();
  case 'update':
    update();
  case 'delete':
    delete();
}

function create()
{
  checkParam( [ 'fach_name', 'lb_id' ] );
  $fachManager = new FachManager(  );
  $fachManager->create( $_POST['fach_name'], $_POST['lb_id'] );
}
function update()
{
  checkParam( [ 'fach_id', 'fach_name', 'lb_id' ] );
  $fachManager = new FachManager(  );


}
function delete()
{
  checkParam( [ 'fach_id' ] );

}
// Beispiel für die Verwendung der FachManager-Klasse


// Ein neues Fach eintragen
$new_fach_id = $fachManager->create( 'Mathematik', 123 );

// Ein Fach anhand der ID suchen und ausgeben
$fach = $fachManager->findById( $new_fach_id );
print_r( $fach );

// Den Namen eines Fachs aktualisieren
$fachManager->updateName( $new_fach_id, 'Neuer Fachname' );

// Ein Fach löschen
$fachManager->delete( $new_fach_id );
?>
;