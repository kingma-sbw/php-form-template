<?php declare(strict_types=1);

class FachManager
{
    private $pdo;

    // Konstruktor für die FachManager-Klasse
    public function __construct()
    {
        try {
            $dsn =
                $this->pdo = new PDO( SETTINGS['dsn'], SETTINGS['db']['user'], SETTINGS['db']['pass'] );
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Fehlermodus auf Ausnahmen setzen
            $this->pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // Standard-Abfrageergebnismodus auf assoziatives Array setzen
        } catch ( PDOException $e ) {
            trigger_error( "Verbindung fehlgeschlagen: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    // Funktion zum Eintragen eines neuen Faches
    public function create( $fach_name, $lb_id )
    {
        try {
            $stmt = $this->pdo->prepare( "INSERT INTO fach (Fach_Name, LB_ID) VALUES (?, ?)" );
            $stmt->execute( [ $fach_name, $lb_id ] );
            return $this->pdo->lastInsertId(); // Die ID des eingefügten Datensatzes zurückgeben
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Eintragen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    // Funktion zum Suchen eines Faches anhand der ID
    public function findById( $fach_id )
    {
        try {
            $stmt = $this->pdo->prepare( "SELECT * FROM fach WHERE Fach_ID = ?" );
            $stmt->execute( [ $fach_id ] );
            return $stmt->fetch();
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    // Funktion zum Ändern eines Fachnamens
    public function update( $fach_id, $new_fach_name, $new_lb_id )
    {
        try {
            $stmt = $this->pdo->prepare( "UPDATE fach SET Fach_Name = ?, LB_ID = ? WHERE Fach_ID = ?" );
            $stmt->execute( [ $new_fach_name, $new_lb_id, $fach_id ] );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Ändern des Fachnamens: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    // Funktion zum Löschen eines Faches anhand der ID
    public function delete( $fach_id )
    {
        try {
            $stmt = $this->pdo->prepare( "DELETE FROM fach WHERE Fach_ID = ?" );
            $stmt->execute( [ $fach_id ] );
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Löschen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }
    public function findAll(string $where="0=0"): Generator {
        try{
            $stmt = $this->pdo->prepare( "SELECT * FROM fach WHERE ($where)");
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield($row);
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }
}

