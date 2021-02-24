<?php

/**
 * user.php
 *
 * Handle user CRUD.
 *
 * @author Errol Sayre
 */

namespace DRyft;

// Setup the bootstrap
require_once('../bootstrap.php');

// Require a coordinator user session
$user = Session::getSession()->getUser();

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

	// determine which action we're here to accomplish
	$action = $_REQUEST[PARAM_ACTION];

	// determine if a user has been provided
	$selectedUser = null;
	if (array_key_exists(PARAM_ID, $_REQUEST)) {
		try {
			$selectedUser = User::getUserById(intval($_REQUEST[PARAM_ID]));
		} catch (Database\Exception $e) {

			// if no user was found display the error and drop out with a dummy action
			echo '<h1>Unable to locate user for id: ' . intval($_REQUEST[PARAM_ID]) . '</h1>';
			echo '<p>' . $e->getMessage() . '</p>';
			$action = ACTION_ERROR;
		}
	}

	if ($action == ACTION_EDIT) {
		// display the edit form for the selected user
		// ensure the user
		echo '<h1>Edit ' . $selectedUser->firstName . ' ' . $selectedUser->lastName . ' (' . $selectedUser->id() . ')</h1>';
	} elseif ($action == ACTION_UPDATE) {
		// load updates for the user from the request
		echo '<h1>WILL IT UPDATE?</h1>';
	} elseif ($action == ACTION_CREATE) {
		// load data to create a new user
		echo '<h1>WILL IT CREATE?</h1>';
	} elseif ($action == ACTION_NEW) {
		// display the edit form for an empty user
		echo '<h1>Create a new user</h1>';
	} else {
		// Present a list of the users in the system
		echo '<h1>Existing users</h1>';
	}
}

include '../testing_links.html';

?>
<h2>User Test Links</h2>
<ul>
	<li><a href="user.php?<?= PARAM_ACTION ?>=<?= ACTION_NEW ?>">Create New</a></li>
	<li><a href="user.php?<?= PARAM_ACTION ?>=<?= ACTION_CREATE ?>">Create Submit</a></li>
	<li><a href="user.php?<?= PARAM_ID ?>=1&<?= PARAM_ACTION ?>=<?= ACTION_EDIT ?>">Edit 1</a> <a href="user.php?<?= PARAM_ID ?>=2&<?= PARAM_ACTION ?>=<?= ACTION_EDIT ?>">Edit 2</a></li>
	<li><a href="user.php?<?= PARAM_ID ?>=2&<?= PARAM_ACTION ?>=<?= ACTION_UPDATE ?>">Edit 2 Submit</a></li>
</ul>
<?php

// add page footer
include '../footer.html';
