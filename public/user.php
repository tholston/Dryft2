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

// determine which action we're here to accomplish
$action = $_REQUEST[Constants::PARAM_ACTION];

// Determine if a user has been specified
$selectedUser = null;
if (array_key_exists(Constants::PARAM_ID, $_REQUEST)) {
	try {
		$selectedUser = User::getUserById(intval($_REQUEST[Constants::PARAM_ID]));
	} catch (Database\Exception $e) {

		// if no user was found display the error and drop out with a dummy action
		$headerLabel = 'Unable to locate user for id: ' . intval($_REQUEST[Constants::PARAM_ID]);
		$error = $e->getMessage();
		$action = Constants::ACTION_ERROR;
	}
}

// Setup a linker to create well-known URLs
$linker = new Linker();

// Handle actions that require redirection before output begins
if ($action == Constants::ACTION_NEW_ADDRESS) {

	// ensure we have a selected user
	if (!$selectedUser instanceof User) {
		$headerLabel = 'Address Create';
		$error = 'No user selected. A valid user is required to create a new address.';
		$action = Constants::ACTION_ERROR;
	} else {
		// default to home address
		$type = Constants::ADDRESS_TYPE_HOME;
		$address = $selectedUser->homeAddress();
		if (array_key_exists(Constants::PARAM_TYPE, $_REQUEST)) {
			$type = $_REQUEST[Constants::PARAM_TYPE];
			if ($type == Constants::ADDRESS_TYPE_MAILING) {
				$address = $selectedUser->mailingAddress();
			}
		}

		// Regardless of the outcome, use the error view
		$action = Constants::ACTION_ERROR;

		// Specify a nickname for this address
		$address->nickname = ucfirst($type);
		// save it to the database to establish a location id
		try {

			$address->save();
			// update the user to save this id to its record
			$user->save();

			// redirect the user to the edit page for the location
			$location = $linker->urlPath() . 'address.php?edit=' . $address->id();
			header('Location: ' . $location);
			$headerLabel = 'Redirect to create address';
			$error = 'Would redirect to: ' . $location;
		} catch (Database\Exception $e) {
			$headerLabel = '<h1>User Address Create Failed</h1>';
			$error = $e->getMessage();
		}
	}
}

// now that actions that modify headers are complete, start the page template

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Users | DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';

// determine if the user has access to this "viewtroller"
if (!$user || !$user->isCoordinator()) {

	// throw an error and exit
	echo '<h1>Access Denied</h1>';
} else {

	// determine which action we're here to accomplish
	$action = $_REQUEST[Constants::PARAM_ACTION];

	// determine if a user has been provided
	$selectedUser = null;
	if (array_key_exists(Constants::PARAM_ID, $_REQUEST)) {
		try {
			$selectedUser = User::getUserById(intval($_REQUEST[Constants::PARAM_ID]));
		} catch (Database\Exception $e) {

			// if no user was found display the error and drop out with a dummy action
			echo '<h1>Unable to locate user for id: ' . intval($_REQUEST[Constants::PARAM_ID]) . '</h1>';
			echo '<p>' . $e->getMessage() . '</p>';
			$action = Constants::ACTION_ERROR;
		}
	}

	if ($action == Constants::ACTION_EDIT) {

		// display the edit form for the selected user
		// setup the submit action
		$selectedAction = Constants::ACTION_UPDATE;
		// setup the header label
		$headerLabel = 'Edit ' . $selectedUser->firstName . ' ' . $selectedUser->lastName . ' (' . $selectedUser->id() . ')';
		// setup the submit label
		$submitLabel = 'Update user';
		// include the form snippet
		include '../views/user-edit.html';
	} elseif ($action == Constants::ACTION_UPDATE) {
		// load updates for the user from the request
		echo '<h1>WILL IT UPDATE?</h1>';
	} elseif ($action == Constants::ACTION_CREATE) {
		// load data to create a new user
		echo '<h1>WILL IT CREATE?</h1>';
	} elseif ($action == Constants::ACTION_NEW) {

		// display the edit form for an empty user
		$selectedUser = new User();

		// setup the submit action
		$selectedAction = Constants::ACTION_CREATE;
		// setup the header label
		$headerLabel = 'Create new user';
		// setup the submit label
		$submitLabel = 'Create user';
		// include the form snippet
		include '../views/user-edit.html';
	} elseif ($action == Constants::ACTION_ERROR) {

		echo '<h1>', $headerLabel, '</h1>';
		echo '<p class="error">', $error, '</p>';
	} else {
		// Present a list of the users in the system
		$users = User::getUsers();

		// include the user list snippet
		include '../views/user-list.html';
	}
}

// add page footer
include '../footer.html';
