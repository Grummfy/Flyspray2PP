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

// first import users
$modUsers = loadModule('Users', $convert_path, $DB, $config);
$modUsers->convert();

// projects	
$modProjects = loadModule('Projects', $convert_path, $DB, $config);
$modProjects->convert();

$modCategories = loadModule('Categories', $convert_path, $DB, $config);
$modCategories->setProjectsConverter($modProjects);
$modCategories->convert();

// tickets
$modTasks = loadModule('Tasks', $convert_path, $DB, $config);
$modTasks->setProjectsConverter($modProjects);
$modTasks->setUsersConverter($modUsers);
$modTasks->setCategoriesConverter($modCategories);
$modTasks->convert();

# EOF
