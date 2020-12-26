# l-extended_todo

La version Française de cette documentation peut être trouvée [ici](/doc/FR/README.md).

French version of this documentation can be found at [here](/doc/FR/README.md).

## Table of Contents
   * [l-extended_todo](#l-extended_todo)
      * [Description :](#description-)
      * [Software dependencies and the pre-requisite :](#software-dependencies-and-the-pre-requisite-)
         * [Pre-requisite :](#pre-requisite-)
         * [Symfony dependencies :](#symfony-dependencies-)
         * [Other dependencies :](#other-dependencies-)
      * [Database :](#database-)
         * [Diagram :](#diagram-)
      * [Docker :](#docker-)
         * [Containers :](#containers-)
         * [Configuration :](#configuration-)

## Description :
**l-extended_todo** is an extended to-do list with time management.

The application name will also be abbreviated to **let** or **LET** in this documentation.

## Software dependencies and the pre-requisite :
**LET** is a web application, mobile application using the following technologies :

**LET** is based upon version 5 of the Symfony framework.

### Pre-requisite :
 - [x] PHP 7.4
 - [x] MariaDB 10.5 (wich is a fork of MySQL, you are free to use **let** with MySQL 8.0 or other SGBD since **let** use the ORM from Doctrine project).
   Differences between MariaDB and Mysql can be found Here : [Click here for documentation](https://mariadb.com/kb/en/incompatibilities-and-feature-differences-between-mariadb-105-and-mysql-80/)  
   (https://mariadb.com/kb/en/incompatibilities-and-feature-differences-between-mariadb-105-and-mysql-80/)
 - [x] Symfony 5.0+
### Symfony dependencies :
[Go to](https://flex.symfony.com/) (https://flex.symfony.com/) for details about those dependencies.

**LET** need and make use of the following components from the Symfony framework :
 - [x] profiler (recommended only in development environment) (symfony/profiler-pack)
 - [x] debug  (recommended only in development environment) (symfony/debug-pack)
 - [x] maker  (recommended only in development environment) (symfony/maker-bundle)
 - [x] orm (symfony/orm-pack)
 - [x] api (api-platform/api-pack)
 - [x] [sensio/framework-extra-bundle](https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html)
 - [x] logger (symfony/monolog-bundle)
### Other dependencies :

 - [x] [Ramsey Uuid Doctrine Type](https://github.com/ramsey/uuid-doctrine)  (ramsey/uuid-doctrine): Provides uuid as database types.
	 - [x] [Ramsey Uuid](https://github.com/ramsey/uuid): this dependency is automatically installed by Uuid-Doctrine.
 - [x] [doctrine-extensions](https://symfony.com/doc/1.2/bundles/StofDoctrineExtensionsBundle/index.html): Those extensions are an easy way to use some behaviours representing good practices :
	 - [x] blameable : Blame which user make a modification (really useful to prove who caused a bug).
	 - [x] loggable : Log what happens to your entities and keep track of modifications.
	 - [x] sluggable : When you don't use the uuid for entities it's useful to dispose a nice url with the title for example instead of the resource id.
	 - [x] timestampable : With the other extensions we keep who did what, this one tells you when. 
	 - [x] translatable : An easy way to keep translated contents in database.
 - [x] [doctrine2-spatial](https://github.com/creof/doctrine2-spatial) : Manipulating spatial (geometrical and geographical) data, we will use this for geo information with lat and long and to calculate distances on the surface. Yes Geo-location will be a important feature in this application.

## Database :

### Diagram :
![Database schema](/doc/schema/dbSchema.png)

You will find the complete database schema in xml format in the file [/doc/l-extended_todo_database_Schema.xml](/doc/l-extended_todo_database_Schema.xml), 
this file can be used in [ondras](https://github.com/ondras) / **[wwwsqldesigner](https://github.com/ondras/wwwsqldesigner)**.

The current database schema is available in this form in a Docker container in the project at: 
**[http://localhost:8002/?keyword=l_extended_todo](http://localhost:8002/?keyword=l_extended_todo)**.

You can also use the functions of **wwwsqldesigner** in the **sqld** container or from another instance via the XML file provided.

See the **[Docker :](#docker-)** section for more information.
## Docker :
### Containers :
**LET** defines and uses **Docker** images useful for development.
The first container **database** is essential for development because it hosts the database for the development phase.

The following 2 are useful if your development host is not configured to run PHP 7.4. They define a **PHP-FPM** container and an **NGINX** container.

The last 2 containers are optional tools to help development.

Here is a description of these images:
- [x] **database**: Container MariaDB 10.5 [mariadb:10.5](https://hub.docker.com/_/mariadb?tab=tags)
- [x] **php**: Container [php:7.4-fpm-buster](https://hub.docker.com/_/php?tab=tags)
- [x] **nginx**: Container [nginx:latest](https://hub.docker.com/_/nginx?tab=tags)
- [x] **phpmyadmin**: Container [phpmyadmin/phpmyadmin:latest](https://hub.docker.com/r/phpmyadmin/phpmyadmin/tags)
- [x] **sqld**: Container offering the tool [wwwsqldesigner](https://github.com/ondras/wwwsqldesigner), [d0whc3r/wwwsqldesigner](https://hub.docker.com/r/d0whc3r/wwwsqldesigner/tags)

### Configuration: 
To make these **Docker** instances work you will need to define a set of configuration variables in your **.env** file *(.env.local is not supported by docker)*.

The detail of each variable is indicated here in comment:
``` 
###> docker ###
# Applicable time zone
TIMEZONE=Europe/Paris
### name of the docker network to use ###
# Docker network to be created beforehand with the command"docker network create l-extended_todo_default" 
# or whatever network name you want
DOCKER_NETWORK=l-extended_todo_default
###Mysql ###
# external access port
MYSQL_EXT_PORT=8086
# root password
MYSQL_ROOT_PASSWORD=changeme
# database for LET
MYSQL_DATABASE=let
# user for LET
MYSQL_USER=let_user
# user password for LET
MYSQL_PASSWORD=changeme_let_passwd
###Nginx ###
# external access port for NGINX
# your instance of LET will be accessible by http: // localhost: 8000 where 8000 is the configured external port
NGINX_EXT_PORT=8000
###PhpMyAdmin ###
# external access port for PhpMyAdmin
# your instance of PhpMyAdmin for LET will be accessible by http://localhost:8001 where 8001 is the configured external port
PMA_EXT_PORT=8001
###SQLD ###
# external access port for wwwsqldesigner
# your instance of wwwsqldesigner for LET will be accessible by http://localhost:8002 where 8002 is the configured external port
SQLD_EXT_PORT=8002
### Proxy conf ###
# proxy configuration
# leave commented or empty if you are not using a proxy
# http proxy defines as: http://plop:tagada@proxy.exemple.com:8080
#PROXY=''
# https proxy defines as: http://plop:tagada@proxy.exemple.com:8080
#PROXYS=''
# 'no_proxy' for direct access
#NO_PROXY='localhost,127.*'
###< docker ###
```
