.<?php
define ('DB_SERVER', '127.0.0.1');
define ('DB_USERNAME', 'marzel');
define ('DB_PASSWORD', 'carreon');
define ('DB_NAME', 'cs313group2-2024');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die ("ERROR: Could not connect," . mysqli_connect());
}
date_default_timezone_set('Asia/Manila');

?>