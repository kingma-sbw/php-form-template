<?php declare(strict_types=1);
require_once './classes/TableManager.php';

class FachLbManager extends TableManager
{
  public function __construct()
  {
    parent::__construct( "fach_lb", [ "fach_id", "lb_id" ] );
  }
  /**
   * Create a new record, return it's ID
   */
  public function create( ...$new_values ): int
  {
    try {
      $stmt = $this->pdo->prepare( "INSERT INTO `{$this->tableName}` VALUES (?, ?)" );
      $stmt->execute( $new_values );
      return (int) $this->pdo->lastInsertId();
    } catch ( PDOException $e ) {
      trigger_error( "Fehler beim Eintragen des Faches: " . $e->getMessage(), E_USER_ERROR );
      return 0;
    }
  }
  /**
   * Update the record with the specifed ID
   *
   * @param $keys [fach_id, lb_id]
   * @param $new_values [fach_id, lb_id]
   */
  public function update( int|array $keys, ...$new_values ): void
  {
    try {
      $where = "WHERE `" . implode( '` = ? AND `', $this->primaryKeyName ) . "` = ?";
      $stmt  = $this->pdo->prepare( "UPDATE {$this->tableName} SET fach_id = ?, lb_id = ? " . $where );
      // set id as last element
      $stmt->execute( array_merge( $new_values, $keys ) );
    } catch ( PDOException $e ) {
      trigger_error( "Fehler beim Ändern des Fachnamens: " . $e->getMessage(), E_USER_ERROR );
    }
  }
}