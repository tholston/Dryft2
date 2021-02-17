<?php
/**
 * bootstrap.php
 *
 * Setup the environment elements required for operation.
 */

namespace DRyft;

require_once( 'Constants.php' );
require_once( 'DRyft/AutoLoader.php' );

// set the autoloader to use the current directory
$loader = new AutoLoader( dirname( __FILE__ ) );

// Determine the current environment
$environment = DEVELOPMENT;

// Setup the database configuration for the current environment
if ( $environment == DEVELOPMENT ) {
	Database\Connection::setUser(     DB_DEV_USER     );
	Database\Connection::setPassword( DB_DEV_PASSWORD );
	Database\Connection::setSchema(   DB_DEV_SCHEMA   );
	Database\Connection::setHost(     DB_DEV_HOST     );
}
