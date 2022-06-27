# A LAMP stack
A [LAMP](https://en.wikipedia.org/wiki/LAMP_(software_bundle)) stack is one of the most common software stacks for many of the web's most popular applications. Each letter in the acronym stands for one of its four open-source building blocks:

* [Linux](https://en.wikipedia.org/wiki/LAMP_(software_bundle)#Linux) for the operating system.
* [Apache](https://en.wikipedia.org/wiki/Apache_HTTP_Server) HTTP Server
* [MySQL](https://en.wikipedia.org/wiki/MySQL) for the relational database management system
* [PHP](https://en.wikipedia.org/wiki/PHP).

> Nowadays LAMP refers to a generic software stack model where its components are largely interchangeable. For example, [MariaDB](https://mariadb.org/) instead of MySQL, and [Python](https://www.python.org/) instead of PHP.

<img src="./images/stack.jpg" height="250" />

# Setting up a LAMP Docker container
The point of using a [Docker container](https://www.docker.com/resources/what-container/) is to avoid issues at deployment. In other words, to avoid incidents when we move our Web app from our **development machine** to the **server**.

> Let's assume **Docker** is already **installed** in our development machine.

## Docker Compose
[Docker Compose](https://docs.docker.com/compose/) is a tool for defining and running **multi-container** Docker applications. In our case, our web-app is gonna need the following containers:

* One for [Apache](https://httpd.apache.org/) (our server of choice).
* Another one for [MySQL](https://www.mysql.com/).
* Lastly, one for [PHP](https://www.php.net/).

With **Compose**, we just have to write a YAML file to configure your application’s services. Then, with a single command, you **create** and **start** all the services from our configuration.

### Writing a Compose file
The [Compose file](https://docs.docker.com/compose/compose-file/) is a [YAML](http://yaml.org/) file named `compose.yaml` located at the **root** of our project. In such a file we'll define things such as:

* [Services](https://docs.docker.com/compose/compose-file/#services-top-level-element), which are the **containers** that our application needs to run (Apache, MySQL, PHP).
* [Networks](https://docs.docker.com/compose/compose-file/#networks-top-level-element), through which services communicate with each other.
* [Volumes](https://docs.docker.com/compose/compose-file/#volumes-top-level-element), where services store and share persistent data.
* [Configs](https://docs.docker.com/compose/compose-file/#configs-top-level-element), for our services.
* [Secrets](https://docs.docker.com/compose/compose-file/#secrets-top-level-element), for our services.

For example, let's create a basic file to serve PHP files:
```yaml
services:
  web:
    image: php:8.1-rc-apache-buster
    volumes:
      - './app:/var/www/html'
    ports:
      - 8080:80
      - 4430:443
```

* The `services` keyword is the only REQUIRED element in the file. Under it, we're creating a service named `web` (arbitrary name). This service is gonna be based on a PHP **container**, whose **image** we'll pull from [Docker Hub](https://hub.docker.com) (find the [PHP official image](https://hub.docker.com/_/php), and once there click on [Available Tags](https://hub.docker.com/_/php?tab=tags) to choose the one we prefer).

* We'll also gonna need a `volume`, which is a mapping of a location in our filesystem to a location within the container. In this case we're connecting the `./app` folder in our **development machine** to the directory from which **Apache** serves the PHP stuff (`/var/www/html`).

> Remember, the location we just mentioned is the [default document root](https://httpd.apache.org/docs/trunk/getting-started.html#content) in Apache.

* Finally, we'll map the port `8080` in our **physical machine**, to the port `80` within the **container**.

For testing purposes, we'll create a basic `index.php` file in our `./app` folder, with the classic call to the [phpinfo()](https://www.php.net/manual/en/function.phpinfo) function:
```php
<?php phpinfo(); ?>
```

### Running stuff
In order to test out our small set up, we have two choices:

* Use the original `docker-compose up` command.
* Or make use of the [version 2](https://docs.docker.com/compose/cli-command/) `docker compose upp`, which integrates the compose features into the `docker` platform.

> Don't forget to make sure the `docker` service is running: ` sudo systemctl start docker`

If you face some `/var/run/docker.sock: connect: permission denied.` issue try adding your user to the `docker` group:
```
sudo usermod -a -G docker $USER
```

> After adding your user to the `docker` group, you may have to reboot your system before continuing on.

That should start **pulling** the images that don't exist in our system. After the process finishes, we should be able of pointing our browser to http://localhost:8080/ and see the image below:

![phpinfo](./README/images/phpinfo.png)

### Stopping stuff
Once we've checked that our configuration file is working, we'll hit `Ctrl + c` to stop the server and:
```
docker compose down
```

This last command will **remove** any containers that were running from Compose.

### The MySQL service
For this container we'll use the [official MySQL image](https://hub.docker.com/_/mysql), along with the `latest` tag. This page contains information about some [environment variables](https://docs.docker.com/compose/reference/envvars/) that we'll have to set up, namely:

* MYSQL_DATABASE: example
* MYSQL_USER: bob
* MYSQL_PASSWORD: 1234
* MYSQL_ROOT_PASSWORD: 1234

So this is what we'll add:
```yaml
services:
  # more stuff
  db:
    image: mysql:latest
    restart: always
    environment:
      MYSQL_DATABASE: example
      MYSQL_USER: bob
      MYSQL_PASSWORD: 1234
      MYSQL_ROOT_PASSWORD: 1234
```

### Administering the database: phpMyAdmin
Let's create a service for [phpMyAdmin](https://www.phpmyadmin.net/); so under `services` we'll add:
```yaml
services:
  # more stuff
  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - 8081:80
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
```

Once you've finished the configuration don't forget to `Ctrl + c` and:
```
docker compose down
docker compose up
```

After this we should be able of pointing our browser to http://localhost:8081/ and start manipulating our database.

> A good idea after creating some records to test our database, would be to **export** our data to our local filesystem.

### Mounting a Volume for our Database
If you have available some data, or we want to use the `dump.sql` file you generated at the end of the last section, we could do so by mounting a `volume` under our `db` service:
```yaml
services:
  # more stuff
  db:
    image: mysql:latest
    restart: always
    environment:
      MYSQL_DATABASE: example
      MYSQL_USER: root
      MYSQL_PASSWORD: 1234
      MYSQL_ROOT_PASSWORD: 1234
    volumes:
      - 'db_data:/var/lib/mysql'
```

In the last line above, we're mapping the `db_data` folder (in our local filesystem) to the standard `/var/lib/mysql` folder in the container.

> Don't forget to place the `dump.sql` file into the `db_data` folder.

Once you've finished the configuration don't forget to `Ctrl + c` and:
```
docker compose down
docker compose up
```

### Final Test
Let's put everything together and try to read data from our database using our `index.php`:
```php
<?php
// Set up database
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

$dsn = "mysql:host=db";

$dbh = new PDO($dsn,
              'root',
              '1234',
              $options);

$dbh->exec('CREATE DATABASE `camagru`');

die('DB has been set the fuck up!');
?>
```

Even though it seems we've done everything right, if we point our browser to, we'd be punished with the following **error**:
```
Fatal error: Uncaught Error: Call to undefined function ...
```

The reason for that is that [PDO](https://www.php.net/manual/en/intro.pdo.php) is a PHP extension that is not installed in the image we're pulling in our `compose.yml` file.

### Adding a Build stage
To solve the issue related above, we have to refactor our `php` service in the following manner:
```yaml
services:
  web:
    build:
      context: ./dockerfiles
      dockerfile: Dockerfile
    volumes:
      - './app:/var/www/html'
    ports:
      - 8080:80
      - 4430:443
# more stuff
```

And in the `./php_extensions` directory, we'll add a `Dockerfile` with the following content:
```docker
FROM php:8.1-rc-apache-buster
RUN docker-php-ext-install pdo && docker-php-ext-enable pdo
```

## Docker Workflow Tricks
One of the things I noticed while developing the app in Docker, was the need of editing configuration files for the server, PHP interpreter and so on. A pattern that proved useful to me was:

1. Copy the original configuration file to your **host**:
```
$ docker cp a22fac:./your_proj/apache2.conf
```

2. Mount it as a [Docker volume](https://docs.docker.com/storage/volumes/):
```
volumes:
  - ./docker/php_apache/apache2.conf:/etc/apache2/apache2.conf
```

After that, you'll have a gate to that configuration file on your running container (Don't forget that some services must be restarted after configuration changes).

### A Small Twist
What if, during the **building image** stage you need to make some change (e.g. change permissions) to a configuration file that does not exist yet? Well, you can create the file, let's say one for configuring [msmtp](https://marlam.de/msmtp/), which must have certain permissions. Then, in your [Dockerfile](https://docs.docker.com/engine/reference/builder/) you have to:

1. Copy the file to the **image**:
```
COPY msmtprc /etc/msmtprc
```

2. Change the permissions in the same `Dockerfile`:
```
RUN chmod 600 /etc/msmtprc
```

What if you have to make configurations to that file while the container is running? Easy, mount the file as a **volume** in your `compose.yml`:
```
volumes:
  - ./docker/php_apache/msmtprc:/etc/msmtprc
```
---
[:arrow_backward:][back] ║ [:house:][home] ║ [:arrow_forward:][next]

<!-- navigation -->
[home]: ../README.md
[back]: ../README.md
[next]: ./mvc.md