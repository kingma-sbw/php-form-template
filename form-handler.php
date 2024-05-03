<?php declare(strict_types=1);
require './inc/lib.php';

checkParam( [ 'action' ], $_POST );

require './classes/FachManager.php';

switch($_POST['action']) {
  case 'create':
    create();
    break;
  case 'update':
    update();
    break;
  case 'delete':
    delete();
    break;
  default:
    trigger_error( 'unknown action ', E_USER_ERROR );
}
// Gehe zurück zum hauptfenster
header( "Location: /" );
exit();


// action functions
function create()
{
  checkParam( [ 'fach-name', 'lb-id' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->create( $_POST['fach-name'], $_POST['lb-id'] );
}
function update()
{
  checkParam( [ 'fach-id', 'fach-name', 'lb-id' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->update( $_POST['fach-id'], $_POST['fach-name'], $_POST['lb-id'] );
}
function delete()
{
  checkParam( [ 'fach-id' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->delete( $_POST['fach-id'] );
}
