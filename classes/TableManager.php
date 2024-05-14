<?php declare(strict_types=1);

/**
 * TableManager abstract class for CRUD (Create Retrieve Update Delete actions)
 * use with SomeTableManager extends TableManager
 *
 * for the ...newvalues make sure to put them in the same order as the columns in the table!
 * 
 * only update must be defined as we don't know the exact colum nnames (replace col1, col2 etc.)
 * 
 */
abstract class TableManager
{
    abstract public function update( int|string $id, ...$new_values ): void;
    /* change col1 = ?, col2 = ?, ...  with the proper fields in the right order
    {
        try {
            $stmt = $this->pdo->prepare( "UPDATE {$this->tableName} SET col1 = ?, col2 = ?, ... WHERE {$this->primaryKeyName} = ?" );
            // set id as last element
            array_push( $new_values, $id ); // put the id at the end of the array of values
            $stmt->execute( $new_values );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Ändern der Tabelle: " . $e->getMessage(), E_USER_ERROR );
        }
    }
    */
    protected $pdo;

    /**
     * constructor connects to the database using the settings.ini file with connection details
     */
    public function __construct(
        readonly string $tableName,
        readonly array|string $primaryKeyName )
    {
        try {

            $this->pdo = new PDO( SETTINGS['db']['dsn'], SETTINGS['db']['user'], SETTINGS['db']['pass'] );
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Fehlermodus auf Ausnahmen setzen
            $this->pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // Standard-Abfrageergebnismodus auf assoziatives Array setzen
        } catch ( PDOException $e ) {
            trigger_error( "Verbindung fehlgeschlagen: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    /**
     * Create a new record, return it's ID
     */
    public function create( ...$new_values ): int
    {
        try {
            $stmt = $this->pdo->prepare( "INSERT INTO `{$this->tableName}` VALUES (null, ?, ?)" );
            $stmt->execute( $new_values );
            return (int) $this->pdo->lastInsertId();
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Eintragen des Faches: " . $e->getMessage(), E_USER_ERROR );
            return 0;
        }
    }

    /**
     * Find a record by ID
     *
     * @param $fach_id
     * @return null|array null if nothing found, otherwise associative array
     */
    public function findById( int|string $id ): null|array
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM `{$this->tableName}` WHERE `{$this->primaryKeyName}` = ?" );
            $stmt->execute( [ $id ] );
            return $stmt->fetch();
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
            return null;
        }
    }
    /**
     * findAll Generator to list all records, with the where clause
     *
     * @param $where if empty list all, otherwise used as where clause
     */
    public function findAll( string $order = null, string $where = "0=0" ): Generator
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM `{$this->tableName}` WHERE ($where) " . ($order?"ORDER BY $order":) );
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield ( $row );
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }



    /**
     * Delete record by ID
     *
     * @param $id
     */
    public function delete( int|string $id )
    {
        try {
            $stmt = $this->pdo->prepare( "DELETE FROM `{$this->tableName}` WHERE `{$this->primaryKeyName}` = ?" );
            $stmt->execute( [ $id ] );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Löschen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    /**
     * @param $selected_id the value of the selectedoption
     * @param $show the name of the attribute that should be shown in the list
     * 
     * @return string
     */
    public function makeSelect( int $selected_id, string $show ): string
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT `{$this->primaryKeyName}`, `$show` FROM `{$this->tableName}` ORDER BY `$show`" );
            $stmt->execute();
            $result = "<select name='{$this->primaryKeyName}'>";
            $result .= "<option value='0'>";

            while( $row = $stmt->fetch() ) {
                $selectstring = $selected_id === $row[ $this->primaryKeyName ] ? 'selected' : '';
                $result .= "<option $selectstring value='{$row[ $this->primaryKeyName ]}'>{$row[ $show ]}";
            }
            $result .= "</select>";
            return $result;
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des {$this->tableName}: " . $e->getMessage(), E_USER_ERROR );
            return '';
        }
    }
    /**
     * Create a form the sends a delete action for a fach table
     * @param $id_name the name of form variable to hold the key
     * @param $id the value of the PK that will be deleted
     * 
     */
    public static function makeDeleteForm( string $id_name, int|string $id ): void
    {
        ?>
        <form method="POST" action="./fach-handler.php">
            <input type="hidden" name="<?= $id_name ?>" value="<?= $id ?>">
            <button name="action" value="delete">Delete</button>
        </form> <?php
    }
}
