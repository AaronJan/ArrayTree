<?php 
//bootstraping test requirement 

require(__DIR__ . '/../vendor/Autoloader.class.php');

$autoloader = Autoloader::GetInstance();
$autoloader->addNamespace('ArrayTree', __DIR__ . '/../ArrayTree');
$autoloader->register();