<?php
$link = mysql_connect('localhost', 'dippk_webapp', 'Ppkw3bapp!');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
printf("MySQL host info: %s\n", mysql_get_host_info());
?>