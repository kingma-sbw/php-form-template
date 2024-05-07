<?php declare(strict_types=1);

class FachManager
{
    private $pdo;

    /**
     * constructor connects to the database using the settings.ini file with connection details
     * @endcode
     */
    public function __construct()
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
     * @param $fachname
     * @param $lb_id
     */
    public function create( $fach_name, $lb_id ): int
    {
        try {
            $stmt = $this->pdo->prepare( "INSERT INTO fach (fach_Name, lb_id) VALUES (?, ?)" );
            $stmt->execute( [ $fach_name, $lb_id ] );
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
    public function findById( $fach_id ): null|array
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM fach WHERE fach_id = ?" );
            $stmt->execute( [ $fach_id ] );
            return $stmt->fetch();
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
            return null;
        }
    }

    /**
     * Update the record with the specifed ID
     *
     * @param $fach_id
     * @param $new_fach_name
     * @param $new_lb_id
     */
    public function update( $fach_id, $new_fach_name, $new_lb_id ): void
    {
        try {
            $stmt = $this->pdo->prepare( "UPDATE fach SET fach_Name = ?, lb_id = ? WHERE fach_ID = ?" );
            $stmt->execute( [ $new_fach_name, $new_lb_id, $fach_id ] );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Ändern des Fachnamens: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    /**
     * Delete record by ID
     *
     * @param $fach_id
     */
    public function delete( int $fach_id )
    {
        try {
            $stmt = $this->pdo->prepare( "DELETE FROM fach WHERE fach_id = ?" );
            $stmt->execute( [ $fach_id ] );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Löschen des Faches: " . $e->getMessage(), E_USER_ERROR );
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
            $stmt = $this->pdo->prepare( "SELECT * FROM lb_fach WHERE ($where) ORDER BY `Fach_Name`" );
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield ( $row );
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }
    /**
     * @param $selected_id the value of the selectedoption
     * @param $table the name of the table
     * @param $id the name of the id attribute in the table
     * @param $show the name of the attribute that should be shown in the list
     * 
     * @return string
     */
    public function makeSelect( int $selected_id, string $table, string $id, string $show ): string
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT `$id`, `$show` FROM `$table` ORDER BY `$show`" );
            $stmt->execute();
            $result = "<select name='$id'>";
            $result .= "<option value='0'>";

            while( $row = $stmt->fetch() ) {
                $selectstring = $selected_id === $row[ $id ] ? 'selected' : '';
                $result .= "<option $selectstring value='{$row[ $id ]}'>{$row[ $show ]}";
            }
            $result .= "</select>";
            return $result;
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des $table: " . $e->getMessage(), E_USER_ERROR );
            return '';
        }
    }
}