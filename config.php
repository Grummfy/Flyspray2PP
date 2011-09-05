<?php

$config = array(
	'flyspray' => array(
		// dsn for flyspray db, see http://php.net/pdo.construct for more information
		'db_dsn'	=> 'mysql:dbname=dev_flyspray_import_pp;host=localhost',
		// username for flyspray db
		'db_user'	=> 'root',
		// password for flyspray db
		'db_pass'	=> 'root',
		// prefix table for flyspray db
		'db_prefix'	=> 'flyspray_'
	),
	'projectpier' => array(
		// dsn for projectpier db
		'db_dsn'	=> 'mysql:dbname=dev_flyspray_import_pp;host=localhost',
		// username for projectpier db
		'db_user'	=> 'root',
		// password for projectpier db
		'db_pass'	=> 'root',
		// prefix table for projectpier db
		'db_prefix'	=> 'PP086_',
		// default campany to set
		'default_compagny_id'=> 1
	)
);

# EOF
