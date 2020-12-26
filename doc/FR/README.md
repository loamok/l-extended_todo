# l-extended_todo
La version Anglaise de cette documentation peut être trouvée [ici](/doc/EN/README.md).

English version of this documentation can be found at [here](/doc/EN/README.md).

## Table des matières
   * [l-extended_todo](#l-extended_todo)
      * [Description :](#description-)
      * [Dépendances logicielles et pré-requis :](#dépendances-logicielles-et-pré-requis-)
         * [Prérequis :](#prérequis-)
         * [Dépendances de Symfony :](#dépendances-de-symfony-)
         * [Autres dépendances :](#autres-dépendances-)
      * [Base de données :](#base-de-données-)
         * [Schema :](#schema-)
      * [Mailer :](#mailer-)
      * [Docker :](#docker-)
         * [Containers :](#containers-)
         * [Configuration :](#configuration-)

## Description :
**l-_todo** est une liste de tâches étendue avec gestion du temps.

Le nom de l'application sera également abrégé en **let** or **LET** dans cette documentation.

## Dépendances logicielles et pré-requis :
**LET** est une application web, application mobile utilisant les technologies suivantes:

**LET** est basé sur la version 5 du framework Symfony.
### Prérequis :
 - [x] PHP 7.4
 - [x] MariaDB 10.5 (qui est un fork de MySQL, vous êtes libre d'utiliser **let** avec MySQL 8.0 ou un autre SGBD puisque **let** utilise le projet ORM de Doctrine).
   Les différences entre MariaDB et Mysql peuvent être trouvées ici: [Cliquez ici pour la documentation](https://mariadb.com/kb/en/incompatibilities-and-feature-differences-between-mariadb-105-and-mysql-80/)  
   (https://mariadb.com/kb/en/incompatibilities-and-feature-differences-between-mariadb-105-and-mysql-80/)
 - [x] Symfony 5.0+
### Dépendances de Symfony :
[Allez à](https://flex.symfony.com/) (https://flex.symfony.com/) pour plus de détails sur ces dépendances.

**LET** a besoin et utilise les composants suivants du framework Symfony :
 - [x] profiler (recommandé uniquement dans l'environnement de développement) (symfony/profiler-pack)
 - [x] debug  (recommandé uniquement dans l'environnement de développement) (symfony/debug-pack)
 - [x] maker  (recommandé uniquement dans l'environnement de développement) (symfony/maker-bundle)
 - [x] orm (symfony/orm-pack)
 - [x] api (api-platform/api-pack)
 - [x] [sensio/framework-extra-bundle](https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html)
 - [x] logger (symfony/monolog-bundle)
### Autres dépendances :
 - [x] [Ramsey Uuid Doctrine Type](https://github.com/ramsey/uuid-doctrine)  (ramsey/uuid-doctrine): Fournit des uuid comme types de base de données.
	 - [x] [Ramsey Uuid](https://github.com/ramsey/uuid): cette dépendance est automatiquement installée par Uuid-Doctrine.
 - [x] [doctrine-extensions](https://symfony.com/doc/1.2/bundles/StofDoctrineExtensionsBundle/index.html): Ces extensions sont un moyen simple d'utiliser certains comportements représentant de bonnes pratiques:
	 - [x] blameable : Blâmez l'utilisateur qui a apporté une modification (vraiment utile pour prouver qui a causé un bug).
	 - [x] loggable : Enregistrez ce qui arrive à vos entités et suivez les modifications.
	 - [x] sluggable : Lorsque vous n'utilisez pas les uuid pour les entités, il est utile de disposer d'une belle URL avec le titre par exemple au lieu de l'ID de la ressource.
	 - [x] timestampable : Avec les autres extensions on garde qui a fait quoi, celle-ci vous dit quand.
	 - [x] translatable : Un moyen simple de conserver le contenu traduit dans la base de données.
 - [x] [doctrine2-spatial](https://github.com/creof/doctrine2-spatial) : Manipuler des données spatiales (géométriques et géographiques), nous allons l'utiliser pour des informations géographiques avec lat et long et pour calculer les distances en surface. Oui, la géolocalisation sera une fonctionnalité importante de cette application.
## Base de données :
### Schema :
![Database schema](/doc/schema/dbSchema.png)

Vous trouverez le schema complet de base de données au format xml dans le fichier 
[/doc/l-extended_todo_database_Schema.xml](/doc/l-extended_todo_database_Schema.xml), 
ce fichier est utilisable dans [ondras](https://github.com/ondras) / **[wwwsqldesigner](https://github.com/ondras/wwwsqldesigner)**.

Le schéma actuel de base de données est disponible sous cette forme dans un container **Docker** du projet à l'adresse : 
**[http://localhost:8002/?keyword=l_extended_todo](http://localhost:8002/?keyword=l_extended_todo)**.

Vous pouvez aussi utiliser les fonctions de **wwwsqldesigner** dans ce container ou depuis une autre instance via le fichier **XML** fournit.

Voir la section **[Docker :](#docker-)** pour plus d'informations.
## Mailer :
**LET** utilise le mailer de symfony (ex swiftMailer) et doit être configuré afin de pouvoir envoyer des mails.
Il n'y as pas de configuration par défaut et nous vous recommandons de vous baser sur la documentation officielle de symfony pour répondre aux besoins de votre infrastructure.

Un container docker est à l'étude afin de fournir un relais mails pour les développements.
## Docker :
### Containers :
**LET** défini et utilise des images **Docker** utiles pour le développement.

Le premier container **database** est indispensable au développement car il héberge la base de données pour la phase de développement.

Les 2 suivants sont utiles si votre hôte de développement n'est pas configuré pour faire tourner PHP 7.4. Ils définissent un container PHP-FPM et un container NGINX.

Les 2 derniers containers sont des outils optionnels pour aider au développement.

Voici un descriptif de ces images :
- [x] **database** : Container MariaDB 10.5 [mariadb:10.5](https://hub.docker.com/_/mariadb?tab=tags)
- [x] **php** : Container [php:7.4-fpm-buster](https://hub.docker.com/_/php?tab=tags)
- [x] **nginx** : Container Nginx [nginx:latest](https://hub.docker.com/_/nginx?tab=tags)
- [x] **phpmyadmin** : Container PhpMyAdmin [phpmyadmin/phpmyadmin:latest](https://hub.docker.com/r/phpmyadmin/phpmyadmin/tags)
- [x] **sqld** : Container proposant l'outil [wwwsqldesigner](https://github.com/ondras/wwwsqldesigner), [d0whc3r/wwwsqldesigner](https://hub.docker.com/r/d0whc3r/wwwsqldesigner/tags)
### Configuration :
Pour faire fonctionner ces instances **Docker** vous aurez besoin de définir un ensemble de variables de configuration dans votre fichier 
.env (.env.local n'est pas pris en charge par docker).

Le détail de chaque variable est indiqué ici en commentaire : 
``` 
###> docker ###
# Fuseau horaire applicable
TIMEZONE=Europe/Paris
### nom du réseau docker à utiliser ###
# Réseau Docker à créer au préalable avec la commande "docker network create l-extended_todo_default" 
# ou quel que soit le nom du réseau que vous souhaitez
DOCKER_NETWORK=l-extended_todo_default
###Mysql ###
# port d'accès externe
MYSQL_EXT_PORT=8086
# mot de passe root
MYSQL_ROOT_PASSWORD=changeme
# base de données pour LET
MYSQL_DATABASE=let
# utilisateur pour LET
MYSQL_USER=let_user
# mot de passe utilisateur pour LET
MYSQL_PASSWORD=changeme_let_passwd
###Nginx ###
# port externe NGINX
# votre instance de LET sera accessible par http://localhost:8000 où 8000 est le port externe configuré
NGINX_EXT_PORT=8000
###PhpMyAdmin ###
# port externe PhpMyAdmin
# votre instance de PhpMyAdmin pour LET sera accessible par http://localhost:8001 où 8001 est le port externe configuré
PMA_EXT_PORT=8001
###SQLD ###
# port externe wwwsqldesigner
# votre instance de wwwsqldesigner pour LET sera accessible par http://localhost:8002 où 8002 est le port externe configuré
SQLD_EXT_PORT=8002
### Proxy conf ###
# configuration proxy
# laissez commenté ou vide si vous n'utilisez pas de proxy
# proxy http définit tel que : http://plop:tagada@proxy.exemple.com:8080
#PROXY=''
# proxy https définit tel que : http://plop:tagada@proxy.exemple.com:8080
#PROXYS=''
# configuration 'no_proxy' pour accès direct
#NO_PROXY='localhost,127.*'
###< docker ###
```
