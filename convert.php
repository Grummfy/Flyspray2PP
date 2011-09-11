<?php

// comment this line to remove warning
die('Comment this line (add a // in front of the line, in file ' . __FILE__ . ' on line ' . __LINE__ . '), if you have backuped all your flyspray AND projectpier data!');



//
//
//
define('_X2PP_ROOT', dirname(__FILE__));

// include config
if(!file_exists(_X2PP_ROOT . '/config.php'))
{
	die('file config.php doesn\'t exist');
}
require _X2PP_ROOT . '/config.php';

// include required things
require _X2PP_ROOT . '/libs/DB.php';
require _X2PP_ROOT . '/libs/IModules.php';
require _X2PP_ROOT . '/libs/AbstractModules.php';
require _X2PP_ROOT . '/libs/functions.php';

function loadModule($module_name, $convert_path, $DB, $config)
{
	include _X2PP_ROOT . '/Modules/' . $convert_path . '/' . $module_name . '.php';

	$module = 'Modules_' . $convert_path . '_' . $module_name;

	return new $module($DB, $config);
}

function loadDB($config, $convert_source)
{
	return new DB($config[ $convert_source ]['db_dsn'], $config[ $convert_source ]['db_user'], $config[ $convert_source ]['db_pass'], $config[ $convert_source ]['db_prefix'], $config['projectpier']['db_dsn'], $config['projectpier']['db_user'], $config['projectpier']['db_pass'], $config['projectpier']['db_prefix']);
}

if (isset($argv[1]) && file_exists($argv[1] . '2pp.php'))
{
	require_once $argv[1] . '2pp.php';
}

# EOF
