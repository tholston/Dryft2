<?php

/**
 * bootstrap.php
 *
 * Setup the environment elements required for operation.
 *
 * @author Errol Sayre
 */

namespace DRyft;

require_once('constants.php');
require_once('DRyft/AutoLoader.php');

// set the autoloader to use the current directory
$loader = new AutoLoader(dirname(__FILE__));

// Determine the current environment
// default to dev
$environment = DEVELOPMENT;
// determine if we're on turing
if (php_uname('n') == HOST_TURING) {
	// determine if this is Clay's account
	if (strpos(dirname(__FILE__), CLAY_USER) !== false) {
		// this is still a dev environment, but should use separate credentials
		$environment = CLAY_ENVIRONMENT;
	} else {
		// this is production
		$environment = PRODUCTION;
	}
}

// Setup the database configuration for the current environment
if ($environment == DEVELOPMENT) {
	Database\Connection::setHost(DB_DEV_HOST);
	Database\Connection::setUser(DB_DEV_USER);
	Database\Connection::setPassword(DB_DEV_PASSWORD);
	Database\Connection::setSchema(DB_DEV_SCHEMA);
} elseif ($environment == CLAY_ENVIRONMENT) {
	Database\Connection::setHost(CLAY_DB_HOST);
	Database\Connection::setUser(CLAY_DB_USER);
	Database\Connection::setPassword(CLAY_DB_PASSWORD);
	Database\Connection::setSchema(CLAY_DB_SCHEMA);
} elseif ($environment == PRODUCTION) {
	Database\Connection::setHost(DB_PROD_HOST);
	Database\Connection::setUser(DB_PROD_USER);
	Database\Connection::setPassword(DB_PROD_PASSWORD);
	Database\Connection::setSchema(DB_PROD_SCHEMA);
}
