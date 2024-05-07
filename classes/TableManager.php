<?php declare(strict_types=1);

abstract class TableManager
{
    protected $pdo;

    /**
     * constructor connects to the database using the settings.ini file with connection details
     * @endcode
     */
    public function __construct(
        readonly string $tableName,
        readonly string $primaryKeyName )
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
    public function findById( int $id ): null|array
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
    public function findAll( string $where = "0=0" ): Generator
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM `{$this->tableName}` WHERE ($where)" );
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield ( $row );
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    abstract public function update( $id, ...$new_values ): void;
    /**
     * Delete record by ID
     *
     * @param $id
     */
    public function delete( int $id )
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
}