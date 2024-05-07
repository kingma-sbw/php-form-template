# php-form-template
Template project for easy developement


Tables `fach`,`lb` and view `lb_fach` created as

```sql
CREATE TABLE `fach` (
  `Fach_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Fach_Name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `LB_ID` int(11) NOT NULL,
  PRIMARY KEY (`Fach_ID`)
);

CREATE TABLE `lb` (
  `LB_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `LB_Name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`LB_ID`)
);


CREATE VIEW `lb_fach` AS
select
  `fach`.`Fach_ID` AS `Fach_ID`,
  `fach`.`Fach_Name` AS `Fach_Name`,
  `lb`.`LB_ID` AS `LB_ID`,
  `lb`.`LB_Name` AS `LB_Name`
from `fach`
join `lb` on(`lb`.`LB_ID` = `fach`.`LB_ID`);

```

# Class FachManager
## constructor
Connects to database according the DB connection in settings.ini

## create
Creates a new Fach, mit fach_name und lb_id

## findById
Search a Fach by the specifed ID and returns an associativ array or a null value

## update
Updates the Fach with the specified fields and ID

## delete
Deletes the Fach with the specified ID

## findAll 
Generator to return all entries as associative array in Fach sorted by Fach_Name
use liek
```php
foreach( $fachManager->findAll() as $fach) {
  echo $fach['Fach_Name'];
}
```

## makeSelect
Create a html `<SELECT>` with all rows in the $table, showing the attribute $show and using $id as the id. 
The list is shorted by $show and the selected elemeent is specified by $selected_id. The first entry has `id=0` and is empty

# index.php
Show a form for a new entry and the current list

# update-fach.php
Show a form to update an existing Fach

# fach-handler.php
Uses the `FachManager` to create, retrieve, update and delete a fach