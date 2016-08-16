<?php
####################
# conexão mysql_connect modo deprecated, mas talvez você ainda utilize em algum projeto
$link = mysql_connect('db', 'user', 'password');
if (!$link) {
    die('Not connected: ' . mysql_error());
}
echo 'Connected with mysql_connect<br />';
mysql_close($link);

####################
# Conexão mysqli com exemplo do http://php.net/manual/pt_BR/mysqli.construct.php
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

# Mostra as informações do PHP
    phpinfo();
?>
