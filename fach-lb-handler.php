<?php declare(strict_types=1);
require './inc/lib.php';

checkParam( [ 'action' ], $_POST );

require './classes/FachLbManager.php';

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
  checkParam( [ 'fach_id', 'lb_id' ], $_POST );
  $fachlbManager = new FachLBManager();
  $fachlbManager->create( $_POST['fach_id'], $_POST['lb_id'] );
}
function update()
{
  checkParam( [ 'fach_id', 'lb_id', 'new_fach_id', 'new_lb_id' ], $_POST );
  $fachlbManager = new FachLbManager();
  $fachlbManager->update( [$_POST['fach_id'],$_POST['lb_id']], $_POST['new_fach_id'], $_POST['new_lb_id'] );
}
function delete()
{
  checkParam( [ 'fach_id', 'lb_id' ], $_POST );
  $fachlbManager = new FachLbManager();
  $fachlbManager->delete( [(int)$_POST['fach_id'], (int)$_POST['lb_id']] );
}
