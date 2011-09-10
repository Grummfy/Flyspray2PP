<?php

/**
 * cli toools to import flysprays stuff to projectpier
 * version of flyspray : 0.9.9.6.dev
 * version of projectpier : 0.8.6
 *
 * requirement
 *  - PHP 5.?
 *  - PDO
 *  - ticket plugins activate on PP
 */

require_once 'convert.php';
 
$convert_source = 'flyspray';
$convert_path = ucfirst($convert_source);

$DB = loadDB($config, $convert_source);
convert2PP($convert_source, $convert_path, $DB, $config);

# EOF
