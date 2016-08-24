	 	 	 	
Docker for PHP projects, with Apache and MySQL

Objective: Do a demo with Docker step by step for php developers.

Notes: Consider that the docker and docker-compose are installed and working.
If you need help go to: https://docs.docker.com/engine/installation/linux/fedora/ and https://docs.docker.com/compose/install/
To this tutorial I used Fedora 23, Docker 1.12.0 and docker-compose 1.8.0.


What 	is Docker? (docker.com)

Docker is the world's leading software containerization platform.
Docker containers wrap a piece of software in a complete filesystem that contains everything needed to run: code, runtime, system tools, system libraries – anything that can be installed on a server. This guarantees that the software will always run the same, regardless of its environment.
How 	to use Docker in PHP projects?
Basically, we need to write a file called Dockerfile, containing the information about requirements to be installed  in this container (eg.. webserver), such as operational system or image, libraries we want to use with our application.
Remember that the easiest way to deployment, it's to create an infrastructure identical the deploy environment.
To help us, we would use that docker-compose. It is an orchestration utilities to organize several machines/containers.
e.g. A webserver and a database server. To use docker-compose, we write a file called docker-compose.yml. In this file, we put the information about the machines/containers, environment variables, Dockerfiles references or images and also to define the information according to environment development, to share the folder of our application with webserver to facilitate and accelerate the workflow.
Below you can look the commented files to better understanding.

Dockerfile

# In FROM we specified the image that we want to use, in this case, I used an oficial image of PHP.
# available in https://hub.docker.com/_/php/ the instruction “FROM php” get the image on this link.
# “:5.4-apache”, get the specific version of PHP with Apache
# Look the link to other available versions.
FROM php:5.4-apache
# In MAINTAINER we can specify the creator and that keeps this dockerfile version.
MAINTAINER Eduardo Colombo <eduardobcolombo@gmail.com>
# The RUN allows to execute commands within the container, the docker-php-ext-install installs PHP  extensions within the container.
# You can also add after other extensions desired.
RUN docker-php-ext-install mysql mysqli pdo pdo_mysql
# Maybe you want to install intl then
RUN apt-get install -y libicu-dev
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
# And mbstring you may need
RUN docker-php-ext-install mbstring



docker-compose.yml

# Define the version of file yml see more information on https://docs.docker.com/v1.8/compose/yml/
version: '2'
# Define the services place
services:
  # Here we named our service, I typed web, however we could call any name, as long as there is :
  web:
    # container_name is to identify our container. eg. docker-compose ps
    container_name: modeloPHP5.4-Apache
    # in the build setting, we do not use image directly, but a Dockerfile previously configured.
    Build: .
      # as we type dot after build: instruction, the Dockerfile default is loaded.
      # the dockerfile, we can identifiy the Dockerfile with other name. eg. DockerfileWebserverPHPApache,
      # it work well when we have several containers. We can create a folder to put Dockerfiles.
      # In this case I didn't specifiy, because in root folder we have a Dockerfile default name
      # then it will load.
      # dockerfile: Dockerfile-alternate
      # image: php:5.4-apache
    # in ports, we can redirect and open the ports between host and container,
    # below we are showing that if we access the port 8889 in host, we will redirected to port 80 in container.
    ports:
     - 8889:80
    # We share the workspace of our host with any folder within the container.
    # below we are showing that in the container, the folder /var/www/html is a link to local folder ./www.
    # sintaxe: - localfolder:containerfolder
    # if you need share your root folder app, you may need to put like this:  .:/var/www/html
    volumes:
     - ./www:/var/www/html
    # in links, we relate to the containers.
    # in this case, we relate db, because we need to access the mysql from this container
    # sintaxe: - servicename
    # we could relate the service name with an alias, however this alias should be defined in /etc/hosts within the container
    # Like this: service:alias  or db:mysql-server provide that the db container has  /etc/hosts configured as mysql-server
    links:
     - db
  # Here we have other service/container, the db, the database mysql
  db:
    # container_name is to identify our container. eg. docker-compose ps
    container_name: modeloMySQL
    # here we use the build to import other Dockerfile, to facilitate the importation of our mysql database
    build:
      # context, specifies the folder of this Dockerfile
      context: ./
      # Here we type the Dockerfile name.
      dockerfile: DockerfileDB
    # We share the workspace of our host with any folder within the container.
    # sintaxe: - localfolder:containerfolder
    # alternatively we can use at the end the third parameter, the access mode eg. :ro (read only)
    volumes:
     - /var/lib/mysql
    # in ports, we want to expose the port 3306 of this container, to access the mysql through host machine
    # below we type the host port 3307 to access mysql through container port 3306
    ports:
     - 3307:3306
    # environment we set the environment variables to this container
    environment:
      # below some variables to mysql connect.
      MYSQL_ROOT_PASSWORD: password
      MYSQL_USER: user
      MYSQL_PASSWORD: password
      MYSQL_DATABASE: db_test




