<?php declare(strict_types=1);
/**
 * DON'T CHANGE THIS FILE!!
 */
if( !defined('ROOT') ) die("Cannot run this file");

/**
 * TableManager abstract class for CRUD (Create Retrieve Update Delete actions)
 * use with SomeTableManager extends TableManager
 *
 * for the fieldNames in the instructor make sure to put them in the same order as the columns in the table!
 * the keys used for the id in the update and delete form have to be called id!
 * 
 * public function makeSelect() to create a <select><option>
 * static function makeDeleteForm() to create a form for a delete request
 * public function handleReqeust() to handle basic actions create,update,delete
 * 
 */
abstract class TableManager
{
    private function buildValuelist(): array
    {
        return array_map(
            fn( $fieldName ) => $_POST[ $fieldName ],
            array_values( $this->fieldNames )
        );
    }
    public function createAction(): void
    {
        checkParam( array_values( $this->fieldNames ), $_POST );
        // buil
        $newValues = $this->buildValuelist();
        $this->create( ...$newValues );
    }

    public function updateAction(): void
    {
        // check for all fields and an 'id'
        checkParam( array_merge(
            [ 'id' ],
            array_values( $this->fieldNames )
        ), $_POST );

        $newValues = $this->buildValuelist();
        $this->update( $_POST['id'], ...$newValues );
    }

    public function deleteAction(): void
    {
        checkParam( [ 'id' ], $_POST );
        $this->delete( $_POST['id'] );
    }

    protected $pdo;

    /**
     * constructor connects to the database using the settings.ini file with connection details
     */
    public function __construct(
        readonly string $tableName,
        readonly array|string $primaryKeyName,
        readonly array $fieldNames
    ) {
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
            $stmt = $this->pdo->prepare( "SELECT * FROM `{$this->tableName}` WHERE ($where) " . ( $order ? "ORDER BY $order" : "" ) );
            $stmt->execute();
            while( $row = $stmt->fetch() ) {
                yield ( $row );
            }
        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Suchen des Faches: " . $e->getMessage(), E_USER_ERROR );
        }
    }

    public function update( int|string $id, ...$new_values )
    {
        try {
            $assignmentList = '`' . implode( '`=?, `', array_keys( $this->fieldNames ) ) . '`=?';
            $query          = "UPDATE {$this->tableName} SET $assignmentList WHERE {$this->primaryKeyName} = ?";
            $stmt           = $this->pdo->prepare( $query );
            // set id as last element
            array_push( $new_values, $id ); // put the id at the end of the array of values
            $stmt->execute( $new_values );

        } catch ( PDOException $e ) {
            trigger_error( "Fehler beim Löschen des Faches: " . $e->getMessage(), E_USER_ERROR );
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
     * handle a request based on action, there are three options, create, update, delete
     * The functions returns to '/' and exist, or triggers and error for unknown actions.
     */
    public function handleRequest()
    {
        switch($_POST['action']) {
            case 'create':
                $this->createAction();
                break;
            case 'update':
                $this->updateAction();
                break;
            case 'delete':
                $this->deleteAction();
                break;
            default:
                trigger_error( 'unknown action ', E_USER_ERROR );
        }
        // Gehe zurück zum hauptfenster
        header( "Location: /" );
        exit();
    }

    /**
     * @param $selected_id the value of the option currently selected, foreign key value
     * @param $show the name of the attribute that should be shown in the list
     * @param $fieldName name of the form field, if empty use the PK-name as fieldName
     * 
     * @return string HTML <select>-tag with <option>-tag for each record ordered by $show
     */
    public function makeSelect( int $selected_id, string $show, string $fieldName = null ): string
    {
        try {
            $fieldName = $fieldName ?? $this->primaryKeyName;
            $stmt      = $this->pdo->prepare( "SELECT `{$this->primaryKeyName}`, `$show` FROM `{$this->tableName}` ORDER BY `$show`" );
            $stmt->execute();
            $result = "<select name='{$fieldName}'>";
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
     * @param $label button text, 'Delete' when ommitted
     */
    public static function makeDeleteForm( string $id_name, int|string $id, string $label = 'Delete' ): void
    {
        ?>
        <form method="POST" action="./fach-action-handler.php">
            <input type="hidden" name="<?= $id_name ?>" value="<?= $id ?>">
            <button name="action" value="delete"><?= $label ?></button>
        </form>
        <?php
    }
}
