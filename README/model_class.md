# The Model Abstract Class
One of the things about creating [dynamic web apps](https://en.wikipedia.org/wiki/Dynamic_web_page), it's having to deal with a database.

<img src="./images/db.jpg" height="350" />

In an MVC framework, models are in charge of communicating with the database. To deal with the DB ting, we defined a `Model` class that will take care of connecting to the database, and even setting it up the first time the application is deployed. A good place for the `Model.class.php` is the `app/core` folder, next to the `Router.class.php` file.

> According to the **42 PHP styleguide**, files containing classes should be capitalized, with the `.class.php` extension.

## Database Configuration and Setup
As per project requirements, we had to create a `config` folder containing:

* A `database.php` file with a bunch of globals for storing database connection settings.
* A `setup.php` file capable of creating the database.

First thing we do is to require the **configuration files** at the top of `Model`.

## PDO
One of the project's **requirements** was to use [PDO](https://www.php.net/manual/en/intro.pdo.php) (short for **PHP Data Objects**), which is a PHP extension that falls into the [Database Access Abstraction Layer](https://en.wikipedia.org/wiki/Database_abstraction_layer) category (it's not an [ORM](https://en.wikipedia.org/wiki/Object%E2%80%93relational_mapping)).

> Check [(The only proper) PDO tutorial](https://phpdelusions.net/pdo) for more information.

PDO provides a consistent API for interacting with several databases engines (database agnostic) using PHP code. That means that **PDO** alone is not enough to interact with databases from PHP. We also need a [database driver](https://www.php.net/manual/en/pdo.drivers.php) in order to talk to the database server. These drivers are specific to the database engine we intend to use, meaning that if we want to interact with MySQL, we need to use the [PDO_MYSQL](https://www.php.net/manual/en/ref.pdo-mysql.php) driver.

## Where to connect to the Database?
Database operations are expensive, on top of that, if we're not careful about where and how we're connecting to our database, we may hit a **too many connections** error, and even crash the database engine. Where not to connect to a database:

* In the model constructors, which would cause a new connection each time a model is instantiated.
* Not even in the `Model` superclass constructor, for the same reason.

What I ended up doing is defining a [static](https://www.php.net/manual/en/language.oop5.static.php) method named `getDB()`, and within it, declared another static variable named `dbh`.

> There's nothing special about the name `dbh`, short for **database handler**, it's just the name used in the PHP docs.

Whenever a method in a model instance needed to perform any database operation, it would call the `static::getDB()` method, which would only create a new connection if none existed.

## What is a Database Connection
Such a **connection** is represented by an **instance** of the `PDO` class. To create a connection, the [PDO](https://www.php.net/manual/en/pdo.connections.php) constructor needs the following arguments:

* What is known as a Data Source Name (more about it later).
* Username.
* Password.
* An array of options.

When attempting to connect, we may hit a problem and an [exception](https://www.php.net/manual/en/language.exceptions.php) may be raised. To handle that, we must wrap any connection attempt in a `try-catch` statement. If an exception is raised in the `try`, in the `catch` we check using the [getCode()](https://www.php.net/manual/en/exception.getcode.php) method the **exception code**. If the code is `1049`, that means there's **no database yet**, so we **set up** one in the `catch` clause. We use an external file that contains of the SQL statements needed for creating the db with tables and all.

### The Data Source Name
One of the arguments required by the `PDO` constructor is a [DSN](https://en.wikipedia.org/wiki/Data_source_name), which is a string where we define several things:

* The database **driver**.
* The **host**, which in my case is the name of the **Docker container** where the MySQL service is running.
* And **optionally**:

    * The **name** of an **existing database**.
    * The **charset**, also **optional**.
    * The **port**, **optional** as well.
    * The **unix_socket**, which as you may have guessed, it's **optional**.

In our `getDB()` function we used two different `dsn` strings:

* In the `try` clause we were trying to connect to an **existing database**, so we must specify the **db name**.
* But in the `catch` clause, we check for a **non-existing database** exception. In this case, we have to omit the database name, obviously!

## Other methods
The `Model` class is a good place to define methods that will be used later on by the models to retrieve and store data from and to the database. Some actions common to all models would be:

* Perform an SQL query that returns one of something (a user, a <del>dick</del>pic, etc).
* An SQL query that returns all of something (a list of posts, a list of comments, etc).
* Another one query that returns true if a record is found in a table; false otherwise.

I hope you get the idea ;-)

## A Model
Let's say our app is gonna have users (duh!), and we need a `User` model. We could test out the `getDB()` method inside a `getUsers()` mmmmethod:
```php
public function getUsers()
{
    $db = static::getDB();
    $stmt = $db->query('SELECT * FROM users');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

The method above would return a list of users that a given controller would use to pass it down to a **view**. The static `getDB()` superclass method opens a gate to the database that is use in the `User` model to extract whatever data we need.

---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: ../README.md
[back]: ./router.md
[next]: ./workflow.md