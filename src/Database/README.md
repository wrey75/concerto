
# Database

This folder contains everything related to the database itself. The
database is directly linked to the [PDO](http://php.net/manual/book.pdo.php)
class provided by PHP.

## How it works?

We use PDO for the database connection. The DAO will
hold the connection to the database.


## DBEntity & DBColumn

This class DBEntity provides an entity description made of DBColumns. You just
have to provide the SQL table name and the column names.

## DAO (Data Access Object)

The DAO object is used to manipulate the entities. Basically, it can insert,
update, delete and requests the entities.

