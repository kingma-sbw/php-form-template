# php-form-template

Template project for easy developement


## Abstract Class TableManager

### constructor

Parameter
 * tablename
 * primary key name
 * assoc array of column => form-field-name

The columns are escaped with ` the form-field-name is used in generated forms
  
Connects to database according the DB connection in settings.ini

### create

Creates a new Fach, mit fach_name und lb_id

### findById

Search a Fach by the specifed ID and returns an associativ array or a null value

### update

Updates the Fach with the specified fields and ID

### delete

Deletes the Fach with the specified ID

### findAll

Generator to return all entries as associative array in Fach sorted by Fach_Name
use like

```php
foreach( $fachManager->findAll('Fach_Name') as $fach) {
  echo $fach['Fach_Name'];
}
```

### makeSelect

Create a html `<SELECT>` with all rows in the $table, showing the attribute $show and using $id as the id. The list is shorted by $show and the selected elemeent is specified by $selected_id. The first entry has `id=0` and is empty

### usage
create a new MyTableManager.php file in classes extending TableManager

```php
<?php declare(strict_types=1);

class MyableManager extends TableManager
{
  public function __construct()
  {
    parent::__construct(
      'tablename',
      'pk-column-name',
      [
        'col1' => 'col1-field',
        'col2' => 'col2-field'
      ]
    );
  }
```

## index.php

Show a form for a new entry and a table for current content list. Send post to fach-handler.php

## record-create-form.php

Show form to creata a new record

## record-action-handler.php

process a post request based on action create, update, delete

## record-update-form.php

Show a form to update an existing record showing old values based on id. Send post to record-action-handler.php

## record-list.php

Show a html table of records
