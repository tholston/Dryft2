<?php

/**
 * client.php
 *
 * Display the client's scheduled and past rides.
 *
 * This view/script and others like it should accomplish the tasks a client will need to do:
 *  - request a ride
 *  - maintain addresses
 *  - register as a user? (not a stated feature)
 *  - view past rides?
 *
 * @author Austin Marotti
 */


// Setup the bootstrap
require_once( '../bootstrap.php' );

// Require a client user session
$user = DRyft\Session::getSession()->getUser();
if ( !$user || !$user-> isClient()){

	// throw an error and exit
	echo '<h1>Access Denied</h1>';
}
else {
	// Present a list of the users in the system
	echo '<h1>List of users!</h1>';
}