# The Database
One of the things about creating [dynamic web apps](https://en.wikipedia.org/wiki/Dynamic_web_page), it's having to deal with a database.

<img src="./images/db.jpg" height="350" />

## PDO
One of the project's **requirements** was to use [PDO](https://www.php.net/manual/en/intro.pdo.php) (short for **PHP Data Objects**), which is a PHP extension that falls into the [Database Access Abstraction Layer](https://en.wikipedia.org/wiki/Database_abstraction_layer) category (it's not an [ORM](https://en.wikipedia.org/wiki/Object%E2%80%93relational_mapping)).

> Check [(The only proper) PDO tutorial](https://phpdelusions.net/pdo) for more information.

PDO provides a consistent API for interacting with several databases engines (database agnostic) using PHP code. That means that **PDO** alone is not enough to interact with databases from PHP. We also need a [database driver](https://www.php.net/manual/en/pdo.drivers.php) in order to talk to the database server. These drivers are specific to the database engine we intend to use, meaning that if we want to interact with MySQL, we need to use the [PDO_MYSQL](https://www.php.net/manual/en/ref.pdo-mysql.php) driver.


## The `Database` Class
To deal with the DB ting, we've defined a `Database` class that will take care of connecting to the database, and even setting it up the first time the application is deployed. A good place for the `Database.class.php` is the `app/core` folder, next to the `Router.class.php` file.

> According to the **42 PHP styleguide**, files containing classes should be capitalized, with the `.class.php` extension.

An **instance** of this class is created every time we need a **connection** to the database. This is done in the **model** constructor, since we know for sure that models have to read/write the database.

## Configuration
First thing we do is to require the **configuration file** where we defined some connection constants.

## The constructor
In the **constructor** we use the afore-mentioned constants to connect to the database. This is done by creating an **instance** of the `PDO` class, which is stored in the `dbh` private member.

We use the [getCode()](https://www.php.net/manual/en/exception.getcode.php) method to get the **exception code**. If the code is `1049`, that means there's no database, so we **set up** one in the `catch` clause. We use an external file that contains of the SQL statements needed for creating the db with tables and all.

### The Data Source Name
One of the arguments required by the `PDO` constructor is a [DSN](https://en.wikipedia.org/wiki/Data_source_name), which is a string where we define several things:

* The database **driver**.
* The **host**, which in my case is the name of the Docker container where the MySQL service is running.
* The **database name**, which is **optional**.

We defined two different `dsn`:

* One in the `try` clause, in case the database already exists.
* One in the `catch` clause, in case the database does not exist yet.

## Other methods
The `Database` class is a good place to define methods that will be used later on by the models to retrieve and store data from and to the database.

---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: #
[back]: ./README/router.md
[next]: #