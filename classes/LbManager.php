<?php declare(strict_types=1);
require_once './classes/TableManager.php';

class LbManager extends TableManager
{
  public function __construct()
  {
    parent::__construct("lb", "LB_ID");
  }
  
    /**
     * Update the record with the specifed ID
     *
     * @param $flb_id
     * @param $new_lb
     */
    public function update( $lb_id, ...$new_values ): void
    {
        try {
            $stmt = $this->pdo->prepare( "UPDATE {$this->tableName} SET lb_Name = ? WHERE {$this->primaryKeyName} = ?" );
            // set id as last element
            array_push( $new_values, $lb_id );
            $stmt->execute( $new_values );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Ändern des Fachnamens: " . $e->getMessage(), E_USER_ERROR );
        }
    }
}