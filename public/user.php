<?php
/**
 * user.php
 *
 * Handle user CRUD.
 */

// Setup the bootstrap
require_once( '../bootstrap.php' );

// Require a coordinator user session
$user = DRyft\Session::getSession()->getUser();
if ( !$user || !$user->isCoordinator() ) {

	// throw an error and exit
	echo '<h1>Access Denied</h1>';
}
else {
	// Present a list of the users in the system
	echo '<h1>List of users!</h1>';
}

include '../testing_links.html';
