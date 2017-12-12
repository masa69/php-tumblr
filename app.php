<?php

define('APP_PATH', realpath(dirname(__FILE__)));

try {
	require APP_PATH . '/vendor/autoload.php';
	require APP_PATH . '/autoloader.php';
	require APP_PATH . '/config/config.php';
} catch (Exception $e) {
	echo $e->getMessage();
}