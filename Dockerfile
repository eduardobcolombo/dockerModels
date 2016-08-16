# No FROM especificamos a imagem que desejamos utilizar, neste caso utilizei uma imagem oficial do PHP
# disponível em https://hub.docker.com/_/php/ a instrução FROM php busca a imagem desta URL,
# :5.4-apache, busca a versão específica do PHP.
# Consulte o link para ver as demais versões disponíveis.
FROM php:5.4-apache
# No MAINTAINER podemos especificar quem criou e mantem este dockerfile.
MAINTAINER Eduardo Colombo <eduardobcolombo@gmail.com>
# O RUN executa comandos dentro do container, o comando docker-php-ext-install instala extensões do PHP dentro do container.
# Basta adicionar ao lado todas as extensões desejadas.
RUN docker-php-ext-install mysql mysqli pdo pdo_mysql
