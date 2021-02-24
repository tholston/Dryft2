<?php

/**
 * user.php
 *
 * Handle user CRUD.
 *
 * @author Errol Sayre
 */

// Setup the bootstrap
require_once('../bootstrap.php');

// Require a coordinator user session
$user = DRyft\Session::getSession()->getUser();

// now that actions that modify headers are complete, start the page template

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Please login | DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';

// determine if the user has access to this "viewtroller"
if (!$user || !$user->isCoordinator()) {

	// throw an error and exit
	echo '<h1>Access Denied</h1>';
} else {
	// Present a list of the users in the system
	echo '<h1>List of users!</h1>';
}

include '../testing_links.html';

// add page footer
include '../footer.html';
