Docker for PHP projects, with Apache and MySQL

Objective: Do a demo with Docker step by step for php developers.

Notes: Consider that the docker and docker-compose are installed and working.
If you need help access: https://docs.docker.com/engine/installation/linux/fedora/ and https://docs.docker.com/compose/install/ 
To this tutorial I used Fedora 23, Docker 1.12.0 and docker-compose 1.8.0.


1. What is Docker? (docker.com)

Docker is the world's leading software containerization platform.
Docker containers wrap a piece of software in a complete filesystem that contains everything needed to run: code, runtime, system tools, system libraries – anything that can be installed on a server. This guarantees that the software will always run the same, regardless of its environment.
2. How to use Docker in PHP projects?
Basically, we need write a file named Dockerfile, containing the information about requirements to install this machine (eg.. webserver), with operational system or image, libraries we want to use with our application.
Remember that  to easy way to deployment, it would be create an infrastructure identical the deploy environment.
To help us, we would to use that docker-compose. It is a orchestration utilities to organize several machines/containers. 
eg. A webserver and a database server. To use docker-compose, we write a file named docker-compose.yml. In this file we put the information about the machines/containers  that we want to get up, environment variables, Dockerfiles references or images and also define the information according with we development environment, share the folder of we application with webserver to facility and accelerate the workflow.
Below you can look the commented files to better understanding.

The files are in this project.

After definitions we files docker-compose.yml and Dockerfile, we need know some fundamental commands to use the docker-compose:
docker-compose build – compile and configure the containers based on we dockerfile and docker-compose.yml. Depending on the amount of extensions of PHP, or other instalations through of RUN command, it can be slowly, because it may need download some package, compile… Take it easy.

docker-compose up – up our containers, create and initialize the containers.
After using the docker-compose up, we can now access our application and also check our dockers running.
If you type on the terminal this command, it locked your terminal. To stop type Ctrl+c.

Well done, let's go to up our containers.

docker-compose build
docker-compose up

docker ps – show containers that are running.
If we type this command we will look something like this:

CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
35554b895a39        modelo_web          "apache2-foreground"     33 seconds ago      Up 27 seconds       0.0.0.0:8889->80/tcp   modeloPHP5.4-Apache
d6a578cf7254        mysql:5.7           "docker-entrypoint.sh"   43 seconds ago      Up 37 seconds       3306/tcp               modeloMySQL

Note that our two containers are running, and the name configured on docker-compose.yml are in column NAMES. It is easy to identify.


docker images – show the images available on your machine
docker-compose down – ends and remove our containers, networks, images and volumes.
docker-compose –-help – show how to use docker-compose, it is good.

Now, we can go to http://127.0.0.1:8889. We see our application that are on folder www.
To test we put in this folder a file index.php with <?php phpinfo(); ?>, it show the php informations.
When you go to url above you see something like this image:



Well done!
Now, we can add to our index.php some tests. A mysql connection as below:

The files are in this project.

Now, if we go to our url, we look something like this:

Connected with mysql_connect
Success with mysqli connection at ... db via TCP/IP 

And PHP informations...

Successful, now we can work with simple projcts PHP using docker.

If you want access your database mysql of container, simply use your mysql client, as mysql-front (windows), Mysql Workbench, or if you use PHPStorm, simply add your database. Remember that configure the correctly port. In this case you can use url 127.0.0.1 and port 3307 with user and password setted on file docker-compose.yml

To up or down your containers you can use docker-compose up and Ctrl + c, however you can also use the argument start and stop too.
Remember that, when you need clean up your containers of your machine, you can use docker-compose down and to remove your containers use the argument remove.

Remember too, that docker-compose stop or Ctrl + c, do not delete your database informations, however if you use docker-compose down, remove all data of your containers.
But, if you need again this container on future, simply type docker-compose up, and you have the same data, user, password, but the database only your dump.sql.




Referências:
https://docs.docker.com/v1.8/compose/yml/
https://github.com/romeOz/docker-apache-php
https://hub.docker.com/_/php/
https://hub.docker.com/_/mysql/