DockerfileDB

# In FROM we type mysql:5.7. I also used recommended references of https://hub.docker.com/_/mysql/
FROM mysql:5.7
# In MAINTAINER we can specify the creator and that keeps this dockerfile version.
MAINTAINER Eduardo Colombo <eduardobcolombo@gmail.com>
# In ADD we type the local of sql dump file to add in container
# And docker-entrypoint-initdb.d do the dump on database within container
ADD dump.sql /docker-entrypoint-initdb.d/dump.sql

After definitions of our files docker-compose.yml and Dockerfile, we need know some fundamental commands to use the docker-compose:
docker-compose build – compile and configure the containers based in our dockerfiles and docker-compose.yml. Depending on the amount of extensions of PHP, or other instalations through of RUN command, it can get slow, because it may need to download some package, compile… Take it easy.

docker-compose up – up our containers, create and initialize the containers.
After using the docker-compose up, we can now access our application and also check our dockers running.
If you type on the terminal this command, it will lock your terminal. To stop press Ctrl+c.

Well done, let's go to put up our containers.

docker-compose build
docker-compose up

docker ps – shows containers that are running.
If we type this command it will look something like this:

CONTAINER ID IMAGE COMMAND CREATED STATUS PORTS NAMES
35554b895a39 modelo_web "apache2-foreground" 33 seconds ago Up 27 seconds 0.0.0.0:8889->80/tcp modeloPHP5.4-Apache
d6a578cf7254 mysql:5.7 "docker-entrypoint.sh" 43 seconds ago Up 37 seconds 3306/tcp modeloMySQL

Note that our two containers are running, and the name configured on docker-compose.yml are in column NAMES. It is easy to identify.


docker images – shows the images available on your machine
docker-compose down – ends and removes our containers, networks, images and volumes.
docker-compose –-help – shows how to use docker-compose, it is good.

Now, we can go to http://127.0.0.1:8889. We see our application that are on folder www.
To test we put in this folder a file index.php with <?php phpinfo(); ?>, it shows the php informations.
When you go to the url above you see something like this:




Well done!
Now, we can add to our index.php some tests. A mysql connection as below:

index.php

<?php
####################
# Connection mysql_connect deprecated, but you may still use in some projects
$link = mysql_connect('db', 'user', 'password');
if (!$link) {
    die('Not connected: ' . mysql_error());
}
echo 'Connected with mysql_connect<br />';
mysql_close($link);
####################
# Connection mysqli as http://php.net/manual/pt_BR/mysqli.construct.php
$mysqli = new mysqli('db', 'user', 'password', 'db_test');
/*
 * This is the "official" OO way to do it,
 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
 */
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}
/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
echo 'Success with mysqli connection at ... ' . $mysqli->host_info . "\n";
$mysqli->close();
# Show PHP informations
# look that we extensions are installed
    phpinfo();
?>



Now, if we go to our url, it will look something like this:

Connected with mysql_connect
Success with mysqli connection at ... db via TCP/IP

And PHP informations...

Successful, now we can work with simple projects PHP using docker.

If you want access your database mysql of container, simply use your mysql client, as mysql-front (windows), Mysql Workbench, or if you use PHPStorm, simply add your database. Remember to configure the correct port. In this case you can use url 127.0.0.1 and port 3307 with username and password setted on file docker-compose.yml

To put up or down your containers you can use docker-compose up and Ctrl + c, however you can also use the argument start and stop too.
Remember that, when you need to clean up your containers on your machine, you can use docker-compose down and, to remove your containers, use the argument remove.

Also remember, that docker-compose stop or Ctrl + c, do not delete your database informations, however if you use docker-compose down, remove all data of your containers.
But, if you need again this container on future, simply type docker-compose up, and you have the same data, username, password, but the database only with your dump.sql.




Referências:
https://docs.docker.com/v1.8/compose/yml/
https://github.com/romeOz/docker-apache-php
https://hub.docker.com/_/php/
https://hub.docker.com/_/mysql/


