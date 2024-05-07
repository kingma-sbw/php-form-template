<?php declare(strict_types=1);
require_once './classes/TableManager.php';

class FachManager extends TableManager
{
    /**
     * constructor connects to the database using the settings.ini file with connection details
     * @endcode
     */
    public function __construct()
    {
        parent::__construct( "fach", "Fach_ID" );
    }

    /**
     * Update the record with the specifed ID
     *
     * @param $fach_id
     * @param $new_fach_name
     * @param $new_lb_id
     */
    public function update( $fach_id, ...$new_values ): void
    {
        try {
            $stmt = $this->pdo->prepare( "UPDATE `{$this->tableName}` SET fach_Name = ?, lb_id = ? WHERE `$this->primaryKeyName` = ?" );
            array_push( $new_values, $fach_id );
            $stmt->execute( $new_values );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Ändern des Fachnamens: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    /**
     * findAll Generator to list all records, in this case we use the view instead of the table
     *
     * @param $where if empty list all, otherwise used as where clause
     */
    public function findAll( string $where = "0=0" ): Generator
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM `lb_fach` WHERE ($where) ORDER BY `Fach_Name`" );
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield ( $row );
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

}