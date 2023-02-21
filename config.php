<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','ingles');
define('DB_CHARSET','utf8');
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$db->set_charset(DB_CHARSET);

?>