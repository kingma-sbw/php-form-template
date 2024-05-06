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
  checkParam( [ 'Fach_Name', 'LB_ID' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->create( $_POST['Fach_Name'], $_POST['LB_ID'] );
}
function update()
{
  checkParam( [ 'Fach_ID', 'Fach_Name', 'LB_ID' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->update( $_POST['Fach_ID'], $_POST['Fach_Name'], $_POST['LB_ID'] );
}
function delete()
{
  checkParam( [ 'Fach_ID' ], $_POST );
  $fachManager = new FachManager();
  $fachManager->delete( $_POST['Fach_ID'] );
}
