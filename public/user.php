<?php

/**
 * user.php
 *
 * Handle user CRUD.
 *
 * The forms on this viewtroller are based on the ["Checkout" example](https://getbootstrap.com/docs/5.0/examples/checkout/) from Bootstrap.
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
echo '		<title>Users | DRyft</title>' . PHP_EOL;

// add page header based on the user's access level

// determine if the user has access to this "viewtroller"
if (!$user || !$user->isCoordinator()) {

	include '../header.html';
	// throw an error and exit
	echo '<h1>Access Denied</h1>';
} else {

	include '../header-coordinator.html';

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
		// setup the submit action
		$selectedAction = ACTION_UPDATE;
		// setup the header label
		$headerLabel = 'Edit ' . $selectedUser->firstName . ' ' . $selectedUser->lastName . ' (' . $selectedUser->id() . ')';
		// setup the submit label
		$submitLabel = 'Update user';
		// include the form snippet
		include '../views/user-edit.html';
	} elseif ($action == ACTION_UPDATE) {
		// load updates for the user from the request
		echo '<h1>WILL IT UPDATE?</h1>';
	} elseif ($action == ACTION_CREATE) {
		// load data to create a new user
		echo '<h1>WILL IT CREATE?</h1>';
	} elseif ($action == ACTION_NEW) {

		// display the edit form for an empty user
		$selectedUser = new User();

		// setup the submit action
		$selectedAction = ACTION_CREATE;
		// setup the header label
		$headerLabel = 'Create new user';
		// setup the submit label
		$submitLabel = 'Create user';
		// include the form snippet
		include '../views/user-edit.html';
	} else {
		// Present a list of the users in the system
		$users = User::getUsers();

		// include the user list snippet
		include '../views/user-list.html';
	}
}

// add page footer
include '../footer.html';
